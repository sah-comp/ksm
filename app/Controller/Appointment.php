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
 * Appointment controller.
 *
 * @package KSM
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Appointment extends Controller
{
    /**
     * Holds the appointment bean.
     *
     * @var object
     */
    public $appointment;

    /**
     * Constructor
     *
     * @param int $id ID of the contract to output as PDF
     */
    public function __construct($id)
    {
        session_start();
        Auth::check();
        $this->appointment = R::load('appointment', $id);
    }

    /**
     * Generates an PDF using mPDF library and downloads it to the client.
     *
     * @return void
     */
    public function pdf()
    {
        $company = R::load('company', CINNEBAR_COMPANY_ID);
        $filename = I18n::__('appointment_servicelist_filename', null, [date('Y-m-d-H-i-s')]);
        $title = I18n::__('appointment_servicelist_docname', null, [date('Y-m-d H:i:s')]);
        $mpdf = new mPDF('c', 'A4-L');
        $mpdf->SetTitle($title);
        $mpdf->SetAuthor($company->legalname);
        $mpdf->SetDisplayMode('fullpage');


        $records = R::find(
            'appointment',
            "confirmed = :yes AND
             completed != :yes
             ORDER BY date, starttime, fix, @joined.person.name, @joined.machine.name, @joined.machine.serialnumber",
            [
                 ':yes' => 1
            ]
        );

        ob_start();
        Flight::render('model/appointment/print', [
            'language' => 'de',
            'records' => $records,
            'company_name' => $company->legalname,
            'pdf_headline' => $title
        ]);
        $html = ob_get_contents();
        ob_end_clean();
        $mpdf->WriteHTML($html);
        $mpdf->Output($filename, 'D');
        exit;
    }

    /*
     * Sets the appointment to completed.
     */
    public function completed()
    {
        R::begin();
        try {
            $this->appointment->complete();
            //R::store($this->appointment);
            R::commit();
            Flight::get('user')->notify(I18n::__("appointment_completion_done"), 'success');
        } catch (Exception $e) {
            error_log($e);
            R::rollback();
            Flight::get('user')->notify(I18n::__("appointment_completion_failed"), 'error');
        }
        $this->redirect("/admin/appointment/edit/{$this->appointment->getId()}");
    }
}
