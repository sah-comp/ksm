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
                placeholder="<?php echo I18n::__('placeholder_intl_date') ?>"
                value="<?php echo htmlspecialchars($record->lastservice) ?>" />
        </div>
        <div class="tab span3">
            <select
                id="machine-masterdata"
                class="autowidth"
                name="dialog[masterdata]">
                <option value="0" <?php echo ($record->masterdata == 0) ? 'selected="selected"' : '' ?>><?php echo I18n::__('machine_label_option_false') ?></option>
                <option value="1" <?php echo ($record->masterdata == 1) ? 'selected="selected"' : '' ?>><?php echo I18n::__('machine_label_option_true') ?></option>
            </select>
        </div>
    </div>
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
    <?php Flight::render('shared/navigation/tabs', [
        'tab_id' => 'machine-tabs',
        'tabs' => [
            'machine-details' => I18n::__('machine_detail_tab'),
            'machine-article' => I18n::__('machine_article_tab'),
            'machine-appointment' => I18n::__('machine_appointment_tab'),
            'machine-contract' => I18n::__('machine_contract_tab')
        ],
        'default_tab' => 'machine-details'
    ]) ?>
    <fieldset
        id="machine-details"
        class="tab"
        style="display: block;">
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
        <!-- first block -->
        <div class="row nomargins">
            <div class="span3">&nbsp;</div>
            <div class="span3">
                <label
                    for="machine-weight"
                    class="number <?php echo ($record->hasError('weight')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_weight') ?>
                </label>
            </div>
            <div class="span3">
                <label
                    for="machine-height"
                    class="number <?php echo ($record->hasError('height')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_height') ?>
                </label>
            </div>
            <div class="span3">
                <label
                    for="machine-maxload"
                    class="number <?php echo ($record->hasError('maxload')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_maxload') ?>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="span3">&nbsp;</div>
            <div class="span3">
                <input
                    id="machine-weight"
                    class="autowidth"
                    type="number"
                    name="dialog[weight]"
                    value="<?php echo htmlspecialchars($record->weight) ?>"/>
            </div>
            <div class="span3">
                <input
                    id="machine-height"
                    class="autowidth"
                    type="number"
                    name="dialog[height]"
                    value="<?php echo htmlspecialchars($record->height) ?>"/>
            </div>
            <div class="span3">
                <input
                    id="machine-maxload"
                    class="autowidth"
                    type="number"
                    name="dialog[maxload]"
                    value="<?php echo htmlspecialchars($record->maxload) ?>"/>
            </div>
        </div>
        <!-- /first block -->
        <!-- second block -->
        <div class="row nomargins">
            <div class="span3">&nbsp;</div>
            <div class="span3">
                <label
                    for="machine-masttype"
                    class="<?php echo ($record->hasError('masttype')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_masttype') ?>
                </label>
            </div>
            <div class="span3">
                <label
                    for="machine-mastserialnumber"
                    class="<?php echo ($record->hasError('mastserialnumber')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_mastserialnumber') ?>
                </label>
            </div>
            <div class="span3">
                <label
                    for="machine-forkmaxheight"
                    class="number <?php echo ($record->hasError('forkmaxheight')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_forkmaxheight') ?>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="span3">&nbsp;</div>
            <div class="span3">
                <input
                    id="machine-masttype"
                    class="autowidth"
                    type="text"
                    name="dialog[masttype]"
                    value="<?php echo htmlspecialchars($record->masttype) ?>"/>
            </div>
            <div class="span3">
                <input
                    id="machine-mastserialnumber"
                    class="autowidth"
                    type="text"
                    name="dialog[mastserialnumber]"
                    value="<?php echo htmlspecialchars($record->mastserialnumber) ?>"/>
            </div>
            <div class="span3">
                <input
                    id="machine-forkmaxheight"
                    class="autowidth"
                    type="number"
                    name="dialog[forkmaxheight]"
                    value="<?php echo htmlspecialchars($record->forkmaxheight) ?>"/>
            </div>
        </div>
        <!-- /second block -->
        <!-- third block -->
        <div class="row nomargins">
            <div class="span3">&nbsp;</div>
            <div class="span3">
                <label
                    for="machine-attachment"
                    class="<?php echo ($record->hasError('attachment')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_attachment') ?>
                </label>
            </div>
            <div class="span3">
                <label
                    for="machine-attachmenttype"
                    class="<?php echo ($record->hasError('attachmenttype')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_attachmenttype') ?>
                </label>
            </div>
            <div class="span3">
                <label
                    for="machine-attachmentserialnumber"
                    class="<?php echo ($record->hasError('attachmentserialnumber')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_attachmentserialnumber') ?>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="span3">&nbsp;</div>
            <div class="span3">
                <input
                    id="machine-attachment"
                    class="autowidth"
                    type="text"
                    name="dialog[attachment]"
                    value="<?php echo htmlspecialchars($record->attachment) ?>"/>
            </div>
            <div class="span3">
                <input
                    id="machine-attachmenttype"
                    class="autowidth"
                    type="text"
                    name="dialog[attachmenttype]"
                    value="<?php echo htmlspecialchars($record->attachmenttype) ?>"/>
            </div>
            <div class="span3">
                <input
                    id="machine-attachmentserialnumber"
                    class="autowidth"
                    type="text"
                    name="dialog[attachmentserialnumber]"
                    value="<?php echo htmlspecialchars($record->attachmentserialnumber) ?>"/>
            </div>
        </div>
        <!-- /third block -->

        <!-- first block -->
        <div class="row nomargins">
            <div class="span3">&nbsp;</div>
            <div class="span4">
                <label
                    for="machine-motor"
                    class="<?php echo ($record->hasError('motor')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_motor') ?>
                </label>
            </div>
            <div class="span4">
                <label
                    for="machine-motorserialnumber"
                    class="<?php echo ($record->hasError('motorserialnumber')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_motorserialnumber') ?>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="span3">&nbsp;</div>
            <div class="span4">
                <input
                    id="machine-motor"
                    class="autowidth"
                    type="text"
                    name="dialog[motor]"
                    value="<?php echo htmlspecialchars($record->motor) ?>"/>
            </div>
            <div class="span4">
                <input
                    id="machine-motorserialnumber"
                    class="autowidth"
                    type="text"
                    name="dialog[motorserialnumber]"
                    value="<?php echo htmlspecialchars($record->motorserialnumber) ?>"/>
            </div>
        </div>
        <!-- /first block -->
        <!-- second block -->
        <div class="row nomargins">
            <div class="span3">&nbsp;</div>
            <div class="span4">
                <label
                    for="machine-controlvalve"
                    class="<?php echo ($record->hasError('controlvalve')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_controlvalve') ?>
                </label>
            </div>
            <div class="span4">
                <label
                    for="machine-shutdownvalve"
                    class="<?php echo ($record->hasError('shutdownvalve')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_shutdownvalve') ?>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="span3">&nbsp;</div>
            <div class="span4">
                <input
                    id="machine-controlvalve"
                    class="autowidth"
                    type="text"
                    name="dialog[controlvalve]"
                    value="<?php echo htmlspecialchars($record->controlvalve) ?>"/>
            </div>
            <div class="span4">
                <input
                    id="machine-shutdownvalve"
                    class="autowidth"
                    type="text"
                    name="dialog[shutdownvalve]"
                    value="<?php echo htmlspecialchars($record->shutdownvalve) ?>"/>
            </div>
        </div>
        <!-- /second block -->
        <!-- third block -->
        <div class="row nomargins">
            <div class="span3">&nbsp;</div>
            <div class="span4">
                <label
                    for="machine-mixer"
                    class="<?php echo ($record->hasError('mixer')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_mixer') ?>
                </label>
            </div>
            <div class="span4">
                <label
                    for="machine-keynumber"
                    class="<?php echo ($record->hasError('keynumber')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_keynumber') ?>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="span3">&nbsp;</div>
            <div class="span4">
                <input
                    id="machine-mixer"
                    class="autowidth"
                    type="text"
                    name="dialog[mixer]"
                    value="<?php echo htmlspecialchars($record->mixer) ?>"/>
            </div>
            <div class="span4">
                <input
                    id="machine-keynumber"
                    class="autowidth"
                    type="text"
                    name="dialog[keynumber]"
                    value="<?php echo htmlspecialchars($record->keynumber) ?>"/>
            </div>
        </div>
        <!-- /third block -->
        <!-- fifth block -->
        <div class="row nomargins">
            <div class="span3">&nbsp;</div>
            <div class="span4">
                <label
                    for="machine-fronttires"
                    class="<?php echo ($record->hasError('fronttires')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_fronttires') ?>
                </label>
            </div>
            <div class="span4">
                <label
                    for="machine-backtires"
                    class="<?php echo ($record->hasError('backtires')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_backtires') ?>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="span3">&nbsp;</div>
            <div class="span4">
                <input
                    id="machine-fronttires"
                    class="autowidth"
                    type="text"
                    name="dialog[fronttires]"
                    value="<?php echo htmlspecialchars($record->fronttires) ?>"/>
            </div>
            <div class="span4">
                <input
                    id="machine-backtires"
                    class="autowidth"
                    type="text"
                    name="dialog[backtires]"
                    value="<?php echo htmlspecialchars($record->backtires) ?>"/>
            </div>
        </div>
        <!-- /fifth block -->
        <!-- sixth block -->
        <div class="row nomargins">
            <div class="span3">&nbsp;</div>
            <div class="span4">
                <label
                    for="machine-controltype"
                    class="<?php echo ($record->hasError('controltype')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_controltype') ?>
                </label>
            </div>
            <div class="span4">
                <label
                    for="machine-battery"
                    class="<?php echo ($record->hasError('battery')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_battery') ?>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="span3">&nbsp;</div>
            <div class="span4">
                <input
                    id="machine-controltype"
                    class="autowidth"
                    type="text"
                    name="dialog[controltype]"
                    value="<?php echo htmlspecialchars($record->controltype) ?>"/>
            </div>
            <div class="span4">
                <input
                    id="machine-battery"
                    class="autowidth"
                    type="text"
                    name="dialog[battery]"
                    value="<?php echo htmlspecialchars($record->battery) ?>"/>
            </div>
        </div>
        <!-- /sixth block -->
        <!-- seventh block -->
        <div class="row nomargins">
            <div class="span3">&nbsp;</div>
            <div class="span4">
                <label
                    for="machine-hourlyrate"
                    class="<?php echo ($record->hasError('hourlyrate')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_hourlyrate') ?>
                </label>
            </div>
            <div class="span4">
                <label
                    for="machine-drivingcost"
                    class="<?php echo ($record->hasError('drivingcost')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_drivingcost') ?>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="span3">&nbsp;</div>
            <div class="span4">
                <input
                    id="machine-hourlyrate"
                    class="autowidth"
                    type="text"
                    name="dialog[hourlyrate]"
                    value="<?php echo htmlspecialchars($record->hourlyrate) ?>"/>
            </div>
            <div class="span4">
                <input
                    id="machine-drivingcost"
                    class="autowidth"
                    type="text"
                    name="dialog[drivingcost]"
                    value="<?php echo htmlspecialchars($record->drivingcost) ?>"/>
            </div>
        </div>
        <!-- /seventh block -->
    </fieldset>
    <fieldset
        id="machine-article"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('machine_article_legend_tab') ?></legend>
        <div class="row nomargins">
            <div class="span3">
                &nbsp;
            </div>
            <div class="span3">
                <label>
                    <?php echo I18n::__('machine_installedpart_article_name') ?>
                </label>
            </div>
            <div class="span2">
                <label>
                    <?php echo I18n::__('machine_installedpart_stamp') ?>
                </label>
            </div>
            <div class="span2">
                <label
                    class="number">
                    <?php echo I18n::__('machine_installedpart_purchaseprice') ?>
                </label>
            </div>
            <div class="span2">
                <label
                    class="number">
                    <?php echo I18n::__('machine_installedpart_salesprice') ?>
                </label>
            </div>
        </div>
        <div
            id="machine-<?php echo $record->getId() ?>-installedpart-container"
            class="container attachable detachable sortable">
            <?php
            $_installedparts = $record->with("ORDER BY stamp")->ownInstalledpart;
            if (count($_installedparts) == 0):
                $_installedpart = R::dispense('installedpart');
                $_installedpart->machine = $record;
                $_installedpart->article = R::dispense('article');
                $_installedparts[] = $_installedpart;
            endif;
            ?>
            <?php $index = 0 ?>
            <?php foreach ($_installedparts as $_id => $_installedpart): ?>
            <?php $index++ ?>
            <?php Flight::render('model/machine/own/installedpart', [
                'record' => $record,
                '_installedpart' => $_installedpart,
                'index' => $index
            ]); ?>
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
                <?php Flight::render('model/machine/own/appointment', [
                    'record' => $record,
                    '_appointment' => $_appointment,
                    'index' => $index
                ]); ?>
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
                <?php Flight::render('model/machine/own/contract', [
                    'record' => $record,
                    '_contract' => $_contract,
                    'index' => $index
                ]); ?>
                <?php endforeach ?>
            </div>
    </fieldset>
</div>
<!-- end of machine edit form -->
