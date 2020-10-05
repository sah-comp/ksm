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
    <legend class="verbose"><?php echo I18n::__('appointment_legend_date') ?></legend>
    <div class="row nomargins">
        <div class="span3">&nbsp;</div>
        <div class="span3">
            <label
                for="appointment-date"
                class="<?php echo ($record->hasError('date')) ? 'error' : ''; ?>">
                <?php echo I18n::__('appointment_label_date') ?>
            </label>
        </div>
        <div class="span2">
            <label
                for="appointment-fix"
                class="<?php echo ($record->hasError('fix')) ? 'error' : ''; ?>">
                <?php echo I18n::__('appointment_label_fix') ?>
            </label>
        </div>
        <div class="span2">
            <label
                for="appointment-confirmed"
                class="<?php echo ($record->hasError('confirmed')) ? 'error' : ''; ?>">
                <?php echo I18n::__('appointment_label_confirmed') ?>
            </label>
        </div>
        <div class="span2">
            <label
                for="appointment-completed"
                class="<?php echo ($record->hasError('completed')) ? 'error' : ''; ?>">
                <?php echo I18n::__('appointment_label_completed') ?>
            </label>
        </div>
    </div>
    <div class="row">
        <div class="span3">&nbsp;</div>
        <div class="span3">
            <input
                id="appointment-date"
                class="autowidth"
                type="date"
                name="dialog[date]"
                value="<?php echo htmlspecialchars($record->localizedDate('date')) ?>"
                required="required" />
        </div>
        <div class="span2">
            <select
                id="appointment-fix"
                class="autowidth"
                name="dialog[fix]">
                <option value="0" <?php echo ($record->fix == 0) ? 'selected="selected"' : '' ?>><?php echo I18n::__('appointment_label_option_false') ?></option>
                <option value="1" <?php echo ($record->fix == 1) ? 'selected="selected"' : '' ?>><?php echo I18n::__('appointment_label_option_true') ?></option>
            </select>
        </div>
        <div class="span2">
            <select
                id="appointment-confirmed"
                class="autowidth"
                name="dialog[confirmed]">
                <option value="0" <?php echo ($record->confirmed == 0) ? 'selected="selected"' : '' ?>><?php echo I18n::__('appointment_label_option_false') ?></option>
                <option value="1" <?php echo ($record->confirmed == 1) ? 'selected="selected"' : '' ?>><?php echo I18n::__('appointment_label_option_true') ?></option>
            </select>
        </div>
        <div class="span2">
            <select
                id="appointment-completed"
                class="autowidth"
                name="dialog[completed]">
                <option value="0" <?php echo ($record->completed == 0) ? 'selected="selected"' : '' ?>><?php echo I18n::__('appointment_label_option_false') ?></option>
                <option value="1" <?php echo ($record->completed == 1) ? 'selected="selected"' : '' ?>><?php echo I18n::__('appointment_label_option_true') ?></option>
            </select>
        </div>
    </div>
</fieldset>

<fieldset>
    <legend class="verbose"><?php echo I18n::__('appointment_legend') ?></legend>

    <div class="row <?php echo ($record->hasError('appointmenttype_id')) ? 'error' : ''; ?>">
        <label
            for="appointment-appointmenttype">
            <?php echo I18n::__('appointment_label_appointmenttype') ?>
        </label>
        <select
            id="appointment-appointmenttype"
            name="dialog[appointmenttype_id]">
            <option value=""><?php echo I18n::__('appointment_appointmenttype_none') ?></option>
            <?php foreach (R::findAll('appointmenttype') as $_id => $_appointmenttype): ?>
            <option
                value="<?php echo $_appointmenttype->getId() ?>"
                <?php echo ($record->appointmenttype_id == $_appointmenttype->getId()) ? 'selected="selected"' : '' ?>><?php echo $_appointmenttype->name ?>
            </option>
            <?php endforeach ?>
        </select>
    </div>

    <div class="row <?php echo ($record->hasError('note')) ? 'error' : ''; ?>">
        <label
            for="appointment-note">
            <?php echo I18n::__('appointment_label_note') ?>
        </label>
        <textarea
            id="appointment-note"
            name="dialog[note]"
            rows="5"
            cols="60"><?php echo htmlspecialchars($record->note) ?></textarea>
        <p class="info"><?php echo I18n::__('appointment_info_note') ?></p>
    </div>
</fieldset>
<!-- end of appointment edit form -->
