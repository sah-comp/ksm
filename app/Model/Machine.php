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
        $ret = [
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
        // check if there a additional fields to output
        if (isset($this->bean->contracttype) && $this->bean->contracttype) {
            //error_log('I may have some additional fields to output');
            $limbs = $this->bean->contracttype->withCondition('list = 1 ORDER BY sequence')->ownLimb;
            if (count($limbs)) {
                foreach ($limbs as $id => $limb) {
                    $ret[] = [
                        'label' => $limb->name,
                        'name' => $limb->stub,
                        'sort' => [
                            'name' => $limb->stub
                        ],
                        'order' => [
                            'name' => "JSON_EXTRACT(payload, '$." . $limb->stub . "')"
                        ],
                        'callback' => [
                            'name' => 'jsonAttribute'
                        ],
                        'filter' => [
                            'tag' => 'json'
                        ]
                    ];
                    //error_log('Add attribute ' . $limb->name);
                }
            }
        }
        return $ret;
    }

    /**
     * Returns the default order field.
     *
     * @return int
     */
    public function getDefaultOrderField()
    {
        return 6;
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
    public function injectJS(): array
    {
        return ['/js/datatables.min'];
    }

    /**
     * Look up searchtext in all fields of a bean.
     *
     * @param string $searchphrase
     * @return array
     */
    public function searchGlobal($searchphrase): array
    {
        /*
        $searchphrase = '%'.$searchphrase.'%';
        return R::find(
            $this->bean->getMeta('type'),
            ' machine.name LIKE :f OR serialnumber LIKE :f OR internalnumber LIKE :f OR buildyear LIKE :f OR lastservice = :f OR specialagreement LIKE :f OR payload LIKE :f OR @joined.machinebrand.name LIKE :f',
            [
                ':f' => $searchphrase,
            ]
        );
        */
        $searchphrase = '%' . $searchphrase . '%';
        $sql = 'SELECT m.* FROM machine AS m LEFT JOIN contract ON contract.machine_id = m.id LEFT JOIN person ON person.id = contract.person_id LEFT JOIN machinebrand ON machinebrand.id = m.machinebrand_id WHERE machinebrand.name LIKE :f OR person.name LIKE :f OR m.name LIKE :f OR m.serialnumber LIKE :f OR m.internalnumber LIKE :f OR m.buildyear LIKE :f OR m.lastservice = :f OR m.specialagreement LIKE :f OR m.payload LIKE :f';
        $rows = R::getAll($sql, [
            ':f' => $searchphrase
        ]);
        return R::convertToBeans($this->bean->getMeta('type'), $rows);
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
     * Return the location bean of the latest appointment of this machine.
     *
     * @return object
     */
    public function getLocation()
    {
        $appointment = R::findOne('appointment', " machine_id = ? ORDER BY date DESC LIMIT 1", [$this->bean->getId()]);
        if ($appointment && $appointment->location) {
            $location = $appointment->location;
        } else {
            $location = R::dispense('location');
        }
        return $location;
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
     * Returns the name of the location (of the latest appointment).
     *
     * @return string
     */
    public function locationName()
    {
        return $this->bean->getLocation()->name;
    }

    /**
     * Returns a string with styling information of a scaffold table row.
     *
     * @return string
     */
    public function scaffoldStyle()
    {
        if (! $this->bean->appointmenttype) {
            return "style=\"border-left: 5px solid inherit;\"";
        }
        return "style=\"border-left: 5px solid {$this->bean->appointmenttype->color};\"";
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
            LEFT JOIN
                appointment ON appointment.id = (SELECT appointment.id FROM appointment WHERE appointment.machine_id = machine.id ORDER BY appointment.date DESC LIMIT 1)
            LEFT JOIN
                location ON location.id = appointment.location_id
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
        $result = R::getAll($sql, array(':searchtext' => $searchtext . '%'));
        return $result;
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->bean->name = '';
        $this->bean->serialnumber = '';
        $this->bean->internalnumber = '';
        $this->bean->contracttype = R::load('contracttype', CINNEBAR_CONTRACTTYPE_MACHINE_BEAN_ID);
        $this->addValidator('name', [
            new Validator_HasValue(),
            //new Validator_IsUnique(['bean' => $this->bean, 'attribute' => 'name'])
        ]);
        $this->addConverter(
            'lastservice',
            new Converter_Mysqldate()
        );
    }

    /**
     * Returns a the given string safely to use as filename or url.
     *
     * @link http://stackoverflow.com/questions/2668854/sanitizing-strings-to-make-them-url-and-filename-safe
     *
     * What it does:
     * - Replace all weird characters with dashes
     * - Only allow one dash separator at a time (and make string lowercase)
     *
     * @param string $string the string to clean
     * @param bool $is_filename false will allow additional filename characters
     * @return string
     */
    public function sanitizeFilename($string = '', $is_filename = false)
    {
        $string = preg_replace('/[^\w\-' . ($is_filename ? '~_\.' : '') . ']+/u', '-', $string);
        return mb_strtolower(preg_replace('/--+/u', '-', $string));
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
        //$this->bean->contracttype = R::load('contracttype', CINNEBAR_CONTRACTTYPE_MACHINE_BEAN_ID);
        $filesArray = (array) Flight::request()->files;
        $file = reset($filesArray);
        if ($file !== false) {
            $file = reset($file);
            if (!empty($file) && !$file['error']) {
                if ($file['error']) {
                    $this->addError($file['error'], 'file');
                    throw new Exception('fileupload error ' . $file['error']);
                }
                $file_parts = pathinfo($file['name']);
                $orgname = $file['name'];
                $extension = strtolower($file_parts['extension']);
                $sanename = $this->sanitizeFilename($file_parts['filename']);
                $filename = md5($this->bean->getId() . $sanename) . '.' . $extension;
                if (! move_uploaded_file($file['tmp_name'], Flight::get('upload_dir') . '/' . $filename)) {
                    $this->addError('move_upload_file_failed', 'file');
                    throw new Exception('move_upload_file_failed');
                }
                $artifact = R::dispense('artifact');
                $artifact->name = $orgname;
                $artifact->filename = $filename;
                $this->bean->ownArtifact[] = $artifact;
            }
        }
        foreach ($this->bean->ownInstalledpart as $id => $installedpart) {
            if (!$installedpart->getId() && !$installedpart->clairvoyant) {
                unset($this->bean->ownInstalledpart[$id]); // this is most likely a blank article, just nill
            }
        }
        if (Flight::request()->method == 'POST') {
            $limb = Flight::request()->data->limb;
            if (is_array($limb)) {
                $this->bean->payload = json_encode($limb);
            }
        }
        parent::update();
    }
}
