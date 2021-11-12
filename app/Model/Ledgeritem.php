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
 * Ledgeritem model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Ledgeritem extends Model
{
    /**
     * Returns the ledger bean.
     *
     * @return RedbeanPHP\OODBBean
     */
    public function getLedger()
    {
        if (!$this->bean->ledger) {
            $this->bean->ledger = R::dispense('ledger');
        }
        return $this->bean->ledger;
    }

    /**
     * Returns the vat bean.
     *
     * @return RedbeanPHP\OODBBean
     */
    public function getVat()
    {
        if (!$this->bean->vat) {
            $this->bean->vat = R::dispense('vat');
        }
        return $this->bean->vat;
    }

    /**
     * Update.
     */
    public function update()
    {
        parent::update();
        if (!$this->bean->vat_id) {
            $this->bean->vat_id = null;
            unset($this->bean->vat);
        } else {
            $this->bean->vat = R::load('vat', $this->bean->vat_id);
        }
        $vatpercentage = $this->bean->getVat()->value / 100;
        $this->bean->vattaking = round($this->bean->taking / (1 + $vatpercentage) * $vatpercentage, 2);
        $this->bean->vatexpense = round($this->bean->expense / (1 + $vatpercentage) * $vatpercentage, 2);
        //$this->bean->balance = $this->bean->getLedger()->cash + $this->bean->taking - $this->bean->expense;
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->bean->bookingdate = null;
        $this->bean->taking = 0;
        $this->bean->expense = 0;
        $this->bean->vattaking = 0;
        $this->bean->balance = 0;
        $this->addConverter(
            'bookingdate',
            new Converter_Mysqldate()
        );
        $this->addConverter('taking', new Converter_Decimal());
        $this->addConverter('expense', new Converter_Decimal());
        $this->addConverter('vattaking', new Converter_Decimal());
        $this->addConverter('vatexpense', new Converter_Decimal());
        $this->addConverter('balance', new Converter_Decimal());
    }
}
