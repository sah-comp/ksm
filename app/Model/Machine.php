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
                ],
                'width' => '14rem'
            ],
            [
                'name' => 'machinebrand.name',
                'sort' => [
                    'name' => 'machinebrand.name'
                ],
                'callback' => [
                    'name' => 'machinebrandName'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => '12rem'
            ],
            [
                'name' => 'serialnumber',
                'sort' => [
                    'name' => 'machine.serialnumber'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => '12rem'
            ],
            [
                'name' => 'internalnumber',
                'sort' => [
                    'name' => 'machine.internalnumber'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => '12rem'
            ],
            [
                'name' => 'person.name',
                'sort' => [
                    'name' => 'person.name'
                ],
                'callback' => [
                    'name' => 'personName'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => 'auto'
            ],
            [
                'name' => 'lastservice',
                'sort' => [
                    'name' => 'machine.lastservice'
                ],
                'filter' => [
                    'tag' => 'date'
                ],
                'callback' => [
                    'name' => 'localizedDate'
                ],
                'width' => '8rem'
            ]
        ];
    }

    /**
     * Returns an array of path to js files.
     *
     * @see Scaffold_Controller
     * @return array
     */
    public function injectJS()
    {
        return ['/js/datatables.min'];
    }

    /**
     * Return the machinebrand bean.
     *
     * @return object
     */
    public function getMachinebrand()
    {
        if (! $this->bean->machinebrand) {
            $this->bean->machinebrand = R::dispense('machinebrand');
        }
        return $this->bean->machinebrand;
    }

    /**
     * Returns the name of the machinebrand.
     *
     * @return string
     */
    public function machinebrandName()
    {
        return $this->bean->getMachinebrand()->name;
    }

    /**
     * Return the person (customer) bean via contract.
     *
     * @return object
     */
    public function getPerson()
    {
        if (! $contract = R::findOne('contract', "machine_id = ? LIMIT 1", [$this->bean->getId()])) {
            $contract = R::dispense('contract');
        }
        return $contract->getPerson();
    }

    /**
     * Returns the name of the person (customer).
     *
     * @return string
     */
    public function personName()
    {
        return $this->bean->getPerson()->name;
    }

    /**
     * Returns a string with styling information of a scaffold table row.
     *
     * @return string
     */
    public function scaffoldStyle()
    {
        if (! $this->bean->appointmenttype) {
            return "style=\"border-left: 3px solid inherit;\"";
        }
        return "style=\"border-left: 3px solid {$this->bean->appointmenttype->color};\"";
        //return "style=\"box-shadow: inset 0 0 0 4px coral;;\"";
    }

    /**
     * Return wether the model has a menu toolbar scaffold or not.
     *
     * @return bool
     */
    public function hasMenu()
    {
        return true;
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
                machinebrand ON machinebrand.id = machine.machinebrand_id
            LEFT JOIN
                contract ON contract.machine_id = machine.id
            LEFT JOIN
                person ON person.id = contract.person_id
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
                    machine.id AS id,
                    CONCAT(mb.name, ' ', machine.name, ' ', machine.serialnumber, ' ', machine.internalnumber) AS label,
                    machine.name AS value
                FROM
                    machine
                LEFT JOIN
                    machinebrand AS mb ON mb.id = machine.machinebrand_id
                WHERE
                    machine.name LIKE :searchtext OR
                    machine.serialnumber LIKE :searchtext
                ORDER BY
                    machine.name
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
     *
     * @todo When a machine entry is edited in the frontend a blank installedpart is
     * added when there are not any installedparts yet. Because RB tries to relate
     * the installedpart beans it will fail, if no article was selected as installedpart.
     * For this reason we check if there art empty article records and if there are any, we
     * unset them.
     */
    public function update()
    {
        foreach ($this->bean->ownInstalledpart as $id => $installedpart) {
            if (!$installedpart->getId() && !$installedpart->clairvoyant) {
                unset($this->bean->ownInstalledpart[$id]); // this is most likely a blank article, just nill
            }
        }
        parent::update();
    }
}
