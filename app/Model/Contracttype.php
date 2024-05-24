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
                'name'   => 'name',
                'sort'   => [
                    'name' => 'name',
                ],
                'filter' => [
                    'tag' => 'text',
                ],
            ],
            [
                'name'   => 'nickname',
                'sort'   => [
                    'name' => 'nickname',
                ],
                'filter' => [
                    'tag' => 'text',
                ],
                'width'  => '8rem',
            ],
            [
                'name'   => 'nextnumber',
                'sort'   => [
                    'name' => 'nextnumber',
                ],
                'class'  => 'number',
                'filter' => [
                    'tag' => 'number',
                ],
                'width'  => '8rem',
            ],
            [
                'name'     => 'enabled',
                'sort'     => [
                    'name' => 'contracttype.enabled',
                ],
                'callback' => [
                    'name' => 'boolean',
                ],
                'filter'   => [
                    'tag' => 'bool',
                ],
                'width'    => '5rem',
            ],
            [
                'name'     => 'service',
                'sort'     => [
                    'name' => 'contracttype.service',
                ],
                'callback' => [
                    'name' => 'boolean',
                ],
                'filter'   => [
                    'tag' => 'bool',
                ],
                'width'    => '5rem',
            ],
            [
                'name'     => 'ledger',
                'sort'     => [
                    'name' => 'contracttype.ledger',
                ],
                'callback' => [
                    'name' => 'boolean',
                ],
                'filter'   => [
                    'tag' => 'bool',
                ],
                'width'    => '5rem',
            ],
        ];
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->bean->wordgros = '';
        $this->addValidator('name', [
            new Validator_IsUnique(['bean' => $this->bean, 'attribute' => 'name']),
        ]);
    }

    /**
     * Update.
     */
    public function update()
    {
        $this->bean->updated = time();
        parent::update();
    }
}
