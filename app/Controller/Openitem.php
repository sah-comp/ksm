<?php
/**
 * KSM.
 *
 * @package KSM
 * @subpackage Controller
 * @author $Author$
 * @version $Id$
 */

/**
 * Openitem controller.
 *
 * @package KSM
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Openitem extends Controller_Scaffold
{
    /**
     * Holds the default template.
     *
     * @var string
     */
    public $template = 'openitem/index';

    /**
     * Holds the javascripts to load on this page.
     *
     * @var array
     */
    public $javascripts = [
        '/js/datatables.min'
    ];

    /**
     * Holds a comma separated string of IDs that are bookable.
     *
     * @see Model_Contracttype::$bookable
     */
    public $bookable_types = '';

    /**
     * Holds the totals of all open items.
     *
     * @var array
     */
    public $totals = [];

    /**
     * Holds the company bean
     */
    public $company;

    /**
    * Constructor
    *
    * @param string $base_url for scaffold links and redirects
    * @param string $type of the bean to scaffold
    * @param int (optional) $id of the bean to handle
    */
    public function __construct($base_url, $type, $id = null)
    {
        session_start();
        Auth::check();
        $this->type = $type;
        $this->record = R::load('transaction', $id);
        $this->actions = $this->record->getActions('openitem');
        if (!isset($_SESSION['openitem']['person_id'])) {
            $_SESSION['openitem']['person_id'] = null;
        }
    }

    /*
     * Index.
     *
     * @uses getOpenBookables()
     *
     * @param string $layout
     * @param int $page
     * @param int $order
     * @param int $dir
     */
    public function index($layout = null, $page = null, $order = null, $dir = null)
    {
        $this->action = 'index';
        if (Flight::request()->method == 'POST') {
            if (! Security::validateCSRFToken(Flight::request()->data->token)) {
                $this->redirect("/logout");
                exit();
            }

            if (Flight::request()->data->submit == I18n::__('openitem_action_print_statement')) {
                $_SESSION['openitem']['person_id'] = Flight::request()->data->person_id;
                $this->pdf($_SESSION['openitem']['person_id']);
                //$this->redirect("/openitem"); // I never get there, PDF download needs exit
                exit();
            }

            //handle a selection
            $this->selection = Flight::request()->data->selection;
            if ($this->selection && $this->applyToSelection($this->selection[$this->type], Flight::request()->data->next_action)) {
                $this->redirect("/openitem/index");
                exit();
            } else {
                Flight::get('user')->notify(I18n::__('warning_no_selection'), 'warning');
                $this->redirect("/openitem/index");
                exit();
            }
        }
        $this->getOpenBookables();
        $this->render();
    }

    /**
     * Sends a email to the transaction recipient, cc to user who clicked button.
     */
    public function mail()
    {
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $user = Flight::get('user');

        $filename = I18n::__('openitem_pdf_filename', null, [$this->record->getFilenameDunning()]);
        $docname = I18n::__('openitem_pdf_docname', null, [$this->record->getDocnameDunning()]);
        $mpdf = $this->dunningWorkhorse($docname);

        $mail = new PHPMailer\PHPMailer\PHPMailer();

        if ($smtp = $this->company->smtp()) {
            $mail->SMTPDebug = 4;                                 // Set debug mode, 1 = err/msg, 2 = msg
            /**
             * uncomment this block to get verbose error logging in your error log file
             */

            $mail->Debugoutput = function ($str, $level) {
                error_log("debug level $level; message: $str");
            };

            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $smtp['host'];                          // Specify main and backup server
            if ($smtp['auth']) {
                $mail->SMTPAuth = true;                           // Enable SMTP authentication
            } else {
                $mail->SMTPAuth = false;                          // Disable SMTP authentication
            }
            $mail->Port = $smtp['port'];						  // SMTP port
            $mail->Username = $smtp['user'];                      // SMTP username
            $mail->Password = $smtp['password'];                  // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted

            /**
             * @see https://stackoverflow.com/questions/30371910/phpmailer-generates-php-warning-stream-socket-enable-crypto-peer-certificate
             */
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
        }

        $mail->CharSet = 'UTF-8';
        $mail->AddEmbeddedImage(__DIR__ . '/../../public/img/ksm-email-signature-icon.jpg', 'ksm-mascot');
        $mail->setFrom($this->company->emailnoreply, $this->company->legalname);
        $mail->addReplyTo($user->email, $user->name);

        //$mail->addAddress(KSM_EMAIL_TESTADDRESS, KSM_EMAIL_TESTNAME);
        $pos = strpos($this->record->dunningemail, ';');
        if ($pos === false) {
            $mail->addAddress($this->record->dunningemail, $this->record->person->name);
        } else {
            $emails = explode(';', $this->record->dunningemail);
            foreach ($emails as $email) {
                $mail->addAddress($email, $this->record->person->name);
            }
        }

        $mail->addBCC($user->email, $user->name);
        //$mail->WordWarp = 50;
        $mail->isHTML(true);
        $mail->Subject = $docname;

        ob_start();
        Flight::render('openitem/mail/html', array(
            'record' => $this->record,
            'company' => $this->company,
            'user' => $user
        ));
        $html = ob_get_clean();
        ob_start();
        Flight::render('openitem/mail/text', array(
            'record' => $this->record,
            'company' => $this->company,
            'user' => $user
        ));
        $text = ob_get_clean();
        $mail->Body = $html;
        $mail->AltBody = $text;
        $attachment = $mpdf->Output('', 'S');

        $mail->addStringAttachment($attachment, $filename);
        if ($mail->send()) {
            //$this->record->sent = true;
            Flight::get('user')->notify(I18n::__("dunning_mail_done"), 'success');
        } else {
            //$this->record->sent = false;
            Flight::get('user')->notify(I18n::__("dunning_mail_fail"), 'error');
        }
        $this->redirect("/openitem/#bean-{$this->record->getId()}");
        exit();
    }

    /**
     * Generate a PDF and download it to the client.
     */
    public function dunning()
    {
        $filename = I18n::__('openitem_pdf_filename', null, [$this->record->getFilenameDunning()]);
        $docname = I18n::__('openitem_pdf_docname', null, [$this->record->getDocnameDunning()]);
        $mpdf = $this->dunningWorkhorse($docname);
        $mpdf->Output($filename, 'D');
        exit;
    }

    /**
     * Generate a PDF showing the dunning (mahnung) layout.
     *
     * @param string $docname
     * @param string $layout which template to render the dunning
     * @return \Mpdf\Mpdf
     */
    private function dunningWorkhorse($docname, $layout = 'dunning')
    {
        if ($this->record->accumulate) {
            $bookable_types = $this->record->getBookables();

            $this->records = R::find('transaction', " contracttype_id IN (".R::genSlots($bookable_types).") AND status IN (?) AND locked = 1 AND person_id = ? ORDER BY duedate", array_merge($bookable_types, ['open'], [$this->record->getPerson()->getId()]));

            $this->totals = R::getRow("SELECT CAST(SUM(net) AS DECIMAL(10, 2)) AS totalnet, CAST(SUM(vat) AS DECIMAL(10, 2)) AS totalvat, CAST(SUM(gros) AS DECIMAL(10, 2)) AS totalgros, CAST(SUM(totalpaid) AS DECIMAL(10, 2)) AS totalpaid, CAST(SUM(penaltyfee) AS DECIMAL(10, 2)) AS totalfee, CAST(SUM(balance) AS DECIMAL(10, 2)) AS totalbalance, CAST((SUM(balance) + SUM(penaltyfee)) AS DECIMAL(10, 2)) AS totalpayable FROM transaction WHERE contracttype_id IN (".R::genSlots($bookable_types).") AND status IN (?) AND locked = 1 AND person_id = ?", array_merge($bookable_types, ['open'], [$this->record->getPerson()->getId()]));
        } else {
            $this->records[$this->record->getId()] = $this->record; // there is only one transaction to enforce payment

            $this->totals = [
                'totalnet' => $this->record->net,
                'totalvat' => $this->record->vat,
                'totalgros' => $this->record->gros,
                'totalpaid' => $this->record->totalpaid,
                'totalfee' => $this->record->penaltyfee,
                'totalbalance' => $this->record->balance,
                'totalpayable' => round($this->record->balance + $this->record->penaltyfee, 2)
            ];
        }
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $mpdf = $this->generatePDF($docname, $layout);
        return $mpdf;
    }

    /**
     * Generate a PDF.
     *
     * @param string $docname
     * @param string $layout which template to render the dunning
     * @return \Mpdf\Mpdf
     */
    private function generatePDF($docname, $layout)
    {
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'PDFA' => true,
            'default_font' => 'dejavusans',
        ]);
        // Set font for all content to ensure embedding
        $mpdf->SetFont('dejavusans');
        $mpdf->SetTitle($docname);
        $mpdf->SetAuthor($this->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('model/transaction/pdf/' . $layout, [
            'title' => $docname,
            'company' => $this->company,
            'record' => $this->record,
            'records' => $this->records,
            'totals' => $this->totals,
            'language' => Flight::get('language')
        ]);
        $html = ob_get_contents();
        ob_end_clean();
        //DEBUG:
        //echo $html;
        //exit;
        $mpdf->WriteHTML($html);
        return $mpdf;
    }

    /**
     * Generate a PDF with all (filtered) records.
     *
     * @param int optional id of person bean
     */
    public function pdf(int $person_id = null)
    {
        $this->getOpenBookables($person_id);

        if (count($this->records) > CINNEBAR_MAX_RECORDS_TO_PDF) {
            Flight::get('user')->notify(I18n::__('warning_too_many_records_to_print', null, [CINNEBAR_MAX_RECORDS_TO_PDF, count($this->records)]), 'warning');
            $this->redirect('/openitem');
            exit();
        }
        //$ts = date('Y-m-d');
        $templates = Flight::get('templates');
        $ts = date($templates['date'], time());
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $filename = I18n::__('openitem_pdf_list_filename', null, [date('Y-m-d')]);
        $docname = I18n::__('openitem_pdf_list_docname', null, [$ts]);
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'PDFA' => true,
            'default_font' => 'dejavusans',
        ]);
        // Set font for all content to ensure embedding
        $mpdf->SetFont('dejavusans');
        $mpdf->SetTitle($docname);
        $mpdf->SetAuthor($this->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('model/transaction/pdf/openitem', [
            'title' => $docname,
            'company' => $this->company,
            'record' => $this->record,
            'records' => $this->records,
            'totals' => $this->totals,
            'language' => Flight::get('language')
        ]);
        $html = ob_get_contents();
        ob_end_clean();
        //echo $html;
        //return;
        $mpdf->WriteHTML($html);
        $mpdf->Output($filename, 'D');
        exit;
    }

    /**
     * Find all transactions that are bookable and open.
     *
     * @param int optional id of person bean
     *
     * @uses $records array to store all bookable open transaction beans
     * @uses $totals
     */
    public function getOpenBookables(int $person_id = null)
    {
        $bookable_types = $this->record->getBookables();

        if ($person_id === null) {
            $this->records = R::find('transaction', " contracttype_id IN (".R::genSlots($bookable_types).") AND status IN (?) AND locked = 1 ORDER BY duedate", array_merge($bookable_types, ['open']));

            $this->totals = R::getRow("SELECT CAST(SUM(net) AS DECIMAL(10, 2)) AS totalnet, CAST(SUM(vat) AS DECIMAL(10, 2)) AS totalvat, CAST(SUM(gros) AS DECIMAL(10, 2)) AS totalgros, CAST(SUM(totalpaid) AS DECIMAL(10, 2)) AS totalpaid, CAST(SUM(balance) AS DECIMAL(10, 2)) AS totalbalance FROM transaction WHERE contracttype_id IN (".R::genSlots($bookable_types).") AND status IN (?) AND locked = 1 ORDER BY duedate", array_merge($bookable_types, ['open']));
        } else {
            $this->records = R::find('transaction', " contracttype_id IN (".R::genSlots($bookable_types).") AND status IN (?) AND locked = 1 AND person_id = ? ORDER BY duedate", array_merge($bookable_types, ['open'], [$person_id]));

            $this->totals = R::getRow("SELECT CAST(SUM(net) AS DECIMAL(10, 2)) AS totalnet, CAST(SUM(vat) AS DECIMAL(10, 2)) AS totalvat, CAST(SUM(gros) AS DECIMAL(10, 2)) AS totalgros, CAST(SUM(totalpaid) AS DECIMAL(10, 2)) AS totalpaid, CAST(SUM(balance) AS DECIMAL(10, 2)) AS totalbalance FROM transaction WHERE contracttype_id IN (".R::genSlots($bookable_types).") AND status IN (?) AND locked = 1 AND person_id = ? ORDER BY duedate", array_merge($bookable_types, ['open'], [$person_id]));
        }
    }

    /**
     * Renders the openitem page.
     */
    protected function render()
    {
        Flight::render('shared/notification', [], 'notification');
        //
        Flight::render('shared/navigation/account', [], 'navigation_account');
        Flight::render('shared/navigation/main', [], 'navigation_main');
        Flight::render('shared/navigation', [], 'navigation');
        Flight::render('openitem/toolbar', [
            'hasRecords' => count($this->records),
            'record' => $this->record,
            'person_id' => $_SESSION['openitem']['person_id']
        ], 'toolbar');
        Flight::render('shared/header', [], 'header');
        Flight::render('shared/footer', [], 'footer');
        Flight::render($this->template, [
            'record' => $this->record,
            'records' => $this->records,
            'totals' => $this->totals,
            'actions' => $this->actions,
            'current_action' => $this->action,
            'title' => I18n::__("openitem_head_title")
        ], 'content');
        Flight::render('html5', [
            'title' => I18n::__("openitem_head_title"),
            'language' => Flight::get('language'),
            'javascripts' => $this->javascripts
        ]);
    }
}
