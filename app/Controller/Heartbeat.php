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
 * Heartbeat controller.
 *
 * Frequently check system status and update information in badges.
 * This will mostly check for service appointments added while being on
 * the service page.
 *
 * @package KSM
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Heartbeat extends Controller
{
    /*
     * A beat of our heart.
     *
     * @return string $jsonEncodedArray JSON encoded response
     */
    public function tick()
    {
        session_start();
        Auth::check();
        $result = [
            'stamp' => time()
        ];
        Flight::json($result);
    }
}
