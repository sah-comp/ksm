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
        <div class="span2">
            <label
                for="treaty-startdate"
                class="<?php echo ($record->hasError('startdate')) ? 'error' : ''; ?>">
                <?php echo I18n::__('treaty_label_startdate') ?>
            </label>
        </div>
        <div class="span2">
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
                name="dialog[contracttype_id]">
                <option value=""><?php echo I18n::__('treaty_contracttype_none') ?></option>
                <?php foreach (R::findAll('contracttype') as $_id => $_contracttype): ?>
                <option
                    value="<?php echo $_contracttype->getId() ?>"
                    <?php echo ($record->contracttype_id == $_contracttype->getId()) ? 'selected="selected"' : '' ?>><?php echo $_contracttype->name ?>
                </option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="span2">
            <input
                id="treaty-number"
                class="autowidth"
                type="text"
                name="dialog[number]"
                value="<?php echo htmlspecialchars($record->number) ?>" />
        </div>
        <div class="span2">
            <input
                id="treaty-startdate"
                class="autowidth"
                type="date"
                name="dialog[startdate]"
                placeholder="<?php echo I18n::__('placeholder_intl_date') ?>"
                value="<?php echo htmlspecialchars($record->startdate) ?>" />
        </div>
        <div class="span2">
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

    <div class="row <?php echo ($record->getMachine()->hasError()) ? 'error' : ''; ?>">
        <label
            for="treaty-machine-name">
            <a href="<?php echo Url::build('/admin/%s/edit/%d', [$record->getMachine()->getMeta('type'), $record->getMachine()->getId()]) ?>" class="ir in-form"><?php echo I18n::__('form_link_related') ?></a>
            <?php echo I18n::__('treaty_label_machine') ?>
        </label>
        <input
            type="hidden"
            name="dialog[machine][type]"
            value="machine" />
        <input
            id="treaty-machine-id"
            type="hidden"
            name="dialog[machine][id]"
            value="<?php echo $record->getMachine()->getId() ?>" />
        <input
            type="text"
            id="treaty-machine-name"
            name="dialog[machine][name]"
            class="autocomplete"
            data-source="<?php echo Url::build('/autocomplete/machine/name/?callback=?') ?>"
            data-spread='<?php
                echo json_encode([
                    'treaty-machine-name' => 'value',
                    'treaty-machine-id' => 'id'
                ]); ?>'
            value="<?php echo htmlspecialchars($record->getMachine()->name) ?>" />
    </div>

    <div class="row <?php echo ($record->hasError('person_id')) ? 'error' : ''; ?>">
        <label
            for="treaty-person-name">
            <a href="<?php echo Url::build('/admin/%s/edit/%d', [$record->getPerson()->getMeta('type'), $record->getPerson()->getId()]) ?>" class="ir in-form"><?php echo I18n::__('form_link_related') ?></a>
            <?php echo I18n::__('treaty_label_person') ?>
        </label>
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
                    'treaty-person-id' => 'id'
                ]); ?>'
            value="<?php echo htmlspecialchars($record->getPerson()->name) ?>" />
    </div>
</fieldset>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('treaty_legend_location') ?></legend>
    <div
        id="person-dependent"
        class="autodafe">

        <?php
        if ($record->getPerson()->getId()):
            // The customer of this appointment is already set. No autodafe needed.
            $_dependents = $record->getDependents($record->getPerson());
            Flight::render('model/treaty/location', [
                'record' => $record,
                'locations' => $_dependents['locations']
            ]);
        else:
            // lazy load, after hunting that heretic.
        ?>
        <div class="heretic"><?php echo I18n::__('treaty_person_select_before_me') ?></div>
        <?php endif; ?>

    </div>
</fieldset>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('treaty_payage') ?></legend>
    <div class="row nomargins">
        <div class="span3">&nbsp;</div>
        <div class="span5">
            <label
                for="treaty-priceperunit"
                class="<?php echo ($record->hasError('priceperunit')) ? 'error' : ''; ?>">
                <?php echo I18n::__('treaty_label_priceperunit') ?>
            </label>
        </div>
        <div class="span4">
            <label
                for="treaty-unit"
                class="<?php echo ($record->hasError('unit')) ? 'error' : ''; ?>">
                <?php echo I18n::__('treaty_label_unit') ?>
            </label>
        </div>
    </div>
    <div class="row">
        <div class="span3">&nbsp;</div>
        <div class="span5">
            <input
                id="treaty-priceperunit"
                class="autowidth"
                type="text"
                name="dialog[priceperunit]"
                value="<?php echo htmlspecialchars($record->priceperunit) ?>" />
        </div>
        <div class="span4">
            <select
                id="treaty-unit"
                class="autowidth"
                name="dialog[unit]">
                <option value=""><?php echo I18n::__('treaty_unit_none') ?></option>
                <?php foreach ($record->getUnits() as $unit): ?>
                <option
                    value="<?php echo $unit ?>"
                    <?php echo ($record->unit == $unit) ? 'selected="selected"' : '' ?>><?php echo I18n::__('treaty_unit_' . $unit) ?>
                </option>
                <?php endforeach ?>
            </select>
        </div>
    </div>

    <div class="row nomargins">
        <div class="span3">&nbsp;</div>
        <div class="span5">
            <label
                for="treaty-currentprice"
                class="<?php echo ($record->hasError('currentprice')) ? 'error' : ''; ?>">
                <?php echo I18n::__('treaty_label_currentprice') ?>
            </label>
        </div>
        <div class="span4">
            <label
                for="treaty-restprice"
                class="<?php echo ($record->hasError('restprice')) ? 'error' : ''; ?>">
                <?php echo I18n::__('treaty_label_restprice') ?>
            </label>
        </div>
    </div>
    <div class="row">
        <div class="span3">&nbsp;</div>
        <div class="span5">
            <input
                id="treaty-currentprice"
                class="autowidth"
                type="text"
                name="dialog[currentprice]"
                value="<?php echo htmlspecialchars($record->currentprice) ?>" />
        </div>
        <div class="span4">
            <input
                id="treaty-restprice"
                class="autowidth"
                type="text"
                name="dialog[restprice]"
                value="<?php echo htmlspecialchars($record->restprice) ?>" />
        </div>
    </div>
</fieldset>
<fieldset>
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
<!-- end of treaty edit form -->
