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

    <div class="row <?php echo ($record->hasError('person_id')) ? 'error' : ''; ?>">
        <label
            for="correspondence-person-name">
            <a href="<?php echo Url::build('/admin/%s/edit/%d', [$record->getPerson()->getMeta('type'), $record->getPerson()->getId()]) ?>" class="ir in-form"><?php echo I18n::__('form_link_related') ?></a>
            <?php echo I18n::__('correspondence_label_person') ?>
        </label>
        <input
            type="hidden"
            id="correspondence-person-id-shadow"
            name="dialog[person_id]"
            value="<?php echo $record->getPerson()->getId() ?>" />
        <input
            type="hidden"
            name="dialog[person][type]"
            value="person" />
        <input
            id="correspondence-person-id"
            type="hidden"
            name="dialog[person][id]"
            value="<?php echo $record->getPerson()->getId() ?>" />
        <input
            type="text"
            id="correspondence-person-name"
            name="dialog[person][name]"
            class="autocomplete"
            data-source="<?php echo Url::build('/autocomplete/person/name/?callback=?') ?>"
            data-spread='<?php
                echo json_encode([
                    'correspondence-person-name' => 'value',
                    'correspondence-person-id' => 'id',
                    'correspondence-person-id-shadow' => 'id',
                    'correspondence-postaladdress' => 'postaladdress'
                ]); ?>'
            value="<?php echo htmlspecialchars($record->getPerson()->name) ?>" />
            <a
                href="#scratch-item"
                title="<?php echo I18n::__('scaffold_action_scratch_title') ?>"
                data-clear="correspondence-person-name"
                data-scratch="correspondence-person-id-shadow"
                class="ir scratch"><?php echo I18n::__('scaffold_action_scratch_linktext') ?></a>
            <p class="info"><?php echo I18n::__('correspondence_info_person') ?></p>
    </div>
</fieldset>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('correspondence_legend_head') ?></legend>
    <div class="row <?php echo ($record->hasError('postaladdress')) ? 'error' : ''; ?>">
        <label
            for="correspondence-postaladdress">
            <?php echo I18n::__('correspondence_label_postaladdress') ?>
        </label>
        <textarea
            id="correspondence-postaladdress"
            name="dialog[postaladdress]"
            rows="5"
            cols="60"
            required="required"><?php echo htmlspecialchars($record->postaladdress) ?></textarea>
    </div>
    <div class="row <?php echo ($record->hasError('writtenon')) ? 'error' : '' ?>">
        <label for="correspondence-writtenon">
            <?php echo I18n::__('correspondence_label_writtenon') ?>
        </label>
        <input
            id="correspondence-writtenon"
            type="date"
            name="dialog[writtenon]"
            placeholder="<?php echo I18n::__('placeholder_intl_date') ?>"
            value="<?php echo htmlspecialchars($record->writtenon) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('subject')) ? 'error' : ''; ?>">
        <label
            for="action-subject">
            <?php echo I18n::__('correspondence_label_subject') ?>
        </label>
        <input
            id="action-subject"
            type="text"
            name="dialog[subject]"
            value="<?php echo htmlspecialchars($record->subject) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('payload')) ? 'error' : ''; ?>">
        <input id="correspondence-payload" type="hidden" name="dialog[payload]" value="">
        <div class="quill-wrapper">
            <div id="correspondence-payload-editor"><?php echo nl2br($record->payload) ?></div>
        </div>
    </div>
</fieldset>
<!-- end of correspondence edit form -->
