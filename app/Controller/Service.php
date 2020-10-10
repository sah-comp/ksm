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
 * Service(appointments) controller.
 *
 * @package KSM
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Service extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        session_start();
        Auth::check();
    }

    /*
     * Index.
     */
    public function index()
    {
        Flight::render('service/index', [
            'title' => 'Hello. I am the page where you will handle service appointments.'
        ]);
    }
}
