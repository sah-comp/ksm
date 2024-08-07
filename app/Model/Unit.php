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
 * unit model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Unit extends Model
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
                'name'   => 'code',
                'sort'   => [
                    'name' => 'code',
                ],
                'filter' => [
                    'tag' => 'text',
                ],
            ],
        ];
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->addValidator('name', [
            new Validator_HasValue(),
            new Validator_IsUnique(['bean' => $this->bean, 'attribute' => 'name']),
        ]);
    }
}
