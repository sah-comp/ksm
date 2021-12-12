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
<!-- headfoot edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('headfoot_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="headfoot-name">
            <?php echo I18n::__('headfoot_label_name') ?>
        </label>
        <input
            id="headfoot-name"
            type="text"
            name="dialog[name]"
            value="<?php echo htmlspecialchars($record->name) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('content')) ? 'error' : ''; ?>">
        <label
            for="headfoot-content">
            <?php echo I18n::__('headfoot_label_content') ?>
        </label>
        <textarea
            id="headfoot-content"
            name="dialog[content]"
            rows="13"
            cols="60"><?php echo htmlspecialchars($record->content) ?></textarea>
        <p class="info"><?php echo I18n::__('headfoot_info_content') ?></p>
    </div>
</fieldset>
<!-- end of headfoot edit form -->
