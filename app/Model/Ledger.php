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
    }
}
