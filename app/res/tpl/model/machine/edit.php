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
<!-- machine edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('machine_legend') ?></legend>
    <div class="row nomargins">
        <div class="span3">&nbsp;</div>
        <div class="span2">
            <label
                for="machine-machinebrand"
                class="<?php echo ($record->hasError('machinebrand_id')) ? 'error' : ''; ?>">
                <?php echo I18n::__('machine_label_machinebrand') ?>
            </label>
        </div>
        <div class="span2">
            <label
                for="machine-name"
                class="<?php echo ($record->hasError('name')) ? 'error' : ''; ?>">
                <?php echo I18n::__('machine_label_name') ?>
            </label>
        </div>
        <div class="span2">
            <label
                for="machine-serialnumber"
                class="<?php echo ($record->hasError('serialnumber')) ? 'error' : ''; ?>">
                <?php echo I18n::__('machine_label_serialnumber') ?>
            </label>
        </div>
        <div class="span1">
            <label
                for="machine-buildyear"
                class="<?php echo ($record->hasError('buildyear')) ? 'error' : ''; ?>">
                <?php echo I18n::__('machine_label_buildyear') ?>
            </label>
        </div>
        <div class="span2">
            <label
                for="machine-workinghours"
                class="<?php echo ($record->hasError('workinghours')) ? 'error' : ''; ?>">
                <?php echo I18n::__('machine_label_workinghours') ?>
            </label>
        </div>
    </div>
    <div class="row">
        <div class="span3">&nbsp;</div>
        <div class="span2">
            <select
                id="machine-machinebrand"
                class="autowidth"
                name="dialog[machinebrand_id]">
                <option value=""><?php echo I18n::__('machine_machinebrand_none') ?></option>
                <?php foreach (R::findAll('machinebrand') as $_id => $_machinebrand): ?>
                <option
                    value="<?php echo $_machinebrand->getId() ?>"
                    <?php echo ($record->machinebrand_id == $_machinebrand->getId()) ? 'selected="selected"' : '' ?>><?php echo $_machinebrand->name ?>
                </option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="span2">
            <input
                id="machine-name"
                class="autowidth"
                type="text"
                name="dialog[name]"
                value="<?php echo htmlspecialchars($record->name) ?>"
                required="required" />
        </div>
        <div class="span2">
            <input
                id="machine-serialnumber"
                class="autowidth"
                type="text"
                name="dialog[serialnumber]"
                value="<?php echo htmlspecialchars($record->serialnumber) ?>" />
        </div>
        <div class="span1">
            <input
                id="machine-buildyear"
                class="number autowidth"
                type="number"
                name="dialog[buildyear]"
                value="<?php echo htmlspecialchars($record->buildyear) ?>" />
        </div>
        <div class="span2">
            <input
                id="machine-workinghours"
                class="number autowidth"
                type="number"
                name="dialog[workinghours]"
                value="<?php echo htmlspecialchars($record->workinghours) ?>" />
        </div>
    </div>
    <div class="row <?php echo ($record->hasError('internalnumber')) ? 'error' : ''; ?>">
        <label
            for="machine-internalnumber">
            <?php echo I18n::__('machine_label_internalnumber') ?>
        </label>
        <input
            id="machine-internalnumber"
            type="text"
            name="dialog[internalnumber]"
            value="<?php echo htmlspecialchars($record->internalnumber) ?>"/>
    </div>
    <div class="row <?php echo ($record->hasError('forks')) ? 'error' : ''; ?>">
        <label
            for="machine-forks">
            <?php echo I18n::__('machine_label_forks') ?>
        </label>
        <input
            id="machine-forks"
            type="text"
            name="dialog[forks]"
            value="<?php echo htmlspecialchars($record->forks) ?>"/>
    </div>
</fieldset>
<fieldset>
    <legend></legend>
    <div class="row nomargins">
        <div class="span3">&nbsp;</div>
        <div class="span3">
            label 1
        </div>
        <div class="span3">
            label 2
        </div>
        <div class="span3">
            label 3
        </div>
    </div>
    <div class="row">
        <div class="span3">&nbsp;</div>
        <div class="span3">
            field 1
        </div>
        <div class="span3">
            field 2
        </div>
        <div class="span3">
            field 3
        </div>
    </div>

    <div class="row nomargins">
        <div class="span3">&nbsp;</div>
        <div class="span3">
            label 1
        </div>
        <div class="span3">
            label 2
        </div>
        <div class="span3">
            label 3
        </div>
    </div>
    <div class="row">
        <div class="span3">&nbsp;</div>
        <div class="span3">
            field 1
        </div>
        <div class="span3">
            field 2
        </div>
        <div class="span3">
            field 3
        </div>
    </div>

    <div class="row nomargins">
        <div class="span3">&nbsp;</div>
        <div class="span3">
            label 1
        </div>
        <div class="span3">
            label 2
        </div>
        <div class="span3">
            label 3
        </div>
    </div>
    <div class="row">
        <div class="span3">&nbsp;</div>
        <div class="span3">
            field 1
        </div>
        <div class="span3">
            field 2
        </div>
        <div class="span3">
            field 3
        </div>
    </div>
</fieldset>
<fieldset>
    <legend></legend>
    <div class="row nomargins">
        <div class="span3">&nbsp;</div>
        <div class="span4">
            label 1
        </div>
        <div class="span4">
            label 2
        </div>
    </div>
    <div class="row">
        <div class="span3">&nbsp;</div>
        <div class="span4">
            field 1
        </div>
        <div class="span4">
            field 2
        </div>
    </div>

    <div class="row nomargins">
        <div class="span3">&nbsp;</div>
        <div class="span4">
            label 1
        </div>
        <div class="span4">
            label 2
        </div>
    </div>
    <div class="row">
        <div class="span3">&nbsp;</div>
        <div class="span4">
            field 1
        </div>
        <div class="span4">
            field 2
        </div>
    </div>

    <div class="row nomargins">
        <div class="span3">&nbsp;</div>
        <div class="span4">
            label 1
        </div>
        <div class="span4">
            label 2
        </div>
    </div>
    <div class="row">
        <div class="span3">&nbsp;</div>
        <div class="span4">
            field 1
        </div>
        <div class="span4">
            field 2
        </div>
    </div>

    <div class="row nomargins">
        <div class="span3">&nbsp;</div>
        <div class="span4">
            label 1
        </div>
        <div class="span4">
            label 2
        </div>
    </div>
    <div class="row">
        <div class="span3">&nbsp;</div>
        <div class="span4">
            field 1
        </div>
        <div class="span4">
            field 2
        </div>
    </div>

    <div class="row nomargins">
        <div class="span3">&nbsp;</div>
        <div class="span4">
            label 1
        </div>
        <div class="span4">
            label 2
        </div>
    </div>
    <div class="row">
        <div class="span3">&nbsp;</div>
        <div class="span4">
            field 1
        </div>
        <div class="span4">
            field 2
        </div>
    </div>

</fieldset>
<!-- end of machine edit form -->