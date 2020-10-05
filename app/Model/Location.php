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
 * Location model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Location extends Model
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
                ]
            ]
        ];
    }

    /**
     * Lookup a searchterm and return the resultset as an array.
     *
     * @param string $searchtext
     * @param string (optional) $query The prepared query or SQL to use for search
     * @return array
     */
    public function clairvoyant($searchtext, $query = 'default')
    {
        switch ($query) {
            default:
            $sql = <<<SQL
                SELECT
                    location.id AS id,
                    location.name AS label,
                    location.name AS value
                FROM
                    location
                WHERE
                    location.name LIKE :searchtext
                ORDER BY
                    location.name
SQL;
        }
        $result = R::getAll($sql, array(':searchtext' => $searchtext . '%' ));
        return $result;
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
    }

    /**
     * Update.
     */
    public function update()
    {
        parent::update();
    }
}
