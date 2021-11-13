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
        return array(
            array(
                'name' => 'name',
                'sort' => array(
                    'name' => 'name'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            )
        );
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
     * Returns an array of adjustmentitems ordered by deliverer and invoice.
     *
     * @return array
     */
    public function getLedgeritems()
    {
        return $this->bean->with(' ORDER BY bookingdate ')->ownLedgeritem;
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
            $ledgeritem->balance = $this->bean->balance - $converter->convert($ledgeritem->expense) + $converter->convert($ledgeritem->taking);
            $this->bean->balance = $this->bean->balance - $converter->convert($ledgeritem->expense) + $converter->convert($ledgeritem->taking);
        }
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->bean->cash = 0;
        $this->addValidator('name', array(
            new Validator_HasValue()
        ));
        $this->addConverter('cash', new Converter_Decimal());
        $this->addConverter('totaltaking', new Converter_Decimal());
        $this->addConverter('totalvattaking', new Converter_Decimal());
        $this->addConverter('totalexpense', new Converter_Decimal());
        $this->addConverter('totalvatexpense', new Converter_Decimal());
        $this->addConverter('balance', new Converter_Decimal());
    }
}
