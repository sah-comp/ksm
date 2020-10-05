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
 * Appointment model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Contract extends Model
{
    /**
     * Returns an array with possible units.
     *
     * @return array
     */
    public function getUnits()
    {
        return [
            'hour',
            'day',
            'week',
            'month',
            'year'
        ];
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
                'name' => 'number',
                'sort' => [
                    'name' => 'contract.number'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => '10rem'
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
                ]
            ],
            [
                'name' => 'machine.name',
                'sort' => [
                    'name' => 'machine.name'
                ],
                'callback' => [
                    'name' => 'machineName'
                ],
                'filter' => [
                    'tag' => 'text'
                ]
            ],
            [
                'name' => 'startdate',
                'sort' => [
                    'name' => 'contract.startdate'
                ],
                'filter' => [
                    'tag' => 'date'
                ],
                'callback' => [
                    'name' => 'localizedDate'
                ],
                'width' => '8rem'
            ],
            [
                'name' => 'enddate',
                'sort' => [
                    'name' => 'contract.enddate'
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
     * Return the location bean.
     *
     * @return $location
     */
    public function getLocation()
    {
        if (! $this->bean->location) {
            $this->bean->location = R::dispense('location');
        }
        return $this->bean->location;
    }

    /**
     * Return the person bean.
     *
     * @return $person
     */
    public function getPerson()
    {
        if (! $this->bean->person) {
            $this->bean->person = R::dispense('person');
        }
        return $this->bean->person;
    }

    /**
     * Returns the name of the person (customer)
     *
     * @return string
     */
    public function personName()
    {
        return $this->bean->getPerson()->name;
    }

    /**
     * Return the machine bean.
     *
     * @return $machine
     */
    public function getMachine()
    {
        if (! $this->bean->machine) {
            $this->bean->machine = R::dispense('machine');
        }
        return $this->bean->machine;
    }

    /**
     * Returns the name of the machine.
     *
     * @return string
     */
    public function machineName()
    {
        return $this->bean->getMachine()->name;
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
            person ON person.id = contract.person_id
        LEFT JOIN
            machine ON machine.id = contract.machine_id
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
        $this->addConverter('startdate', new Converter_Mysqldate());
        $this->addConverter('enddate', new Converter_Mysqldate());
    }

    /**
     * Update.
     */
    public function update()
    {
        parent::update();
    }
}
