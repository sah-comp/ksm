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
<!-- transaction edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('transaction_legend') ?></legend>
    <div class="row nomargins">
        <div class="span3">&nbsp;</div>
        <div class="span3">
            <label
                for="transaction-number"
                class="<?php echo ($record->hasError('number')) ? 'error' : ''; ?>">
                <?php echo I18n::__('transaction_label_number') ?>
            </label>
        </div>
        <div class="span3">&nbsp;</div>
        <div class="span3">
            <label
                for="transaction-bookingdate"
                class="<?php echo ($record->hasError('bookingdate')) ? 'error' : ''; ?>">
                <?php echo I18n::__('transaction_label_bookingdate') ?>
            </label>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <select
                id="transaction-contracttype"
                class="autowidth"
                name="dialog[contracttype_id]"
                disabled="disabled"
                required="required">
                <option value=""><?php echo I18n::__('transaction_contracttype_none') ?></option>
                <?php foreach (R::find('contracttype', "enabled = 1 AND ledger = 1 ORDER BY name") as $_id => $_contracttype): ?>
                <option
                    value="<?php echo $_contracttype->getId() ?>"
                    <?php echo ($record->contracttype_id == $_contracttype->getId()) ? 'selected="selected"' : '' ?>><?php echo $_contracttype->name ?>
                </option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="span3">
            <input
                id="transaction-number"
                class="autowidth"
                type="text"
                readonly="readonly"
                name="dialog[number]"
                required="required"
                value="<?php echo htmlspecialchars($record->number) ?>" />
            <?php if ($_parent = $record->hasParent()): ?>
            <p class="info"><?php echo I18n::__('transaction_info_parent', null, [$_parent->getId(), $_parent->getContracttype()->name, $_parent->number]) ?></p>
            <?php endif; ?>
        </div>
        <div class="span3">
            &nbsp;
        </div>
        <div class="span3">
            <input
                id="transaction-bookingdate"
                class="autowidth"
                type="date"
                name="dialog[bookingdate]"
                required="required"
                placeholder="<?php echo I18n::__('placeholder_intl_date') ?>"
                value="<?php echo htmlspecialchars($record->bookingdate) ?>" />
        </div>
    </div>
