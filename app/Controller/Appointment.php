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
     * @param string optional $base_url
     * @param string optional $type
     * @param int optional $id ID of the contract to output as PDF
     */
    public function __construct($base_url = null, $type = null, $id = null)
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
        $filename = I18n::__('appointment_list_filename', null, [date('Y-m-d-H-i-s')]);
        $title = I18n::__('appointment_list_docname', null, [date('Y-m-d H:i:s')]);
        $mpdf = new \Mpdf\Mpdf(['mode' => 'c', 'format' => 'A4-L']);
        $mpdf->SetTitle($title);
        $mpdf->SetAuthor($company->legalname);
        $mpdf->SetDisplayMode('fullpage');

        $filter = R::load('filter', $_SESSION['scaffold']['appointment']['filter']['id']);
        $where = $filter->buildWhereClause();
        $sqlCollection = $this->appointment->getSql(
            "DISTINCT(appointment.id) AS id",
            $where,
            'person.name, machine.serialnumber'
        );
        $rows = R::getAssoc($sqlCollection, $filter->getFilterValues());
        $records = R::batch('appointment', array_keys($rows));

        if (count($records) > CINNEBAR_MAX_RECORDS_TO_PDF) {
            Flight::get('user')->notify(I18n::__('warning_too_many_records_to_print', null, [CINNEBAR_MAX_RECORDS_TO_PDF, count($records)]), 'warning');
            $this->redirect('/admin/appointment');
            exit();
        }

        ob_start();
        Flight::render('model/appointment/print', [
            'language' => Flight::get('language'),
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
        exit();
    }

    /**
     * Rerenders the "person-dependent" part of an appointment form.
     *
     * @return JSONP
     */
    public function dependent()
    {
        $person = R::load('person', Flight::request()->data->person_id);
        $dependents = $this->appointment->getDependents($person);
        $this->appointment->person = $person;
        ob_start();
        Flight::render('model/appointment/machinecontactlocation', [
            'person' => $person,
            'record' => $this->appointment,
            'machines' => $dependents['machines'],
            'contacts' => $dependents['contacts'],
            'locations' => $dependents['locations']
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
     * Rerenders the "person-dependent" part of an appointment form.
     *
     * @param int $person_id
     * @return JSONP
     */
    public function contractLocationByMachineWith($person_id)
    {
        $location_id = R::getCell("SELECT location_id FROM contract WHERE person_id = ? AND machine_id = ? LIMIT 1", [$person_id, Flight::request()->data->machine_id]);
        $result = [
            'okay' => true,
            'location_id' => $location_id
        ];
        Flight::jsonp($result, 'callback');
    }
}
