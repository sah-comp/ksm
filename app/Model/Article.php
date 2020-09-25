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
 * Article model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Article extends Model
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
                'name' => 'isfilter',
                'sort' => [
                    'name' => 'article.isfilter'
                ],
                'callback' => [
                    'name' => 'boolean'
                ],
                'filter' => [
                    'tag' => 'bool'
                ],
                'width' => '8rem'
            ],
            [
                'name' => 'isoriginal',
                'sort' => [
                    'name' => 'article.isoriginal'
                ],
                'callback' => [
                    'name' => 'boolean'
                ],
                'filter' => [
                    'tag' => 'bool'
                ],
                'width' => '8rem'
            ],
            [
                'name' => 'number',
                'sort' => [
                    'name' => 'number'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => '8rem'
            ],
            [
                'name' => 'description',
                'sort' => [
                    'name' => 'description'
                ],
                'filter' => [
                    'tag' => 'text'
                ]
            ],
            [
                'name' => 'purchaseprice',
                'sort' => [
                    'name' => 'purchaseprice'
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
                'name' => 'salesprice',
                'sort' => [
                    'name' => 'salesprice'
                ],
                'class' => 'number',
                'callback' => [
                    'name' => 'decimal'
                ],
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
        $this->addConverter('purchaseprice', new Converter_Decimal());
        $this->addConverter('salesprice', new Converter_Decimal());
    }

    /**
     * Update.
     */
    public function update()
    {
        parent::update();

        if ($this->bean->isfilter) {
            $this->bean->salesprice = $this->bean->purchaseprice * 5 * 1.15;
        } else {
            $this->bean->salesprice = $this->bean->purchaseprice * 3 * 1.15;
        }
        
        // if the price has changed, we record it in our article statistics.
        if ($this->bean->purchaseprice != $this->bean->old('purchaseprice')) {
            $artstat = R::dispense('artstat');
            $artstat->salesprice = $this->bean->old('salesprice');
            $artstat->purchaseprice = $this->bean->old('purchaseprice');
            $this->bean->ownArtstat[] = $artstat;
        }
    }
}
