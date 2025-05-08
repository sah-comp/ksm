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
<!-- location edit subform -->
<fieldset
    id="person-<?php echo $record->getId() ?>-ownlocation-<?php echo $index ?>">
    <legend class="verbose"><?php echo I18n::__('person_legend_location') ?></legend>
    <a
    	href="<?php echo Url::build(sprintf('/admin/person/detach/location/%d', $_location->getId())) ?>"
    	class="ir detach"
    	title="<?php echo I18n::__('scaffold_detach') ?>"
    	data-target="person-<?php echo $record->getId() ?>-ownlocation-<?php echo $index ?>">
    		<?php echo I18n::__('scaffold_detach') ?>
    </a>
    <a
    	href="<?php echo Url::build(sprintf('/admin/person/attach/own/location/%d', $record->getId())) ?>"
    	class="ir attach"
    	title="<?php echo I18n::__('scaffold_attach') ?>"
    	data-target="person-<?php echo $record->getId() ?>-location-container">
    		<?php echo I18n::__('scaffold_attach') ?>
    </a>
    <div>
        <input
            type="hidden"
            name="dialog[ownLocation][<?php echo $index ?>][type]"
            value="<?php echo $_location->getMeta('type') ?>" />
        <input
            type="hidden"
            name="dialog[ownLocation][<?php echo $index ?>][id]"
            value="<?php echo $_location->getId() ?>" />
    </div>
    <div class="row nomargins">
        <div class="span3">
            &nbsp;
        </div>
        <div class="span9">
            <input
                type="text"
                id="person-<?php echo $record->getId() ?>-location-<?php echo $index ?>-name"
                class="autowidth"
                name="dialog[ownLocation][<?php echo $index ?>][name]"
                value="<?php echo htmlspecialchars($_location->name ?? '') ?>" />
        </div>
    </div>
</fieldset>
<!-- /location edit subform -->
