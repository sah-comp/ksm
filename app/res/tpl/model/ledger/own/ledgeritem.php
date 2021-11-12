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
<!-- ledgeritem edit subform -->
<fieldset
    id="ledger-<?php echo $record->getId() ?>-ownledgeritem-<?php echo $index ?>">
    <legend class="verbose"><?php echo I18n::__('ledger_legend_ledgeritem') ?></legend>
    <a
    	href="<?php echo Url::build(sprintf('/admin/ledger/detach/ledgeritem/%d', $_ledgeritem->getId())) ?>"
    	class="ir detach"
    	title="<?php echo I18n::__('scaffold_detach') ?>"
    	data-target="ledger-<?php echo $record->getId() ?>-ownledgeritem-<?php echo $index ?>">
    		<?php echo I18n::__('scaffold_detach') ?>
    </a>
    <a
    	href="<?php echo Url::build(sprintf('/admin/ledger/attach/own/ledgeritem/%d', $record->getId())) ?>"
    	class="ir attach"
    	title="<?php echo I18n::__('scaffold_attach') ?>"
    	data-target="ledger-<?php echo $record->getId() ?>-ledgeritem-container">
    		<?php echo I18n::__('scaffold_attach') ?>
    </a>
    <div>
        <input type="hidden" name="dialog[ownLedgeritem][<?php echo $index ?>][type]" value="<?php echo $_ledgeritem->getMeta('type') ?>" />
        <input type="hidden" name="dialog[ownLedgeritem][<?php echo $index ?>][id]" value="<?php echo $_ledgeritem->getId() ?>" />
    </div>
    <div class="row">
        <div class="span1">&nbsp;</div>
        <div class="span2">
            <input
                id="ledger-ledgeritem-<?php echo $index ?>-bookingdate"
                class="autowidth"
                type="date"
                placeholder="yyyy-mm-dd"
                name="dialog[ownLedgeritem][<?php echo $index ?>][bookingdate]"
                value="<?php echo htmlspecialchars($_ledgeritem->bookingdate) ?>" />
        </div>
        <div class="span3">
            <input
                id="ledger-ledgeritem-<?php echo $index ?>-desc"
                type="text"
                name="dialog[ownLedgeritem][<?php echo $index ?>][desc]"
                value="<?php echo htmlspecialchars($_ledgeritem->desc) ?>" />
        </div>
        <div class="span1">
            <input
                id="ledger-ledgeritem-<?php echo $index ?>-taking"
                type="text"
                class="number"
                name="dialog[ownLedgeritem][<?php echo $index ?>][taking]"
                value="<?php echo htmlspecialchars($_ledgeritem->decimal('taking')) ?>" />
        </div>
        <div class="span1">
            <input
                id="ledger-ledgeritem-<?php echo $index ?>-expense"
                type="text"
                class="number"
                name="dialog[ownLedgeritem][<?php echo $index ?>][expense]"
                value="<?php echo htmlspecialchars($_ledgeritem->decimal('expense')) ?>" />
        </div>
        <div class="span1">
            <select
                id="ledger-ledgeritem-<?php echo $index ?>-vat"
                name="dialog[ownLedgeritem][<?php echo $index ?>][vat_id]">
                <option value=""><?php echo I18n::__('ledgeritem_label_vat_select') ?></option>
                <?php foreach (R::find('vat', " ORDER BY name") as $_vat_id => $_vat): ?>
                <option
                    value="<?php echo $_vat->getId() ?>"
                    <?php echo ($_ledgeritem->vat_id == $_vat->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_vat->name) ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="span1">
            <input
                id="ledger-ledgeritem-<?php echo $index ?>-vat-taking"
                type="text"
                class="number"
                readonly="readonly"
                name="dialog[ownLedgeritem][<?php echo $index ?>][vattaking]"
                value="<?php echo htmlspecialchars($_ledgeritem->decimal('vattaking')) ?>" />
        </div>
        <div class="span1">
            <input
                id="ledger-ledgeritem-<?php echo $index ?>-vat-expense"
                type="text"
                class="number"
                readonly="readonly"
                name="dialog[ownLedgeritem][<?php echo $index ?>][vatexpense]"
                value="<?php echo htmlspecialchars($_ledgeritem->decimal('vatexpense')) ?>" />
        </div>
        <div class="span1">
            <input
                id="ledger-ledgeritem-<?php echo $index ?>-balance"
                type="text"
                class="number"
                readonly="readonly"
                name="dialog[ownLedgeritem][<?php echo $index ?>][balance]"
                value="<?php echo htmlspecialchars($_ledgeritem->decimal('balance')) ?>" />
        </div>
    </div>
</fieldset>
<!-- /margin edit subform -->