</fieldset>
<div class="tab-container">
    <?php Flight::render('shared/navigation/tabs', array(
        'tab_id' => 'transaction-tabs',
        'tabs' => array(
            'transaction-head' => I18n::__('transaction_tab_head'),
            'transaction-position' => I18n::__('transaction_tab_position')
        ),
        'default_tab' => 'transaction-head'
    )) ?>
    <fieldset
        id="transaction-head"
        class="tab"
        style="display: block;">
        <legend class="verbose"><?php echo I18n::__('transaction_legend_person') ?></legend>
        <div class="row <?php echo ($record->hasError('person_id')) ? 'error' : ''; ?>">
            <label
                for="transaction-person-name">
                <a href="<?php echo Url::build('/admin/%s/edit/%d', [$record->getPerson()->getMeta('type'), $record->getPerson()->getId()]) ?>" class="ir in-form"><?php echo I18n::__('form_link_related') ?></a>
                <?php echo I18n::__('transaction_label_person') ?>
            </label>
            <input
                type="hidden"
                id="transaction-person-id-shadow"
                name="dialog[person_id]"
                value="<?php echo $record->getPerson()->getId() ?>" />
            <input
                type="hidden"
                name="dialog[person][type]"
                value="person" />
            <input
                id="transaction-person-id"
                type="hidden"
                name="dialog[person][id]"
                value="<?php echo $record->getPerson()->getId() ?>" />
            <input
                type="text"
                id="transaction-person-name"
                name="dialog[person][name]"
                class="autocomplete"
                data-source="<?php echo Url::build('/autocomplete/person/name/?callback=?') ?>"
                data-spread='<?php
                    echo json_encode([
                        'transaction-person-name' => 'value',
                        'transaction-person-id' => 'id',
                        'transaction-person-id-shadow' => 'id',
                        'transaction-postaladdress' => 'postaladdress'
                    ]); ?>'
                value="<?php echo htmlspecialchars($record->getPerson()->name) ?>" />
                <a
                    href="#scratch-item"
                    title="<?php echo I18n::__('scaffold_action_scratch_title') ?>"
                    data-clear="transaction-person-name"
                    data-scratch="transaction-person-id-shadow"
                    class="ir scratch"><?php echo I18n::__('scaffold_action_scratch_linktext') ?></a>
        </div>
        <div class="row <?php echo ($record->hasError('postaladdress')) ? 'error' : ''; ?>">
            <label
                for="transaction-postaladdress">
                <?php echo I18n::__('transaction_label_postaladdress') ?>
            </label>
            <textarea
                id="transaction-postaladdress"
                name="dialog[postaladdress]"
                rows="5"
                cols="60"
                required="required"><?php echo htmlspecialchars($record->postaladdress) ?></textarea>
            <p class="info"><?php echo I18n::__('transaction_info_postaladdress') ?></p>
        </div>
    </fieldset>
    <fieldset
        id="transaction-position"
        class="tab smaller-font"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('transaction_legend_position') ?></legend>
        <div class="row">
            <div class="span1">
                &nbsp;
            </div>
            <div class="span1">
                <?php echo I18n::__('position_label_product') ?>
            </div>
            <div class="span4">
                <?php echo I18n::__('position_label_product_desc') ?>
            </div>
            <div class="span1 tar">
                <?php echo I18n::__('position_label_count') ?>
            </div>
            <div class="span1">
                <?php echo I18n::__('position_label_unit') ?>
            </div>
            <div class="span2 tar">
                <?php echo I18n::__('position_label_salesprice') ?>
            </div>
            <div class="span2 tar">
                <?php echo I18n::__('position_label_total') ?>
            </div>
        </div>
        <div
            id="transaction-<?php echo $record->getId() ?>-position-container"
            class="container attachable detachable sortable">
            <?php $_positions = $record->with(' ORDER BY id ASC ')->ownPosition ?>
            <?php if (count($_positions) == 0):
            $_positions[] = R::dispense('position');
        endif; ?>
        <?php $index = 0 ?>
        <?php foreach ($_positions as $_position_id => $_position): ?>
        <?php $index++ ?>
        <?php Flight::render('model/transaction/own/position', array(
            'record' => $record,
            '_position' => $_position,
            'index' => $index
        )) ?>
        <?php endforeach ?>
        </div>
        <div class="row">
            <div class="span1">
                &nbsp;
            </div>
            <div class="span9 tar">
                <?php echo I18n::__('transaction_label_total_net') ?>
            </div>
            <div class="span2">
                <input
                    type="text"
                    class="number"
                    name="dialog[net]"
                    readonly="readonly"
                    value="<?php echo htmlspecialchars($record->decimal('net', 2)) ?>">
            </div>
        </div>
        <?php $vats = $record->getVatSentences(); ?>
        <?php foreach ($vats as $_id => $_vat): ?>
        <div class="row">
            <div class="span1">
                &nbsp;
            </div>
            <div class="span9 tar">
                <?php echo htmlspecialchars($_vat['vatpercentage'] . ' ' . $_vat['net']) ?>
            </div>
            <div class="span2">
                <?php echo htmlspecialchars($_vat['vatvalue']) ?>
            </div>
        </div>
        <?php endforeach; ?>
        <div class="row">
            <div class="span1">
                &nbsp;
            </div>
            <div class="span9 tar">
                <?php echo I18n::__('transaction_label_total_gros') ?>
            </div>
            <div class="span2">
                <input
                    type="text"
                    class="number"
                    name="dialog[gros]"
                    readonly="readonly"
                    value="<?php echo htmlspecialchars($record->decimal('gros', 2)) ?>">
            </div>
        </div>
    </fieldset>
</div>
<!-- end of transaction edit form -->
