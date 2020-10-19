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

    <input type="hidden" name="dialog[receipt]" value="<?php echo $record->localizedDate('receipt') ?>" />
    <input type="hidden" name="dialog[terminationdate]" value="<?php echo $record->localizedDate('terminationdate') ?>" />
</div>

<fieldset>
    <legend class="verbose"><?php echo I18n::__('appointment_legend_date') ?></legend>
    <div class="row nomargins">
        <div class="span3">&nbsp;</div>
        <div class="span2">
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
        <div class="span1">
            <label
                for="appointment-completed"
                class="<?php echo ($record->hasError('completed')) ? 'error' : ''; ?>">
                <?php echo I18n::__('appointment_label_completed') ?>
            </label>
        </div>
        <div class="span2">
            <label
                for="appointment-invoice"
                class="<?php echo ($record->hasError('invoice')) ? 'error' : ''; ?>">
                <?php echo I18n::__('appointment_label_invoice') ?>
            </label>
        </div>
    </div>
    <div class="row">
        <div class="span3">&nbsp;</div>
        <div class="span2">
            <input
                id="appointment-date"
                class="autowidth"
                type="date"
                name="dialog[date]"
                placeholder="<?php echo I18n::__('placeholder_intl_date') ?>"
                value="<?php echo htmlspecialchars($record->date) ?>"
                required="required" />
                <p class="info"><?php echo I18n::__('appointment_info_date', null, [$record->localizedDate('receipt')]) ?></p>
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
        <div class="span1">
            <select
                id="appointment-completed"
                class="autowidth"
                name="dialog[completed]">
                <option value="0" <?php echo ($record->completed == 0) ? 'selected="selected"' : '' ?>><?php echo I18n::__('appointment_label_option_false') ?></option>
                <option value="1" <?php echo ($record->completed == 1) ? 'selected="selected"' : '' ?>><?php echo I18n::__('appointment_label_option_true') ?></option>
            </select>
        </div>
        <div class="span2">
            <input
                id="appointment-invoice"
                class="autowidth"
                type="text"
                name="dialog[invoice]"
                value="<?php echo htmlspecialchars($record->invoice) ?>">
        </div>
    </div>

    <div class="row nomargins">
        <div class="span3">&nbsp;</div>
        <div class="span3">
            <label
                for="appointment-starttime"
                class="<?php echo ($record->hasError('starttime')) ? 'error' : ''; ?>">
                <?php echo I18n::__('appointment_label_starttime') ?>
            </label>
        </div>
        <div class="span3">
            <label
                for="appointment-endtime"
                class="<?php echo ($record->hasError('endtime')) ? 'error' : ''; ?>">
                <?php echo I18n::__('appointment_label_endtime') ?>
            </label>
        </div>
        <div class="span3 number">
            <label
                for="appointment-duration"
                class="<?php echo ($record->hasError('duration')) ? 'error' : ''; ?>">
                <?php echo I18n::__('appointment_label_duration') ?>
            </label>
        </div>
    </div>
    <div class="row">
        <div class="span3">&nbsp;</div>
        <div class="span3">
            <input
                id="appointment-starttime"
                class="autowidth"
                type="time"
                name="dialog[starttime]"
                placeholder="<?php echo I18n::__('placeholder_intl_time') ?>"
                value="<?php echo htmlspecialchars($record->starttime) ?>" />
        </div>
        <div class="span3">
            <input
                id="appointment-endtime"
                class="autowidth"
                type="time"
                name="dialog[endtime]"
                placeholder="<?php echo I18n::__('placeholder_intl_time') ?>"
                value="<?php echo htmlspecialchars($record->endtime) ?>" />
        </div>
        <div class="span3">
            <input
                id="appointment-duration"
                class="autowidth number"
                type="text"
                name="dialog[duration]"
                readonly="readonly"
                value="<?php echo htmlspecialchars($record->decimal('duration', 2)) ?>" />
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
</fieldset>
<fieldset
    id="machine-failure"
    class="only-on-service"
    style="display: <?php echo ($record->appointmenttype_id == Flight::setting()->appointmenttypeservice) ? 'block' : 'none'; ?>">
    <legend class="verbose"><?php echo I18n::__('appointment_legend_failure') ?></legend>
    <div class="row <?php echo ($record->hasError('failure')) ? 'error' : ''; ?>">
        <label
            for="appointment-failure">
            <?php echo I18n::__('appointment_label_failure') ?>
        </label>
        <textarea
            id="appointment-failure"
            name="dialog[failure]"
            rows="5"
            cols="60"><?php echo htmlspecialchars($record->failure) ?></textarea>
        <p class="info"><?php echo I18n::__('appointment_info_failure') ?></p>
    </div>
