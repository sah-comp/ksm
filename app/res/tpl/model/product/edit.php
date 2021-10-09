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
<!-- product edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('product_legend') ?></legend>
    <div class="row nomargins">
        <div class="span3">
            &nbsp;
        </div>
        <div class="span6">
            <label
                for="product-number"
                class="<?php echo ($record->hasError('number')) ? 'error' : ''; ?>">
                <?php echo I18n::__('product_label_number') ?>
            </label>
        </div>
        <div class="span3">
            <label
                for="product-matchcode"
                class="<?php echo ($record->hasError('matchcode')) ? 'error' : ''; ?>">
                <?php echo I18n::__('product_label_matchcode') ?>
            </label>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            &nbsp;
        </div>
        <div class="span6">
            <input
                id="product-number"
                class="autowidth"
                type="text"
                name="dialog[number]"
                value="<?php echo htmlspecialchars($record->number) ?>"
                required="required" />
        </div>
        <div class="span3">
            <input
                id="product-matchcode"
                class="autowidth"
                type="text"
                name="dialog[matchcode]"
                value="<?php echo htmlspecialchars($record->matchcode) ?>" />
        </div>
    </div>
    <div class="row <?php echo ($record->hasError('description')) ? 'error' : ''; ?>">
        <label
            for="product-description">
            <?php echo I18n::__('product_label_description') ?>
        </label>
        <textarea
            id="product-description"
            name="dialog[description]"
            rows="3"
            cols="60"><?php echo htmlspecialchars($record->description) ?></textarea>
    </div>
    <div class="row <?php echo ($record->hasError('purchaseprice')) ? 'error' : ''; ?>">
        <label
            for="product-purchaseprice">
            <?php echo I18n::__('product_label_purchaseprice') ?>
        </label>
        <input
            id="product-purchaseprice"
            type="text"
            class="number"
            name="dialog[purchaseprice]"
            value="<?php echo htmlspecialchars(number_format((float)$record->purchaseprice, 2, ',', '.')) ?>" />
            <p class="info">
                <?php echo I18n::__('product_info_purchaseprice') ?>
            </p>
    </div>
    <div class="row">
        <label
            for="product-vatid">
            <?php echo I18n::__('product_label_vatid') ?>
        </label>
        <select
            id="product-vatid"
            class=""
            name="dialog[vat_id]">
            <option value=""><?php echo I18n::__('product_vat_please_select') ?></option>
            <?php foreach (R::find('vat', ' ORDER BY name') as $_id => $_vat): ?>
            <option
                value="<?php echo $_vat->getId() ?>"
                <?php echo ($record->vat_id == $_vat->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_vat->name) ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <div class="row <?php echo ($record->hasError('salesprice')) ? 'error' : ''; ?>">
        <label
            for="product-salesprice">
            <?php echo I18n::__('product_label_salesprice') ?>
        </label>
        <input
            id="product-salesprice"
            type="text"
            class="number"
            name="dialog[salesprice]"
            value="<?php echo htmlspecialchars(number_format((float)$record->salesprice, 2, ',', '.')) ?>" />
            <p class="info">
                <?php echo I18n::__('product_info_salesprice') ?>
            </p>
    </div>
</fieldset>
<!-- end of product edit form -->
