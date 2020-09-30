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
        $this->stamp = time();
        $this->addConverter('purchaseprice', new Converter_Decimal());
        $this->addConverter('salesprice', new Converter_Decimal());
    }

    /**
     * Update.
     */
    public function update()
    {
        error_log('Updating Installedpart ' . $this->bean->getId() . ' ' . $this->bean->purchaseprice);
        parent::update();
    }
}
