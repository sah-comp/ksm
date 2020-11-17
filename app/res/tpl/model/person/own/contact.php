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
<!-- contact edit subform -->
<fieldset
    id="person-<?php echo $record->getId() ?>-owncontact-<?php echo $index ?>">
    <legend class="verbose"><?php echo I18n::__('person_legend_contact') ?></legend>
    <a
    	href="<?php echo Url::build(sprintf('/admin/person/detach/contact/%d', $_contact->getId())) ?>"
    	class="ir detach"
    	title="<?php echo I18n::__('scaffold_detach') ?>"
    	data-target="person-<?php echo $record->getId() ?>-owncontact-<?php echo $index ?>">
    		<?php echo I18n::__('scaffold_detach') ?>
    </a>
    <a
    	href="<?php echo Url::build(sprintf('/admin/person/attach/own/contact/%d', $record->getId())) ?>"
    	class="ir attach"
    	title="<?php echo I18n::__('scaffold_attach') ?>"
    	data-target="person-<?php echo $record->getId() ?>-contact-container">
    		<?php echo I18n::__('scaffold_attach') ?>
    </a>
    <div>
        <input
            type="hidden"
            name="dialog[ownContact][<?php echo $index ?>][type]"
            value="<?php echo $_contact->getMeta('type') ?>" />
        <input
            type="hidden"
            name="dialog[ownContact][<?php echo $index ?>][id]"
            value="<?php echo $_contact->getId() ?>" />
    </div>
    <div class="row <?php echo ($_contact->hasError('gender')) ? 'error' : ''; ?>">
        <label
            for="person-<?php echo $record->getId() ?>-contact-<?php echo $index ?>-gender">
            <?php echo I18n::__('contact_label_gender') ?>
        </label>
        <select
            id="person-<?php echo $record->getId() ?>-contact-<?php echo $index ?>-gender"
            name="dialog[ownContact][<?php echo $index ?>][gender]">
            <option value=""><?php echo I18n::__('contact_gender_select') ?></option>
            <?php foreach ($_contact->getGenders() as $_gender): ?>
            <option
                value="<?php echo $_gender ?>"
                <?php echo ($_contact->gender == $_gender) ? 'selected="selected"' : '' ?>>
                <?php echo I18n::__('contact_gender_'.$_gender) ?>
            </option>
            <?php endforeach ?>
        </select>
    </div>
    <div class="row <?php echo ($_contact->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="person-<?php echo $record->getId() ?>-contact-<?php echo $index ?>-name">
            <?php echo I18n::__('contact_label_name') ?>
        </label>
        <input
            type="text"
            id="person-<?php echo $record->getId() ?>-contact-<?php echo $index ?>-name"
            name="dialog[ownContact][<?php echo $index ?>][name]"
            value="<?php echo htmlspecialchars($_contact->name) ?>" />
    </div>
    <div class="row <?php echo ($_contact->hasError('jobdescription')) ? 'error' : ''; ?>">
        <label
            for="person-<?php echo $record->getId() ?>-contact-<?php echo $index ?>-jobdescription">
            <?php echo I18n::__('contact_label_jobdescription') ?>
        </label>
        <input
            type="text"
            id="person-<?php echo $record->getId() ?>-contact-<?php echo $index ?>-jobdescription"
            name="dialog[ownContact][<?php echo $index ?>][jobdescription]"
            value="<?php echo htmlspecialchars($_contact->jobdescription) ?>" />
    </div>
</fieldset>
<fieldset
    id="person-contact-contactinfo"
    class="tab"
    style="display: block;">
    <legend class="verbose"><?php echo I18n::__('person_legend_contact_contactinfo') ?></legend>
    <div class="row nomargins">
        <div class="span3">
            &nbsp;
        </div>
        <div class="span3">
            <label>
                <?php echo I18n::__('contactinfo_label_label') ?>
            </label>
        </div>
        <div class="span6">
            <label>
                <?php echo I18n::__('contactinfo_label_value') ?>
            </label>
        </div>
    </div>
    <div
        id="person-<?php echo $record->getId() ?>-contact-<?php echo $_contact->getId() ?>-container"
        class="contact-info container attachable detachable sortable">
        <?php
        if (count($_contact->ownContactinfo) == 0):
            $_contact->ownContactinfo[] = R::dispense('contactinfo');
        endif;
        ?>
        <?php $_index = 0 ?>
        <?php foreach ($_contact->ownContactinfo as $_contactinfo_id => $_contactinfo): ?>
        <?php $_index++ ?>
        <?php Flight::render('model/contact/own/contactinfo', array(
            'record' => $record,
            '_contact' => $_contact,
            '_contactinfo' => $_contactinfo,
            'index' => $index,
            '_index' => $_index
        )) ?>
        <?php endforeach ?>
    </div>
</fieldset>
<hr class="person-contact-divider" />
<!-- /contact edit subform -->
