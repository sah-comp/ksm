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
class Model_Appointment extends Model
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
                'name' => 'date',
                'sort' => [
                    'name' => 'appointment.date'
                ],
                'callback' => [
                    'name' => 'localizedDate'
                ],
                'filter' => [
                    'tag' => 'date'
                ],
                'width' => '8rem'
            ],
            [
                'name' => 'starttime',
                'sort' => [
                    'name' => 'appointment.starttime'
                ],
                'callback' => [
                    'name' => 'localizedTime'
                ],
                'filter' => [
                    'tag' => 'time'
                ],
                'width' => '8rem'
            ],
            [
                'name' => 'fix',
                'sort' => [
                    'name' => 'appointment.fix'
                ],
                'callback' => [
                    'name' => 'boolean'
                ],
                'filter' => [
                    'tag' => 'bool'
                ],
                'width' => '4rem'
            ],
            [
                'name' => 'appointmenttype.name',
                'sort' => [
                    'name' => 'appointmenttype.name'
                ],
                'callback' => [
                    'name' => 'appointmenttypeName'
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
                'name' => 'note',
                'sort' => [
                    'name' => 'appointment.note'
                ],
                'filter' => [
                    'tag' => 'text'
                ]
            ]
        ];
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
     * Return wether the appointment is completed or not.
     *
     * @return bool
     */
    public function isCompleted()
    {
        return $this->bean->completed;
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
     * Return the contact bean.
     *
     * @return $contact
     */
    public function getContact()
    {
        if (! $this->bean->contact) {
            $this->bean->contact = R::dispense('contact');
        }
        return $this->bean->contact;
    }

    /**
     * Return the appointmenttype bean.
     *
     * @return $appointmenttype
     */
    public function getAppointmenttype()
    {
        if (! $this->bean->appointmenttype) {
            $this->bean->appointmenttype = R::dispense('appointmenttype');
        }
        return $this->bean->appointmenttype;
    }

    /**
     * Returns the name of the appointmenttype.
     *
     * @return string
     */
    public function appointmenttypeName()
    {
        return $this->bean->getAppointmenttype()->name;
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
            person ON person.id = appointment.person_id
        LEFT JOIN
            machine ON machine.id = appointment.machine_id
        LEFT JOIN
            contact ON contact.id = appointment.contact_id
        LEFT JOIN
            location ON location.id = appointment.location_id
        LEFT JOIN
            appointmenttype ON appointmenttype.id = appointment.appointmenttype_id
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
        $this->addConverter(
            'date',
            new Converter_Mysqldate()
        );
        $this->addConverter(
            'starttime',
            new Converter_Mysqltime()
        );
        $this->addConverter(
            'endtime',
            new Converter_Mysqltime()
        );
        $this->addConverter(
            'terminationdate',
            new Converter_Mysqldatetime()
        );
        $this->addConverter(
            'duration',
            new Converter_Decimal()
        );
    }

    /**
     * Update.
     */
    public function update()
    {
        $this->bean->duration = abs(strtotime($this->bean->date . ' ' . $this->bean->endtime) - strtotime($this->bean->date . ' ' . $this->bean->starttime)) / 3600;
        parent::update();
    }
}
