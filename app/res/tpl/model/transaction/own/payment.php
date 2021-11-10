<?php
/**
 * Cinnebar.
 *
 * @package Cinnebar
 * @subpackage Template
 * @author $Author$
 * @version $Id$
 */
?>
<!-- payment edit subform -->
<fieldset
    id="transaction-<?php echo $record->getId() ?>-ownPayment-<?php echo $index ?>">
    <legend class="verbose"><?php echo I18n::__('transaction_legend_payment') ?></legend>
    <a
    	href="<?php echo Url::build(sprintf('/admin/transaction/detach/payment/%d', $_payment->getId())) ?>"
    	class="ir detach"
    	title="<?php echo I18n::__('scaffold_detach') ?>"
    	data-target="transaction-<?php echo $record->getId() ?>-ownPayment-<?php echo $index ?>">
    		<?php echo I18n::__('scaffold_detach') ?>
    </a>
    <a
    	href="<?php echo Url::build(sprintf('/admin/transaction/attach/own/payment/%d', $record->getId())) ?>"
    	class="ir attach"
    	title="<?php echo I18n::__('scaffold_attach') ?>"
    	data-target="transaction-<?php echo $record->getId() ?>-payment-container">
    		<?php echo I18n::__('scaffold_attach') ?>
    </a>

    <div>
        <input
            type="hidden"
            name="dialog[ownPayment][<?php echo $index ?>][type]"
            value="<?php echo $_payment->getMeta('type') ?>" />
        <input
            type="hidden"
            name="dialog[ownPayment][<?php echo $index ?>][id]"
            value="<?php echo $_payment->getId() ?>" />
    </div>

    <div class="row">

        <div class="span1">
            &nbsp;
        </div>
        <div class="span2">
            <input
                type="date"
                name="dialog[ownPayment][<?php echo $index ?>][bookingdate]"
                value="<?php echo htmlspecialchars($_payment->bookingdate) ?>">
        </div>
        <div class="span7">
            <textarea
                id="transaction-<?php echo $record->getId() ?>-payment-<?php echo $index ?>-desc"
                name="dialog[ownPayment][<?php echo $index ?>][desc]"
                rows="1"
                cols="60"><?php echo htmlspecialchars($_payment->desc) ?></textarea>
        </div>
        <div class="span2">
            <input
                id="transaction-<?php echo $record->getId() ?>-payment-<?php echo $index ?>-amount"
                type="text"
                class="number"
                name="dialog[ownPayment][<?php echo $index ?>][amount]"
                value="<?php echo htmlspecialchars($_payment->decimal('amount')) ?>">
        </div>
    </div>
</fieldset>
<!-- /payment edit subform -->
