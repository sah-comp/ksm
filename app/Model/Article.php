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
                'width' => '5rem'
            ],
            [
                'name' => 'number',
                'sort' => [
                    'name' => 'article.number'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => '12rem'
            ],
            [
                'name' => 'supplier.name',
                'sort' => [
                    'name' => 'supplier.name'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'callback' => [
                    'name' => 'supplierName'
                ],
                'width' => '12rem'
            ],
            [
                'name' => 'description',
                'sort' => [
                    'name' => 'article.description'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => 'auto'
            ],
            [
                'name' => 'lastchange',
                'sort' => [
                    'name' => 'article.lastchange'
                ],
                'callback' => [
                    'name' => 'localizedDate'
                ],
                'filter' => [
                    'tag' => 'date'
                ],
                'class' => 'date',
                'width' => '8rem'
            ],
            [
                'name' => 'purchaseprice',
                'sort' => [
                    'name' => 'article.purchaseprice'
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
                    'name' => 'article.salesprice'
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
     * Returns an array of path to js files.
     *
     * @see Scaffold_Controller
     * @return array
     */
    public function injectJS()
    {
        return ['/js/Chart.bundle.min'];
    }

    /**
     * Return the supplier bean.
     *
     * @return object
     */
    public function getSupplier()
    {
        if (! $this->bean->supplier) {
            $this->bean->supplier = R::dispense('supplier');
        }
        return $this->bean->supplier;
    }

    /**
     * Returns the name of the supplier.
     *
     * @return string
     */
    public function supplierName()
    {
        return $this->bean->getSupplier()->name;
    }

    /**
     * Returns a string with styling information of a scaffold table row.
     *
     * @return string
     */
    public function scaffoldStyle()
    {
        if (! $this->bean->isoriginal) {
            return "";
        }
        return "style=\"color: green;\"";
    }

    /**
     * Lookup a searchterm and return the resultset as an array.
     *
     * @param string $searchtext
     * @param string (optional) $query The prepared query or SQL to use for search
     * @return array
     */
    public function clairvoyant($searchtext, $query = 'default', $limit = 10)
    {
        switch ($query) {
            default:
            $sql = <<<SQL
                SELECT
                    article.id AS id,
                    CONCAT(article.number, ' ', article.description, ' ', IF(article.isoriginal, 'Original', '')) AS label,
                    CONCAT(article.number, ' ', article.description) AS value,
                    FORMAT(article.purchaseprice, 2, 'de_DE') AS purchaseprice,
                    FORMAT(article.salesprice, 2, 'de_DE') AS salesprice,
                    article.isoriginal AS isoriginal,
                    IF(article.isoriginal, 'Original', '') AS original
                FROM
                    article
                WHERE
                    article.number LIKE :searchtext OR
                    article.description LIKE :searchtext
                ORDER BY
                    article.isoriginal DESC,
                    article.number
                LIMIT {$limit}
SQL;
        }
        $result = R::getAll($sql, array(':searchtext' => $searchtext . '%' ));
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
                supplier ON supplier.id = article.supplier_id
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
        $this->bean->lastchange = date('Y-m-d');
        $this->addConverter('lastchange', new Converter_Mysqldate());
        $this->addConverter('purchaseprice', new Converter_Decimal());
        $this->addConverter('salesprice', new Converter_Decimal());
    }

    /**
     * Update.
     */
    public function update()
    {
        parent::update();
        if (! $this->bean->salesprice) {
            if ($this->bean->isfilter) {
                $this->bean->salesprice = (float)$this->bean->purchaseprice * 5 * 1.15;
            } else {
                $this->bean->salesprice = (float)$this->bean->purchaseprice * 3 * 1.15;
            }
        }
        // if the price has changed, we record it in our article statistics.
        if ($this->bean->getId() && ($this->bean->purchaseprice != $this->bean->old('purchaseprice'))) {
            $artstat = R::dispense('artstat');
            $artstat->salesprice = $this->bean->old('salesprice');
            $artstat->purchaseprice = $this->bean->old('purchaseprice');
            $this->bean->ownArtstat[] = $artstat;
            //$this->bean->lastchange = date('Y-m-d');
        }
    }
}
