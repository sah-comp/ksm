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
 * Position model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Position extends Model
{
    /**
     * Define constant kind position.
     *
     * @const string
     */
    public const KIND_POSITION = 'position';

    /**
     * Define constant kind subtotal.
     *
     * @const string
     */
    public const KIND_SUBTOTAL = 'subtotal';

    /**
     * Define constant kind free text.
     *
     * @const string
     */
    public const KIND_FREETEXT = 'freetext';

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
                'name' => 'description',
                'sort' => [
                    'name' => 'position.description'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => 'auto'
            ],
            [
                'name' => 'total',
                'sort' => [
                    'name' => 'position.total'
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
     * Returns the default sort direction.
     *
     * 0 = asc
     * 1 = desc
     *
     * @return int
     */
    public function getDefaultSortDir()
    {
        return 1;
    }

    /**
     * Return the product bean.
     *
     * @return $person
     */
    public function getProduct()
    {
        if (! $this->bean->product) {
            $this->bean->product = R::dispense('product');
        }
        return $this->bean->product;
    }

    /**
     * Returns the name of the product
     *
     * @return string
     */
    public function productName()
    {
        return $this->bean->getProduct()->name;
    }

    /**
     * Return the vat bean.
     *
     * @return RedbeanPHP\OODBBean
     */
    public function getVat()
    {
        if (! $this->bean->vat) {
            $this->bean->vat = R::dispense('vat');
        }
        return $this->bean->vat;
    }

    /**
     * returns the vatpercentage.
     *
     * @return float
     */
    public function getVatPercentage(): float
    {
        return (float)$this->bean->vatpercentage;
    }

    /**
     * Return the costunittype bean.
     *
     * @return RedbeanPHP\OODBBean
     */
    public function getCostunittype()
    {
        if (! $this->bean->costunittype) {
            $this->bean->costunittype = R::dispense('costunittype');
        }
        return $this->bean->costunittype;
    }

    /**
     * Returns wether the position has alternative true or not
     *
     * @return bool
     */
    public function isAlternative()
    {
        return $this->bean->alternative;
    }

    /**
     * Returns either the desc attribute or a specific string if desc is empty.
     *
     * @return string
     */
    public function getStringSubtotal()
    {
        if ($this->bean->desc) {
            return $this->bean->desc;
        }
        return I18n::__('position_string_subtotal');
    }

    /**
     * Returns wether the position has adjustment or not
     *
     * @return bool
     */
    public function hasAdjustment()
    {
        if ($this->bean->adjustment == 0) {
            return false;
        }
        return true;
    }

    /**
     * Returns position kind for CSS styling.
     *
     * @return string
     */
    public function kindAsCss()
    {
        return strtolower($this->bean->kind);
    }

    /**
     * Returns SQL string.
     *
     * @param string (optional) $fields to select
     * @param string (optional) $where
     * @param string (optional) $order
     * @param int (optional) $offset
     * @param int (optional) $limit
     * @return string $sql
     */
    public function getSql($fields = 'id', $where = '1', $order = null, $offset = null, $limit = null)
    {
        $sql = <<<SQL
            SELECT
                {$fields}
            FROM
                {$this->bean->getMeta('type')}
            WHERE
                {$where}
SQL;
        //add optional order by
        if ($order) {
            $sql .= " ORDER BY {$order}";
        }
        //add optional limit
        if ($offset || $limit) {
            $sql .= " LIMIT {$offset}, {$limit}";
        }
        return $sql;
    }

    /**
     * Dispense.
     *
     * A position is used in a transaction and holds either a product, free text or a subtotal of
     * other positions. How a position works is defined be the kind attribute.
     *
     * Attributes:
     *
     */
    public function dispense()
    {
        $this->bean->kind = self::KIND_POSITION;//what kind of pos is it? position, subtotal, freetext
        $this->bean->count = 0;
        $this->bean->salesprice = 0;
        $this->bean->adjustment = 0;
        $this->bean->adjustval = 0;
        $this->bean->total = 0;
        $this->bean->vatpercentage = 0;
        $this->bean->vatamount = 0;
        $this->bean->gros = 0;
        $this->bean->currentindex = 0;
        $this->bean->sequence = 0;
        $this->addConverter('sequence', new Converter_Decimal());
        $this->addConverter('count', new Converter_Decimal());
        $this->addConverter('salesprice', new Converter_Decimal());
        $this->addConverter('adjustment', new Converter_Decimal());
        $this->addConverter('adjustval', new Converter_Decimal());
        $this->addConverter('total', new Converter_Decimal());
        $this->addConverter('vatpercentage', new Converter_Decimal());
        $this->addConverter('vatamount', new Converter_Decimal());
        $this->addConverter('gros', new Converter_Decimal());
    }

    /**
     * Calculate the position.
     */
    public function calcPosition()
    {
        if (!$this->bean->alternative && $this->bean->kind == self::KIND_POSITION) {

            //$this->bean->total = round($this->bean->count * $this->bean->salesprice, 2);
            $this->bean->total = $this->bean->count * $this->bean->salesprice;

            if ($this->bean->adjustment) {

                //$this->bean->adjustval = round($this->bean->total * $this->bean->adjustment / 100, 2);
                $this->bean->adjustval = $this->bean->total * $this->bean->adjustment / 100;

                $this->bean->total = $this->bean->total + $this->bean->adjustval;
            }

            //$this->bean->vatamount = round($this->bean->total * $this->bean->vatpercentage / 100, 2);
            $this->bean->vatamount = $this->bean->total * $this->bean->vatpercentage / 100;

            $this->bean->gros = $this->bean->total + $this->bean->vatamount;
        }
    }

    /**
     * Update.
     */
    public function update()
    {
        parent::update();

        if (!$this->bean->product_id) {
            $this->bean->product_id = null;
            unset($this->bean->product);
        }
        if (!$this->bean->vat_id) {
            $this->bean->vat_id = null;
            unset($this->bean->vat);
        }
        $this->bean->vatpercentage = $this->bean->getVat()->value;

        if (!$this->bean->costunittype_id) {
            $this->bean->costunittype_id = null;
            unset($this->bean->costunittype);
        }
        // calculate net, vat and gros
        $this->calcPosition();
    }
}
