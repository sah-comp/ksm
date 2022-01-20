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
     * Pattern for the number code
     *
     * @var string
     */
    public const PATTERN = "%s-%02d-%02d-%04d";

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
                'width' => '12rem'
            ],
            [
                'name' => 'treatygroup.name',
                'sort' => [
                    'name' => 'treatygroup.name'
                ],
                'callback' => [
                    'name' => 'treatygroupName'
                ],
                'filter' => [
                    'tag' => 'select',
                    'sql' => 'getTreatygroups'
                ],
                'width' => '23rem'
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
                    'tag' => 'select',
                    'sql' => 'getContracttypes'
                ],
                'width' => '14rem'
            ],
            [
                'name' => 'bookingdate',
                'sort' => [
                    'name' => 'treaty.bookingdate'
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
                'name' => 'prospect',
                'sort' => [
                    'name' => 'prospect'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => '6rem'
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
                'width' => '8rem'
            ],
            [
                'name' => 'serialnumber',
                'sort' => [
                    'name' => 'serialnumber'
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
            ],
            [
                'name' => 'archived',
                'sort' => [
                    'name' => 'treaty.archived'
                ],
                'callback' => [
                    'name' => 'boolean'
                ],
                'filter' => [
                    'tag' => 'bool'
                ],
                'width' => '4rem'
            ]
        ];
    }

    /**
     * Constructor.
     *
     * Set actions for list views.
     */
    public function __construct()
    {
        $this->setAction('index', array('idle', 'toggleArchived', 'expunge'));
    }


    /**
     * Returns special css classes depending on the type of treaty.
     *
     * @return string
     */
    public function classesCss()
    {
        if ($this->bean->contracttype->hidesome) {
            return 'visuallyhidden';
        }
        return '';
    }

    /**
     * Toggle the archived attribute and store the bean.
     *
     * When the bean is archived its former status is saved, just in case
     * it will eventually be unarchived once again later on.
     *
     * This is performed by a raw SQL query because we dont want to mess with
     * the RB update cylce.
     *
     * @return void
     */
    public function toggleArchived()
    {
        $this->bean->archived = ! $this->bean->archived;
        R::store($this->bean);
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
        $stack[] = $this->bean->getPerson()->nickname;
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
     * Returns true if the bean has a quick filter attribute.
     *
     * @return bool
     */
    public function hasQuickFilter(): bool
    {
        return false;// true to have a select menu after the header h1 allowing users to quickly select by contracttype
    }

    /**
     * Returns an array of RedBeanPHP\OODBBean objects.
     *
     * @return array
     */
    public function getQuickFilterValues(): array
    {
        return R::find('contracttype', "enabled = 1 AND service = 1 ORDER BY name");
    }

    /**
     * Returns the QF bean option value, e.g. the id.
     *
     * @see getAttributes()
     * @return mixed
     */
    public function getQuickFilterOptionValue(RedbeanPHP\OODBBean $bean): mixed
    {
        return $bean->name; //we need the name as value, because our filter tag is text
    }

    /**
     * Returns the QF bean option label, e.g. the name or number.
     *
     * @return mixed
     */
    public function getQuickFilterLabel(RedbeanPHP\OODBBean $bean): mixed
    {
        return $bean->name;
    }

    /**
     * Preset the filter (for scaffold list view) on inital request or reset.
     *
     * We want to see only non-archived transactions initally.
     *
     * @param RedBeanPHP\OODBBean
     * @param mixed $value of the attribute to filter
     * @return bool
     */
    public function quickFilterSetup(RedBeanPHP\OODBBean $filter, $value = null): bool
    {
        $criteria = R::dispense('criteria');
        $criteria->op = 'eq';
        $criteria->tag = 'text';
        $criteria->attribute = 'contracttype.name';
        $criteria->value = $value;
        $filter->ownCriteria[] = $criteria;
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
     * Returns associated array of contracttype beans for use in scaffold filter.
     *
     * @return array
     */
    public function getContracttypes(): array
    {
        $sql = "SELECT name, name FROM contracttype WHERE service = 1 AND enabled = 1 ORDER BY name";
        return R::getAssoc($sql);
    }

    /**
     * Return the treatygroup bean.
     *
     * @return RedbeanPHP\OODBBean
     */
    public function getTreatygroup()
    {
        if (! $this->bean->treatygroup) {
            $this->bean->treatygroup = R::dispense('treatygroup');
        }
        return $this->bean->treatygroup;
    }

    /**
     * Returns the name of the treatygroup.
     *
     * @return string
     */
    public function treatygroupName()
    {
        return $this->bean->getTreatygroup()->name;
    }

    /**
     * Returns an array with treatygroup beans, aka. "folders" where to "store" treaty beans.
     *
     * @return array
     */
    public function getTreatygroups(): array
    {
        $sql = "SELECT name, name FROM treatygroup ORDER BY sequence";
        return R::getAssoc($sql);
    }

    /**
     * Returns a string with a readable folder name.
     *
     * @return string
     */
    public function folderReadable(): string
    {
        return I18n::__('treaty_folder_' . $this->bean->folder);
    }

    /**
     * Returns a string with styling information of a scaffold table row.
     *
     * @return string
     */
    public function scaffoldStyle()
    {
        if (! $this->bean->getTreatygroup()->getId()) {
            return "style=\"border-left: 5px solid inherit;\"";
        }
        return "style=\"border-left: 5px solid {$this->bean->getTreatygroup()->color};\"";
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
                treatygroup ON treatygroup.id = treaty.treatygroup_id
            LEFT JOIN
                person ON person.id = treaty.person_id
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
     * Returns a treaty bean if this bean has derived from a former one or false if not.
     *
     * @return mixed
     */
    public function hasParent()
    {
        if ($this->bean->mytreatyid) {
            $parent = R::load('treaty', $this->bean->mytreatyid);
            if ($parent->getId()) {
                return $parent;
            }
        }
        return false;
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->bean->mytreatyid = 0;
        $this->bean->bookingdate = date('Y-m-d');
        $this->addConverter('bookingdate', new Converter_Mysqldate());
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
            if (!$this->bean->treatygroup_id) {
                $this->bean->treatygroup_id = null;
                unset($this->bean->treatygroup);
            }
            if (!$this->bean->location_id) {
                $this->bean->location_id = null;
                unset($this->bean->location);
            }
        }

        if (!$this->bean->person_id) {
            $this->bean->person_id = null;
            unset($this->bean->person);
        }

        if (!$this->bean->getId()) {
            // This is a new bean, we want to stamp its number
            $number = $this->bean->contracttype->nextnumber;
            $this->bean->contracttype->nextnumber++;
            $this->bean->number = sprintf(self::PATTERN, $this->bean->contracttype->nickname, Flight::setting()->fiscalyear, date('m', strtotime($this->bean->bookingdate)), $number);
        }

        //if ($this->bean->ctext == '') {
        // fetch the contract text from the contracttype if not already set
        $this->bean->ctext = $this->bean->contracttype->text;
        //}
        $this->bean->updated = time();
        if (Flight::request()->method == 'POST') {
            //error_log('POST treaty');
            $limb = Flight::request()->data->limb;
            $this->bean->payload = json_encode($limb);
            //error_log($this->bean->payload);
        }
        parent::update();
    }
}
