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
 * Correspondence controller.
 *
 * @package KSM
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Correspondence extends Controller_Scaffold
{
    /**
     * Holds the company bean.
     *
     * @var RedBeanPHP\OODBBean
     */
    public $company;

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
        Flight::render('model/correspondence/contact', [
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

    /**
     * Duplicates the given correspondence as another correspondencetype and redirects to edit it.
     */
    public function copy()
    {
        Permission::check(Flight::get('user'), $this->type, 'add');
        R::begin();
        try {
            $copy = R::duplicate($this->record);
            $copy->resetAfterCopy();
            R::store($copy);
            R::commit();
            Flight::get('user')->notify(I18n::__('correspondence_success_copy', null, [$this->record->number]), 'success');
            $this->redirect('/admin/correspondence/edit/' . $copy->getId());
            exit();
        } catch (\Exception $e) {
            R::rollback();
            error_log($e);
            Flight::get('user')->notify(I18n::__('correspondence_error_copy'), 'error');
            $this->redirect('/admin/correspondence/edit/' . $this->record->getId());
            exit();
        }
    }

    /**
     * Sends a email to the transaction recipient, cc to user who clicked button.
     */
    public function mail()
    {
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $user = Flight::get('user');

        //$filename = I18n::__('transaction_pdf_filename', null, [$this->record->getFilename()]);
        //$docname = I18n::__('transaction_pdf_docname', null, [$this->record->getDocname()]);
        //$mpdf = $this->generatePDF('letterhead', $docname); //when sending email, it can only be letterhead

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
        $mail->Subject = $this->record->subject;

        ob_start();
        Flight::render('model/correspondence/mail/html', array(
            'record' => $this->record,
            'company' => $this->company,
            'user' => $user
        ));
        $html = ob_get_clean();
        ob_start();
        Flight::render('model/correspondence/mail/text', array(
            'record' => $this->record,
            'company' => $this->company,
            'user' => $user
        ));
        $text = ob_get_clean();
        $mail->Body = $html;
        $mail->AltBody = $text;

        // add artifact(s) as attachment
        foreach ($this->record->ownArtifact as $id => $artifact) {
            $mail->AddAttachment(Flight::get('upload_dir') . '/' . $artifact->filename, $artifact->name);
        }

        if ($this->record->attachpdf) {
            $filename = I18n::__('correspondence_pdf_filename', null, [$this->record->getFilename()]);
            $mpdf = $this->generateSinglePDF($filename, 'letterhead');
            $attachment = $mpdf->Output('', 'S');
            $mail->addStringAttachment($attachment, $filename);
        }

        if ($mail->send()) {
            $this->record->sent = true;
            Flight::get('user')->notify(I18n::__("correspondence_mail_done"), 'success');
        } else {
            $this->record->sent = false;
            Flight::get('user')->notify(I18n::__("correspondence_mail_fail"), 'error');
        }
        R::store($this->record);
        $this->redirect("/admin/correspondence/edit/{$this->record->getId()}");
        exit();
    }

    /*
     * Generate a PDF with data deriving from the addressed correspondence bean.
     */
    public function pdf()
    {
        if ($this->record->getId()) {
            $this->pdfSingleCorrespondence();
        }
        $this->pdfList();
    }

    /**
     * Generate a PDF with all (filtered) records.
     */
    public function pdfList()
    {
        $this->getCollection();

        if (count($this->records) > CINNEBAR_MAX_RECORDS_TO_PDF) {
            Flight::get('user')->notify(I18n::__('warning_too_many_records_to_print', null, [CINNEBAR_MAX_RECORDS_TO_PDF, count($this->records)]), 'warning');
            $this->redirect('/admin/correspondence');
            exit();
        }
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $ts = date('Y-m-d-H-i-s');
        $filename = I18n::__('correspondence_pdf_list_filename', null, [$ts]);
        $docname = I18n::__('correspondence_pdf_list_docname', null, [$ts]);
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
        Flight::render('model/correspondence/pdf/list', [
            'title' => $docname,
            'company' => $this->company,
            'record' => $this->record,
            'records' => $this->records,
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

    /*
     * Download a PDF to the client.
     */
    public function pdfSingleCorrespondence()
    {
        $layout = Flight::request()->query->layout; //get the choosen layout from the query paramter "layout"
        $filename = I18n::__('correspondence_pdf_filename', null, [$this->record->getFilename()]);
        $mpdf = $this->generateSinglePDF($filename, $layout);
        $mpdf->Output($filename, 'D');
        exit;
    }

    /*
     * Generate a PDF and return the mpdf object;
     *
     * @param string $filename
     * @param string $layout
     * @ereturn \Mpdf\Mpdf
     */
    public function generateSinglePDF($filename, $layout)
    {
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $docname = I18n::__('correspondence_pdf_docname', null, [$this->record->getDocname()]);
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
        Flight::render('model/correspondence/pdf/' . $layout, [
            'title' => $docname,
            'company' => $this->company,
            'record' => $this->record,
            'language' => Flight::get('language')
        ]);
        $html = ob_get_contents();
        ob_end_clean();
        $mpdf->WriteHTML($html);
        return $mpdf;
    }
}
