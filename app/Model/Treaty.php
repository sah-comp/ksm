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
 * Treaty model.
 *
 * Due to misunderstandings the treaty model is the (real) contract model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Treaty extends Model
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
                    'name' => 'treaty.number'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => '10rem'
            ],
            [
                'name' => 'contracttype.name',
                'sort' => [
                    'name' => 'contracttype.name'
                ],
                'callback' => [
                    'name' => 'contracttypeName'
                ],
                'filter' => [
                    'tag' => 'text'
                ]
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
                'name' => 'machine.serialnumber',
                'sort' => [
                    'name' => 'machine.serialnumber'
                ],
                'callback' => [
                    'name' => 'machineSerialnumber'
                ],
                'filter' => [
                    'tag' => 'text'
                ]
            ],
            [
                'name' => 'location.name',
                'sort' => [
                    'name' => 'location.name'
                ],
                'callback' => [
                    'name' => 'locationName'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => '6rem'
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
                    'name' => 'treaty.enddate'
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
     * Returns a string that will work as a filename for treaty as PDF.
     *
     * @return string
     */
    public function getFilename()
    {
        $stack = [];
        $stack[] = $this->bean->contracttypeName();
        $stack[] = $this->bean->number;
        $stack[] = $this->bean->person->nickname;
        return trim(implode('-', $stack));
    }

    /**
     * Returns a string that will work as a title of treaty.
     *
     * @return string
     */
    public function getDocname()
    {
        return $this->bean->getFilename();
    }

    /**
     * Returns wether the model has a toolbar menu extension or not.
     *
     * @return bool
     */
    public function hasMenu()
    {
        return true;
    }

    /**
     * Return localized unit.
     *
     * @param string $void
     * @return string
     */
    public function localizedUnit($option)
    {
        if (empty($this->bean->unit)) {
            return '';
        }
        return I18n::__('treaty_unit_' . $this->bean->unit);
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
     * Returns the name of the location.
     *
     * @return string
     */
    public function locationName()
    {
        return $this->bean->getLocation()->name;
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
     * Returns the serialnumber of the machine.
     *
     * @return string
     */
    public function machineSerialnumber()
    {
        return $this->bean->getMachine()->serialnumber;
    }

    /**
     * Return the contracttype bean.
     *
     * @return RedbeanPHP\OODBBean
     */
    public function getContracttype()
    {
        if (! $this->bean->contracttype) {
            $this->bean->contracttype = R::dispense('contracttype');
        }
        return $this->bean->contracttype;
    }

    /**
     * Returns the name of the contracttype.
     *
     * @return string
     */
    public function contracttypeName()
    {
        return $this->bean->getContracttype()->name;
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
     * Returns an array with dependent data. Depending on person given.
     *
     * @param RedBeanPHP\OODBBean
     * @return array
     */
    public function getDependents($person)
    {
        $result = [
            'locations' => $person->with("ORDER BY name")->ownLocation
        ];
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
                contracttype ON contracttype.id = treaty.contracttype_id
            LEFT JOIN
                person ON person.id = treaty.person_id
            LEFT JOIN
                machine ON machine.id = treaty.machine_id
            LEFT JOIN
                location ON location.id = treaty.location_id
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
        $this->addConverter('signdate', new Converter_Mysqldate());
    }

    /**
     * Update.
     */
    public function update()
    {
        if (!CINNEBAR_MIP) {
            if (!$this->bean->contracttype_id) {
                $this->bean->contracttype_id = null;
                unset($this->bean->contracttype);
            }
            if (!$this->bean->location_id) {
                $this->bean->location_id = null;
                unset($this->bean->location);
            }
        }
        parent::update();
    }
}
