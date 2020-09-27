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
 * Appointment model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Appointment extends Model
{
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
                'name' => 'note',
                'sort' => [
                    'name' => 'note'
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
        $this->addConverter(
            'date',
            new Converter_Mysqldate()
        );
        $this->addConverter(
            'starttime',
            new Converter_Mysqltime()
        );
        $this->addConverter(
            'endtime',
            new Converter_Mysqltime()
        );
        $this->addConverter(
            'terminationdate',
            new Converter_Mysqldatetime()
        );
    }

    /**
     * Update.
     */
    public function update()
    {
        parent::update();
    }
}
