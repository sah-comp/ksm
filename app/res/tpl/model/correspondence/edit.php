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

    <div class="row <?php echo ($record->hasError('confidential')) ? 'error' : ''; ?>">
        <label
            for="correspondence-confidential">
            <?php echo I18n::__('correspondence_label_confidential') ?>
        </label>
        <input
            id="correspondence-confidential"
            type="text"
            name="dialog[confidential]"
            value="<?php echo htmlspecialchars($record->confidential) ?>"/>
    </div>

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
            data-target="person-dependent"
            data-extra="correspondence-person-id"
            data-dynamic="<?php echo Url::build('/correspondence/%d/person/changed/?callback=?', [$record->getId()]) ?>"
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
</fieldset>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('correspondence_legend_contact_location') ?></legend>
    <div
        id="person-dependent"
        class="autodafe">

        <?php
        if ($record->getPerson()->getId()):
            // The customer of this appointment is already set. No autodafe needed.
            $_dependents = $record->getDependents($record->getPerson());
            Flight::render('model/correspondence/contact', [
                'person' => $record->getPerson(),
                'record' => $record,
                'contacts' => $_dependents['contacts']
            ]);
        else:
            // lazy load, after hunting that heretic.
        ?>
        <div class="heretic"><?php echo I18n::__('correspondence_person_select_before_me') ?></div>
        <?php endif; ?>

    </div>
    <div class="row">
        <label for="correspondence-cc"><?php echo I18n::__('correspondence_label_cc') ?></label>
        <input id="correspondence-cc" type="text" name="dialog[cc]" value="<?php echo htmlspecialchars($record->cc) ?>">
        <p class="info"><?php echo I18n::__('correspondence_info_cc') ?></p>
    </div>
</fieldset>
<fieldset>
    <legend class="verbose"></legend>
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
            for="correspondence-subject">
            <?php echo I18n::__('correspondence_label_subject') ?>
        </label>
        <input
            id="correspondence-subject"
            type="text"
            name="dialog[subject]"
            value="<?php echo htmlspecialchars($record->subject) ?>"
            required="required" />
    </div>
</fieldset>
<div class="tab-container">
    <?php Flight::render('shared/navigation/tabs', [
        'tab_id' => 'correspondence-tabs',
        'tabs' => [
            'correspondence-mail' => I18n::__('correspondence_mail_tab'),
            'correspondence-artifact' => I18n::__('correspondence_artifact_tab')
        ],
        'default_tab' => 'correspondence-mail'
    ]) ?>
    <fieldset
        id="correspondence-mail"
        class="tab"
        style="display: block;">
        <legend class="verbose"><?php echo I18n::__('correspondence_legend_mail') ?></legend>
        <div class="row <?php echo ($record->hasError('payload')) ? 'error' : ''; ?>">
            <input id="correspondence-payload" type="hidden" name="dialog[payload]" value="">
            <div class="quill-wrapper">
                <!-- Create toolbar container -->
                <div id="quill-toolbar">
                    <span class="quill-spacer">
                        <select class="ql-size" title="<?php echo I18n::__('quill-font-size') ?>">
                            <option value="small"><?php echo I18n::__('quill-font-size-small') ?></option>
                            <option selected><?php echo I18n::__('quill-font-size-normal') ?></option>
                            <option value="large"><?php echo I18n::__('quill-font-size-large') ?></option>
                            <option value="huge"><?php echo I18n::__('quill-font-size-huge') ?></option>
                        </select>
                    </span>

                    <span class="quill-spacer">
                        <button class="ql-bold" title="<?php echo I18n::__('quill-font-weight-bold') ?>"></button>
                        <button class="ql-italic" title="<?php echo I18n::__('quill-font-style-italic') ?>"></button>
                        <button class="ql-underline" title="<?php echo I18n::__('quill-font-decoration-underline') ?>"></button>
                        <button class="ql-strike" title="<?php echo I18n::__('quill-font-decoration-strike') ?>"></button>
                    </span>

                    <span class="quill-spacer">
                        <button class="ql-header" value="1" title="<?php echo I18n::__('quill-headline-1') ?>"></button>
                        <button class="ql-header" value="2" title="<?php echo I18n::__('quill-headline-2') ?>"></button>
                    </span>

                    <span class="quill-spacer">
                        <button class="ql-list" value="ordered" title="<?php echo I18n::__('quill-list-ordered') ?>"></button>
                        <button class="ql-list" value="bullet" title="<?php echo I18n::__('quill-list-unordered') ?>"></button>
                    </span>

                    <span class="quill-spacer">
                        <select class="ql-color" title="<?php echo I18n::__('quill-font-color') ?>"></select>
                        <select class="ql-background" title="<?php echo I18n::__('quill-background-color') ?>"></select>
                    </span>
                </div>
                <div id="correspondence-payload-editor"><?php echo nl2br($record->payload) ?></div>
            </div>
        </div>
    </fieldset>
    <fieldset
        id="correspondence-artifact"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('correspondence_legend_artifact') ?></legend>
        <div
            id="correspondence-<?php echo $record->getId() ?>-contract-container"
            class="container attachable detachable sortable">
            <?php Flight::render('model/correspondence/tables/artifact', array(
                'record' => $record
            )) ?>
        </div>
    </fieldset>
</div>
<!-- end of correspondence edit form -->
