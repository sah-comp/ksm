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
                'name' => 'date',
                'sort' => [
                    'name' => 'appointment.date'
                ],
                'callback' => [
                    'name' => 'localizedDate'
                ],
                'filter' => [
                    'tag' => 'date'
                ],
                'width' => '8rem'
            ],
            [
                'name' => 'starttime',
                'sort' => [
                    'name' => 'appointment.starttime'
                ],
                'callback' => [
                    'name' => 'localizedTime'
                ],
                'filter' => [
                    'tag' => 'time'
                ],
                'width' => '8rem'
            ],
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
     * Returns a string with styling information of a scaffold table row.
     *
     * @return string
     */
    public function scaffoldStyle()
    {
        if (! $this->bean->appointmenttype) {
            return "style=\"border-left: 3px solid inherit;\"";
        }
        return "style=\"border-left: 3px solid {$this->bean->appointmenttype->color};\"";
        //return "style=\"box-shadow: inset 0 0 0 4px coral;;\"";
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
