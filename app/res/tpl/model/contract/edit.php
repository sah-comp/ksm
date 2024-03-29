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
<!-- contract edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('contract_legend') ?></legend>

    <div class="row <?php echo ($record->hasError('number')) ? 'error' : ''; ?>">
        <label
            for="contract-number">
            <?php echo I18n::__('contract_label_number') ?>
        </label>
        <input
            id="contract-number"
            type="text"
            name="dialog[number]"
            value="<?php echo htmlspecialchars($record->number ?? '') ?>" />
    </div>

</fieldset>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('contract_legend_customer') ?></legend>

    <div class="row <?php echo ($record->getMachine()->hasError()) ? 'error' : ''; ?>">
        <label
            for="contract-machine-name">
            <a href="<?php echo Url::build('/admin/%s/edit/%d', [$record->getMachine()->getMeta('type'), $record->getMachine()->getId()]) ?>" class="ir in-form"><?php echo I18n::__('form_link_related') ?></a>
            <?php echo I18n::__('contract_label_machine') ?>
        </label>
        <input
            type="hidden"
            name="dialog[machine][type]"
            value="machine" />
        <input
            id="contract-machine-id"
            type="hidden"
            name="dialog[machine][id]"
            value="<?php echo $record->getMachine()->getId() ?>" />
        <input
            type="text"
            id="contract-machine-name"
            name="dialog[machine][name]"
            class="autocomplete"
            data-source="<?php echo Url::build('/autocomplete/machine/name/?callback=?') ?>"
            data-spread='<?php
                echo json_encode([
                    'contract-machine-name' => 'value',
                    'contract-machine-id' => 'id'
                ]); ?>'
            value="<?php echo htmlspecialchars($record->getMachine()->name ?? '') ?>" />
    </div>

    <div class="row <?php echo ($record->hasError('person_id')) ? 'error' : ''; ?>">
        <label
            for="contract-person-name">
            <a href="<?php echo Url::build('/admin/%s/edit/%d', [$record->getPerson()->getMeta('type'), $record->getPerson()->getId()]) ?>" class="ir in-form"><?php echo I18n::__('form_link_related') ?></a>
            <?php echo I18n::__('contract_label_person') ?>
        </label>
        <input
            type="hidden"
            name="dialog[person][type]"
            value="person" />
        <input
            id="contract-person-id"
            type="hidden"
            name="dialog[person][id]"
            value="<?php echo $record->getPerson()->getId() ?>" />
        <input
            type="text"
            id="contract-person-name"
            name="dialog[person][name]"
            class="autocomplete"
            data-target="person-dependent"
            data-extra="contract-person-id"
            data-dynamic="<?php echo Url::build('/contract/%d/person/changed/?callback=?', [$record->getId()]) ?>"
            data-source="<?php echo Url::build('/autocomplete/person/name/?callback=?') ?>"
            data-spread='<?php
                echo json_encode([
                    'contract-person-name' => 'value',
                    'contract-person-id' => 'id'
                ]); ?>'
            value="<?php echo htmlspecialchars($record->getPerson()->name ?? '') ?>" />
    </div>
</fieldset>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('contract_legend_location') ?></legend>
    <div
        id="person-dependent"
        class="autodafe">

        <?php
        if ($record->getPerson()->getId()):
            // The customer of this appointment is already set. No autodafe needed.
            $_dependents = $record->getDependents($record->getPerson());
            Flight::render('model/contract/location', [
                'record' => $record,
                'locations' => $_dependents['locations']
            ]);
        else:
            // lazy load, after hunting that heretic.
        ?>
        <div class="heretic"><?php echo I18n::__('contract_person_select_before_me') ?></div>
        <?php endif; ?>

    </div>
</fieldset>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('contract_legend_note') ?></legend>
    <div class="row <?php echo ($record->hasError('note')) ? 'error' : ''; ?>">
        <label
            for="contract-note">
            <?php echo I18n::__('contract_label_note') ?>
        </label>
        <textarea
            id="contract-note"
            name="dialog[note]"
            rows="5"
            cols="60"><?php echo htmlspecialchars($record->note ?? '') ?></textarea>
        <p class="info"><?php echo I18n::__('contract_info_note') ?></p>
    </div>
</fieldset>
<!-- end of contract edit form -->
