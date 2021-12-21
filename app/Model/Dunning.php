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
 * Dunning model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Dunning extends Model
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
                ],
                'width' => '20rem'
            ],
            [
                'name' => 'grace',
                'sort' => [
                    'name' => 'grace'
                ],
                'class' => 'number',
                'callback' => [
                    'name' => 'decimal'
                ],
                'filter' => [
                    'tag' => 'number'
                ],
                'width' => '8rem'
            ],
            [
                'name' => 'applyto',
                'sort' => [
                    'name' => 'applyto'
                ],
                'callback' => [
                    'name' => 'applytoReadable'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => '10rem'
            ],
            [
                'name' => 'penaltyfee',
                'sort' => [
                    'name' => 'penaltyfee'
                ],
                'class' => 'number',
                'callback' => [
                    'name' => 'decimal'
                ],
                'filter' => [
                    'tag' => 'number'
                ],
                'width' => '6rem'
            ],
            [
                'name' => 'head',
                'sort' => [
                    'name' => 'head'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => 'auto'
            ],
            [
                'name' => 'foot',
                'sort' => [
                    'name' => 'foot'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => 'auto'
            ]
        ];
    }

    /**
     * Returns an array with attributes (of transaction) that the grace can by applied to.
     *
     * @return array
     */
    public function getApplyToAttributes()
    {
        return [
            'bookingdate',
            'dunningdate',
            'today'
        ];
    }

    /**
     * Returns a string with readable applyto attribute.
     *
     * @return string^
     */
    public function applytoReadable()
    {
        return I18n::__('dunning_option_' . $this->bean->applyto);
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->bean->sequence = 0;
        $this->bean->grace = 7;//days
        $this->bean->penaltyfee = 0;//additional payment
        $this->bean->applyto = 'bookingdate';//which shall the grace be applied upon?
        $this->addValidator('name', [
            new Validator_HasValue()
        ]);
        $this->addConverter('sequence', new Converter_Decimal());
        $this->addConverter('grace', new Converter_Decimal());
        $this->addConverter('penaltyfee', new Converter_Decimal());
    }
}
