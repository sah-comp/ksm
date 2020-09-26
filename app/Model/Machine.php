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
 * Machine model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Machine extends Model
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
                    'name' => 'machine.name'
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
        $this->addValidator('name', [
            new Validator_HasValue(),
            new Validator_IsUnique(['bean' => $this->bean, 'attribute' => 'name'])
        ]);
        $this->addConverter(
            'lastservice',
            new Converter_Mysqldate()
        );
    }
}
