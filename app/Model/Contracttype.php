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
 * Contracttype model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Contracttype extends Model
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
                'width' => '10rem'
            ],
            [
                'name' => 'text',
                'sort' => [
                    'name' => 'text'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => 'auto'
            ]
        ];
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->addValidator('name', [
            new Validator_IsUnique(['bean' => $this->bean, 'attribute' => 'name'])
        ]);
    }
}
