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
<!-- payment edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('payment_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('bookingdate')) ? 'error' : '' ?>">
        <label for="payment-bookingdate">
            <?php echo I18n::__('payment_label_bookingdate') ?>
        </label>
        <input
            id="payment-bookingdate"
            type="date"
            name="dialog[bookingdate]"
            placeholder="<?php echo I18n::__('placeholder_intl_date') ?>"
            value="<?php echo htmlspecialchars($record->bookingdate) ?>"
            required="required" />
    </div>

    <div class="row <?php echo ($record->hasError('transaction_id')) ? 'error' : ''; ?>">
        <label
            for="payment-transaction-number">
            <a href="<?php echo Url::build('/admin/%s/edit/%d', [$record->getTransaction()->getMeta('type'), $record->getTransaction()->getId()]) ?>" class="ir in-form"><?php echo I18n::__('form_link_related') ?></a>
            <?php echo I18n::__('payment_label_transaction') ?>
        </label>
        <input
            type="hidden"
            id="payment-transaction-id-shadow"
            name="dialog[transaction_id]"
            value="<?php echo $record->getTransaction()->getId() ?>" />
        <input
            type="hidden"
            name="dialog[transaction][type]"
            value="transaction" />
        <input
            id="payment-transaction-id"
            type="hidden"
            name="dialog[transaction][id]"
            value="<?php echo $record->getTransaction()->getId() ?>" />
        <input
            type="text"
            id="payment-transaction-number"
            name="dialog[transaction][number]"
            class="autocomplete"
            data-source="<?php echo Url::build('/autocomplete/transaction/number/?callback=?') ?>"
            data-spread='<?php
                echo json_encode([
                    'payment-transaction-number' => 'value',
                    'payment-transaction-id' => 'id',
                    'payment-transaction-id-shadow' => 'id',
                    //'payment-something' => 'something'
                ]); ?>'
            value="<?php echo htmlspecialchars($record->getTransaction()->number) ?>" />
            <a
                href="#scratch-item"
                title="<?php echo I18n::__('scaffold_action_scratch_title') ?>"
                data-clear="payment-transaction-number"
                data-scratch="payment-transaction-id-shadow"
                class="ir scratch"><?php echo I18n::__('scaffold_action_scratch_linktext') ?></a>
    </div>

    <div class="row <?php echo ($record->hasError('desc')) ? 'error' : ''; ?>">
        <label
            for="payment-desc">
            <?php echo I18n::__('payment_label_desc') ?>
        </label>
        <textarea
            id="payment-desc"
            name="dialog[desc]"
            rows="3"
            cols="60"><?php echo htmlspecialchars($record->desc) ?></textarea>
    </div>
    <div class="row <?php echo ($record->hasError('statement')) ? 'error' : ''; ?>">
        <label
            for="payment-statement">
            <?php echo I18n::__('payment_label_statement') ?>
        </label>
        <textarea
            id="payment-statement"
            name="dialog[statement]"
            rows="3"
            cols="60"><?php echo htmlspecialchars($record->statement) ?></textarea>
    </div>
    <div class="row <?php echo ($record->hasError('closingpayment')) ? 'error' : ''; ?>">
        <input
            type="hidden"
            name="dialog[closingpayment]"
            value="0" />
        <input
            id="payment-closingpayment"
            type="checkbox"
            name="dialog[closingpayment]"
            <?php echo ($record->closingpayment) ? 'checked="checked"' : '' ?>
            value="1" />
        <label
            for="payment-closingpayment"
            class="cb">
            <?php echo I18n::__('payment_label_closingpayment') ?>
        </label>
    </div>
    <div class="row <?php echo ($record->hasError('amount')) ? 'error' : ''; ?>">
        <label
            for="payment-amount">
            <?php echo I18n::__('payment_label_amount') ?>
        </label>
        <input
            id="payment-amount"
            type="text"
            class="number"
            name="dialog[amount]"
            value="<?php echo htmlspecialchars($record->decimal('amount')) ?>" />
            <p class="info">
                <?php echo I18n::__('payment_info_amount') ?>
            </p>
    </div>
</fieldset>
<!-- end of payment edit form -->
