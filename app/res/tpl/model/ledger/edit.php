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
<!-- ledger edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('ledger_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('fy')) ? 'error' : ''; ?>">
        <label
            for="ledger-fy">
            <?php echo I18n::__('ledger_label_fy') ?>
        </label>
        <input
            id="ledger-fy"
            type="text"
            name="dialog[fy]"
            value="<?php echo htmlspecialchars($record->fy ?? '') ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('month')) ? 'error' : ''; ?>">
        <label
            for="ledger-month">
            <?php echo I18n::__('ledger_label_month') ?>
        </label>
        <select
            id="ledger-month"
            name="dialog[month]">
            <?php foreach ($record->getMonths() as $_month): ?>
            <option
                value="<?php echo $_month ?>"
                <?php echo ($record->month == $_month) ? 'selected="selected"' : '' ?>><?php echo I18n::__('month_label_' . $_month) ?>
            </option>
            <?php endforeach ?>
        </select>
    </div>
    <div class="row <?php echo ($record->hasError('cash')) ? 'error' : ''; ?>">
        <label
            for="ledger-cash">
            <?php echo I18n::__('ledger_label_cash') ?>
        </label>
        <input
            id="ledger-cash"
            type="text"
            class="number"
            name="dialog[cash]"
            value="<?php echo htmlspecialchars($record->decimal('cash') ?? '') ?>" />
    </div>
</fieldset>
<div
    class="tab-container">
    <?php Flight::render('shared/navigation/tabs', array(
        'tab_id' => 'ledger-tabs',
        'tabs' => array(
            'ledger-ledgeritem' => I18n::__('ledger_ledgeritem_tab')
        ),
        'default_tab' => 'ledger-ledgeritem'
    )) ?>
    <fieldset
        id="ledger-ledgeritem"
        class="tab"
        style="display: block;">
        <legend class="verbose"><?php echo I18n::__('ledgeritem_legend') ?></legend>
        <!-- grid based header -->
        <div class="row">
            <div class="span1">&nbsp;</div>
            <div class="span2">
                <label>
                    <?php echo I18n::__('ledgeritem_label_bookingdate') ?>
                </label>
            </div>
            <div class="span3">
                <label>
                    <?php echo I18n::__('ledgeritem_label_desc') ?>
                </label>
            </div>
            <div class="span1 tar">
                <label>
                    <?php echo I18n::__('ledgeritem_label_taking') ?>
                </label>
            </div>
            <div class="span1 tar">
                <label>
                    <?php echo I18n::__('ledgeritem_label_expense') ?>
                </label>
            </div>
            <div class="span1">
                <label>
                    <?php echo I18n::__('ledgeritem_label_vat') ?>
                </label>
            </div>
            <div class="span1 tar">
                <label>
                    <?php echo I18n::__('ledgeritem_label_vat_taking') ?>
                </label>
            </div>
            <div class="span1 tar">
                <label>
                    <?php echo I18n::__('ledgeritem_label_vat_expense') ?>
                </label>
            </div>
            <div class="span1 tar">
                <label>
                    <?php echo I18n::__('ledgeritem_label_balance') ?>
                </label>
            </div>
        </div>
        <!-- end of grid based header -->
        <!-- grid based data -->
        <div
            id="ledger-<?php echo $record->getId() ?>-ledgeritem-container"
            class="container attachable detachable sortable">
		<?php $_ledgeritems = $record->getLedgeritems() ?>
        <?php if (count($_ledgeritems) == 0) {
        $_ledgeritems[] = R::dispense('ledgeritem');
    } ?>
        <?php
        $index = 0;
        ?>
        <?php foreach ($_ledgeritems as $_ledgeritem_id => $_ledgeritem):
            $index++;
        ?>
            <?php Flight::render('model/ledger/own/ledgeritem', array(
                'record' => $record,
                '_ledgeritem' => $_ledgeritem,
                'index' => $index
            )) ?>
        <?php endforeach ?>
        </div>
        <!-- end of grid based data -->
        <div class="row">
            <div class="span1">&nbsp;</div>
            <div class="span5">
                <label>
                    <?php echo I18n::__('ledger_label_totals') ?>
                </label>
            </div>
            <div class="span1">
                <input
                    type="text"
                    class="number"
                    name="dialog[totaltaking]"
                    readonly="readonly"
                    value="<?php echo htmlspecialchars($record->decimal('totaltaking') ?? '') ?>">
            </div>
            <div class="span1">
                <input
                    type="text"
                    class="number"
                    name="dialog[totalexpense]"
                    readonly="readonly"
                    value="<?php echo htmlspecialchars($record->decimal('totalexpense') ?? '') ?>">
            </div>
            <div class="span1">
                &nbsp;
            </div>
            <div class="span1">
                <input
                    type="text"
                    class="number"
                    name="dialog[totalvattaking]"
                    readonly="readonly"
                    value="<?php echo htmlspecialchars($record->decimal('totalvattaking') ?? '') ?>">
            </div><div class="span1">
                <input
                    type="text"
                    class="number"
                    name="dialog[totalvatexpense]"
                    readonly="readonly"
                    value="<?php echo htmlspecialchars($record->decimal('totalvatexpense') ?? '') ?>">
            </div><div class="span1">
                <input
                    type="text"
                    class="number"
                    name="dialog[balance]"
                    readonly="readonly"
                    value="<?php echo htmlspecialchars($record->decimal('balance') ?? '') ?>">
            </div>
        </div>
    </fieldset>
</div>
<!-- end of ledger edit form -->
