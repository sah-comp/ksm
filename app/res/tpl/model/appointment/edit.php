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
        <div class="span2">
            <label
                for="appointment-starttime"
                class="<?php echo ($record->hasError('starttime')) ? 'error' : ''; ?>">
                <?php echo I18n::__('appointment_label_starttime') ?>
            </label>
        </div>
        <div class="span2">
            <label
                for="appointment-endtime"
                class="<?php echo ($record->hasError('endtime')) ? 'error' : ''; ?>">
                <?php echo I18n::__('appointment_label_endtime') ?>
            </label>
        </div>
        <div class="span2 number">
            <label
                for="appointment-duration"
                class="<?php echo ($record->hasError('duration')) ? 'error' : ''; ?>">
                <?php echo I18n::__('appointment_label_duration') ?>
            </label>
        </div>
        <div class="span2 number">
            <label
                for="appointment-interval"
                class="<?php echo ($record->hasError('interval')) ? 'error' : ''; ?>">
                <?php echo I18n::__('appointment_label_interval') ?>
            </label>
        </div>
    </div>
    <div class="row">
        <div class="span3">&nbsp;</div>
        <div class="span2">
            <input
                id="appointment-starttime"
                class="autowidth"
                type="time"
                name="dialog[starttime]"
                placeholder="<?php echo I18n::__('placeholder_intl_time') ?>"
                value="<?php echo htmlspecialchars($record->starttime) ?>" />
        </div>
        <div class="span2">
            <input
                id="appointment-endtime"
                class="autowidth"
                type="time"
                name="dialog[endtime]"
                placeholder="<?php echo I18n::__('placeholder_intl_time') ?>"
                value="<?php echo htmlspecialchars($record->endtime) ?>" />
        </div>
        <div class="span2">
            <input
                id="appointment-duration"
                class="autowidth number"
                type="text"
                name="dialog[duration]"
                value="<?php echo htmlspecialchars($record->decimal('duration')) ?>" />
        </div>
        <div class="span3">
            <input
                id="appointment-interval"
                class="autowidth number"
                type="text"
                name="dialog[interval]"
                value="<?php echo htmlspecialchars($record->decimal('interval', 0)) ?>" />
            <p class="info"><?php echo I18n::__('appointment_info_interval') ?></p>
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
<fieldset>
    <legend class="verbose"><?php echo I18n::__('appointment_legend_customer') ?></legend>

    <div class="row <?php echo ($record->getPerson()->hasError()) ? 'error' : ''; ?>">
        <label
            for="appointment-person-name">
            <a href="<?php echo Url::build('/admin/%s/edit/%d', [$record->getPerson()->getMeta('type'), $record->getPerson()->getId()]) ?>" class="ir in-form"><?php echo I18n::__('form_link_related') ?></a>
            <?php echo I18n::__('appointment_label_person') ?>
        </label>
        <input
            type="hidden"
            id="appointment-person-id-shadow"
            name="dialog[person_id]"
            value="<?php echo $record->getPerson()->getId() ?>" />
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
            data-target="person-dependent"
            data-extra="appointment-person-id"
            data-dynamic="<?php echo Url::build('/appointment/%d/person/changed/?callback=?', [$record->getId()]) ?>"
            data-source="<?php echo Url::build('/autocomplete/person/name/?callback=?') ?>"
            data-spread='<?php
                echo json_encode([
                    'appointment-person-name' => 'value',
                    'appointment-person-id' => 'id',
                    'appointment-person-id-shadow' => 'id',
                    'appointment-person-note' => 'note'
                ]); ?>'
            value="<?php echo htmlspecialchars($record->getPerson()->name) ?>" />
            <a
                href="#scratch-item"
                title="<?php echo I18n::__('scaffold_action_scratch_title') ?>"
                data-clear="appointment-person-name"
                data-scratch="appointment-person-id-shadow"
                class="ir scratch"><?php echo I18n::__('scaffold_action_scratch_linktext') ?></a>
    </div>

</fieldset>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('appointment_legend_machine_contact_location') ?></legend>
    <div
        id="person-dependent"
        class="autodafe">

        <?php
        if ($record->getPerson()->getId()):
            // The customer of this appointment is already set. No autodafe needed.
            $_dependents = $record->getDependents($record->getPerson());
            Flight::render('model/appointment/machinecontactlocation', [
                'person' => $record->getPerson(),
                'record' => $record,
                'machines' => $_dependents['machines'],
                'contacts' => $_dependents['contacts'],
                'locations' => $_dependents['locations']
            ]);
        else:
            // lazy load, after hunting that heretic.
        ?>
        <div class="heretic"><?php echo I18n::__('appointment_person_select_before_me') ?></div>
        <?php endif; ?>

    </div>
</fieldset>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('appointment_legend_user') ?></legend>

    <div class="row <?php echo ($record->getUser()->hasError()) ? 'error' : ''; ?>">
        <label
            for="appointment-user-name">
            <a href="<?php echo Url::build('/admin/%s/edit/%d', [$record->getUser()->getMeta('type'), $record->getUser()->getId()]) ?>" class="ir in-form"><?php echo I18n::__('form_link_related') ?></a>
            <?php echo I18n::__('appointment_label_user') ?>
        </label>
        <select
            id="appointment-user-name"
            name="dialog[user_id]">
            <option value=""><?php echo I18n::__('appointment_user_select') ?></option>
            <?php foreach (R::findAll('user', "ORDER BY name") as $_id => $_user): ?>
            <option
                value="<?php echo $_user->getId() ?>"
                <?php echo ($record->user_id == $_user->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_user->getName() . ' ' . $_user->getContactinfo()) ?>
            </option>
            <?php endforeach ?>
        </select>
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
