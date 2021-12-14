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
 * Ledger model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Ledger extends Model
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
                'name' => 'fy',
                'sort' => [
                    'name' => 'fy, month'
                ],
                'filter' => [
                    'tag' => 'number'
                ],
                'width' => '8rem'
            ],
            [
                'name' => 'month',
                'sort' => [
                    'name' => 'month'
                ],
                'filter' => [
                    'tag' => 'number'
                ],
                'callback' => [
                    'name' => 'monthname'
                ],
                'width' => '8rem'
            ],
            [
                'name' => 'name',
                'sort' => [
                    'name' => 'name'
                ],
                'filter' => [
                    'tag' => 'text'
                ]
            ],
            [
                'name' => 'cash',
                'sort' => [
                    'name' => 'cash'
                ],
                'class' => 'number',
                'callback' => [
                    'name' => 'decimal'
                ],
                'filter' => [
                    'tag' => 'number'
                ],
                'width' => '10rem'
            ],
            [
                'name' => 'balance',
                'sort' => [
                    'name' => 'balance'
                ],
                'class' => 'number',
                'callback' => [
                    'name' => 'decimal'
                ],
                'filter' => [
                    'tag' => 'number'
                ],
                'width' => '10rem'
            ]
        ];
    }

    /**
     * Return a string with the month name.
     *
     * @return string
     */
    public function monthname()
    {
        return I18n::__('month_label_' . $this->bean->month);
    }

    /**
     * returns an array with month numbers.
     *
     * @return array
     */
    public function getMonths()
    {
        return [
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            10,
            11,
            12
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
     * Returns a string that will work as a filename for ledger as PDF.
     *
     * @return string
     */
    public function getFilename()
    {
        $stack = [];
        $stack[] = $this->bean->fy;
        $stack[] = $this->bean->monthname();
        $stack[] = $this->bean->name;
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
     * Returns an array of adjustmentitems ordered by deliverer and invoice.
     *
     * @return array
     */
    public function getLedgeritems()
    {
        return $this->bean->with(' ORDER BY bookingdate ')->ownLedgeritem;
    }

    /**
     * Generate an array with ledgeritem beans to be used in csv export.
     *
     * @return array
     */
    public function makeCsvData()
    {
        $data = [];
        foreach ($this->bean->with(' ORDER BY bookingdate')->ownLedgeritem as $id => $ledgeritem) {
            $data[$id] = [
                'bookingdate' => $ledgeritem->bookingdate,
                'desc' => $ledgeritem->desc,
                'taking' => $ledgeritem->decimal('taking'),
                'expense' => $ledgeritem->decimal('expense'),
                'vatpercentage' => $ledgeritem->decimal('vat'),
                'vattaking' => $ledgeritem->decimal('vattaking'),
                'vatexpense' => $ledgeritem->decimal('vatexpense'),
                'balance' => $ledgeritem->decimal('balance')
            ];
        }
        return $data;
    }

    /**
     * Update.
     */
    public function update()
    {
        parent::update();
        $converter = new Converter_Decimal();
        // calculate totals
        $this->bean->totaltaking = 0;
        $this->bean->totalexpense = 0;
        $this->bean->totalvattaking = 0;
        $this->bean->totalvatexpense = 0;
        $this->bean->balance = $this->bean->cash;
        foreach ($this->bean->ownLedgeritem as $id => $ledgeritem) {
            $this->bean->totaltaking += $converter->convert($ledgeritem->taking);
            $this->bean->totalvattaking += $converter->convert($ledgeritem->vattaking);
            $this->bean->totalexpense += $converter->convert($ledgeritem->expense);
            $this->bean->totalvatexpense += $converter->convert($ledgeritem->vatexpense);
            //$ledgeritem->balance = $this->bean->balance - $converter->convert($ledgeritem->expense) + $converter->convert($ledgeritem->taking);
            $this->bean->balance = $this->bean->balance - $converter->convert($ledgeritem->expense) + $converter->convert($ledgeritem->taking);
        }
    }

    /**
     * Calculate the ledgeritem balance.
     */
    public function after_update()
    {
        $converter = new Converter_Decimal();
        $last_balance = $this->bean->cash;
        foreach ($this->bean->with("ORDER BY bookingdate")->ownLedgeritem as $id => $ledgeritem) {
            $ledgeritem->balance = $last_balance - $converter->convert($ledgeritem->expense) + $converter->convert($ledgeritem->taking);
            $last_balance = $ledgeritem->balance;
            R::store($ledgeritem);
        }
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->bean->cash = 0;
        $this->bean->fy = date('Y');
        $this->bean->month = date('m');
        $this->addValidator('name', array(
            new Validator_HasValue()
        ));
        $this->addConverter('fy', new Converter_Decimal());
        $this->addConverter('month', new Converter_Decimal());
        $this->addConverter('cash', new Converter_Decimal());
        $this->addConverter('totaltaking', new Converter_Decimal());
        $this->addConverter('totalvattaking', new Converter_Decimal());
        $this->addConverter('totalexpense', new Converter_Decimal());
        $this->addConverter('totalvatexpense', new Converter_Decimal());
        $this->addConverter('balance', new Converter_Decimal());
    }
}
