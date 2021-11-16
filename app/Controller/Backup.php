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
 * Backup controller.
 *
 * @package KSM
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Backup extends Controller
{
    /*
     * Backup smth.
     */
    public function run()
    {
        session_start();
        Auth::check();
        $this->redirect('/cockpit');
        exit();
    }
}
