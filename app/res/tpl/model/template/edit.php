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
<!-- template edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('template_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="template-name">
            <?php echo I18n::__('template_label_name') ?>
        </label>
        <input
            id="template-name"
            type="text"
            name="dialog[name]"
            value="<?php echo htmlspecialchars($record->name) ?>"
            required="required" />
    </div>
</fieldset>
<div class="tab-container">
    <?php Flight::render('shared/navigation/tabs', array(
        'tab_id' => 'template-tabs',
        'tabs' => array(
            'template-region' => I18n::__('template_region_tab'),
            'template-html' => I18n::__('template_html_tab'),
            'template-text' => I18n::__('template_text_tab')
        ),
        'default_tab' => 'template-region'
    )) ?>
    <fieldset
        id="template-region"
        class="tab"
        style="display: block;">
        <legend class="verbose"><?php echo I18n::__('region_legend') ?></legend>
            <div
                id="template-<?php echo $record->getId() ?>-region-container"
                class="container attachable detachable sortable">
                <?php if (count($record->ownRegion) == 0) $record->ownRegion[] = R::dispense('region') ?>
                <?php $index = 0 ?>
                <?php foreach ($record->ownRegion as $_region_id => $_region): ?>
                <?php $index++ ?>
                <?php Flight::render('model/template/own/region', array(
                    'record' => $record,
                    '_region' => $_region,
                    'index' => $index
                )) ?>
                <?php endforeach ?>
            </div>
    </fieldset>
    <fieldset
        id="template-html"
        class="tab"
        style="display:none;">
        <legend class="verbose"><?php echo I18n::__('template_legend_html') ?></legend>
        <div class="row <?php echo ($record->hasError('html')) ? 'error' : ''; ?>">
            <label
                for="template-html">
                <?php echo I18n::__('template_label_html') ?>
            </label>
            <textarea
                id="template-html"
                name="dialog[html]"
                rows="23"
                cols="60"><?php echo htmlspecialchars($record->html) ?></textarea>
            <p class="info"><?php echo I18n::__('template_html_info') ?></p>
        </div>
    </fieldset>
    <fieldset
        id="template-text"
        class="tab"
        style="display:none;">
        <legend class="verbose"><?php echo I18n::__('template_legend_text') ?></legend>
        <div class="row <?php echo ($record->hasError('txt')) ? 'error' : ''; ?>">
            <label
                for="template-txt">
                <?php echo I18n::__('template_label_text') ?>
            </label>
            <textarea
                id="template-txt"
                name="dialog[txt]"
                rows="23"
                cols="60"><?php echo htmlspecialchars($record->txt) ?></textarea>
            <p class="info"><?php echo I18n::__('template_text_info') ?></p>
        </div>
    </fieldset>
</div>
<!-- end of template edit form -->