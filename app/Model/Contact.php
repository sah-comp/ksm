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
 * Contact model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Contact extends Model
{
    /**
     * Returns an array with gender names.
     *
     * @return array
     */
    public function getGenders()
    {
        return array(
            'female',
            'male',
            'nonbinary',
            'transgender',
            'intersex',
            'twospirit',
            'nonconforming',
            'dontsay',
            'unknown'
        );
    }

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
                    'name' => 'contact.name'
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
                    contact.id AS id,
                    contact.name AS label,
                    contact.name AS value
                FROM
                    contact
                WHERE
                    contact.name LIKE :searchtext
                ORDER BY
                    contact.name
                LIMIT {$limit}
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
