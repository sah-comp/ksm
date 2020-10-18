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
 * Enpassant controller.
 *
 * This controller is a co-worker to ajax call which want to update a single
 * value of a bean
 *
 * @package KSM
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Enpassant extends Controller
{
    /*
     * Lookup term of jQuery autocomplete requests and returns results json encoded.
     *
     * @param string $type The bean type to search
     * @param int $id
     * @param string $attr(ibute)
     * @return string $jsonEncodedArray JSON encoded response
     */
    public function update($type, $id, $attribute)
    {
        session_start();
        Auth::check();
        $bean = R::load($type, $id);
        $bean->{$attribute} = Flight::request()->data->value;
        R::begin();
        try {
            R::store($bean);
            R::commit();
            $result = [
                'okay' => true,
                'bean' => 'bean-' . $bean->getId(),
                'sortorder' => $bean->sortorder(),
                'woy' => $bean->weekofyear(),
                'trclass' => $bean->isOverdue()
            ];
        } catch (Exception $e) {
            error_log($e);
            R::rollback();
            $result = [
                'okay' => false,
                'bean' => 'bean-' . $bean->getId()
            ];
        }
        Flight::jsonp($result, 'callback');
    }
}
