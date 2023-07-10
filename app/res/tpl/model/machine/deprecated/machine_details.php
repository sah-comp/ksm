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
                    class="<?php echo ($record->hasError('weight')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_weight') ?>
                </label>
            </div>
            <div class="span3">
                <label
                    for="machine-height"
                    class="<?php echo ($record->hasError('height')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_height') ?>
                </label>
            </div>
            <div class="span3">
                <label
                    for="machine-maxload"
                    class="<?php echo ($record->hasError('maxload')) ? 'error' : ''; ?>">
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
                    type="text"
                    name="dialog[weight]"
                    value="<?php echo htmlspecialchars($record->weight) ?>"/>
            </div>
            <div class="span3">
                <input
                    id="machine-height"
                    class="autowidth"
                    type="text"
                    name="dialog[height]"
                    value="<?php echo htmlspecialchars($record->height) ?>"/>
            </div>
            <div class="span3">
                <input
                    id="machine-maxload"
                    class="autowidth"
                    type="text"
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
                    class="<?php echo ($record->hasError('forkmaxheight')) ? 'error' : ''; ?>">
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
                    type="text"
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
            <div class="span3">
                <label
                    for="machine-motor"
                    class="<?php echo ($record->hasError('motor')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_motor') ?>
                </label>
            </div>
            <div class="span3">
                <label
                    for="machine-motorserialnumber"
                    class="<?php echo ($record->hasError('motorserialnumber')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_motorserialnumber') ?>
                </label>
            </div>
            <div class="span3">
                <label
                    for="machine-lever"
                    class="<?php echo ($record->hasError('lever')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_lever') ?>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="span3">&nbsp;</div>
            <div class="span3">
                <input
                    id="machine-motor"
                    class="autowidth"
                    type="text"
                    name="dialog[motor]"
                    value="<?php echo htmlspecialchars($record->motor) ?>"/>
            </div>
            <div class="span3">
                <input
                    id="machine-motorserialnumber"
                    class="autowidth"
                    type="text"
                    name="dialog[motorserialnumber]"
                    value="<?php echo htmlspecialchars($record->motorserialnumber) ?>"/>
            </div>
            <div class="span3">
                <input
                    id="machine-lever"
                    class="autowidth"
                    type="text"
                    name="dialog[lever]"
                    value="<?php echo htmlspecialchars($record->lever) ?>"/>
            </div>
        </div>
        <!-- /first block -->
        <!-- second block -->
        <div class="row nomargins">
            <div class="span3">&nbsp;</div>
            <div class="span3">
                <label
                    for="machine-controlvalve"
                    class="<?php echo ($record->hasError('controlvalve')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_controlvalve') ?>
                </label>
            </div>
            <div class="span3">
                <label
                    for="machine-shutdownvalve"
                    class="<?php echo ($record->hasError('shutdownvalve')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_shutdownvalve') ?>
                </label>
            </div>
            <div class="span3">
                <label
                    for="machine-charger"
                    class="<?php echo ($record->hasError('charger')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_charger') ?>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="span3">&nbsp;</div>
            <div class="span3">
                <input
                    id="machine-controlvalve"
                    class="autowidth"
                    type="text"
                    name="dialog[controlvalve]"
                    value="<?php echo htmlspecialchars($record->controlvalve) ?>"/>
            </div>
            <div class="span3">
                <input
                    id="machine-shutdownvalve"
                    class="autowidth"
                    type="text"
                    name="dialog[shutdownvalve]"
                    value="<?php echo htmlspecialchars($record->shutdownvalve) ?>"/>
            </div>
            <div class="span3">
                <input
                    id="machine-charger"
                    class="autowidth"
                    type="text"
                    name="dialog[charger]"
                    value="<?php echo htmlspecialchars($record->charger) ?>"/>
            </div>
        </div>
        <!-- /second block -->
        <!-- third block -->
        <div class="row nomargins">
            <div class="span3">&nbsp;</div>
            <div class="span3">
                <label
                    for="machine-mixer"
                    class="<?php echo ($record->hasError('mixer')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_mixer') ?>
                </label>
            </div>
            <div class="span3">
                <label
                    for="machine-keynumber"
                    class="<?php echo ($record->hasError('keynumber')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_keynumber') ?>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="span3">&nbsp;</div>
            <div class="span3">
                <input
                    id="machine-mixer"
                    class="autowidth"
                    type="text"
                    name="dialog[mixer]"
                    value="<?php echo htmlspecialchars($record->mixer) ?>"/>
            </div>
            <div class="span3">
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
            <div class="span3">
                <label
                    for="machine-fronttires"
                    class="<?php echo ($record->hasError('fronttires')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_fronttires') ?>
                </label>
            </div>
            <div class="span3">
                <label
                    for="machine-backtires"
                    class="<?php echo ($record->hasError('backtires')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_backtires') ?>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="span3">&nbsp;</div>
            <div class="span3">
                <input
                    id="machine-fronttires"
                    class="autowidth"
                    type="text"
                    name="dialog[fronttires]"
                    value="<?php echo htmlspecialchars($record->fronttires) ?>"/>
            </div>
            <div class="span3">
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
            <div class="span3">
                <label
                    for="machine-controltype"
                    class="<?php echo ($record->hasError('controltype')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_controltype') ?>
                </label>
            </div>
            <div class="span3">
                <label
                    for="machine-battery"
                    class="<?php echo ($record->hasError('battery')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_battery') ?>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="span3">&nbsp;</div>
            <div class="span3">
                <input
                    id="machine-controltype"
                    class="autowidth"
                    type="text"
                    name="dialog[controltype]"
                    value="<?php echo htmlspecialchars($record->controltype) ?>"/>
            </div>
            <div class="span3">
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
            <div class="span3">
                <label
                    for="machine-hourlyrate"
                    class="<?php echo ($record->hasError('hourlyrate')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_hourlyrate') ?>
                </label>
            </div>
            <div class="span3">
                <label
                    for="machine-drivingcost"
                    class="<?php echo ($record->hasError('drivingcost')) ? 'error' : ''; ?>">
                    <?php echo I18n::__('machine_label_drivingcost') ?>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="span3">&nbsp;</div>
            <div class="span3">
                <input
                    id="machine-hourlyrate"
                    class="autowidth"
                    type="text"
                    name="dialog[hourlyrate]"
                    value="<?php echo htmlspecialchars($record->hourlyrate) ?>"/>
            </div>
            <div class="span3">
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