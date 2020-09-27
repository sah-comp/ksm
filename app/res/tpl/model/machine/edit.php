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
    <legend class="verbose"><?php echo I18n::__('machine_legend_manufactor') ?></legend>
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
</fieldset>
<fieldset>
    <legend><?php echo I18n::__('machine_legend_internals') ?></legend>
    <div class="row nomargins">
        <div class="span3">&nbsp;</div>
        <div class="span3">
            <label
                for="machine-internalnumber"
                class="<?php echo ($record->hasError('internalnumber')) ? 'error' : ''; ?>">
                <?php echo I18n::__('machine_label_internalnumber') ?>
            </label>
        </div>
        <div class="span3">
            <label
                for="machine-lastservice"
                class="<?php echo ($record->hasError('lastservice')) ? 'error' : ''; ?>">
                <?php echo I18n::__('machine_label_lastservice') ?>
            </label>
        </div>
        <div class="span3">
            <label
                for="machine-masterdata"
                class="<?php echo ($record->hasError('masterdata')) ? 'error' : ''; ?>">
                <?php echo I18n::__('machine_label_masterdata') ?>
            </label>
        </div>
    </div>
    <div class="row">
        <div class="span3">&nbsp;</div>
        <div class="span3">
            <input
                id="machine-internalnumber"
                class="autowidth"
                type="text"
                name="dialog[internalnumber]"
                value="<?php echo htmlspecialchars($record->internalnumber) ?>"/>
        </div>
        <div class="span3">
            <input
                id="machine-lastservice"
                class="autowidth"
                type="date"
                name="dialog[lastservice]"
                value="<?php echo htmlspecialchars($record->localizedDate('lastservice')) ?>" />
        </div>
        <div class="tab span3">
            <select
                id="machine-masterdata"
                class="autowidth">
                <option value="0" <?php echo ($record->masterdata == 0) ? 'selected="selected"' : '' ?>><?php echo I18n::__('machine_label_option_false') ?></option>
                <option value="1" <?php echo ($record->masterdata == 1) ? 'selected="selected"' : '' ?>><?php echo I18n::__('machine_label_option_true') ?></option>
            </select>
        </div>
    </div>
</fieldset>
<fieldset>
    <legend><?php echo I18n::__('machine_legend_techincal_specifications') ?></legend>
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
<fieldset>
    <legend class="verbose"><?php echo I18n::__('machine_legend_note') ?></legend>
    <div class="row <?php echo ($record->hasError('note')) ? 'error' : ''; ?>">
        <label
            for="machine-note">
            <?php echo I18n::__('machine_label_note') ?>
        </label>
        <textarea
            id="machine-note"
            name="dialog[note]"
            rows="3"
            cols="60"><?php echo htmlspecialchars($record->note) ?></textarea>
    </div>
    <div class="row <?php echo ($record->hasError('specialagreement')) ? 'error' : ''; ?>">
        <label
            for="machine-specialagreement">
            <?php echo I18n::__('machine_label_specialagreement') ?>
        </label>
        <textarea
            id="machine-specialagreement"
            name="dialog[specialagreement]"
            rows="3"
            cols="60"><?php echo htmlspecialchars($record->specialagreement) ?></textarea>
    </div>
</fieldset>
<div class="tab-container">
    <?php Flight::render('shared/navigation/tabs', array(
        'tab_id' => 'machine-tabs',
        'tabs' => array(
            'machine-article' => I18n::__('machine_article_tab'),
            'machine-appointment' => I18n::__('machine_appointment_tab'),
            'machine-contract' => I18n::__('machine_contract_tab')
        ),
        'default_tab' => 'machine-article'
    )) ?>
    <fieldset
        id="machine-article"
        class="tab"
        style="display: block;">
        <legend class="verbose"><?php echo I18n::__('machine_article_legend_tab') ?></legend>
            <div
                id="machine-<?php echo $record->getId() ?>-article-container"
                class="container attachable detachable sortable">
                <?php $index = 0 ?>
                <?php foreach ($record->with("ORDER BY @joined.article.number")->ownInstalledpart as $_id => $_ip): ?>
                <?php $index++ ?>
                <?php Flight::render('model/machine/own/installedpart', array(
                    'record' => $record,
                    '_installedpart' => $_ip,
                    'index' => $index
                )) ?>
                <?php endforeach ?>
            </div>
    </fieldset>
    <fieldset
        id="machine-appointment"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('machine_appointment_legend_tab') ?></legend>
            <div
                id="machine-<?php echo $record->getId() ?>-appointment-container"
                class="container attachable detachable sortable">
                <?php $index = 0 ?>
                <?php foreach ($record->with("ORDER BY date, starttime")->ownAppointment as $_id => $_appointment): ?>
                <?php $index++ ?>
                <?php Flight::render('model/machine/own/appointment', array(
                    'record' => $record,
                    '_appointment' => $_appointment,
                    'index' => $index
                )) ?>
                <?php endforeach ?>
            </div>
    </fieldset>
    <fieldset
        id="machine-contract"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('machine_contract_legend_tab') ?></legend>
            <div
                id="machine-<?php echo $record->getId() ?>-contract-container"
                class="container attachable detachable sortable">
                <?php $index = 0 ?>
                <?php foreach ($record->with("ORDER BY startdate")->ownContract as $_id => $_contract): ?>
                <?php $index++ ?>
                <?php Flight::render('model/machine/own/contract', array(
                    'record' => $record,
                    '_contract' => $_contract,
                    'index' => $index
                )) ?>
                <?php endforeach ?>
            </div>
    </fieldset>
</div>
<!-- end of machine edit form -->
