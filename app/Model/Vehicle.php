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
 * Vehicle model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Vehicle extends Model
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
                'name' => 'licenseplate',
                'sort' => [
                    'name' => 'licenseplate'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => '13rem'
            ],
            [
                'name' => 'name',
                'sort' => [
                    'name' => 'name'
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
        $this->addValidator('licenseplate', [
            new Validator_HasValue(),
            new Validator_IsUnique(['bean' => $this->bean, 'attribute' => 'licenseplate'])
        ]);
    }
}
