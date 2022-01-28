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
 * Headfoot model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Headfoot extends Model
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
                'width' => '20rem'
            ],
            [
                'name' => 'content',
                'sort' => [
                    'name' => 'content'
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
    public function clairvoyant($searchtext, $query = 'default', $limit = 10)
    {
        switch ($query) {
            default:
            $sql = <<<SQL
                SELECT
                    headfoot.id AS id,
                    CONCAT(headfoot.name, ' ', LEFT(REPLACE(headfoot.content, "\r\n", ' '), 80), '...') AS label,
                    CONCAT(headfoot.content) AS value
                FROM
                    headfoot
                WHERE
                    headfoot.name LIKE :searchtext OR
                    headfoot.content LIKE :searchtext
                ORDER BY
                    headfoot.name
                LIMIT {$limit}
SQL;
        }
        $result = R::getAll($sql, array(':searchtext' => '%' . $searchtext . '%' ));
        return $result;
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->addValidator('name', [
            new Validator_HasValue()
        ]);
    }
}
