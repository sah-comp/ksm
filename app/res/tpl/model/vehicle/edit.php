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
<!-- vehicle edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('vehicle_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('licenseplate')) ? 'error' : ''; ?>">
        <label
            for="vehicle-licenseplate">
            <?php echo I18n::__('vehicle_label_licenseplate') ?>
        </label>
        <input
            id="vehicle-licenseplate"
            type="text"
            name="dialog[licenseplate]"
            value="<?php echo htmlspecialchars($record->licenseplate) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="vehicle-name">
            <?php echo I18n::__('vehicle_label_name') ?>
        </label>
        <input
            id="vehicle-name"
            type="text"
            name="dialog[name]"
            value="<?php echo htmlspecialchars($record->name) ?>" />
    </div>
</fieldset>
<!-- end of vehicle edit form -->
