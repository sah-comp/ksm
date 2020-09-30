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
    <div class="row nomargins">
        <div class="span3">
            &nbsp;
        </div>
        <div class="span3">
            <label
                for="article-supplier"
                class="<?php echo ($record->hasError('supplier_id')) ? 'error' : ''; ?>">
                <?php echo I18n::__('article_label_supplier') ?>
            </label>
        </div>
        <div class="span2">
            <label
                for="article-number"
                class="<?php echo ($record->hasError('number')) ? 'error' : ''; ?>">
                <?php echo I18n::__('article_label_number') ?>
            </label>
        </div>
        <div class="span2">
            <label
                for="article-isoriginal"
                class="<?php echo ($record->hasError('isoriginal')) ? 'error' : ''; ?>">
                <?php echo I18n::__('article_label_isoriginal') ?>
            </label>
        </div>
        <div class="span2">
            <label
                for="article-isfilter"
                class="<?php echo ($record->hasError('isfilter')) ? 'error' : ''; ?>">
                <?php echo I18n::__('article_label_isfilter') ?>
            </label>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            &nbsp;
        </div>
        <div class="span3">
            <select
                id="article-supplier"
                class="autowidth"
                name="dialog[supplier_id]">
                <option value=""><?php echo I18n::__('article_supplier_none') ?></option>
                <?php foreach (R::findAll('supplier') as $_id => $_supplier): ?>
                <option
                    value="<?php echo $_supplier->getId() ?>"
                    <?php echo ($record->supplier_id == $_supplier->getId()) ? 'selected="selected"' : '' ?>><?php echo $_supplier->name ?>
                </option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="span2">
            <input
                id="article-number"
                class="autowidth"
                type="text"
                name="dialog[number]"
                value="<?php echo htmlspecialchars($record->number) ?>"
                required="required" />
        </div>
        <div class="span2">
            <select
                id="article-isoriginal"
                class="autowidth">
                <option value="0" <?php echo ($record->isoriginal == 0) ? 'selected="selected"' : '' ?>><?php echo I18n::__('article_label_isoriginal_false') ?></option>
                <option value="1" <?php echo ($record->isoriginal == 1) ? 'selected="selected"' : '' ?>><?php echo I18n::__('article_label_isoriginal_true') ?></option>
            </select>
        </div>
        <div class="span2">
            <select
                id="article-isfilter"
                class="autowidth">
                <option value="0" <?php echo ($record->isfilter == 0) ? 'selected="selected"' : '' ?>><?php echo I18n::__('article_label_isfilter_false') ?></option>
                <option value="1" <?php echo ($record->isfilter == 1) ? 'selected="selected"' : '' ?>><?php echo I18n::__('article_label_isfilter_true') ?></option>
            </select>
        </div>
    </div>
    <div class="row <?php echo ($record->hasError('description')) ? 'error' : ''; ?>">
        <label
            for="article-description">
            <?php echo I18n::__('article_label_description') ?>
        </label>
        <textarea
            id="article-description"
            name="dialog[description]"
            rows="3"
            cols="60"><?php echo htmlspecialchars($record->description) ?></textarea>
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
</fieldset>
<div class="tab-container">
    <?php Flight::render('shared/navigation/tabs', array(
        'tab_id' => 'article-tabs',
        'tabs' => array(
            'article-machine' => I18n::__('article_machine_tab'),
            'article-statistic' => I18n::__('article_statistic_tab')
        ),
        'default_tab' => 'article-machine'
    )) ?>
    <fieldset
        id="article-machine"
        class="tab"
        style="display: block;">
        <legend class="verbose"><?php echo I18n::__('article_machine_tab_legend') ?></legend>
        <div class="row nomargins">
            <div class="span3">
                &nbsp;
            </div>
            <div class="span3">
                <label>
                    <?php echo I18n::__('article_machine_name') ?>
                </label>
            </div>
            <div class="span2">
                <label>
                    <?php echo I18n::__('article_machine_stamp') ?>
                </label>
            </div>
            <div class="span2">
                <label
                    class="number">
                    <?php echo I18n::__('article_machine_purchaseprice') ?>
                </label>
            </div>
            <div class="span2">
                <label
                    class="number">
                    <?php echo I18n::__('article_machine_salesprice') ?>
                </label>
            </div>
        </div>
        <div
            id="article-<?php echo $record->getId() ?>-machine-container"
            class="container attachable detachable sortable">
            <?php $index = 0 ?>
            <?php foreach ($record->with("ORDER BY stamp")->ownInstalledpart as $_id => $_installedpart): ?>
            <?php $index++ ?>
            <?php Flight::render('model/article/own/installedpart', array(
                'record' => $record,
                '_installedpart' => $_installedpart,
                'index' => $index
            )) ?>
            <?php endforeach ?>
        </div>
    </fieldset>
    <fieldset
        id="article-statistic"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('article_statistic_tab_legend') ?></legend>
        <p>Linechart of this article</p>
    </fieldset>
</div>
<!-- end of article edit form -->
