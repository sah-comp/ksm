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
 * Discount model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Discount extends Model
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
                'name' => 'name',
                'sort' => [
                    'name' => 'name'
                ],
                'filter' => [
                    'tag' => 'text'
                ]
            ],
            [
                'name' => 'value',
                'sort' => [
                    'name' => 'value'
                ],
                'callback' => [
                    'name' => 'decimal'
                ],
                'class' => 'number',
                'filter' => [
                    'tag' => 'number'
                ],
                'width' => '8rem'
            ],
            [
                'name' => 'days',
                'sort' => [
                    'name' => 'days'
                ],
                'callback' => [
                    'name' => 'integer'
                ],
                'class' => 'number',
                'filter' => [
                    'tag' => 'number'
                ],
                'width' => '8rem'
            ]
        ];
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
        /*
        $this->addValidator('name', array(
            new Validator_HasValue()
        ));
        */
        $this->addConverter('value', [
            new Converter_Decimal()
        ]);
        $this->addConverter('days', [
            new Converter_Decimal()
        ]);
    }
}
