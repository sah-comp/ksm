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
 * Art(icle)Stat(istic) model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Artstat extends Model
{
    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->stamp = date('Y-m-d H:i:s');
        $this->addConverter('purchaseprice', new Converter_Decimal());
        $this->addConverter('salesprice', new Converter_Decimal());
    }

    /**
     * Update.
     */
    public function update()
    {
        parent::update();
    }
}
