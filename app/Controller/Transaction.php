<?php

use horstoeko\zugferd\ZugferdDocumentPdfMerger;

/**
 * KSM.
 *
 * @package KSM
 * @subpackage Controller
 * @author $Author$
 * @version $Id$
 */

/**
 * Transaction controller.
 *
 * @package KSM
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Transaction extends Controller_Scaffold
{
    /**
     * Holds the company bean.
     *
     * @var RedBeanPHP\OODBBean
     */
    public $company;

    /**
     * Holds the totals.
     *
     * @var array
     */
    public $totals = [];

    /**
     * Duplicates the given transaction as another transactiontype and redirects to edit it.
     */
    public function copy()
    {
        Permission::check(Flight::get('user'), $this->type, 'add');
        if (Flight::request()->query->submit == I18n::__('transaction_action_copy_as')) {
            if (! Security::validateCSRFToken(Flight::request()->query->token)) {
                $this->redirect("/logout");
                exit();
            }
            R::begin();
            try {
                $copy                  = R::duplicate($this->record);
                $copy->contracttype_id = Flight::request()->query->copyas;
                $copy->mytransactionid = $this->record->getId();
                $copy->resetAfterCopy();
                R::store($copy);
                R::commit();
                Flight::get('user')->notify(I18n::__('transaction_success_copy', null, [$this->record->number, $copy->contracttype->name]), 'success');
                $this->redirect('/admin/transaction/edit/' . $copy->getId());
                exit();
            } catch (\Exception $e) {
                R::rollback();
                error_log($e);
                Flight::get('user')->notify(I18n::__('transaction_error_copy'), 'error');
                $this->redirect('/admin/transaction/edit/' . $this->record->getId());
                exit();
            }
        }
    }

    /*
     * Sets the transaction to paid.
     */
    public function bookaspaid()
    {
        R::begin();
        try {
            //error_log('Transaction #' . $this->record->getId() . ' paid?');
            $this->record->status = 'paid';
            R::store($this->record);
            R::commit();
            Flight::get('user')->notify(I18n::__("transaction_paid_done"), 'success');
        } catch (Exception $e) {
            error_log($e);
            R::rollback();
            Flight::get('user')->notify(I18n::__("transaction_paid_failed"), 'error');
        }
        $this->redirect("/admin/transaction/edit/{$this->record->getId()}");
        exit();
    }

    /*
     * Generate a PDF with data deriving from the addressed transaction bean.
     */
    public function pdf()
    {
        if ($this->record->getId()) {
            $this->pdfSingleTransaction();
        }
        $this->pdfList();
    }

    /**
     * Switch the booking semaphore of the current user session and redirects to the index page.
     */
    public function booking()
    {
        if (isset($_SESSION['user']['booking']) && $_SESSION['user']['booking'] === true) {
            $_SESSION['user']['booking'] = false;
        } else {
            $_SESSION['user']['booking'] = true;
        }
        $this->redirect("/admin/transaction");
    }

    /**
     * Sends a email to the transaction recipient, cc to user who clicked button.
     */
    public function mail()
    {

        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $user          = Flight::get('user');

        $filename = I18n::__('transaction_pdf_filename', null, [$this->record->getFilename()]);
        $docname  = I18n::__('transaction_pdf_docname', null, [$this->record->getDocname()]);
        $mpdf     = $this->generatePDF('letterhead', $docname); //when sending email, it can only be letterhead

        $mail = new PHPMailer\PHPMailer\PHPMailer();

        if ($smtp = $this->company->smtp()) {
            $mail->SMTPDebug = 4; // Set debug mode, 1 = err/msg, 2 = msg
            /**
             * uncomment this block to get verbose error logging in your error log file
             */

            $mail->Debugoutput = function ($str, $level) {
                error_log("debug level $level; message: $str");
            };

            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = $smtp['host']; // Specify main and backup server
            if ($smtp['auth']) {
                $mail->SMTPAuth = true; // Enable SMTP authentication
            } else {
                $mail->SMTPAuth = false; // Disable SMTP authentication
            }
            $mail->Port       = $smtp['port']; // SMTP port
            $mail->Username   = $smtp['user']; // SMTP username
            $mail->Password   = $smtp['password']; // SMTP password
            $mail->SMTPSecure = 'tls'; // Enable encryption, 'ssl' also accepted

            /**
             * @see https://stackoverflow.com/questions/30371910/phpmailer-generates-php-warning-stream-socket-enable-crypto-peer-certificate
             */
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true,
                ],
            ];
        }

        $mail->CharSet = 'UTF-8';
        $mail->AddEmbeddedImage(__DIR__ . '/../../public/img/ksm-email-signature-icon.jpg', 'ksm-mascot');
        $mail->setFrom($this->company->emailnoreply, $this->company->legalname);

        $mail->addReplyTo($user->email, $user->name);

        //$mail->addAddress(KSM_EMAIL_TESTADDRESS, KSM_EMAIL_TESTNAME);

        $pos = strpos($this->record->billingemail, ';');
        if ($pos === false) {
            $mail->addAddress($this->record->billingemail, $this->record->person->name);
        } else {
            $emails = explode(';', $this->record->billingemail);
            foreach ($emails as $email) {
                $mail->addAddress($email, $this->record->person->name);
            }
        }

        $mail->addBCC($user->email, $user->name);

        $mail->isHTML(true);
        $mail->Subject = $docname;

        ob_start();
        Flight::render('model/transaction/mail/html', [
            'record'  => $this->record,
            'company' => $this->company,
            'user'    => $user,
        ]);
        $html = ob_get_clean();
        ob_start();
        Flight::render('model/transaction/mail/text', [
            'record'  => $this->record,
            'company' => $this->company,
            'user'    => $user,
        ]);
        $text          = ob_get_clean();
        $mail->Body    = $html;
        $mail->AltBody = $text;

        $attachment = $mpdf->Output('', 'S'); //the pdf as a string
        // if that type of transaction wants to empbed XML into the pdf, do it:
        if ($this->record->getContracttype()->xmlit) {
            $xml   = $this->record->getXML(); //the xml of the invoice
            $horse = new ZugferdDocumentPdfMerger($xml, $attachment);

            $fullpdf = $horse->generateDocument()->downloadString($filename);
        } else {
            $fullpdf = $attachment;
        }

        $mail->addStringAttachment($fullpdf, $filename);

        if ($mail->send()) {
            $this->record->sent = true;
            Flight::get('user')->notify(I18n::__("transaction_mail_done"), 'success');
        } else {
            $this->record->sent = false;
            Flight::get('user')->notify(I18n::__("transaction_mail_fail"), 'error');
        }
        R::store($this->record);
        $this->redirect("/admin/transaction/edit/{$this->record->getId()}");
        exit();
    }

    /**
     * Generate a PDF with all (filtered) records.
     */
    public function pdfList()
    {
        $this->getCollection();

        if (count($this->records) > CINNEBAR_MAX_RECORDS_TO_PDF) {
            Flight::get('user')->notify(I18n::__('warning_too_many_records_to_print', null, [CINNEBAR_MAX_RECORDS_TO_PDF, count($this->records)]), 'warning');
            $this->redirect('/admin/transaction');
            exit();
        }
        $ts = date('Y-m-d');
        $this->getTotals();
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $filename      = I18n::__('transaction_pdf_list_filename', null, [$ts]);
        $docname       = I18n::__('transaction_pdf_list_docname', null, [$ts]);
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
        Flight::render('model/transaction/pdf/list', [
            'title'    => $docname,
            'company'  => $this->company,
            'record'   => $this->record,
            'records'  => $this->records,
            'totals'   => $this->totals,
            'language' => Flight::get('language'),
        ]);
        $html = ob_get_contents();
        ob_end_clean();
        //echo $html;
        //return;
        $mpdf->WriteHTML($html);
        $mpdf->Output($filename, 'D');
        exit;
    }

    /*
     * Output the PDF.
     *
     * @uses generatePDF()
     */
    public function pdfSingleTransaction()
    {
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $layout        = Flight::request()->query->layout; //get the choosen layout from the query paramter "layout"
        $filename      = I18n::__('transaction_pdf_filename', null, [$this->record->getFilename()]);
        $docname       = I18n::__('transaction_pdf_docname', null, [$this->record->getDocname()]);
        $mpdf          = $this->generatePDF($layout, $docname);
        $mpdf->Output($filename, 'D');
        exit;
    }

    /**
     * Generates a PDF using the mpdf library.
     *
     * @param string $layout which template to use for rendering
     * @param string $docname name of the template
     * @return \Mpdf\Mpdf
     */
    private function generatePDF($layout = 'letterhead', $docname = 'Transaction')
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
            'title'    => $docname,
            'company'  => $this->company,
            'record'   => $this->record,
            'language' => Flight::get('language'),
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
     * Calculates the totals.
     *
     * @uses $totals to store the calculated totals of all (or filtered) records
     *
     * @return void
     */
    public function getTotals()
    {
        $where = $this->filter->buildWhereClause();
        $sql   = "SELECT CAST(SUM(gros) AS DECIMAL(10, 2)) AS totalgros, CAST(SUM(net) AS DECIMAL(10, 2)) AS totalnet, CAST(SUM(vat) AS DECIMAL(10, 2)) AS totalvat FROM transaction LEFT JOIN contracttype ON contracttype.id = transaction.contracttype_id LEFT JOIN person ON person.id = transaction.person_id WHERE " . $where;
        R::debug(true);
        $this->totals = R::getRow($sql, $this->filter->getFilterValues());
        R::debug(false);
        return null;
    }

    /**
     * Rerenders the "person-dependent" part of an transaction form.
     *
     * @todo documentation
     * Requires the following data-* in your html:
     *  - data-extra="transaction-person-id"
     *  - data-dynamic="URL TO THIS FUNCTION"
     *
     * @return JSONP
     */
    public function dependent()
    {
        $person               = R::load('person', Flight::request()->data->person_id);
        $dependents           = $this->record->getDependents($person);
        $this->record->person = $person;
        ob_start();
        Flight::render('model/transaction/billingmail', [
            'person'   => $person,
            'record'   => $this->record,
            'contacts' => $dependents['contacts'],
        ]);
        $html = ob_get_contents();
        ob_end_clean();

        $result = [
            'okay' => true,
            'html' => $html,
        ];

        Flight::jsonp($result, 'callback');
    }
}
