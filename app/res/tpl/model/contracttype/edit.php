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
<!-- contracttype edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('contracttypetype_legend') ?></legend>

    <div class="row <?php echo ($record->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="contracttype-name">
            <?php echo I18n::__('contracttype_label_name') ?>
        </label>
        <input
            id="contracttype-name"
            type="text"
            name="dialog[name]"
            value="<?php echo htmlspecialchars($record->name) ?>"
            required="required" />
    </div>
</fieldset>
<div class="tab-container">
    <?php Flight::render('shared/navigation/tabs', array(
        'tab_id' => 'contracttype-tabs',
        'tabs' => array(
            'contracttype-detail' => I18n::__('contracttype_detail_tab'),
            'contracttype-limb' => I18n::__('contracttype_limb_tab'),
        ),
        'default_tab' => 'contracttype-detail'
    )) ?>
    <fieldset
        id="contracttype-detail"
        class="tab"
        style="display: block;">
        <div class="row <?php echo ($record->hasError('text')) ? 'error' : ''; ?>">
            <label
                for="contracttype-text">
                <?php echo I18n::__('contracttype_label_text') ?>
            </label>
            <textarea
                id="contracttype-text"
                name="dialog[text]"
                rows="23"
                cols="60"><?php echo htmlspecialchars($record->text) ?></textarea>
            <p class="info"><?php echo I18n::__('contracttype_info_text') ?></p>
        </div>
    </fieldset>
    <fieldset
        id="contracttype-limb"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('contracttype_limb_legend') ?></legend>
        <div class="row">
            <div class="span3">
                &nbsp;
            </div>
            <div class="span1">
                <?php echo I18n::__('limb_label_active') ?>
            </div>
            <div class="span1">
                <?php echo I18n::__('limb_label_sequence') ?>
            </div>
            <div class="span2">
                <?php echo I18n::__('limb_label_name') ?>
            </div>
            <div class="span2">
                <?php echo I18n::__('limb_label_kind') ?>
            </div>
            <div class="span2">
                <?php echo I18n::__('limb_label_tag') ?>
            </div>
            <div class="span1">
                <?php echo I18n::__('limb_label_filter') ?>
            </div>
        </div>
        <div
            id="contracttype-<?php echo $record->getId() ?>-limb-container"
            class="container attachable detachable sortable">
        <?php $_limbs = $record->with(' ORDER BY sequence ASC ')->ownLimb ?>
        <?php if (count($_limbs) == 0):
            $_limbs[] = R::dispense('limb');
        endif; ?>
        <?php $index = 0 ?>
        <?php foreach ($_limbs as $_limb_id => $_limb): ?>
        <?php $index++ ?>
        <?php Flight::render('model/contracttype/own/limb', array(
            'record' => $record,
            '_limb' => $_limb,
            'index' => $index
        )) ?>
        <?php endforeach ?>
        </div>
    </fieldset>
</div>
<!-- end of contracttype edit form -->
