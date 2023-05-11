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
<!-- position edit subform -->
<fieldset
    id="transaction-<?php echo $record->getId() ?>-ownPosition-<?php echo $index ?>">
    <legend class="verbose"><?php echo I18n::__('transaction_legend_position') ?></legend>

    <a
        href="<?php echo Url::build(sprintf('/admin/transaction/detach/position/%d', $_position->getId())) ?>"
        class="ir detach"
        title="<?php echo I18n::__('scaffold_detach') ?>"
        data-target="transaction-<?php echo $record->getId() ?>-ownPosition-<?php echo $index ?>">
            <?php echo I18n::__('scaffold_detach') ?>
    </a>
    <a
        href="<?php echo Url::build(sprintf('/admin/transaction/attach/own/position/%d', $record->getId())) ?>"
        class="ir attach"
        title="<?php echo I18n::__('scaffold_attach') ?>"
        data-target="transaction-<?php echo $record->getId() ?>-position-container">
            <?php echo I18n::__('scaffold_attach') ?>
    </a>

    <div>
        <input
            type="hidden"
            name="dialog[ownPosition][<?php echo $index ?>][type]"
            value="<?php echo $_position->getMeta('type') ?>" />
        <input
            type="hidden"
            name="dialog[ownPosition][<?php echo $index ?>][id]"
            value="<?php echo $_position->getId() ?>" />
        <input
            type="hidden"
            class="currentindex"
            name="dialog[ownPosition][<?php echo $index ?>][currentindex]"
            value="<?php echo $index ?>" />
        <input
            id="transaction-<?php echo $record->getId() ?>-position-<?php echo $index ?>-product-id-shadow"
            type="hidden"
            name="dialog[ownPosition][<?php echo $index ?>][product_id]"
            value="<?php echo $_position->getProduct()->getId() ?>" />
        <input
            type="hidden"
            name="dialog[ownPosition][<?php echo $index ?>][product][type]"
            value="product" />
        <input
            type="hidden"
            name="dialog[ownPosition][<?php echo $index ?>][alternative]"
            value="0" />
        <input
            id="transaction-<?php echo $record->getId() ?>-position-<?php echo $index ?>-product-id"
            type="hidden"
            name="dialog[ownPosition][<?php echo $index ?>][product][id]"
            value="<?php echo $_position->getProduct()->getId() ?>" />
    </div>

    <div class="row">

        <div class="span1">
            &nbsp;
        </div>
        <div class="span1">
            <div class="row flex-center">
                <div class="span6">
                    <h2 class="ir drag-handle"><?php echo I18n::__('ui_action_drag_handle') ?></h2>
                    <select
                        id="transaction-<?php echo $record->getId() ?>-position-<?php echo $index ?>-kind"
                        name="dialog[ownPosition][<?php echo $index ?>][kind]">
                        <option
                            value="<?php echo Model_Position::KIND_POSITION ?>"
                            <?php echo ($_position->kind == Model_Position::KIND_POSITION) ? 'selected="selected"' : '' ?>><?php echo I18n::__('position_kind_position') ?></option>
                        <option
                            value="<?php echo Model_Position::KIND_SUBTOTAL ?>"
                            <?php echo ($_position->kind == Model_Position::KIND_SUBTOTAL) ? 'selected="selected"' : '' ?>><?php echo I18n::__('position_kind_subtotal') ?></option>
                        <option
                            value="<?php echo Model_Position::KIND_FREETEXT ?>"
                            <?php echo ($_position->kind == Model_Position::KIND_FREETEXT) ? 'selected="selected"' : '' ?>><?php echo I18n::__('position_kind_freetext') ?></option>
                        <option
                            value="<?php echo Model_Position::KIND_HR ?>"
                            <?php echo ($_position->kind == Model_Position::KIND_HR) ? 'selected="selected"' : '' ?>><?php echo I18n::__('position_kind_hr') ?></option>
                    </select>
                </div>
                <div class="span4">
                    <input
                        id="transaction-<?php echo $record->getId() ?>-position-<?php echo $index ?>-sequence"
                        class="sequence"
                        type="text"
                        name="dialog[ownPosition][<?php echo $index ?>][sequence]"
                        value="<?php echo htmlspecialchars($_position->decimal('sequence', 0)) ?>">
                </div>
                <div class="span2">
                    <input
                        id="transaction-<?php echo $record->getId() ?>-position-<?php echo $index ?>-alternative"
                        title="<?php echo I18n::__('position_title_alternative') ?>"
                        type="checkbox"
                        name="dialog[ownPosition][<?php echo $index ?>][alternative]"
                        <?php echo ($_position->alternative) ? 'checked="checked"' : '' ?>
                        value="1" />
                </div>
            </div>
        </div>
        <div class="span1">
            <input
                type="text"
                id="transaction-<?php echo $record->getId() ?>-position-<?php echo $index ?>-product-ska"
                name="dialog[ownPosition][<?php echo $index ?>][ska]"
                class="autocomplete"
                data-source="<?php echo Url::build('/autocomplete/product/number/?callback=?') ?>"
                data-spread='<?php
                    echo json_encode([
                        'transaction-'.$record->getId().'-position-'.$index.'-product-ska' => 'ska',
                        'transaction-'.$record->getId().'-position-'.$index.'-product-desc' => 'value',
                        'transaction-'.$record->getId().'-position-'.$index.'-product-count' => 'count',
                        'transaction-'.$record->getId().'-position-'.$index.'-product-id' => 'id',
                        'transaction-'.$record->getId().'-position-'.$index.'-product-id-shadow' => 'id',
                        'transaction-'.$record->getId().'-position-'.$index.'-product-unit' => 'unit',
                        'transaction-'.$record->getId().'-position-'.$index.'-product-salesprice' => 'salesprice',
                        'transaction-'.$record->getId().'-position-'.$index.'-product-vat-id' => 'vat_id',
                        'transaction-'.$record->getId().'-position-'.$index.'-product-costunittype-id' => 'costunittype_id',
                        'transaction-'.$record->getId().'-position-'.$index.'-vatpercentage' => 'vatpercentage'
                    ]); ?>'
                value="<?php echo htmlspecialchars($_position->ska) ?>" />
        </div>
        <div class="span3">
            <textarea
                id="transaction-<?php echo $record->getId() ?>-position-<?php echo $index ?>-product-desc"
                name="dialog[ownPosition][<?php echo $index ?>][desc]"
                rows="2"
                cols="60"><?php echo htmlspecialchars($_position->desc) ?></textarea>
        </div>
        <div class="span1">
            <input
                id="transaction-<?php echo $record->getId() ?>-position-<?php echo $index ?>-product-count"
                type="text"
                class="number"
                name="dialog[ownPosition][<?php echo $index ?>][count]"
                value="<?php echo htmlspecialchars($_position->decimal('count')) ?>">
        </div>
        <div class="span1">
            <input
                id="transaction-<?php echo $record->getId() ?>-position-<?php echo $index ?>-product-unit"
                type="text"
                name="dialog[ownPosition][<?php echo $index ?>][unit]"
                value="<?php echo htmlspecialchars($_position->unit) ?>">
        </div>
        <div class="span1">
            <div class="row">
                <div class="span6">
                    <select
                        id="transaction-<?php echo $record->getId() ?>-position-<?php echo $index ?>-product-costunittype-id"
                        name="dialog[ownPosition][<?php echo $index ?>][costunittype_id]">
                        <option value=""><?php echo I18n::__('product_costunittype_none') ?></option>
                        <?php foreach (R::findAll('costunittype') as $_id => $_costunittype) : ?>
                        <option
                            value="<?php echo $_costunittype->getId() ?>"
                            <?php echo ($_position->getCostunittype()->getId() == $_costunittype->getId()) ? 'selected="selected"' : '' ?>><?php echo $_costunittype->name ?>
                        </option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="span6">
                    <input
                        id="transaction-<?php echo $record->getId() ?>-position-<?php echo $index ?>-vatpercentage"
                        type="hidden"
                        name="dialog[ownPosition][<?php echo $index ?>][vatpercentage]"
                        value="<?php echo $_position->vatpercentage ?>" />
                    <select
                        id="transaction-<?php echo $record->getId() ?>-position-<?php echo $index ?>-product-vat-id"
                        name="dialog[ownPosition][<?php echo $index ?>][vat_id]">
                        <?php foreach (R::findAll('vat') as $_id => $_vat) : ?>
                        <option
                            value="<?php echo $_vat->getId() ?>"
                            <?php echo ($_position->getVat()->getId() == $_vat->getId()) ? 'selected="selected"' : '' ?>><?php echo $_vat->name ?>
                        </option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="span1">
            <input
                id="transaction-<?php echo $record->getId() ?>-position-<?php echo $index ?>-product-salesprice"
                type="text"
                class="number"
                name="dialog[ownPosition][<?php echo $index ?>][salesprice]"
                value="<?php echo htmlspecialchars($_position->decimal('salesprice')) ?>">
        </div>
        <div class="span1">
            <input
                id="transaction-<?php echo $record->getId() ?>-position-<?php echo $index ?>-adjustment"
                type="text"
                class="number"
                name="dialog[ownPosition][<?php echo $index ?>][adjustment]"
                value="<?php echo htmlspecialchars($_position->decimal('adjustment')) ?>">
        </div>
        <div class="span1">
            <input
                id="transaction-<?php echo $record->getId() ?>-position-<?php echo $index ?>-product-total"
                type="text"
                class="number"
                readonly="readonly"
                name="dialog[ownPosition][<?php echo $index ?>][total]"
                value="<?php echo htmlspecialchars($_position->decimal('total')) ?>">
        </div>
    </div>
</fieldset>
<!-- /position edit subform -->
