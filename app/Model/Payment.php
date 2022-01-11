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
 * Payment model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Payment extends Model
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
                'name' => 'bookingdate',
                'sort' => [
                    'name' => 'payment.bookingdate'
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
                'name' => 'transaction.number',
                'sort' => [
                    'name' => 'transaction.number'
                ],
                'callback' => [
                    'name' => 'transactionNumber'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => '12rem'
            ],
            [
                'name' => 'transaction.gros',
                'sort' => [
                    'name' => 'transaction.gros'
                ],
                'class' => 'number',
                'callback' => [
                    'name' => 'transactionGros'
                ],
                'filter' => [
                    'tag' => 'number'
                ],
                'width' => '10rem'
            ],
            [
                'name' => 'amount',
                'sort' => [
                    'name' => 'payment.amount'
                ],
                'callback' => [
                    'name' => 'decimal'
                ],
                'class' => 'number',
                'filter' => [
                    'tag' => 'number'
                ],
                'width' => '10rem'
            ],
            [
                'name' => 'closingpayment',
                'sort' => [
                    'name' => 'payment.closingpayment'
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
                'name' => 'payment.desc',
                'sort' => [
                    'name' => 'payment.desc'
                ],
                'class' => 'text',
                'filter' => [
                    'tag' => 'text'
                ]
            ],
            [
                'name' => 'payment.statement',
                'sort' => [
                    'name' => 'payment.statement'
                ],
                'class' => 'text',
                'filter' => [
                    'tag' => 'text'
                ]
            ]
        ];
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
     * Returns the name of the person (customer)
     *
     * @return string
     */
    public function transactionGros()
    {
        return $this->bean->getTransaction()->decimal('gros');
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
                transaction ON transaction.id = payment.transaction_id
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
        $this->bean->amount = 0;
        //$this->bean->bookingdate = date('Y-m-d');
        $this->addConverter('amount', new Converter_Decimal());
    }

    /**
     * Update.
     */
    public function update()
    {
        parent::update();
        // transaction
        if (!$this->bean->transaction_id) {
            $this->bean->transaction_id = null;
            unset($this->bean->transaction);
        }
    }

    /**
     * After deleting a payment.
     *
     * @uses recalculate()
     */
    public function after_delete()
    {
        $this->recalculate();
    }

    /**
     * After updating a payment.
     *
     * @uses recalculate()
     */
    public function after_update()
    {
        $this->recalculate();
    }

    /**
     * Recalculate the transaction of this payment bean.
     *
     * @see after_delete()
     * @see after_update()
     */
    public function recalculate()
    {
        $transaction = $this->bean->getTransaction();
        if ($transaction->status != 'canceled' && $transaction->getId()) {
            // when there is a valid transaction
            $transaction->status = 'open'; //re-open just in case this payment was fulfilling the amount to pay
            $transaction->totalpaid = 0;
            foreach ($transaction->ownPayment as $id => $payment) {
                $transaction->totalpaid += $payment->amount;
            }
            $transaction->totalpaid = round($transaction->totalpaid, 2);
            $transaction->balance = round($transaction->gros - $transaction->totalpaid, 2);
            if ($transaction->balance == 0) {
                $transaction->status = "paid";// automatically set as paid when transaction is balanced
            }
            R::store($transaction);
        }
    }
}
