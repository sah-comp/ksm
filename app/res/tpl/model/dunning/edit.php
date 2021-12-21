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
<!-- dunning edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('dunning_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="dunning-name">
            <?php echo I18n::__('dunning_label_name') ?>
        </label>
        <input
            id="dunning-name"
            type="text"
            name="dialog[name]"
            value="<?php echo htmlspecialchars($record->name) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('sequence')) ? 'error' : ''; ?>">
        <label
            for="dunning-sequence">
            <?php echo I18n::__('dunning_label_sequence') ?>
        </label>
        <input
            id="dunning-sequence"
            type="number"
            name="dialog[sequence]"
            value="<?php echo htmlspecialchars($record->sequence) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('grace')) ? 'error' : ''; ?>">
        <label
            for="dunning-grace">
            <?php echo I18n::__('dunning_label_grace') ?>
        </label>
        <input
            id="dunning-grace"
            type="text"
            class="number"
            name="dialog[grace]"
            value="<?php echo htmlspecialchars($record->decimal('grace')) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('applyto')) ? 'error' : ''; ?>">
        <label
            for="dunning-applyto">
            <?php echo I18n::__('dunning_label_applyto') ?>
        </label>
        <select
            id="dunning-applyto"
            class=""
            name="dialog[applyto]">
            <?php foreach ($record->getApplyToAttributes() as $_attribute): ?>
            <option
                value="<?php echo $_attribute ?>"
                <?php echo ($record->applyto == $_attribute) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars(I18n::__('dunning_option_' . $_attribute)) ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <div class="row <?php echo ($record->hasError('penaltyfee')) ? 'error' : ''; ?>">
        <label
            for="dunning-penaltyfee">
            <?php echo I18n::__('dunning_label_penaltyfee') ?>
        </label>
        <input
            id="dunning-penaltyfee"
            type="text"
            class="number"
            name="dialog[penaltyfee]"
            value="<?php echo htmlspecialchars($record->decimal('penaltyfee')) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('head')) ? 'error' : ''; ?>">
        <label
            for="dunning-head">
            <?php echo I18n::__('dunning_label_head') ?>
        </label>
        <textarea
            id="dunning-head"
            name="dialog[head]"
            rows="13"
            cols="60"><?php echo htmlspecialchars($record->head) ?></textarea>
        <p class="info"><?php echo I18n::__('dunning_info_head') ?></p>
    </div>
    <div class="row <?php echo ($record->hasError('foot')) ? 'error' : ''; ?>">
        <label
            for="dunning-foot">
            <?php echo I18n::__('dunning_label_foot') ?>
        </label>
        <textarea
            id="dunning-foot"
            name="dialog[foot]"
            rows="13"
            cols="60"><?php echo htmlspecialchars($record->foot) ?></textarea>
        <p class="info"><?php echo I18n::__('dunning_info_foot') ?></p>
    </div>
</fieldset>
<!-- end of dunning edit form -->
