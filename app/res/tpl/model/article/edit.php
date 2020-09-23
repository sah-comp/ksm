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
<!-- article edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('article_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('number')) ? 'error' : ''; ?>">
        <label
            for="article-number">
            <?php echo I18n::__('article_label_number') ?>
        </label>
        <input
            id="article-number"
            type="text"
            name="dialog[number]"
            value="<?php echo htmlspecialchars($record->number) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('description')) ? 'error' : ''; ?>">
        <label
            for="article-description">
            <?php echo I18n::__('article_label_description') ?>
        </label>
        <input
            id="article-description"
            type="text"
            name="dialog[description]"
            value="<?php echo htmlspecialchars($record->description) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('isfilter')) ? 'error' : ''; ?>">
        <input
            type="hidden"
            name="dialog[isfilter]"
            value="0" />
        <input
            id="article-isfilter"
            type="checkbox"
            name="dialog[isfilter]"
            <?php echo ($record->isfilter) ? 'checked="checked"' : '' ?>
            value="1" />
        <label
            for="article-isfilter"
            class="cb">
            <?php echo I18n::__('article_label_isfilter') ?>
        </label>
    </div>
    <div class="row <?php echo ($record->hasError('isoriginal')) ? 'error' : ''; ?>">
        <input
            type="hidden"
            name="dialog[isoriginal]"
            value="0" />
        <input
            id="article-isoriginal"
            type="checkbox"
            name="dialog[isoriginal]"
            <?php echo ($record->isoriginal) ? 'checked="checked"' : '' ?>
            value="1" />
        <label
            for="article-isoriginal"
            class="cb">
            <?php echo I18n::__('article_label_isoriginal') ?>
        </label>
    </div>
    <div class="row <?php echo ($record->hasError('purchaseprice')) ? 'error' : ''; ?>">
        <label
            for="article-purchaseprice">
            <?php echo I18n::__('article_label_purchaseprice') ?>
        </label>
        <input
            id="article-purchaseprice"
            type="text"
            class="number"
            name="dialog[purchaseprice]"
            value="<?php echo htmlspecialchars(number_format((float)$record->purchaseprice, 2, ',', '.')) ?>" />
            <p class="info">
                <?php echo I18n::__('article_info_purchaseprice') ?>
            </p>
    </div>
    <div class="row <?php echo ($record->hasError('salesprice')) ? 'error' : ''; ?>">
        <label
            for="article-salesprice">
            <?php echo I18n::__('article_label_salesprice') ?>
        </label>
        <input
            id="article-salesprice"
            type="text"
            class="number"
            name="dialog[salesprice]"
            value="<?php echo htmlspecialchars(number_format((float)$record->salesprice, 2, ',', '.')) ?>" />
            <p class="info">
                <?php echo I18n::__('article_info_salesprice') ?>
            </p>
    </div>
    <div class="row <?php echo ($record->hasError('supplier_id')) ? 'error' : ''; ?>">
        <label
            for="article-supplier">
            <?php echo I18n::__('article_label_supplier') ?>
        </label>
        <select
            id="article-supplier"
            name="dialog[supplier_id]">
            <option value=""><?php echo I18n::__('article_supplier_none') ?></option>
            <?php foreach (R::findAll('supplier') as $_id => $_supplier): ?>
            <option
                value="<?php echo $_supplier->getId() ?>"
                <?php echo ($record->getId() == $_supplier->getId()) ? 'disabled="disabled"' : '' ?>
                <?php echo ($record->supplier_id == $_supplier->getId()) ? 'selected="selected"' : '' ?>><?php echo $_supplier->name ?></option>
            <?php endforeach ?>
        </select>
    </div>
</fieldset>
<!-- end of article edit form -->
