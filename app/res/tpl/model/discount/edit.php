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
<!-- discount edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('discount_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="discount-name">
            <?php echo I18n::__('discount_label_name') ?>
        </label>
        <input
            id="discount-name"
            type="text"
            name="dialog[name]"
            value="<?php echo htmlspecialchars($record->name) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('value')) ? 'error' : ''; ?>">
        <label
            for="discount-value">
            <?php echo I18n::__('discount_label_value') ?>
        </label>
        <input
            id="discount-value"
            class="number"
            type="text"
            name="dialog[value]"
            value="<?php echo htmlspecialchars($record->decimal('value', 3)) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('days')) ? 'error' : ''; ?>">
        <label
            for="discount-days">
            <?php echo I18n::__('discount_label_days') ?>
        </label>
        <input
            id="discount-days"
            class="number"
            type="text"
            name="dialog[days]"
            value="<?php echo htmlspecialchars($record->decimal('days', 0)) ?>" />
    </div>
</fieldset>
<!-- end of discount edit form -->
