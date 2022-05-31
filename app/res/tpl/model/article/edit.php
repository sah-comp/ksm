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
                for="article-person"
                class="<?php echo ($record->hasError('person_id')) ? 'error' : ''; ?>">
                <a href="<?php echo Url::build('/admin/%s/edit/%d', [$record->getPerson()->getMeta('type'), $record->getPerson()->getId()]) ?>" class="ir in-form"><?php echo I18n::__('form_link_related') ?></a>
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
        <div class="span3 badge-container">
            <input
                type="hidden"
                id="article-person-id-shadow"
                name="dialog[person_id]"
                value="<?php echo $record->getPerson()->getId() ?>" />
            <input
                type="hidden"
                name="dialog[person][type]"
                value="person" />
            <input
                id="article-person-id"
                type="hidden"
                name="dialog[person][id]"
                value="<?php echo $record->getPerson()->getId() ?>" />
            <input
                type="text"
                id="article-person-name"
                name="dialog[person][name]"
                class="autocomplete"
                data-source="<?php echo Url::build('/autocomplete/person/name/?callback=?&attr=%s&value=%d', [Model_Person::ATTR_PERSONKIND_ID, Model_Person::PERSONKIND_ID_SUPPLIER]) ?>"
                data-spread='<?php
                    echo json_encode([
                        'article-person-name' => 'value',
                        'article-person-id' => 'id',
                        'article-person-id-shadow' => 'id',
                        'article-postaladdress' => 'postaladdress',
                        'article-billingemail' => 'billingemail',
                        'article-dunningemail' => 'dunningemail',
                        'article-duedays' => 'duedays',
                        'article-discount-id' => 'discount_id'
                    ]); ?>'
                value="<?php echo htmlspecialchars($record->getPerson()->name) ?>" />
                <a
                    href="#scratch-item"
                    title="<?php echo I18n::__('scaffold_action_scratch_title') ?>"
                    data-clear="article-person-name"
                    data-scratch="article-person-id-shadow"
                    class="ir scratch"><?php echo I18n::__('scaffold_action_scratch_linktext') ?></a>
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
                class="autowidth"
                name="dialog[isoriginal]">
                <option value="0" <?php echo ($record->isoriginal == 0) ? 'selected="selected"' : '' ?>><?php echo I18n::__('article_label_isoriginal_false') ?></option>
                <option value="1" <?php echo ($record->isoriginal == 1) ? 'selected="selected"' : '' ?>><?php echo I18n::__('article_label_isoriginal_true') ?></option>
            </select>
        </div>
        <div class="span2">
            <select
                id="article-isfilter"
                class="autowidth"
                name="dialog[isfilter]">
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
            value="<?php echo htmlspecialchars($record->decimal('purchaseprice')) ?>" />
            <p class="info">
                <?php echo I18n::__('article_info_purchaseprice') ?>
            </p>
    </div>
    <div class="row <?php echo ($record->hasError('salesprice')) ? 'error' : ''; ?>">
        <label
            for="article-salesprice"
            class="salesprice">
            <?php echo I18n::__('article_label_salesprice') ?>
        </label>
        <input
            id="article-salesprice"
            type="text"
            class="number"
            name="dialog[salesprice]"
            value="<?php echo htmlspecialchars($record->decimal('salesprice')) ?>" />
            <p class="info">
                <?php echo I18n::__('article_info_salesprice') ?>
            </p>
    </div>
    <div class="row <?php echo ($record->hasError('lastchange')) ? 'error' : '' ?>">
        <label
            for="article-lastchange">
            <?php echo I18n::__('article_label_lastchange') ?>
        </label>
        <input
            id="article-lastchange"
            type="date"
            class="date"
            name="dialog[lastchange]"
            placeholder="<?php echo I18n::__('placeholder_intl_date') ?>"
            value="<?php echo htmlspecialchars($record->lastchange) ?>"
            required="required" />
    </div>
</fieldset>
<div class="tab-container">
    <?php Flight::render('shared/navigation/tabs', array(
        'tab_id' => 'article-tabs',
        'tabs' => array(
            'article-statistic' => I18n::__('article_statistic_tab')
        ),
        'default_tab' => 'article-statistic'
    )) ?>
    <fieldset
        id="article-statistic"
        class="tab"
        style="display: block;">
        <legend class="verbose"><?php echo I18n::__('article_statistic_tab_legend') ?></legend>
        <canvas
            id="chart"
            data-url="<?php echo Url::build('/article/chartdata/%d', [$record->getId()]) ?>"
            width="100%"
            height="400"></canvas>
    </fieldset>
</div>
<!-- end of article edit form -->
