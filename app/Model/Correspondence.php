<?php
/**
 * Cinnebar.
 *
 * @package Cinnebar
 * @subpackage Model
 * @author $Author$
 * @version $Id$
 */

/**
 * Correspondence model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Correspondence extends Model
{
    /**
     * Returns an array with attributes for lists.
     *
     * @param string (optional) $layout
     * @return array
     */
    public function getAttributes($layout = 'table')
    {
        return array(
            array(
                'name' => 'subject',
                'sort' => array(
                    'name' => 'subject'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            )
        );
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->addValidator('subject', array(
            new Validator_HasValue()
        ));
    }
}
