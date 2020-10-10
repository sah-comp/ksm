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
 * Cockpit controller.
 *
 * This is the home- or start page of the KSM solution.
 *
 * @package KSM
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Cockpit extends Controller
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
        Flight::render('cockpit/index', [
            'title' => 'Hello. Welcome to KSM solution.'
        ]);
    }
}
