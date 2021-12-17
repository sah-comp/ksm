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
     * Return actions array.
     */
    public function getActions()
    {
        return [
            'index' => ['idle', 'cancel', 'expunge'],
            'add' => ['add', 'edit', 'index'],
            'edit' => ['edit', 'next_edit', 'prev_edit', 'index', 'book'],
            'delete' => ['index']
        ];
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
                    'tag' => 'text'
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
            ]
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
     * Returns a string that will work as a title of treaty.
     *
     * @return string
     */
    public function getDocname()
    {
        return $this->bean->getFilename();
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
        //return I18n::__('transaction_payment_condition_discount', null, [$this->bean->duedays, $discount->days, $discount->value]);
        } elseif ($this->bean->duedays == 0) {
            // duedays is 0 (zero)
            return $company->conditionimmediately;
        //return I18n::__('transaction_payment_condition_immediately');
        } else {
            // no discount, but due days are not zero
            return vsprintf($company->conditionnodiscount, [$this->bean->duedays]);
            //return I18n::__('transaction_payment_condition_no_discount', null, [$this->bean->duedays]);
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
        $sql = "SELECT ROUND(SUM(total), 2) AS net FROM position WHERE transaction_id = :trans_id AND costunittype_id = :cut_id AND kind = :kind_position";
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
        $sql = "SELECT ROUND(SUM(gros), 2) AS gros FROM position WHERE transaction_id = :trans_id AND costunittype_id = :cut_id AND kind = :kind_position";
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
        switch ($query) {
            default:
            $sql = <<<SQL
                SELECT
                    transaction.id AS id,
                    transaction.number AS label,
                    transaction.number AS value
                FROM
                    transaction
                WHERE
                    transaction.number LIKE :searchtext
                ORDER BY
                    transaction.number
                LIMIT {$limit}
    SQL;
        }
        $result = R::getAll($sql, array(':searchtext' => $searchtext . '%' ));
        return $result;
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
                sum(total) AS net,
                (sum(total) * vatpercentage / 100) AS vatvalue
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
            $net = $converter->convert($position->count) * $converter->convert($position->salesprice);
            $vat = $net * $position->vatpercentage / 100;
            $this->bean->net += $net;
            $this->bean->vat += $vat;
            $this->bean->gros += $net + $vat;
        }
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->bean->locked = false;
        $this->bean->number = '';//I18n::__('transaction_placeholder_number');
        $this->bean->mytransactionid = 0;
        $this->bean->duedays = 0;
        $this->bean->status = 'open';
        $this->bean->bookingdate = date('Y-m-d', time());
        $this->addConverter('bookingdate', new Converter_Mysqldate());
        $this->addConverter('duedate', new Converter_Mysqldate());
        $this->addConverter('duedays', new Converter_Decimal());
        $this->addConverter('net', new Converter_Decimal());
        $this->addConverter('vat', new Converter_Decimal());
        $this->addConverter('gros', new Converter_Decimal());
        $this->addConverter('totalpaid', new Converter_Decimal()); //saldo
        $this->addConverter('balance', new Converter_Decimal()); //saldo
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

        // rounding
        $this->bean->net = round($this->bean->net, 2);
        $this->bean->vat = round($this->bean->vat, 2);
        $this->bean->gros = round($this->bean->gros, 2);

        // calculate payments, if it is not canceled
        if ($this->bean->status != 'canceled') {
            $this->bean->totalpaid = 0;
            foreach ($this->bean->ownPayment as $id => $payment) {
                if ($payment->closingpayment) {
                    $this->bean->status = "paid";//manually accepted payment as last payment, closing this transaction
                }
                $this->bean->totalpaid += $converter->convert($payment->amount);
            }
            $this->bean->totalpaid = round($this->bean->totalpaid, 2);

            $this->bean->balance = round($this->bean->gros - $this->bean->totalpaid, 2);
            if ($this->bean->balance == 0) {
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
