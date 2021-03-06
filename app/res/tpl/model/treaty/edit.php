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
<!-- treaty edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('treaty_legend') ?></legend>
    <div class="row nomargins">
        <div class="span3">&nbsp;</div>
        <div class="span3">
            <label
                for="treaty-contracttype"
                class="<?php echo ($record->getContracttype()->hasError()) ? 'error' : ''; ?>">
                <?php echo I18n::__('treaty_label_contracttype') ?>
            </label>
        </div>
        <div class="span2">
            <label
                for="treaty-number"
                class="<?php echo ($record->hasError('number')) ? 'error' : ''; ?>">
                <?php echo I18n::__('treaty_label_number') ?>
            </label>
        </div>
        <div class="span2 <?php echo $record->classesCss() ?>">
            <label
                for="treaty-startdate"
                class="<?php echo ($record->hasError('startdate')) ? 'error' : ''; ?>">
                <?php echo I18n::__('treaty_label_startdate') ?>
            </label>
        </div>
        <div class="span2 <?php echo $record->classesCss() ?>">
            <label
                for="treaty-enddate"
                class="<?php echo ($record->hasError('enddate')) ? 'error' : ''; ?>">
                <?php echo I18n::__('treaty_label_enddate') ?>
            </label>
        </div>
    </div>
    <div class="row">
        <div class="span3">&nbsp;</div>
        <div class="span3">
            <select
                id="treaty-contracttype"
                class="autowidth"
                name="dialog[contracttype_id]"
                disabled="disabled">
                <option value=""><?php echo I18n::__('treaty_contracttype_none') ?></option>
                <?php foreach (R::find('contracttype', "enabled = 1 ORDER BY name") as $_id => $_contracttype): ?>
                <option
                    value="<?php echo $_contracttype->getId() ?>"
                    <?php echo ($record->contracttype_id == $_contracttype->getId()) ? 'selected="selected"' : '' ?>><?php echo $_contracttype->name ?>
                </option>
                <?php endforeach ?>
            </select>
            <?php if ($_parent = $record->hasParent()): ?>
            <p class="info"><?php echo I18n::__('treaty_info_parent', null, [$_parent->getId(), $_parent->getContracttype()->name, $_parent->number]) ?></p>
            <?php endif; ?>
        </div>
        <div class="span2">
            <input
                id="treaty-number"
                class="autowidth"
                type="text"
                name="dialog[number]"
                value="<?php echo htmlspecialchars($record->number) ?>" />
        </div>
        <div class="span2 <?php echo $record->classesCss() ?>">
            <input
                id="treaty-startdate"
                class="autowidth"
                type="date"
                name="dialog[startdate]"
                placeholder="<?php echo I18n::__('placeholder_intl_date') ?>"
                value="<?php echo htmlspecialchars($record->startdate) ?>" />
        </div>
        <div class="span2 <?php echo $record->classesCss() ?>">
            <input
                id="treaty-enddate"
                class="autowidth"
                type="date"
                name="dialog[enddate]"
                placeholder="<?php echo I18n::__('placeholder_intl_date') ?>"
                value="<?php echo htmlspecialchars($record->enddate) ?>"/>
        </div>
    </div>
</fieldset>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('treaty_legend_customer') ?></legend>
    <div class="row <?php echo ($record->hasError('prospect')) ? 'error' : ''; ?>">
        <label
            for="treaty-prospect">
            <?php echo I18n::__('treaty_label_prospect') ?>
        </label>
        <input
            type="text"
            id="treaty-prospect"
            name="dialog[prospect]"
            value="<?php echo htmlspecialchars($record->prospect) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('person_id')) ? 'error' : ''; ?>">
        <label
            for="treaty-person-name">
            <a href="<?php echo Url::build('/admin/%s/edit/%d', [$record->getPerson()->getMeta('type'), $record->getPerson()->getId()]) ?>" class="ir in-form"><?php echo I18n::__('form_link_related') ?></a>
            <?php echo I18n::__('treaty_label_person') ?>
        </label>
        <input
            type="hidden"
            id="treaty-person-id-shadow"
            name="dialog[person_id]"
            value="<?php echo $record->getPerson()->getId() ?>" />
        <input
            type="hidden"
            name="dialog[person][type]"
            value="person" />
        <input
            id="treaty-person-id"
            type="hidden"
            name="dialog[person][id]"
            value="<?php echo $record->getPerson()->getId() ?>" />
        <input
            type="text"
            id="treaty-person-name"
            name="dialog[person][name]"
            class="autocomplete"
            data-target="person-dependent"
            data-extra="treaty-person-id"
            data-dynamic="<?php echo Url::build('/treaty/%d/person/changed/?callback=?', [$record->getId()]) ?>"
            data-source="<?php echo Url::build('/autocomplete/person/name/?callback=?') ?>"
            data-spread='<?php
                echo json_encode([
                    'treaty-person-name' => 'value',
                    'treaty-person-id' => 'id',
                    'treaty-person-id-shadow' => 'id',
                    'postal-address' => 'postaladdress'
                ]); ?>'
            value="<?php echo htmlspecialchars($record->getPerson()->name) ?>" />
            <a
                href="#scratch-item"
                title="<?php echo I18n::__('scaffold_action_scratch_title') ?>"
                data-clear="treaty-person-name"
                data-scratch="treaty-person-id-shadow"
                class="ir scratch"><?php echo I18n::__('scaffold_action_scratch_linktext') ?></a>
    </div>
    <div class="row <?php echo ($record->hasError('serialnumber')) ? 'error' : ''; ?>">
        <label
            for="treaty-serialnumber">
            <?php echo I18n::__('treaty_label_serialnumber') ?>
        </label>
        <input
            type="text"
            id="treaty-serialnumber"
            name="dialog[serialnumber]"
            value="<?php echo htmlspecialchars($record->serialnumber) ?>" />
    </div>
</fieldset>
<div class="tab-container">
    <?php Flight::render('shared/navigation/tabs', array(
        'tab_id' => 'treaty-tabs',
        'tabs' => array(
            'treaty-limb' => I18n::__('treaty_limb_tab'),
            'treaty-note' => I18n::__('treaty_note_tab')
        ),
        'default_tab' => 'treaty-limb'
    )) ?>
    <fieldset
        id="treaty-limb"
        class="tab"
        style="display: block;">
        <legend class="verbose"><?php echo I18n::__('treaty_legend_limb') ?></legend>
        <?php $_payload = json_decode($record->payload, true) ?>
        <?php
        foreach ($record->contracttype->withCondition("active = 1 ORDER BY sequence")->ownLimb as $_id => $_limb):
            Flight::render('model/treaty/part/limb', [
                'payload' => $_payload,
                'limb' => $_limb
            ]);
        endforeach;
        ?>
    </fieldset>
    <fieldset
        id="treaty-note"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('treaty_legend_note') ?></legend>
        <div class="row <?php echo ($record->hasError('note')) ? 'error' : ''; ?>">
            <label
                for="treaty-note">
                <?php echo I18n::__('treaty_label_note') ?>
            </label>
            <textarea
                id="treaty-note"
                name="dialog[note]"
                rows="5"
                cols="60"><?php echo htmlspecialchars($record->note) ?></textarea>
            <p class="info"><?php echo I18n::__('treaty_info_note') ?></p>
        </div>
    </fieldset>
</div>
<!-- end of treaty edit form -->
