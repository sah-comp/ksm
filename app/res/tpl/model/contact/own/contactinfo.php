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
<!-- contactinfo edit subform -->
<fieldset
    class="sub"
    id="person-<?php echo $record->getId() ?>-owncontact-<?php echo $_contact->getId() ?>-<?php echo $_index ?>">
    <legend class="verbose"><?php echo I18n::__('person_legend_contact_contactinfo') ?></legend>
    <a
    	href="<?php echo Url::build(sprintf('/admin/contact/detach/contactinfo/%d', $_contactinfo->getId())) ?>"
    	class="ir detach small"
    	title="<?php echo I18n::__('scaffold_detach') ?>"
    	data-target="person-<?php echo $record->getId() ?>-owncontact-<?php echo $_contact->getId() ?>-<?php echo $_index ?>">
    		<?php echo I18n::__('scaffold_detach') ?>
    </a>
    <a
    	href="<?php echo Url::build(sprintf('/admin/contact/attach/own/contactinfo/%d/person/%d/%d/%d', $_contact->getId(), $record->getId(), $_index, $index)) ?>"
    	class="ir attach small"
    	title="<?php echo I18n::__('scaffold_attach') ?>"
    	data-target="person-<?php echo $record->getId() ?>-contact-<?php echo $_contact->getId() ?>-container">
    		<?php echo I18n::__('scaffold_attach') ?>
    </a>
    <div>
        <input
            type="hidden"
            name="dialog[ownContact][<?php echo $index ?>][ownContactinfo][<?php echo $_index ?>][type]"
            value="<?php echo $_contactinfo->getMeta('type') ?>" />
        <input
            type="hidden"
            name="dialog[ownContact][<?php echo $index ?>][ownContactinfo][<?php echo $_index ?>][id]"
            value="<?php echo $_contactinfo->getId() ?>" />
    </div>
    <div class="row nomargins">
        <div class="span3">
            &nbsp;
        </div>
        <div class="span3">
            <select
                id="person-<?php echo $record->getId() ?>-contact-<?php echo $index ?>-contactinfo-<?php echo $_index ?>-label"
                name="dialog[ownContact][<?php echo $index ?>][ownContactinfo][<?php echo $_index ?>][label]">
                <option value=""><?php echo I18n::__('contactinfo_label_select') ?></option>
                <?php foreach ($_contactinfo->getLabels() as $_label): ?>
                <option
                    value="<?php echo $_label ?>"
                    <?php echo ($_contactinfo->label == $_label) ? 'selected="selected"' : '' ?>>
                    <?php echo I18n::__('contactinfo_label_'.$_label) ?>
                </option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="span6">
            <input
                type="text"
                id="person-<?php echo $record->getId() ?>-contact-<?php echo $index ?>-contactinfo-<?php echo $_index ?>-value"
                name="dialog[ownContact][<?php echo $index ?>][ownContactinfo][<?php echo $_index ?>][value]"
                value="<?php echo htmlspecialchars($_contactinfo->value) ?>" />
        </div>
    </div>
</fieldset>
<!-- /contactinfo edit subform -->
