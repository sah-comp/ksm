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
 * Costunittype model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Costunittype extends Model
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
                'name' => 'color',
                'sort' => [
                    'name' => 'color'
                ],
                'filter' => [
                    'tag' => 'text'
                ]
            ],
            [
                'name' => 'sequence',
                'sort' => [
                    'name' => 'sequence'
                ],
                'filter' => [
                    'tag' => 'number'
                ],
                'width' => '8rem'
            ],
        ];
    }

    /**
     * Returns a string with styling information of a scaffold table row.
     *
     * @return string
     */
    public function scaffoldStyle()
    {
        return "style=\"border-left: 5px solid {$this->bean->color};\"";
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->bean->sequence = 0;
        $this->addValidator('name', [
            new Validator_HasValue(),
            new Validator_IsUnique(['bean' => $this->bean, 'attribute' => 'name'])
        ]);
        $this->addValidator(
            'color',
            new Validator_HasValue()
        );
    }
}
