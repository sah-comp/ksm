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
<!-- transaction edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('transaction_legend') ?></legend>
    <div class="row nomargins">
        <div class="span3">&nbsp;</div>
        <div class="span3">
            <label
                for="transaction-number"
                class="<?php echo ($record->hasError('number')) ? 'error' : ''; ?>">
                <?php echo I18n::__('transaction_label_number') ?>
            </label>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <select
                id="transaction-contracttype"
                class="autowidth"
                name="dialog[contracttype_id]"
                disabled="disabled">
                <option value=""><?php echo I18n::__('transaction_contracttype_none') ?></option>
                <?php foreach (R::find('contracttype', "enabled = 1 AND ledger = 1 ORDER BY name") as $_id => $_contracttype): ?>
                <option
                    value="<?php echo $_contracttype->getId() ?>"
                    <?php echo ($record->contracttype_id == $_contracttype->getId()) ? 'selected="selected"' : '' ?>><?php echo $_contracttype->name ?>
                </option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="span3">
            <input
                id="transaction-number"
                class="autowidth"
                type="text"
                readonly="readonly"
                name="dialog[number]"
                value="<?php echo htmlspecialchars($record->number) ?>" />
            <?php if ($_parent = $record->hasParent()): ?>
            <p class="info"><?php echo I18n::__('transaction_info_parent', null, [$_parent->getId(), $_parent->getContracttype()->name, $_parent->number]) ?></p>
            <?php endif; ?>
        </div>
    </div>
</fieldset>
<!-- end of transaction edit form -->
