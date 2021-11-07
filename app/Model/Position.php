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
     */
    public function isAlternative()
    {
        return $this->bean->alternative;
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
     */
    public function dispense()
    {
        $this->bean->count = 0;
        $this->bean->salesprice = 0;
        $this->bean->total = 0;
        $this->bean->vatpercentage = 0;
        $this->bean->vatamount = 0;
        $this->bean->gros = 0;
        $this->bean->currentindex = 0;
        $this->addConverter('count', new Converter_Decimal());
        $this->addConverter('salesprice', new Converter_Decimal());
        $this->addConverter('total', new Converter_Decimal());
        $this->addConverter('vatpercentage', new Converter_Decimal());
        $this->addConverter('vatamount', new Converter_Decimal());
        $this->addConverter('gros', new Converter_Decimal());
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
        if (!$this->bean->costunittype_id) {
            $this->bean->costunittype_id = null;
            unset($this->bean->costunittype);
        }
        // calculate net, vat and gros
        $this->bean->total = $this->bean->count * $this->bean->salesprice;
        $this->bean->vatamount = $this->bean->total * $this->bean->vatpercentage / 100;
        $this->bean->gros = $this->bean->total + $this->bean->vatamount;
    }
}
