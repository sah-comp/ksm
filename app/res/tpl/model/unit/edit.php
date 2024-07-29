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
<!-- unit edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('unit_legend') ?></legend>
    <div class="<?php echo ($record->hasError('name')) ? 'error' : ''; ?> row">
        <label
            for="unit-name">
            <?php echo I18n::__('unit_label_name') ?>
        </label>
        <input
            id="unit-name"
            type="text"
            name="dialog[name]"
            value="<?php echo htmlspecialchars($record->name ?? '') ?>"
            required="required" />
    </div>
    <div class="<?php echo ($record->hasError('code')) ? 'error' : ''; ?> row">
        <label
            for="unit-code">
            <?php echo I18n::__('unit_label_code') ?>
        </label>
        <input
            id="unit-code"
            type="text"
            name="dialog[code]"
            value="<?php echo htmlspecialchars($record->code ?? '') ?>"
            required="required" />
    </div>
</fieldset>
<!-- end of unit edit form -->
