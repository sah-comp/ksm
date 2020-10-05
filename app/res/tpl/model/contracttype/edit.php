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
<!-- contracttype edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('contracttypetype_legend') ?></legend>

    <div class="row <?php echo ($record->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="contracttype-name">
            <?php echo I18n::__('contracttype_label_name') ?>
        </label>
        <input
            id="contracttype-name"
            type="text"
            name="dialog[name]"
            value="<?php echo htmlspecialchars($record->name) ?>"
            required="required" />
    </div>

    <div class="row <?php echo ($record->hasError('text')) ? 'error' : ''; ?>">
        <label
            for="contracttype-text">
            <?php echo I18n::__('contracttype_label_text') ?>
        </label>
        <textarea
            id="contracttype-text"
            name="dialog[text]"
            rows="23"
            cols="60"><?php echo htmlspecialchars($record->text) ?></textarea>
        <p class="info"><?php echo I18n::__('contracttype_info_text') ?></p>
    </div>
</fieldset>
<!-- end of contracttype edit form -->
