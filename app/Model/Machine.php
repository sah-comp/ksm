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
 * Machine model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Machine extends Model
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
                    'name' => 'machine.name'
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
                    machine.id AS id,
                    CONCAT(machine.name, ' ', machine.serialnumber) AS label,
                    machine.name AS value
                FROM
                    machine
                WHERE
                    machine.name LIKE :searchtext OR
                    machine.serialnumber LIKE :searchtext
                ORDER BY
                    machine.name
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
        $this->addValidator('name', [
            new Validator_HasValue(),
            new Validator_IsUnique(['bean' => $this->bean, 'attribute' => 'name'])
        ]);
        $this->addConverter(
            'lastservice',
            new Converter_Mysqldate()
        );
    }

    /**
     * Update.
     */
    public function update()
    {
        parent::update();
    }
}
