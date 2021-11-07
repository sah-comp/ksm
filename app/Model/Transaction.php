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
     * Constructor
     */
    public function __construct()
    {
        $this->setAction('index', ['idle', 'cancel', 'expunge']);
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
        //$stack[] = $this->bean->getPerson()->nickname;
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
        $discount = $this->bean->getDiscount();
        if ($discount->days != 0 && $discount->value != 0) {
            return I18n::__('transaction_payment_condition_discount', null, [$this->bean->duedays, $discount->days, $discount->value]);
        } else {
            return I18n::__('transaction_payment_condition_no_discount', null, [$this->bean->duedays]);
        }
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
        $sql = "SELECT ROUND(SUM(total), 2) AS net FROM position WHERE transaction_id = :trans_id AND costunittype_id = :cut_id";
        $result = R::getCell($sql, [
            ':trans_id' => $this->bean->getId(),
            ':cut_id' => $costunittype->getId()
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
        $sql = "SELECT ROUND(SUM(gros), 2) AS gros FROM position WHERE transaction_id = :trans_id AND costunittype_id = :cut_id";
        $result = R::getCell($sql, [
            ':trans_id' => $this->bean->getId(),
            ':cut_id' => $costunittype->getId()
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
                alternative != 1
            GROUP BY
                vatpercentage
            ORDER BY
                vatpercentage
SQL;
        $result = R::getAll($sql, [$this->bean->getId()]);
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
        return "style=\"border-left: 3px solid {$bordercolor};\"";
    }

    /**
     * Cancels this transaction.
     *
     * @return mixed
     */
    public function cancel()
    {
        if ($this->bean->status == 'canceled') {
            throw new Exception(I18n::__('transaction_is_already_canceled'));
        }
        $dup = R::duplicate($this->bean);
        $dup->status = 'canceled';
        $dup->bookingdate = date('Y-m-d');
        $dup->mytransactionid = $this->bean->getId();
        foreach ($dup->ownPosition as $id => $position) {
            $position->count = $position->count * -1;
        }
        R::store($dup);
        $this->bean->status = 'canceled';
        R::store($this->bean);
        return $dup;
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
     * Dispense.
     */
    public function dispense()
    {
        $this->bean->mytransactionid = 0;
        $this->bean->duedays = 0;
        $this->bean->status = 'open';
        $this->bean->bookingdate = date('Y-m-d', time());
        $this->addConverter('bookingdate', new Converter_Mysqldate());
        $this->addConverter('duedate', new Converter_Mysqldate());
        $this->addConverter('net', new Converter_Decimal());
    }

    /**
     * Update.
     */
    public function update()
    {
        parent::update();

        // calculate the net, vats and gros of this transaction
        $converter = new Converter_Decimal();
        $this->bean->net = 0;
        $this->bean->vat = 0;
        $this->bean->gros = 0;
        foreach ($this->bean->ownPosition as $id => $position) {
            if ($position->alternative) {
                // skip this position if it is an alternative position
                continue;
            }
            $net = $converter->convert($position->count) * $converter->convert($position->salesprice);
            $vat = $net * $position->vatpercentage / 100;
            $this->bean->net += $net;
            $this->bean->vat += $vat;
            $this->bean->gros += $net + $vat;
        }
        $this->bean->net = round($this->bean->net, 2);
        $this->bean->vat = round($this->bean->vat, 2);
        $this->bean->gros = round($this->bean->gros, 2);

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
            // This is a new bean, we want to stamp its number
            $number = $this->bean->contracttype->nextnumber;
            $this->bean->contracttype->nextnumber++;
            $this->bean->number = sprintf(self::PATTERN, $this->bean->contracttype->nickname, Flight::setting()->fiscalyear, Flight::setting()->companyyear, $number);
        }
    }
}
