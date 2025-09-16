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
 * Treaty controller.
 *
 * Due to misunderstandings the treaty controller is the (real) contract controller.
 *
 * @package KSM
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Treaty extends Controller_Scaffold
{
    /**
     * Holds the company bean.
     *
     * @var object
     */
    public $company;

    /**
     * Holds the contract bean.
     *
     * @var object
     */
    public $treaty;

    /**
     * Holds the machine bean.
     *
     * @var object
     */
    public $machine;

    /**
     * Holds the location bean.
     *
     * @var object
     */
    public $location;

    /**
     * Holds the person (customer) bean.
     *
     * @var object
     */
    public $person;

    /**
     * Holds the text of the contract, depending on the contracttype.
     *
     * @var string
     */
    public $text;

    /**
     * Holds the placeholders and attributes to be filled in.
     * @var array
     */
    public $placeholders = [
        'treaty.number' => '',
        'company.legalname' => '',
        'company.formattedaddress' => 'formattedAddress',
        'company.senderline' => 'getSenderline',
        'person.name' => '',
        'person.formattedaddress' => 'formattedAddress',
        'treaty.startdate' => 'localizedDate',
        'treaty.priceperunit' => 'decimal',
        'treaty.unit' => 'localizedUnit',
        'treaty.enddate' => 'localizedDate',
        'location.name' => '',
        'company.city' => '',
        'company.email' => '',
        'company.fax' => '',
        'treaty.signdate' => 'localizedDate'
    ];

    /**
     * Constructs a new Scaffold controller.
     *
     * @todo get rid of eval and develop gestalt more
     *
     * @param string $base_url for scaffold links and redirects
     * @param string $type of the bean to scaffold
     * @param int (optional) $id of the bean to handle
     */
    public function __construct($base_url, $type, $id = null)
    {
        parent::__construct($base_url, $type, $id);
        $this->treaty = $this->record;
    }

    /**
     * Sends a email to the treaty recipient, cc to user who clicked button.
     */
    public function mail()
    {
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $user = Flight::get('user');

        $filename = I18n::__('treaty_pdf_filename', null, [$this->record->getFilename()]);
        $docname = I18n::__('treaty_pdf_docname', null, [$this->record->getDocname()]);
        $mpdf = $this->pdf(true);

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
            $mail->Port = $smtp['port'];                          // SMTP port
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
        if ($this->record->toAddress()) {
            $mail->addAddress($this->record->toAddress(), $this->record->toName());
        }

        if ($this->record->to !== '') {
            $pos = strpos($this->record->to, ';');
            if ($pos === false) {
                $mail->addCC($this->record->to);
            } else {
                $emails = explode(';', $this->record->to);
                foreach ($emails as $email) {
                    $mail->addAddress(trim($email));
                }
            }
        }


        if ($this->record->cc !== '') {
            $pos = strpos($this->record->cc, ';');
            if ($pos === false) {
                $mail->addCC($this->record->cc);
            } else {
                $emails = explode(';', $this->record->cc);
                foreach ($emails as $email) {
                    $mail->addAddress(trim($email));
                }
            }
        }

        $mail->addBCC($user->email, $user->name);
        //$mail->WordWarp = 50;
        $mail->isHTML(true);
        $mail->Subject = $docname;

        ob_start();
        Flight::render('model/treaty/mail/html', array(
            'record' => $this->record,
            'company' => $this->company,
            'user' => $user
        ));
        $html = ob_get_clean();
        ob_start();
        Flight::render('model/treaty/mail/text', array(
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
            $this->record->sent = true;
            Flight::get('user')->notify(I18n::__("treaty_mail_done"), 'success');
        } else {
            $this->record->sent = false;
            Flight::get('user')->notify(I18n::__("treaty_mail_fail"), 'error');
        }
        R::store($this->record);
        $this->redirect("/admin/treaty/edit/{$this->record->getId()}");
        exit();
    }

    /*
     * Generate a PDF with data deriving from the addressed contract bean.
     *
     * @param bool $returnAsAttachment
     * @return mixed
     */
    public function pdf($returnMpdf = false)
    {
        $this->treaty->signdate = date('Y-m-d');
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $this->person = $this->treaty->getPerson();
        //$this->machine = $this->treaty->getMachine();
        $this->location = $this->treaty->getLocation();
        $this->text = $this->treaty->ctext;
        R::store($this->treaty);
        $filename = I18n::__('treaty_pdf_filename', null, [$this->treaty->getFilename()]);
        $docname = I18n::__('treaty_pdf_docname', null, [$this->treaty->getDocname()]);
        $this->text = $this->substitute();
        $this->text = $this->substituteLimb();
        $mpdf = $this->generatePDF($docname);
        if ($returnMpdf) {
            return $mpdf;
        }
        $mpdf->Output($filename, 'D');
        exit;
    }

    /**
     * Generates a PDF.
     *
     * @param string $docname
     * @return \Mpdf\Mpdf
     */
    private function generatePDF($docname)
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
        Flight::render('model/treaty/treaty/treaty', [
            'title' => $docname,
            'company' => $this->company,
            'record' => $this->treaty,
            'text' => $this->text,
            'language' => Flight::get('language')
        ]);
        $html = ob_get_contents();
        ob_end_clean();
        //echo $html;
        //return;
        $mpdf->WriteHTML($html);
        return $mpdf;
    }

    /*
     * Generate a HTML page with data deriving from the addressed contract bean.
     */
    public function form()
    {
        if (Flight::request()->method == 'POST') {
            if (! Security::validateCSRFToken(Flight::request()->data->token)) {
                $this->redirect("/logout");
                exit();
            }
            Permission::check(Flight::get('user'), $this->treaty->getMeta('type'), 'edit');
            $limb = Flight::request()->data->limb;
            $this->treaty->payload = json_encode($limb);
            R::begin();
            try {
                R::store($this->treaty);
                R::commit();
                Flight::get('user')->notify(I18n::__('scaffold_success_edit'), 'success');
                $this->redirect('/admin/treaty/edit/' . $this->treaty->getId());
                exit();
            } catch (Exception $e) {
                R::rollback();
                Flight::get('user')->notify(I18n::__('scaffold_error_edit'), 'error');
                error_log($e);
            }
        }
        $this->treaty->signdate = date('Y-m-d');
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $this->person = $this->treaty->getPerson();
        //$this->machine = $this->treaty->getMachine();
        $this->location = $this->treaty->getLocation();
        $this->text = $this->treaty->ctext;
        R::store($this->treaty);
        $this->text = $this->substitute();
        $this->text = $this->substituteLimbAsInput();
        Flight::render('model/treaty/treaty/form', [
            'title' => I18n::__('treaty_pdf_docname', null, [$this->treaty->getDocname()]),
            'company' => $this->company,
            'record' => $this->treaty,
            'text' => $this->text,
            'language' => Flight::get('language')
        ]);
    }

    /**
     * Duplicates the given treaty as another contracttype and redirects to edit it.
     */
    public function copy()
    {
        Permission::check(Flight::get('user'), 'treaty', 'add');
        if (Flight::request()->query->submit == I18n::__('treaty_action_copy_as')) {
            if (! Security::validateCSRFToken(Flight::request()->query->token)) {
                $this->redirect("/logout");
                exit();
            }
            R::begin();
            try {
                $copy = R::duplicate($this->treaty);
                $copy->contracttype_id = Flight::request()->query->copyas;
                $copy->mytreatyid = $this->treaty->getId();
                $copy->treatygroup_id = null;
                $copy->treatygroup = null;
                $copy->bookingdate = date('Y-m-d');
                $copy->archived = false;
                R::store($copy);
                R::commit();
                Flight::get('user')->notify(I18n::__('treaty_success_copy', null, [$this->treaty->number, $copy->contracttype->name]), 'success');
                $this->redirect('/admin/treaty/edit/' . $copy->getId());
                exit();
            } catch (\Exception $e) {
                R::rollback();
                error_log($e);
                Flight::get('user')->notify(I18n::__('treaty_error_copy'), 'error');
                $this->redirect('/admin/treaty/edit/' . $this->treaty->getId());
                exit();
            }
        }
    }

    /**
     * Replaces the placeholders with actual content.
     *
     * @return string
     */
    public function substitute()
    {
        foreach ($this->placeholders as $searchtext => $callback) {
            $splitter = explode('.', $searchtext);
            $bean = $splitter[0];
            $attribute = $splitter[1];
            if ($callback) {
                $replacetext = $this->{$bean}->{$callback}($attribute);
            } else {
                $replacetext = $this->{$bean}->{$attribute};
            }
            if (empty($replacetext)) {
                $replacetext = I18n::__('treaty_replacetext_empty');
            }
            $this->text = str_replace("{{".$searchtext."}}", nl2br($replacetext), $this->text);
        }
        return $this->text;
    }

    /**
     * Replaces even more placeholders with actual content.
     *
     * @return string
     */
    public function substituteLimb()
    {
        $payload = json_decode($this->treaty->payload, true);
        foreach ($this->treaty->contracttype->withCondition("active = 1 ORDER BY sequence")->ownLimb as $id => $limb) {
            $value = '';
            if (isset($payload[$limb->stub])) {
                if ($limb->tag == 'textarea') {
                    $value = Flight::textile($payload[$limb->stub]);
                } else {
                    $value = $payload[$limb->stub];
                    if (empty($value)) {
                        $value = I18n::__('treaty_replacetext_empty');
                    }
                }
            }
            $this->text = str_replace("{{".$limb->stub."}}", $value, $this->text);
        }
        return $this->text;
    }

    /**
     * Replaces placeholders with input fields instead of content.
     *
     * @return string
     */
    public function substituteLimbAsInput()
    {
        $payload = json_decode($this->treaty->payload, true);
        foreach ($this->treaty->contracttype->withCondition("active = 1 ORDER BY sequence")->ownLimb as $id => $limb) {
            $value = '';
            $size = max(25, mb_strlen($limb->placeholder) + 3);
            if (isset($payload[$limb->stub])) {
                $value = htmlspecialchars($payload[$limb->stub]);
            }
            switch ($limb->tag) {
                ;
                case 'textarea':
                    $input = <<<HTML
                        <textarea
                            name="limb[{$limb->stub}]"
                            placeholder="{$limb->placeholder}"
                            title="{$limb->name}"
                            rows="5"
                            cols="60">{$value}</textarea>
HTML;
                    break;

                default:
                    $input = <<<HTML
                    <input
                        type="text"
                        name="limb[{$limb->stub}]"
                        size="{$size}"
                        placeholder="{$limb->placeholder}"
                        title="{$limb->name}"
                        value="{$value}">
HTML;
                    break;
            }
            $this->text = str_replace("{{".$limb->stub."}}", $input, $this->text);
        }
        return $this->text;
    }

    /**
     * Rerenders the "person-dependent" part of an correspondence form.
     *
     * @todo documentation
     * Requires the following data-* in your html:
     *  - data-extra="correspondence-person-id"
     *  - data-dynamic="URL TO THIS FUNCTION"
     *
     * @return JSONP
     */
    public function dependent()
    {
        $person = R::load('person', Flight::request()->data->person_id);
        $dependents = $this->record->getDependents($person);
        $this->record->person = $person;
        ob_start();
        Flight::render('model/treaty/contact', [
            'person' => $person,
            'record' => $this->record,
            'contacts' => $dependents['contacts']
        ]);
        $html = ob_get_contents();
        ob_end_clean();

        $result = [
            'okay' => true,
            'html' => $html
        ];

        Flight::jsonp($result, 'callback');
    }
}
