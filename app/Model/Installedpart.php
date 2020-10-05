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
 * Installedpart model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Installedpart extends Model
{
    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->stamp = date('Y-m-d', time());
        $this->addConverter('purchaseprice', new Converter_Decimal());
        $this->addConverter('salesprice', new Converter_Decimal());
        $this->addConverter('stamp', new Converter_Mysqldate());
    }

    /**
     * Update.
     */
    public function update()
    {
        parent::update();
    }
}
