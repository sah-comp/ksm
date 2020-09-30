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
<!-- contract edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('contract_legend') ?></legend>

    <div class="row <?php echo ($record->hasError('note')) ? 'error' : ''; ?>">
        <label
            for="contract-number">
            <?php echo I18n::__('contract_label_number') ?>
        </label>
        <textarea
            id="contract-number"
            name="dialog[number]"
            rows="5"
            cols="60"
            required="required"><?php echo htmlspecialchars($record->number) ?></textarea>
        <p class="info"><?php echo I18n::__('contract_info_number') ?></p>
    </div>
</fieldset>
<!-- end of contract edit form -->
