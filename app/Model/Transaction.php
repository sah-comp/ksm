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
                'name' => 'status',
                'sort' => [
                    'name' => 'transaction.status'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => '4rem'
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
        $stack[] = 'Transaction';
        $stack[] = $this->bean->number;
        $stack[] = 'Customer';
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
     * Storno.
     */
    public function expunge()
    {
        $this->bean->status = 'canceled';
        R::store($this->bean);
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->bean->status = 'open';
        $this->bean->bookingdate = date('Y-m-d', time());
        $this->addConverter('bookingdate', new Converter_Mysqldate());
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

        if (!CINNEBAR_MIP) {
            if (!$this->bean->contracttype_id) {
                $this->bean->contracttype_id = null;
                unset($this->bean->contracttype);
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
            $this->bean->number = sprintf(self::PATTERN, $this->bean->contracttype->nickname, Flight::setting()->fiscalyear, Flight::setting()->companyyear, $number);
        }
    }
}