</fieldset>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('appointment_legend_customer') ?></legend>

    <div class="row <?php echo ($record->hasError('worker')) ? 'error' : ''; ?>">
        <label
            for="appointment-worker">
            <?php echo I18n::__('appointment_label_worker') ?>
        </label>
        <input
            id="appointment-worker"
            type="text"
            name="dialog[worker]"
            value="<?php echo htmlspecialchars($record->worker) ?>" />
    </div>

    <div class="row <?php echo ($record->hasError('person_id')) ? 'error' : ''; ?>">
        <label
            for="appointment-person-name">
            <?php echo I18n::__('appointment_label_person') ?>
        </label>
        <input
            type="hidden"
            name="dialog[person][type]"
            value="person" />
        <input
            id="appointment-person-id"
            type="hidden"
            name="dialog[person][id]"
            value="<?php echo $record->getPerson()->getId() ?>" />
        <input
            type="text"
            id="appointment-person-name"
            name="dialog[person][name]"
            class="autocomplete"
            data-source="<?php echo Url::build('/autocomplete/person/name/?callback=?') ?>"
            data-spread='<?php
                echo json_encode([
                    'appointment-person-name' => 'value',
                    'appointment-person-id' => 'id'
                ]); ?>'
            value="<?php echo htmlspecialchars($record->getPerson()->name) ?>" />
    </div>

    <div class="row <?php echo ($record->getMachine()->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="appointment-machine-name">
            <?php echo I18n::__('appointment_label_machine') ?>
        </label>
        <input
            type="hidden"
            name="dialog[machine][type]"
            value="machine" />
        <input
            id="appointment-machine-id"
            type="hidden"
            name="dialog[machine][id]"
            value="<?php echo $record->getMachine()->getId() ?>" />
        <input
            type="text"
            id="appointment-machine-name"
            name="dialog[machine][name]"
            class="autocomplete"
            data-source="<?php echo Url::build('/autocomplete/machine/name/?callback=?') ?>"
            data-extra="appointment-person-id"
            data-spread='<?php
                echo json_encode([
                    'appointment-machine-name' => 'value',
                    'appointment-machine-id' => 'id'
                ]); ?>'
            value="<?php echo htmlspecialchars($record->getMachine()->name) ?>" />
    </div>

    <div class="row <?php echo ($record->hasError('contact_id')) ? 'error' : ''; ?>">
        <label
            for="appointment-contact-name">
            <?php echo I18n::__('appointment_label_contact') ?>
        </label>
        <input
            type="hidden"
            name="dialog[contact][type]"
            value="contact" />
        <input
            id="appointment-contact-id"
            type="hidden"
            name="dialog[contact][id]"
            value="<?php echo $record->getContact()->getId() ?>" />
        <input
            type="text"
            id="appointment-contact-name"
            name="dialog[contact][name]"
            class="autocomplete"
            data-source="<?php echo Url::build('/autocomplete/contact/name/?callback=?') ?>"
            data-spread='<?php
                echo json_encode([
                    'appointment-contact-name' => 'value',
                    'appointment-contact-id' => 'id'
                ]); ?>'
            value="<?php echo htmlspecialchars($record->getContact()->name) ?>" />
    </div>

    <div class="row <?php echo ($record->hasError('location_id')) ? 'error' : ''; ?>">
        <label
            for="appointment-location-name">
            <?php echo I18n::__('appointment_label_location') ?>
        </label>
        <input
            type="hidden"
            name="dialog[location][type]"
            value="location" />
        <input
            id="appointment-location-id"
            type="hidden"
            name="dialog[location][id]"
            value="<?php echo $record->getLocation()->getId() ?>" />
        <input
            type="text"
            id="appointment-location-name"
            name="dialog[location][name]"
            class="autocomplete"
            data-source="<?php echo Url::build('/autocomplete/location/name/?callback=?') ?>"
            data-spread='<?php
                echo json_encode([
                    'appointment-location-name' => 'value',
                    'appointment-location-id' => 'id'
                ]); ?>'
            value="<?php echo htmlspecialchars($record->getLocation()->name) ?>" />
    </div>
</fieldset>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('appointment_legend_note') ?></legend>
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
