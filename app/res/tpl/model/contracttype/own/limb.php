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
<!-- limb edit subform -->
<fieldset
    id="contracttype-<?php echo $record->getId() ?>-ownlimb-<?php echo $index ?>">
    <legend class="verbose"><?php echo I18n::__('contracttype_legend_limb') ?></legend>
    <a
    	href="<?php echo Url::build(sprintf('/admin/contracttype/detach/limb/%d', $_limb->getId())) ?>"
    	class="ir detach"
    	title="<?php echo I18n::__('scaffold_detach') ?>"
    	data-target="contracttype-<?php echo $record->getId() ?>-ownlimb-<?php echo $index ?>">
    		<?php echo I18n::__('scaffold_detach') ?>
    </a>
    <a
    	href="<?php echo Url::build(sprintf('/admin/contracttype/attach/own/limb/%d', $record->getId())) ?>"
    	class="ir attach"
    	title="<?php echo I18n::__('scaffold_attach') ?>"
    	data-target="contracttype-<?php echo $record->getId() ?>-limb-container">
    		<?php echo I18n::__('scaffold_attach') ?>
    </a>

    <div>
        <input
            type="hidden"
            name="dialog[ownLimb][<?php echo $index ?>][type]"
            value="<?php echo $_limb->getMeta('type') ?>" />
        <input
            type="hidden"
            name="dialog[ownLimb][<?php echo $index ?>][id]"
            value="<?php echo $_limb->getId() ?>" />
        <input
            type="hidden"
            name="dialog[ownLimb][<?php echo $index ?>][kind]"
            value="attribute" />
        <input
            type="hidden"
            name="dialog[ownLimb][<?php echo $index ?>][filter]"
            value="0" />
    </div>

    <div class="row">

        <div class="span1">
            &nbsp;
        </div>
        <div class="span1">
            <input
                type="hidden"
                name="dialog[ownLimb][<?php echo $index ?>][active]"
                value="0" />
            <input
                type="checkbox"
                name="dialog[ownLimb][<?php echo $index ?>][active]"
                <?php echo ($_limb->active) ? 'checked="checked"' : '' ?>
                value="1" />
        </div>
        <div class="span1">
            <input
                type="number"
                min="0"
                step="10"
                name="dialog[ownLimb][<?php echo $index ?>][sequence]"
                value="<?php echo htmlspecialchars($_limb->sequence) ?>" />
        </div>
        <div class="span3">
            <input
                type="text"
                name="dialog[ownLimb][<?php echo $index ?>][name]"
                value="<?php echo htmlspecialchars($_limb->name) ?>" />
        </div>
        <div class="span2">
            <input
                type="text"
                name="dialog[ownLimb][<?php echo $index ?>][placeholder]"
                value="<?php echo htmlspecialchars($_limb->placeholder) ?>" />
        </div>
        <div class="span2">
            <select
                name="dialog[ownLimb][<?php echo $index ?>][tag]">
                <option
                    value="">
                    <?php echo I18n::__('select_one_or_leave_empty') ?>
                </option>
                <?php foreach ($_limb->getTags() as $_attr_name): ?>
                <option
                    value="<?php echo $_attr_name ?>"
                    <?php echo ($_limb->tag == $_attr_name) ? 'selected="selected"' : '' ?>>
                    <?php echo I18n::__(sprintf('limb_tag_%s', $_attr_name)) ?>
                </option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="span2">
            <input
                type="text"
                name="dialog[ownLimb][<?php echo $index ?>][stub]"
                value="<?php echo htmlspecialchars($_limb->stub) ?>" />
        </div>
    </div>
</fieldset>
<!-- /limb edit subform -->
