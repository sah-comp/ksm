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
    </div>

    <div class="row">

        <div class="span1">
            &nbsp;
        </div>
        <div class="span1">
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
            <input
                id="transaction-<?php echo $record->getId() ?>-position-<?php echo $index ?>-product-vat-id"
                type="hidden"
                name="dialog[ownPosition][<?php echo $index ?>][vat_id]"
                value="<?php echo $_position->getVat()->getId() ?>" />
            <input
                id="transaction-<?php echo $record->getId() ?>-position-<?php echo $index ?>-vatpercentage"
                type="hidden"
                name="dialog[ownPosition][<?php echo $index ?>][vatpercentage]"
                value="<?php echo $_position->vatpercentage ?>" />
            <input
                id="transaction-<?php echo $record->getId() ?>-position-<?php echo $index ?>-product-costunittype-id"
                type="hidden"
                name="dialog[ownPosition][<?php echo $index ?>][costunittype_id]"
                value="<?php echo $_position->getCostunittype()->getId() ?>" />
            <input
                type="text"
                id="transaction-<?php echo $record->getId() ?>-position-<?php echo $index ?>-product-number"
                name="dialog[ownPosition][<?php echo $index ?>][product][number]"
                class="autocomplete"
                data-source="<?php echo Url::build('/autocomplete/product/number/?callback=?') ?>"
                data-spread='<?php
                    echo json_encode([
                        'transaction-'.$record->getId().'-position-'.$index.'-product-number' => 'ska',
                        'transaction-'.$record->getId().'-position-'.$index.'-product-desc' => 'value',
                        'transaction-'.$record->getId().'-position-'.$index.'-product-id' => 'id',
                        'transaction-'.$record->getId().'-position-'.$index.'-product-id-shadow' => 'id',
                        'transaction-'.$record->getId().'-position-'.$index.'-product-unit' => 'unit',
                        'transaction-'.$record->getId().'-position-'.$index.'-product-salesprice' => 'salesprice',
                        'transaction-'.$record->getId().'-position-'.$index.'-product-vat-id' => 'vat_id',
                        'transaction-'.$record->getId().'-position-'.$index.'-product-costunittype-id' => 'costunittype_id',
                        'transaction-'.$record->getId().'-position-'.$index.'-vatpercentage' => 'vatpercentage'
                    ]); ?>'
                value="<?php echo htmlspecialchars($_position->getProduct()->number) ?>" />
        </div>
        <div class="span4">
            <textarea
                id="transaction-<?php echo $record->getId() ?>-position-<?php echo $index ?>-product-desc"
                name="dialog[ownPosition][<?php echo $index ?>][desc]"
                rows="2"
                cols="60"><?php echo htmlspecialchars($_position->desc) ?></textarea>
        </div>
        <div class="span1">
            <input
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
            <input
                id="transaction-<?php echo $record->getId() ?>-position-<?php echo $index ?>-alternative"
                title="<?php echo I18n::__('position_title_alternative') ?>"
                type="checkbox"
                name="dialog[ownPosition][<?php echo $index ?>][alternative]"
                <?php echo ($_position->alternative) ? 'checked="checked"' : '' ?>
                value="1" />
        </div>
        <div class="span1">
            <input
                id="transaction-<?php echo $record->getId() ?>-position-<?php echo $index ?>-product-salesprice"
                type="text"
                class="number"
                name="dialog[ownPosition][<?php echo $index ?>][salesprice]"
                value="<?php echo htmlspecialchars($_position->decimal('salesprice')) ?>">
        </div>
        <div class="span2">
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
