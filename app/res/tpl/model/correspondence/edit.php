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
<!-- correspondence edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('correspondence_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="action-name">
            <?php echo I18n::__('correspondence_label_name') ?>
        </label>
        <input
            id="action-subject"
            type="text"
            name="dialog[subject]"
            value="<?php echo htmlspecialchars($record->subject) ?>"
            required="required" />
    </div>
</fieldset>
<!-- end of correspondence edit form -->
