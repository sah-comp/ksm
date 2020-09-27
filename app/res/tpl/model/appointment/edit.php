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
<!-- appointment edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('appointment_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('note')) ? 'error' : ''; ?>">
        <label
            for="appointment-note">
            <?php echo I18n::__('appointment_label_note') ?>
        </label>
        <textarea
            id="appointment-note"
            name="dialog[note]"
            rows="5"
            cols="60"
            required="required"><?php echo htmlspecialchars($record->note) ?></textarea>
        <p class="info"><?php echo I18n::__('appointment_info_note') ?></p>
    </div>
</fieldset>
<!-- end of appointment edit form -->
