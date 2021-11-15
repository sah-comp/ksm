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
     * Return a string with the booking time.
     *
     * @return string
     */
    public function getBookingtime()
    {
        if (!$this->bean->bookingtime) {
            $this->bean->bookingtime = date('H:i:s');
        }
        return $this->bean->bookingtime;
    }

    /**
     * Update.
     */
    public function update()
    {
        parent::update();
        $vatpercentage = $this->bean->vat / 100;
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
        $this->bean->bookingtime = null;
        $this->bean->vat = 0;
        $this->bean->taking = 0;
        $this->bean->expense = 0;
        $this->bean->vattaking = 0;
        $this->bean->balance = 0;
        $this->addConverter(
            'bookingdate',
            new Converter_Mysqldate()
        );
        $this->addConverter('vat', new Converter_Decimal());
        $this->addConverter('taking', new Converter_Decimal());
        $this->addConverter('expense', new Converter_Decimal());
        $this->addConverter('vattaking', new Converter_Decimal());
        $this->addConverter('vatexpense', new Converter_Decimal());
        $this->addConverter('balance', new Converter_Decimal());
    }
}
