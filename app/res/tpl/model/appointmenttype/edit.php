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
<!-- appointmenttype edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('appointmenttype_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="appointmenttype-name">
            <?php echo I18n::__('appointmenttype_label_name') ?>
        </label>
        <input
            id="appointmenttype-name"
            type="text"
            name="dialog[name]"
            value="<?php echo htmlspecialchars($record->name) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('color')) ? 'error' : ''; ?>">
        <label
            for="appointmenttype-color">
            <?php echo I18n::__('appointmenttype_label_color') ?>
        </label>
        <input
            id="appointmenttype-color"
            type="text"
            name="dialog[color]"
            value="<?php echo htmlspecialchars($record->color) ?>"
            required="required" />
    </div>
</fieldset>
<!-- end of appointmenttype edit form -->
