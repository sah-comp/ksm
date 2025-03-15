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
     * How to dates are divided to let users search for a date range.
     *
     * e.g. 2020-03-01...2020-09-01
     */
    public $daterangedelimiter = '...';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setAction('index', ['idle', 'expunge', 'fix', 'complete', 'adjourn', 'adjournweekday', 'multiedit']);
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
                'name'     => 'date',
                'sort'     => [
                    'name' => 'appointment.date',
                ],
                'callback' => [
                    'name' => 'localizedDate',
                ],
                'filter'   => [
                    'tag' => 'date',
                ],
                'width'    => '7rem',
            ],
            [
                'name'     => 'starttime',
                'sort'     => [
                    'name' => 'appointment.starttime',
                ],
                'callback' => [
                    'name' => 'localizedTime',
                ],
                'filter'   => [
                    'tag' => 'time',
                ],
                'width'    => '5rem',
            ],
            [
                'name'     => 'fix',
                'sort'     => [
                    'name' => 'appointment.fix',
                ],
                'callback' => [
                    'name' => 'boolean',
                ],
                'filter'   => [
                    'tag' => 'bool',
                ],
                'width'    => '4rem',
            ],
            [
                'name'     => 'completed',
                'sort'     => [
                    'name' => 'appointment.completed',
                ],
                'callback' => [
                    'name' => 'boolean',
                ],
                'filter'   => [
                    'tag' => 'bool',
                ],
                'width'    => '4rem',
            ],
            [
                'name'     => 'confirmed',
                'sort'     => [
                    'name' => 'appointment.confirmed',
                ],
                'callback' => [
                    'name' => 'boolean',
                ],
                'filter'   => [
                    'tag' => 'bool',
                ],
                'width'    => '4rem',
            ],
            [
                'name'     => 'receipt',
                'sort'     => [
                    'name' => 'appointment.receipt',
                ],
                'callback' => [
                    'name' => 'localizedDate',
                ],
                'filter'   => [
                    'tag' => 'date',
                ],
                'width'    => '7rem',
            ],
            [
                'name'     => 'appointmenttype.id',
                'sort'     => [
                    'name' => 'appointmenttype.id',
                ],
                'callback' => [
                    'name' => 'appointmenttypeName',
                ],
                'filter'   => [
                    'tag'     => 'in',
                    'postvar' => 'appointmenttype',
                    'options' => [
                        'bean'  => 'appointmenttype',
                        'id'    => 'id',
                        'label' => 'name',
                    ],
                ],
                'width'    => '7rem',
            ],
            [
                'name'     => 'user.name',
                'sort'     => [
                    'name' => 'user.name',
                ],
                'callback' => [
                    'name' => 'userName',
                ],
                'filter'   => [
                    'tag' => 'text',
                ],
                'width'    => '4rem',
            ],
            [
                'name'     => 'person.name',
                'sort'     => [
                    'name' => 'person.name',
                ],
                'callback' => [
                    'name' => 'personName',
                ],
                'filter'   => [
                    'tag' => 'text',
                ],
                'prefix'   => [
                    'callback' => [
                        'name' => 'linkAdditionalInfo', //'prefixContact'
                    ],
                ],
                'width'    => '8rem',
            ],
            [
                'name'     => 'location.name',
                'sort'     => [
                    'name' => 'location.name',
                ],
                'callback' => [
                    'name' => 'locationName',
                ],
                'filter'   => [
                    'tag' => 'text',
                ],
                'width'    => '6rem',
            ],
            [
                'name'     => 'machine.name',
                'sort'     => [
                    'name' => 'machine.name',
                ],
                'callback' => [
                    'name' => 'machineName',
                ],
                'filter'   => [
                    'tag' => 'text',
                ],
            ],
            [
                'name'     => 'machine.serialnumber',
                'sort'     => [
                    'name' => 'machine.serialnumber',
                ],
                'callback' => [
                    'name' => 'machineSerialnumber',
                ],
                'filter'   => [
                    'tag' => 'text',
                ],
            ],
            [
                'name'     => 'machine.internalnumber',
                'sort'     => [
                    'name' => 'machine.internalnumber',
                ],
                'callback' => [
                    'name' => 'machineInternalnumber',
                ],
                'filter'   => [
                    'tag' => 'text',
                ],
                'width'    => '4rem',
            ],
            [
                'name'   => 'note',
                'sort'   => [
                    'name' => 'appointment.note',
                ],
                'filter' => [
                    'tag' => 'text',
                ],
            ],
        ];
    }

    /**
     * Returns the default order field.
     *
     * @return int
     */
    public function getDefaultOrderField()
    {
        return 0;
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
     * Returns string that is output before the attribute value (callback value) in a scaffold
     * list view column.
     *
     * @return string
     */
    public function prefixContact()
    {
        Flight::render('model/person/tooltip/contactinfo', [
            'record' => $this->bean->getPerson(),
        ]);
    }

    /**
     * Outputs a link that will load additional information about the bean into a area
     * on the screen. Make sure to have that place covered, as well as the callback routine.
     *
     * @return string
     */
    public function linkAdditionalInfo()
    {
        return '<a href="' . Url::build(sprintf('/admin/appointment/additional/%d/contactinfo', $this->bean->getId())) . '" class="additional-info ir contactinfo" data-target="additional-info-container">CI</a>';
    }

    /**
     * Renders additional info.
     *
     * @return void
     */
    public function renderAdditional($info): void
    {
        Flight::render('model/person/additional/contactinfo', [
            'record' => $this->bean->getPerson(),
        ]);
    }

    /**
     * Returns an array with "service" appointment beans.
     *
     * @param string $date
     * @return array
     */
    public function getConfirmedUndone($date = '')
    {
        $filter   = [':yes' => 1];
        $add_date = '';
        if ($date) {
            if (strpos($date, $this->daterangedelimiter)) {
                $dates                = explode($this->daterangedelimiter, $date);
                $date_from            = $this->convertToDate($dates[0]);
                $date_to              = $this->convertToDate($dates[1]);
                $add_date             = ' AND (date >= :pday_from AND date <= :pday_to)';
                $filter[':pday_from'] = $date_from;
                $filter[':pday_to']   = $date_to;
            } else {
                $add_date        = ' AND date = :pday ';
                $date            = $this->convertToDate($date);
                $filter[':pday'] = $date;
            }
        }
        $sql = "confirmed = :yes AND completed != :yes {$add_date} ORDER BY date, fix DESC, starttime, @joined.person.name, @joined.machine.name, @joined.machine.serialnumber";
        return R::find('appointment', $sql, $filter);
    }

    /**
     * Returns the timestamp of the latest updated "service" bean or false.
     *
     * @param string $date
     * @return mixed
     */
    public function getLastUpdated($date = '')
    {
        $filter   = [':yes' => 1];
        $add_date = '';
        if ($date) {
            if (strpos($date, $this->daterangedelimiter)) {
                $dates                = explode($this->daterangedelimiter, $date);
                $date_from            = $this->convertToDate($dates[0]);
                $date_to              = $this->convertToDate($dates[1]);
                $add_date             = ' AND (date >= :pday_from AND date <= :pday_to)';
                $filter[':pday_from'] = $date_from;
                $filter[':pday_to']   = $date_to;
            } else {
                $add_date        = ' AND date = :pday ';
                $date            = $this->convertToDate($date);
                $filter[':pday'] = $date;
            }
        }
        $sql = "confirmed = :yes AND completed != :yes {$add_date} ORDER BY updated DESC LIMIT 1";
        if ($latest = R::findOne('appointment', $sql, $filter)) {
            return (int) $latest->updated;
        }
        return false;
    }

    /**
     * Return the transaction bean.
     *
     * @return $transaction
     */
    public function getTransaction()
    {
        if (! $this->bean->transaction) {
            $this->bean->transaction = R::dispense('transaction');
        }
        return $this->bean->transaction;
    }

    /**
     * Returns the name of the person (customer)
     *
     * @return string
     */
    public function transactionNumber()
    {
        return $this->bean->getTransaction()->number;
    }

    /**
     * Returns a mysql date string.
     *
     * @param string the value to convert
     * @return string
     */
    public function convertToDate($value)
    {
        return date('Y-m-d', strtotime($value));
    }

    /**
     * Adjourn the appointment for the time period given.
     *
     * @param string $period
     * @return string The new date string
     */
    public function adjourn($period = '+ 1 days')
    {
        $this->bean->date = date('Y-m-d', strtotime($this->bean->date . ' ' . $period));
        $this->bean->adjourned++;
        R::store($this->bean);
        return $this->bean->date;
    }

    /**
     * Adjourn the appointment for the weekdays given.
     *
     * @param string $period
     * @return string The new date string
     */
    public function adjournweekday($period = '+ 1 weekdays')
    {
        $this->bean->date = date('Y-m-d', strtotime($this->bean->date . ' ' . $period));
        $this->bean->adjourned++;
        R::store($this->bean);
        return $this->bean->date;
    }

    /**
     * Return wether the appointment date is overdue or not.
     *
     * @return bool
     */
    public function isOverdue()
    {
        if (! $this->bean->getId()) {
            return '';
        }
        if ($this->bean->getId() && (strtotime($this->bean->date . ' ' . $this->bean->starttime) < time())) {
            return 'overdue';
        }
        if ($this->bean->getId() && (date('Y-m-d', strtotime($this->bean->date)) == date('Y-m-d', time()))) {
            return 'today';
        }
        return 'coming-up';
    }

    /**
     * Return a html fragement with the week of year for the service list.
     *
     * @return string
     */
    public function weekofyear()
    {
        //$class = $this->bean->isOverdue();
        return htmlspecialchars($this->bean->localizedDate('date', 'W'));
    }

    /**
     * Returns a string which allows ordering in a frontend table view.
     *
     * @return string
     */
    public function sortorder()
    {
        return trim(strtotime($this->bean->date . ' ' . $this->bean->starttime) . '-' . $this->bean->fix);
    }

    /**
     * Set this appointment to completed.
     *
     * A completed appointment of type service will no longer appear in the
     * dedicated serice list under /service.
     */
    public function complete()
    {
        $this->bean->completed       = true;
        $this->bean->terminationdate = date('Y-m-d');
        R::store($this->bean);
    }

    /**
     * Set this appointment to be fixed.
     *
     * A fixed appointment has to be started for completion on the given date and time.
     */
    public function fix()
    {
        $this->bean->fix = true;
        R::store($this->bean);
    }

    /**
     * Update this appointment from the multiedit footer in tpl/service/index.
     */
    public function multiedit()
    {
        if (Flight::request()->data->multiedit_date) {
            $this->bean->date = Flight::request()->data->multiedit_date;
        }
        if (Flight::request()->data->multiedit_time) {
            $this->bean->starttime = Flight::request()->data->multiedit_time;
        }
        if (Flight::request()->data->multiedit_worker) {
            $this->bean->user_id = Flight::request()->data->multiedit_worker;
        }
        R::store($this->bean);
    }

    /**
     * Returns an array of path to js files.
     *
     * @see Scaffold_Controller
     * @return array
     */
    public function injectJS(): array
    {
        return [
            '/js/select2.min',
            '/js/appointment',
        ];
    }

    /**
     * Returns an array of path to css files.
     *
     * @see Scaffold_Controller
     * @return array
     */
    public function injectCSS(): array
    {
        return [
            'select2.min',
        ];
    }

    /**
     * Returns wether the model has a toolbar menu extension or not.
     *
     * @todo Really check for an existing template.
     *
     * @return bool
     */
    public function hasMenu()
    {
        return true;
    }

    /**
     * Returns wether the model has a scaffold buttons template or not.
     *
     * @todo Really check for an existing template.
     *
     * @return bool
     */
    public function hasScaffoldButtons()
    {
        return false;
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
     * Returns the name of the location.
     *
     * @return string
     */
    public function locationName()
    {
        $loca = $this->bean->getLocation();
        if ($loca->getId()) {
            return $this->bean->getLocation()->name;
        }
        return $this->bean->getPerson()->postalAddress();
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
     * Return the user bean.
     *
     * @return $user
     */
    public function getUser()
    {
        if (! $this->bean->user) {
            $this->bean->user = R::dispense('user');
        }
        return $this->bean->user;
    }

    /**
     * Returns the name of the user.
     *
     * @return string
     */
    public function userName()
    {
        return $this->bean->getUser()->getName();
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
     * Returns the internalnumber of the machine.
     *
     * @return string
     */
    public function machineInternalnumber()
    {
        return $this->bean->getMachine()->internalnumber;
    }

    /**
     * Returns a string with styling information of a scaffold table row.
     *
     * @return string
     */
    public function scaffoldStyle()
    {
        if (! $this->bean->getAppointmenttype()->getId()) {
            return "style=\"border-left: 5px solid inherit;\"";
        }
        return "style=\"border-left: 5px solid {$this->bean->getAppointmenttype()->color};\"";
    }

    /**
     * Look up searchtext in all fields of a bean.
     *
     * @param string $searchphrase
     * @return array
     */
    public function searchGlobal($searchphrase): array
    {
        $searchphrase = '%' . $searchphrase . '%';
        return R::find(
            $this->bean->getMeta('type'),
            ' @joined.person.name LIKE :f OR @joined.contact.name LIKE :f OR (@joined.user.name LIKE :f OR @joined.user.email LIKE :f OR @joined.user.shortname LIKE :f OR @joined.user.screenname LIKE :f) OR @joined.appointmenttype.name LIKE :f OR (@joined.machine.name LIKE :f OR @joined.machine.name LIKE :f OR @joined.machine.serialnumber LIKE :f OR @joined.machine.internalnumber LIKE :f OR @joined.machine.note LIKE :f) OR @joined.location.name LIKE :f OR transactionnumber LIKE :f OR date = :f OR appointment.note LIKE :f',
            [
                ':f' => $searchphrase,
            ]
        );
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
            LEFT JOIN
                user ON user.id = appointment.user_id
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
        $this->bean->transactionnumber  = '';
        $this->bean->note               = '';
        $this->bean->date               = date('Y-m-d');
        $this->bean->interval           = 0;
        $this->bean->adjourned          = 0; // Counts how many times the appointment was adjournded
        $this->bean->receipt            = date('Y-m-d'); // Date when the appointment was arranged
        $this->bean->starttime          = date('H:i:s', strtotime('00:00:00'));
        $this->bean->endtime            = date('H:i:s', strtotime('00:00:00'));
        $this->bean->appointmenttype_id = Flight::setting()->appointmenttypeservice;
        $this->addConverter(
            'date',
            new Converter_Mysqldate()
        );
        $this->addConverter(
            'receipt',
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
        $this->addConverter(
            'interval',
            new Converter_Decimal()
        );
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
            'machines'  => R::findFromSQL('machine', " SELECT machine.* FROM machine LEFT JOIN contract ON contract.machine_id = machine.id WHERE contract.person_id = ? ORDER BY machine.name, machine.serialnumber", [$person->getId()]),
            'contacts'  => $person->with("ORDER BY name")->ownContact,
            'locations' => $person->with("ORDER BY name")->ownLocation,
        ];
        return $result;
    }

    /**
     * Update.
     */
    public function update()
    {
        if ((int) $this->bean->interval > 0 && $this->bean->date && ! $this->bean->old('completed') && $this->bean->completed && $this->bean->getId()) {
            // Existing appointment with interval set to completed will be re-newed with a new date.
            $dup                    = R::duplicate($this->bean);
            $dup->date              = date('Y-m-d', strtotime($this->bean->date . " + " . (int) $this->bean->interval . " days"));
            $dup->completed         = false;
            $dup->confirmed         = false;
            $dup->transactionnumber = '';
            $dup->transaction       = null;
            $dup_id                 = R::store($dup);
            //$dup->receipt = date('Y-m-d');
            $this->bean->ownAppointment[] = $dup;
            Flight::get('user')->notify(I18n::__("appointment_completion_renewed", null, [$dup_id]), 'success');
        }

        if (! $this->bean->person_id) {
            $this->bean->person_id = null;
            unset($this->bean->person);
        }

        if (! CINNEBAR_MIP) {
            if (! $this->bean->machine_id) {
                $this->bean->machine_id = null;
                unset($this->bean->machine);
            }
            if (! $this->bean->user_id) {
                $this->bean->user_id = null;
                unset($this->bean->user);
            }
            if (! $this->bean->contact_id) {
                $this->bean->contact_id = null;
                unset($this->bean->contact);
            }
            if (! $this->bean->location_id) {
                $this->bean->location_id = null;
                unset($this->bean->location);
            }
            // transaction
            if (! $this->bean->transaction_id) {
                $this->bean->transaction_id = null;
                unset($this->bean->transaction);
            }
        }
        $this->bean->updated = time();
        parent::update();
    }
}
