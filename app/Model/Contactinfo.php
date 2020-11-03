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
 * Contactinfo model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Contactinfo extends Model
{
    /**
     * Returns an array with label names.
     *
     * @return array
     */
    public function getLabels()
    {
        return array(
            'mobile',
            'email',
            'telephone',
            'fax',
            'other'
        );
    }

    /**
     * Returns an array with attributes for lists.
     *
     * @param string (optional) $layout
     * @return array
     */
    public function getAttributes($layout = 'table')
    {
        return [
            [
                'name' => 'label',
                'sort' => [
                    'name' => 'contactinfo.label'
                ],
                'filter' => [
                    'tag' => 'text'
                ]
            ],
            [
                'name' => 'value',
                'sort' => [
                    'name' => 'contactinfo.value'
                ],
                'filter' => [
                    'tag' => 'text'
                ]
            ]
        ];
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
    }

    /**
     * Update.
     */
    public function update()
    {
        parent::update();
    }
}
