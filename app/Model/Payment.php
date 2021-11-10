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
    }
}
