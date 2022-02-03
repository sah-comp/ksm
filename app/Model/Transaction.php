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
 * Transaction model.
 *
 * A transaction can be a order, delivery, invoice and so on.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Transaction extends Model
{
    /**
     * Pattern for the number code
     *
     * @var string
     */
    public const PATTERN = "%s-%02d-%02d-%04d";

    /**
     * Pattern for the interim number code
     *
     * @var string
     */
    public const PATTERN_INTERIM = "%s-%02d-%02d-%s";

    /**
     * Holds a comma separated string of IDs that are bookable.
     *
     * @see Model_Contracttype::$bookable
     */
    public $bookable_types = '';

    /**
     * Return actions array.
     *
     * @param string $switch decide which action array to return
     * @return array
     */
    public function getActions($switch = 'default')
    {
        switch ($switch) {
            case 'openitem':
                return [
                    'index' => ['idle', 'paid', 'cancel'],
                    'add' => ['add', 'edit', 'index'],
                    'edit' => ['edit', 'next_edit', 'prev_edit', 'index', 'book'],
                    'delete' => ['index']
                ];
                break;

            default:
                return [
                    'index' => ['idle', 'cancel', 'toggleArchived', 'expunge'],
                    'add' => ['add', 'edit', 'index'],
                    'edit' => ['edit', 'next_edit', 'prev_edit', 'index', 'book'],
                    'delete' => ['index']
                ];
                break;
        }
    }

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
     * Returns an array with layouts for PDF.
     *
     * @return array
     */
    public function getPrintLayouts()
    {
        return [
            'letterhead' => true,
            'blank' => false
        ];
    }

    /**
     * Returns a string in the meaning of transaction gros value, aka. Invoice total or Rechnungsbetrag.
     *
     * @param string optional $label to use
     * @return string
     */
    public function getWordingGros($label = 'transaction_label_total_gros')
    {
        if (trim($this->getContracttype()->wordgros) == '') {
            return I18n::__($label);
        }
        return $this->getContracttype()->wordgros;
    }

    /**
     * Returns an array with dunninglevels.
     *
     * @return array
     */
    public function getDunnings()
    {
        return R::find('dunning', " ORDER BY sequence");
    }

    /**
     * Returns the dunning bean of this transaction.
     *
     * @return RedbeanPHP\OODBBean
     */
    public function getDunning()
    {
        if (! $this->bean->dunning) {
            $this->bean->dunning = R::dispense('dunning');
        }
        return $this->bean->dunning;
    }

    /**
     * Returns a string with the date of payment.
     *
     * Date of payment can be the normal duedate or the dunningdate, depending on the
     * current dunning state.
     *
     * @return string
     */
    public function getDateOfPayment()
    {
        if ($this->getDunning()->getId()) {
            return $this->bean->dunningdate;
        }
        return $this->bean->duedate;
    }

    /**
     * Return the localized date of payment
     *
     * @return string
     */
    public function getLocalizedDateOfPayment()
    {
        $templates = Flight::get('templates');
        return strftime($templates['date'], strtotime($this->bean->getDateOfPayment()));
    }

    /**
     * Book this transaction as paid.
     *
     * @uses Converter_Decimal to handle user input of amount
     * @see Openitem_Controller::applyToSelection()
     * @see Transaction_Model::getActions()
     */
    public function paid()
    {
        $payment = R::dispense('payment');

        $payment->desc = Flight::request()->data->payment_desc;
        $payment->transaction_id = $this->bean->getId();
        $payment->bookingdate = date('Y-m-d');

        $payment->amount = $this->bean->balance;
        if (Flight::request()->data->payment_amount) {
            $converter = new Converter_Decimal();
            $user_amount = $converter->convert(Flight::request()->data->payment_amount);
            $payment->amount = $user_amount;
        }
        $payment->closingpayment = false;
        if ($payment->amount == $this->bean->balance) {
            $payment->closingpayment = true;
        }

        R::store($payment);
        return true;
    }

    /**
     * Evaluate dunning settings.
     *
     * This is eventually called by Controller_Enpassant::update() when user has
     * clicked openitem_action_dunning_pdf in openitem/index.
     *
     */
    public function dunning()
    {
        //error_log('I am dunned');
        $dunning = $this->bean->getDunning();
        if (!$dunning->getId()) {
            $this->bean->penaltyfee = 0;
            $this->bean->dunningdate = null;
            return false;
        }
        $this->bean->penaltyfee = $dunning->penaltyfee;
        $this->bean->dunningdate = date('Y-m-d', strtotime($this->bean->{$dunning->applyto} . ' +' . $dunning->grace . 'days'));
        $this->bean->dunningprintedon = date('Y-m-d');
        return true;
    }

    /**
     * Set fields after the bean was copied.
     *
     * @see Transaction_Controller()
     * @return RedBeanPHP\OODBBean
     */
    public function resetAfterCopy()
    {
        $this->bean->bookingdate = date('Y-m-d');
        $this->bean->status = 'open';
        if ($this->bean->getContracttype()->resetheader) {
            $this->bean->header = '';
        }
        if ($this->bean->getContracttype()->resetfooter) {
            $this->bean->footer = '';
        }
        $this->bean->locked = false;
        $this->bean->ownPayment = [];
        return $this->bean;
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
                    'name' => 'transaction.number'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => '12rem'
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
                'width' => '6rem'
            ],
            [
                'name' => 'bookingdate',
                'sort' => [
                    'name' => 'transaction.bookingdate'
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
                'name' => 'status',
                'sort' => [
                    'name' => 'transaction.status'
                ],
                'callback' => [
                    'name' => 'statusReadable'
                ],
                'filter' => [
                    'tag' => 'select',
                    'values' => $this->getStati()
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
                ]
            ],
            [
                'name' => 'net',
                'sort' => [
                    'name' => 'net'
                ],
                'callback' => [
                    'name' => 'decimal'
                ],
                'class' => 'number',
                'filter' => [
                    'tag' => 'number'
                ],
                'width' => '8rem'
            ],
            [
                'name' => 'vat',
                'sort' => [
                    'name' => 'vat'
                ],
                'callback' => [
                    'name' => 'decimal'
                ],
                'class' => 'number',
                'filter' => [
                    'tag' => 'number'
                ],
                'width' => '8rem'
            ],
            [
                'name' => 'gros',
                'sort' => [
                    'name' => 'gros'
                ],
                'callback' => [
                    'name' => 'decimal'
                ],
                'class' => 'number',
                'filter' => [
                    'tag' => 'number'
                ],
                'width' => '8rem'
            ],
            [
                'name' => 'archived',
                'sort' => [
                    'name' => 'transaction.archived'
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
     * Returns associated array of contracttype beans for use in scaffold filter.
     *
     * @return array
     */
    public function getContracttypes(): array
    {
        $sql = "SELECT name, name FROM contracttype WHERE ledger = 1 AND enabled = 1 ORDER BY name";
        return R::getAssoc($sql);
    }

    /**
     * Returns an array with stati.
     *
     * @return array
     */
    public function getStati(): array
    {
        return [
            'open' => I18n::__('transaction_status_readable_open'),
            'paid' => I18n::__('transaction_status_readable_paid'),
            'canceled' => I18n::__('transaction_status_readable_canceled'),
            'closed' =>  I18n::__('transaction_status_readable_closed')
        ];
    }

    /**
     * Returns a string that will work as a filename for transaction as PDF.
     *
     * @return string
     */
    public function getFilename()
    {
        $stack = [];
        $stack[] = $this->bean->getContracttype()->name;
        $stack[] = $this->bean->number;
        $stack[] = I18n::__('person_label_account');
        $stack[] = $this->bean->getPerson()->account;
        return trim(implode('-', $stack));
    }

    /**
     * Returns a string that will be the filename for a transaction dunned as PDF.
     */
    public function getFilenameDunning()
    {
        $fn = $this->getFilename();
        $fn = $this->bean->getDunning()->level . '-' . $fn;
        return $fn;
    }

    /**
     * Returns a string that will work as a title of transaction.
     *
     * @return string
     */
    public function getDocname()
    {
        return $this->bean->getFilename();
    }

    /**
     * Returns a string that will be the docname for a transaction dunned as PDF.
     */
    public function getDocnameDunning()
    {
        $fn = $this->getDocname();
        $fn = $this->bean->getDunning()->level . '-' . $fn;
        return $fn;
    }

    /**
     * Returns a string with payment conditions.
     *
     * @return string
     */
    public function paymentConditions()
    {
        $company = R::load('company', CINNEBAR_COMPANY_ID);
        $discount = $this->bean->getDiscount();
        if ($discount->days != 0 && $discount->value != 0) {
            // there is a possible discount within a certain time period, aka. skonto
            return vsprintf($company->conditiondiscount, [$this->bean->duedays, $discount->days, $discount->value]);
        } elseif ($this->bean->duedays == 0) {
            // duedays is 0 (zero)
            return $company->conditionimmediately;
        } else {
            // no discount, but due days are not zero
            return vsprintf($company->conditionnodiscount, [$this->bean->duedays]);
        }
    }

    /**
     * Returns a string with transaction conditions.
     *
     * @return string
     */
    public function transactionConditions()
    {
        $company = R::load('company', CINNEBAR_COMPANY_ID);
        return $company->conditionother;
    }

    /**
     * Returns the number of days overdue
     *
     * @return string
     */
    public function getOverdueDays()
    {
        //$paydate = $this->bean->getDateOfPayment();
        $paydate = $this->bean->duedate;
        $duedate = date_create_from_format('Y-m-d', $paydate);
        $today = date_create_from_format('Y-m-d', date('Y-m-d'));
        $diff = (array)date_diff($duedate, $today);
        $days = $diff['days'];
        if (time() > strtotime($paydate)) {
            if ($days == 0) {
                return I18n::__('transaction_due_today');
            } elseif ($days == 1) {
                return I18n::__('transaction_due_day_1');
            } else {
                return I18n::__('transaction_due_days', null, [$days]);
            }
        }
        return '';
    }

    /**
     * Return the discount bean.
     *
     * @return RedbeanPHP\OODBBean
     */
    public function getDiscount()
    {
        if (! $this->bean->discount) {
            $this->bean->discount = R::dispense('discount');
        }
        return $this->bean->discount;
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
     * Returns the sum of all net value of all positions of the given cost unit type.
     *
     * @param $costunittype Redbean\OODBBean
     * @return float
     */
    public function netByCostunit(RedBeanPHP\OODBBean $costunittype)
    {
        $sql = "SELECT CAST(SUM(total) AS DECIMAL(10, 2)) AS net FROM position WHERE transaction_id = :trans_id AND costunittype_id = :cut_id AND kind = :kind_position";
        $result = R::getCell($sql, [
            ':trans_id' => $this->bean->getId(),
            ':cut_id' => $costunittype->getId(),
            ':kind_position' => Model_Position::KIND_POSITION
        ]);
        return $result;
    }

    /**
     * Returns the sum of all gros value of all positions of the given cost unit type.
     *
     * @param $costunittype RedbeanPHP\OODBBean
     * @return float
     */
    public function grosByCostunit(RedBeanPHP\OODBBean $costunittype)
    {
        $sql = "SELECT CAST(SUM(total + total * vatpercentage / 100) AS DECIMAL(10, 2)) AS gros FROM position WHERE transaction_id = :trans_id AND costunittype_id = :cut_id AND kind = :kind_position";
        $result = R::getCell($sql, [
            ':trans_id' => $this->bean->getId(),
            ':cut_id' => $costunittype->getId(),
            ':kind_position' => Model_Position::KIND_POSITION
        ]);
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
                contracttype ON contracttype.id = transaction.contracttype_id
            LEFT JOIN
                person ON person.id = transaction.person_id
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
        $this->getBookables();
        $slots = R::genSlots($this->bookable_types);
        switch ($query) {
            default:
            $sql = <<<SQL
                SELECT
                    transaction.id AS id,
                    CONCAT(transaction.number, ' ', DATE_FORMAT(transaction.bookingdate, '%d.%m.%Y'), ' ', REPLACE(person.name, "\r\n", ' '), ' ', FORMAT(transaction.gros, 2, 'de_DE')) AS label,
                    transaction.number AS value,
                    FORMAT(transaction.gros, 2, 'de_DE') AS gros,
                    FORMAT(transaction.balance, 2, 'de_DE') AS balance,
                    FORMAT(transaction.totalpaid, 2, 'de_DE') AS totalpaid
                FROM
                    transaction
                LEFT JOIN
                    person ON person.id = transaction.person_id
                WHERE
                    transaction.number LIKE ? AND
                    transaction.locked = 1 AND
                    transaction.status NOT IN ('canceled', 'paid') AND
                    transaction.contracttype_id IN ($slots)
                ORDER BY
                    transaction.number
                LIMIT {$limit}
    SQL;
        }
        $result = R::getAll($sql, array_merge(['%' . $searchtext . '%'], $this->bookable_types));
        return $result;
    }

    /**
     * Find bookable contracttype beans and flatted them for SQL queries.
     *
     * @uses bookable_types
     * @return string with flattened IDs of contracttype beans which are bookable
     */
    public function getBookables()
    {
        $bookables = R::find('contracttype', " ledger = 1 AND enabled = 1 AND bookable = 1");
        $types = [];
        foreach ($bookables as $id => $contracttype) {
            $types[$id] = $contracttype->nickname;
        }
        return $this->bookable_types = array_keys($types);
    }

    /**
     * Returns vat values grouped by possible differnt vat percentages.
     *
     * @return array
     */
    public function getVatSentences()
    {
        $sql = <<<SQL
            SELECT
                vatpercentage,
                CAST(SUM(total) AS DECIMAL(10, 2)) AS net,
                CAST((SUM(total) * vatpercentage / 100) AS DECIMAL(10, 2)) AS vatvalue
            FROM
                position
            WHERE
                transaction_id = ? AND
                alternative != 1 AND
                kind = ?
            GROUP BY
                vatpercentage
            ORDER BY
                vatpercentage
SQL;
        $result = R::getAll($sql, [$this->bean->getId(), Model_Position::KIND_POSITION]);
        return $result;
    }

    /**
     * Returns a string with styling information of a scaffold table row.
     *
     * The styles shall reflect the status of the transaction. Maybe type and status?
     *
     * Stati are NULL, open, canceled
     *
     * @return string
     */
    public function scaffoldStyle()
    {
        switch ($this->bean->status) {
            case 'open':
                $bordercolor = 'orange';
                break;

            case 'closed':
                $bordercolor = 'blue';
                break;

            case 'paid':
                $bordercolor = 'green';
                break;

            case 'canceled':
                $bordercolor = 'red';
                break;

            default:
                $bordercolor = 'inherit';
                break;
        }
        return "style=\"border-left: 5px solid {$bordercolor};\"";
    }

    /**
     * Returns a readable string giving the status of this transaction.
     *
     * @return string
     */
    public function statusReadable()
    {
        return I18n::__('transaction_status_readable_' . $this->bean->status);
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
        $archived = ! $this->bean->archived;
        if ($archived && $this->bean->getContracttype()->closeonarchive) {
            $oldstatus = $this->bean->status;
            $status = 'closed';
        } elseif ($archived) {
            $oldstatus = $this->bean->status;
            $status = $this->bean->status;
        } elseif (!$archived) {
            // un-archived, reset status
            $status = $this->bean->oldstatus;
            $oldstatus = '';
        } else {
            // do nothing
            $status = $this->bean->status;
            $oldstatus = $this->bean->oldstatus;
        }
        R::exec('UPDATE transaction SET oldstatus = :oldstatus, status = :status, archived = :archived WHERE id = :id LIMIT 1', [
            ':oldstatus' => $oldstatus,
            ':status' => $status,
            ':archived' => $archived,
            ':id' => $this->bean->getId()
        ]);
        //R::store($this->bean);
    }

    /**
     * Cancels this transaction.
     *
     * @return mixed false if not canceled or RedBeanPHP\OODBBean when canceld
     */
    public function cancel()
    {
        if ($this->bean->status == 'canceled') {
            error_log('Transaction #' . $this->bean->getId() . ' is already canceled. Can not be canceld again.');
            return false;
            //throw new Exception(I18n::__('transaction_is_already_canceled'));
        }
        if (!$this->bean->locked) {
            error_log('Transaction #' . $this->bean->getId() . ' is not yet booked and can not be canceled.');
            return false;
            //throw new Exception(I18n::__('transaction_is_already_canceled'));
        }
        $converter = new Converter_Decimal();
        $dup = R::duplicate($this->bean);
        $dup->locked = false;
        $dup->status = 'canceled';
        $dup->bookingdate = date('Y-m-d');
        $dup->mytransactionid = $this->bean->getId();
        foreach ($dup->ownPosition as $id => $position) {
            $position->count = $position->count * -1;
        }
        $dup->calcSums($converter);
        R::store($dup);//we have to store it once to gather an ID
        $dup->book();//then we book it, because a cancelation has to be booked immediately
        R::store($dup);//and store it again to make cancelation persistent
        $this->bean->status = 'canceled';
        R::store($this->bean);
        return $dup;
    }

    /**
     * Expunge is an alias of R::trash().
     *
     * The UI uses "expunge" to give models the option to handle trash differntly.
     * E.g. a invoice may never be trashed, instead it will be stored as canceled.
     */
    public function expunge()
    {
        if ($this->bean->locked) {
            //We can not trash this transaction, as it is already booked.
            return false;
        }
        parent::expunge();
    }

    /**
     * Preset the filter (for scaffold list view) on inital request or reset.
     *
     * We want to see only non-archived transactions initally.
     *
     * @param RedBeanPHP\OODBBean
     * @return bool
     */
    public function presetFilter(RedBeanPHP\OODBBean $filter): bool
    {
        return false;
        // delete line above and uncomment the following lines to have a filter preset to show only
        // unarchived transaction beans
        /*
        $criteria = R::dispense('criteria');
        $criteria->op = 'eq';
        $criteria->tag = 'bool';
        $criteria->attribute = 'transaction.archived';
        $criteria->value = false;
        $filter->ownCriteria[] = $criteria;
        return true;
        */
    }

    /**
     * Returns an array with person beans that have unpaid revenue relevant transactions.
     *
     * @return array
     */
    public function getCustomersWithOpenItems()
    {
        $bookable_types = $this->bean->getBookables();
        $sql = "SELECT person.id, person.name FROM transaction AS trans LEFT JOIN person ON person.id = trans.person_id WHERE trans.contracttype_id IN (".R::genSlots($bookable_types).") AND trans.status IN (?) AND trans.locked = 1 GROUP BY person.id ORDER BY person.name";
        return $result = R::getAssoc($sql, array_merge($bookable_types, ['open']));
    }

    /**
     * Returns a transaction bean if this bean has derived from a former one or false if not.
     *
     * @return mixed
     */
    public function hasParent()
    {
        if ($this->bean->mytransactionid) {
            $parent = R::load('transaction', $this->bean->mytransactionid);
            if ($parent->getId()) {
                return $parent;
            }
        }
        return false;
    }

    /**
     * Returns the latest user bean.
     *
     * @return RedBeanPHP\OODBBean
     */
    public function getEditor()
    {
        if (!$this->bean->fetchAs('user')->editor) {
            $this->bean->editor = R::dispense('user');
        }
        return $this->bean->fetchAs('user')->editor;
    }

    /**
     * Returns wether the transaction is locked or not.
     *
     * @return bool
     */
    public function isLocked()
    {
        return $this->bean->locked;
    }

    /**
     * Book this transaction.
     *
     * When a transaction is booked the number is set and the whole transaction is locked.
     *
     * @uses $_SESSION
     */
    public function book()
    {
        $_SESSION['scaffold'][$this->bean->getMeta('type')]['edit']['next_action'] = 'edit';
        if ($this->bean->locked) {
            return false;
        }
        $this->bean->locked = true;
        $number = $this->bean->contracttype->nextnumber;
        $this->bean->contracttype->nextnumber++;
        $this->bean->number = sprintf(self::PATTERN, $this->bean->contracttype->nickname, Flight::setting()->fiscalyear, date('m', strtotime($this->bean->bookingdate)), $number);
        //R::store($this->bean);
        return true;
    }

    /**
     * Calculate net, vat and gros of this transaction from ownPosition.
     *
     * @param $converter Converter_Decimal
     * @uses $bean
     */
    public function calcSums(Converter_Decimal $converter)
    {
        //error_log('Calc sums of #' . $this->bean->getId());
        // calculate totals
        $this->bean->net = 0;
        $this->bean->vat = 0;
        $this->bean->gros = 0;
        $seq = 0;
        foreach ($this->bean->ownPosition as $id => $position) {
            if ($position->kind == Model_Position::KIND_POSITION) {
                // count me
                $seq++;
                //$position->sequence = $seq;
            }
            if ($position->alternative || $position->kind != Model_Position::KIND_POSITION) {
                // skip this position if it is an alternative position
                continue;
            }

            //$net = round($converter->convert($position->count) * $converter->convert($position->salesprice), 2);
            $net = $converter->convert($position->count) * $converter->convert($position->salesprice);

            if ($position->hasAdjustment()) {

                //$adjustment = $net * $converter->convert($position->adjustment) / 100;
                $adjustment = round($net * $converter->convert($position->adjustment) / 100, 2);

                $net = $net + $adjustment;
            }
            $vatpercentage = $position->getVatPercentage();

            //$vat = round($net * $vatpercentage / 100, 2);
            $vat = $net * $vatpercentage / 100;

            //$net = $converter->convert($position->total);
            //$vatamount = $converter->convert($position->vatamount);
            $this->bean->net += $net;
            $this->bean->vat += $vat;
            $this->bean->gros += ($net + $vat);
            //$this->bean->gros += $net + $vatamount;
        }

        // rounding
        //$this->bean->net = round($this->bean->net, 2);
        //$this->bean->vat = round($this->bean->vat, 2);
        //$this->bean->gros = round($this->bean->gros, 2);
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->bean->archived = 0;
        $this->bean->locked = false;
        $this->bean->accumulate = false;//flag to be used for dunning, if true all open items will be combinded in a pdf
        $this->bean->number = '';//I18n::__('transaction_placeholder_number');
        $this->bean->mytransactionid = 0;
        $this->bean->duedays = 0;
        $this->bean->status = 'open';
        $this->bean->bookingdate = date('Y-m-d', time());
        $this->bean->dunningprintedon = date('Y-m-d', time());
        $this->addConverter('bookingdate', new Converter_Mysqldate());
        $this->addConverter('duedate', new Converter_Mysqldate());
        $this->addConverter('dunningprintedon', new Converter_Mysqldate());
        $this->addConverter('duedays', new Converter_Decimal());
        $this->addConverter('net', new Converter_Decimal());
        $this->addConverter('vat', new Converter_Decimal());
        $this->addConverter('gros', new Converter_Decimal());
        $this->addConverter('totalpaid', new Converter_Decimal()); //saldo
        $this->addConverter('balance', new Converter_Decimal()); //saldo
        $this->addConverter('dunningdate', new Converter_Mysqldate()); //last date this transaction was reinforced
        $this->addConverter('penaltyfee', new Converter_Decimal());
    }

    /**
     * Update.
     *
     * @uses calcSums()
     */
    public function update()
    {
        parent::update();

        $converter = new Converter_Decimal();
        $this->bean->calcSums($converter);

        // calculate payments, if it is not canceled and it is an already existing transaction
        if ($this->bean->status != 'canceled' && $this->bean->getId()) {
            $this->bean->status = 'open'; //re-open just in case a payment was deleted
            $this->bean->totalpaid = 0;
            foreach ($this->bean->ownPayment as $id => $payment) {
                if ($payment->closingpayment) {
                    $this->bean->status = "paid";//manually accepted payment as last payment, closing this transaction
                }
                $this->bean->totalpaid += $converter->convert($payment->amount);
            }
            $this->bean->totalpaid = round($this->bean->totalpaid, 2);

            $this->bean->balance = round($this->bean->gros - $this->bean->totalpaid, 2);
            if ($this->bean->balance == 0 && $this->bean->gros != 0) {
                $this->bean->status = "paid";// automatically set as paid when transaction is balanced
            }
        }

        if (!CINNEBAR_MIP) {
            if (!$this->bean->contracttype_id) {
                $this->bean->contracttype_id = null;
                unset($this->bean->contracttype);
            }
        }

        // duedate
        $this->bean->duedate = date('Y-m-d', strtotime($this->bean->bookingdate . ' +' . $this->bean->duedays . 'days'));

        // customer (person)
        if (!$this->bean->person_id) {
            $this->bean->person_id = null;
            unset($this->bean->person);
        }

        // customer (person)
        if (!$this->bean->dunning_id) {
            $this->bean->dunning_id = null;
            unset($this->bean->dunning);
        }

        // discount (skonto) copied from customer (person)
        if (!$this->bean->discount_id) {
            $this->bean->discount_id = null;
            unset($this->bean->discount);
        }

        if (!$this->bean->getId()) {
            // BEHOLD: This has to happen on a dedicated action, not when saving the first time
            // This is a new bean, we want to stamp its number
            //$number = $this->bean->contracttype->nextnumber;
            //$this->bean->contracttype->nextnumber++;
            //$this->bean->number = sprintf(self::PATTERN, $this->bean->contracttype->nickname, Flight::setting()->fiscalyear, date('m', strtotime($this->bean->bookingdate)), $number);
            //$this->bean->number = sprintf(self::PATTERN_INTERIM, $this->bean->contracttype->nickname, Flight::setting()->fiscalyear, date('m', strtotime($this->bean->bookingdate)), I18n::__('transaction_placeholder_nonce'));
            $this->bean->number = '';
        }

        $this->bean->stamp = time();
        $this->bean->editor = Flight::get('user');
    }
}
