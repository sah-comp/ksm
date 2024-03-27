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
 * Product model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Product extends Model
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
                'name' => 'number',
                'sort' => [
                    'name' => 'product.number'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => '12rem'
            ],
            [
                'name' => 'matchcode',
                'sort' => [
                    'name' => 'product.matchcode'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => '12rem'
            ],
            [
                'name' => 'description',
                'sort' => [
                    'name' => 'product.description'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => 'auto'
            ],
            [
                'name' => 'costunittype.name',
                'sort' => [
                    'name' => 'costunittype.name'
                ],
                'callback' => [
                    'name' => 'costunittypeName'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => '8rem'
            ],
            [
                'name' => 'vat.name',
                'sort' => [
                    'name' => 'vat.name'
                ],
                'callback' => [
                    'name' => 'vatName'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => '8rem'
            ],
            [
                'name' => 'purchaseprice',
                'sort' => [
                    'name' => 'product.purchaseprice'
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
                    'name' => 'product.salesprice'
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
     * Returns the name of the costunittype.
     *
     * @return string
     */
    public function costunittypeName()
    {
        return $this->bean->getCostunittype()->name;
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
     * Returns the name of the vat.
     *
     * @return string
     */
    public function vatName()
    {
        return $this->bean->getVat()->name;
    }

    /**
     * Look up searchtext in all fields of a bean.
     *
     * @param string $searchphrase
     * @return array
     */
    public function searchGlobal($searchphrase):array
    {
        $searchphrase = '%'.$searchphrase.'%';
        return R::find(
            $this->bean->getMeta('type'),
            ' number LIKE :f OR matchcode LIKE :f OR description LIKE :f OR unit LIKE :f',
            [
                ':f' => $searchphrase,
            ]
        );
    }

    /**
     * Lookup a searchterm and return the resultset as an array.
     *
     * @param string $searchtext
     * @param string (optional) $query The prepared query or SQL to use for search
     * @return array
     */
    public function clairvoyant($searchtext, $query = 'default', $limit = 23)
    {
        switch ($query) {
            default:
                $sql = <<<SQL
                SELECT
                    product.id AS id,
                    product.number AS ska,
                    product.vat_id AS vat_id,
                    product.costunittype_id AS costunittype_id,
                    vat.value AS vatpercentage,
                    CONCAT(product.number, ' ', product.description, ' ', FORMAT(product.salesprice, 2, 'de_DE')) AS label,
                    CONCAT(product.description) AS value,
                    1 AS count,
                    product.unit AS unit,
                    FORMAT(product.purchaseprice, 2, 'de_DE') AS purchaseprice,
                    FORMAT(product.salesprice, 2, 'de_DE') AS salesprice
                FROM
                    product
                LEFT JOIN
                    vat ON vat.id = product.vat_id
                WHERE
                    product.number LIKE :searchtext OR
                    product.matchcode LIKE :searchtext OR
                    product.description LIKE :searchtext
                ORDER BY
                    product.number
                LIMIT {$limit}
SQL;
        }
        $result = R::getAll($sql, array(':searchtext' => '%' . $searchtext . '%' ));
        return $result;
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
            LEFT JOIN
                vat ON vat.id = product.vat_id
            LEFT JOIN
                costunittype ON costunittype.id = product.costunittype_id
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
        $this->addValidator('number', array(
            new Validator_HasValue(),
            new Validator_IsUnique(array('bean' => $this->bean, 'attribute' => 'number'))
        ));
        $this->addConverter('purchaseprice', new Converter_Decimal());
        $this->addConverter('salesprice', new Converter_Decimal());
    }

    /**
     * Update.
     */
    public function update()
    {
        if (!$this->bean->costunittype_id) {
            $this->bean->costunittype_id = null;
            unset($this->bean->costunittype);
        }

        if (!$this->bean->vat_id) {
            $this->bean->vat_id = null;
            unset($this->bean->vat);
        }
        parent::update();
    }
}
