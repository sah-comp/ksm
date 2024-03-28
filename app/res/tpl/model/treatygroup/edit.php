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
<!-- treatygroup edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('treatygroup_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('sequence')) ? 'error' : ''; ?>">
        <label
            for="treatygroup-sequence">
            <?php echo I18n::__('treatygroup_label_sequence') ?>
        </label>
        <input
            id="treatygroup-sequence"
            type="number"
            name="dialog[sequence]"
            value="<?php echo htmlspecialchars($record->sequence ?? '') ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="treatygroup-name">
            <?php echo I18n::__('treatygroup_label_name') ?>
        </label>
        <input
            id="treatygroup-name"
            type="text"
            name="dialog[name]"
            value="<?php echo htmlspecialchars($record->name ?? '') ?>"
            required="required" />
    </div>
    <div class="row">
        <label
            for="treatygroup-contracttype">
            <?php echo I18n::__('treatygroup_label_contracttype') ?>
        </label>
        <select
            id="treatygroup-contracttype"
            name="dialog[contracttype_id]">
            <option value=""><?php echo I18n::__('treatygroup_contracttype_none') ?></option>
            <?php foreach (R::find('contracttype', "enabled = 1 AND service = 1 ORDER BY name") as $_id => $_contracttype): ?>
            <option
                value="<?php echo $_contracttype->getId() ?>"
                <?php echo ($record->contracttype_id == $_contracttype->getId()) ? 'selected="selected"' : '' ?>><?php echo $_contracttype->name ?>
            </option>
            <?php endforeach ?>
        </select>
        <p class="info"><?php echo I18n::__('treatygroup_contracttype_info') ?></p>
    </div>
    <div class="row <?php echo ($record->hasError('color')) ? 'error' : ''; ?>">
        <label
            for="treatygroup-color">
            <?php echo I18n::__('treatygroup_label_color') ?>
        </label>
        <input
            style="<?php echo ($record->color) ? 'color: ' . $record->color : '' ?>"
            id="treatygroup-color"
            type="text"
            name="dialog[color]"
            value="<?php echo htmlspecialchars($record->color ?? '') ?>"
            required="required" />
        <p class="info"><?php echo I18n::__('treatygroup_info_color') ?></p>
    </div>
</fieldset>
<!-- end of treatygroup edit form -->
