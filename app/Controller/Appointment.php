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

    /*
     * Sets the appointment to completed.
     */
    public function completed()
    {
        R::begin();
        try {
            $this->appointment->complete();
            R::store($this->appointment);
            R::commit();
            Flight::get('user')->notify(I18n::__("appointment_completion_done"), 'success');
        } catch (Exception $e) {
            R::rollback();
            Flight::get('user')->notify(I18n::__("appointment_completion_failed"), 'error');
        }
        $this->redirect("/admin/appointment/edit/{$this->appointment->getId()}");
    }
}
