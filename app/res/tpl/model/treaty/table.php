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
<?php if (! $_attributes = $record->getAttributes($layout)) : ?>
    <?php if (! $_gestalt = R::findOne('gestalt', ' name = ?', array($record->getMeta('type')))) : ?>
        <?php $_attributes = array(
            'name' => 'id',
            'sort' => array(
                'name' => $this->bean->getMeta('type') . '.name'
            ),
            'filter' => array(
                'tag' => 'number'
            )
        ) ?>
    <?php else : ?>
        <?php $_attributes = $_gestalt->getVirtualAttributes() ?>
    <?php endif; ?>
<?php endif ?>
<!-- <?php echo $record->getMeta('type') ?> scaffold table -->
<div class="directory fixable">
    <ul>
        <li><a href="<?php echo Url::build('/admin/%s/?qf_reset=1', [$record->getMeta('type')]) ?>" class="<?php echo ($quickfilter_value == '') ? 'current' : '' ?>">Alle</a></li>
        <?php foreach ($record->getQuickFilterValues() as $_id => $_ctype) : ?>
            <li><a href="<?php echo Url::build('/admin/%s/?qf_reset=1&amp;qf_value=%s&amp;contracttype=%d', [$record->getMeta('type'), ($_ctype->name), $_id]) ?>" title="<?php echo htmlspecialchars($_ctype->name) ?>" class="<?php echo ($quickfilter_value == $_ctype->name) ? 'current' : '' ?>"><?php echo $_ctype->name ?></a></li>
        <?php endforeach ?>
    </ul>
</div>
<div class="listing">
    <figure class="fig-table">
        <table class="scaffold treaty">

            <caption>
                <?php echo I18n::__('scaffold_caption_index', null, array($total_records)) ?>
            </caption>

            <thead>
                <tr class="coltitles">
                    <th class="edit">
                        &nbsp;
                    </th>
                    <th class="switch">
                        <input
                            class="all"
                            type="checkbox"
                            name="void"
                            value="1"
                            title="<?php echo I18n::__('scaffold_select_all') ?>" />
                    </th>
                    <!-- header attributes -->
                    <?php foreach ($_attributes as $_i => $_attribute) : ?>
                        <?php
                        $_class = $record->getMeta('type') . ' fn-' . $_attribute['name'] . ' order';
                        //$_dir = 0;
                        if ($order == $_i) :
                            if ($dir == 1) {
                                $_dir = 0;
                            } else {
                                $_dir = 1;
                            }
                            $_class .= ' active dir-' . $dir_map[$_dir];
                        else :
                            $_dir = $record->getDefaultSortDir();
                        endif;
                        if (isset($_attribute['class'])) {
                            $_class .= ' ' . $_attribute['class'];
                        }
                        ?>
                        <th class="<?php echo $_class ?>" <?php echo (isset($_attribute['width']) ? 'style="width: ' . $_attribute['width'] . ';"' : '') ?> data-title="<?php echo (isset($_attribute['label'])) ? $_attribute['label'] : I18n::__($record->getMeta('type') . '_label_' . $_attribute['name']) ?>">
                            <a href="<?php echo Url::build("{$base_url}/{$type}/{$layout}/1/{$_i}/{$_dir}") ?>"><?php echo (isset($_attribute['label'])) ? $_attribute['label'] : I18n::__($record->getMeta('type') . '_label_' . $_attribute['name']) ?></a>
                        </th>
                    <?php endforeach ?>
                    <!-- end of header attributes -->
                </tr>

                <?php if (isset($filter) && is_a($filter, 'RedBeanPHP\OODBBean')) : ?>
                    <tr
                        class="filter">
                        <th>
                            <input
                                type="hidden"
                                name="filter[type]"
                                value="filter" />
                            <input
                                type="hidden"
                                name="filter[id]"
                                value="<?php echo $filter->getId() ?>" />
                            <input
                                type="hidden"
                                name="filter[model]"
                                value="<?php echo $record->getMeta('type') ?>" />
                            <input
                                type="submit"
                                class="ir filter-refresh"
                                name="submit"
                                title="<?php echo I18n::__('filter_submit_refresh') ?>"
                                value="<?php echo I18n::__('filter_submit_refresh') ?>" />
                        </th>

                        <th>
                            <input
                                type="submit"
                                class="ir filter-clear"
                                name="submit"
                                title="<?php echo I18n::__('filter_submit_clear') ?>"
                                value="<?php echo I18n::__('filter_submit_clear') ?>" />
                        </th>

                        <?php foreach ($_attributes as $_i => $_attribute) : ?>
                            <th data-title="<?php echo (isset($_attribute['label'])) ? $_attribute['label'] : I18n::__($record->getMeta('type') . '_label_' . $_attribute['name']) ?>">
                                <?php if (isset($_attribute['filter']) && is_array($_attribute['filter'])) : ?>
                                    <?php $_criteria = $filter->getCriteria($_attribute) ?>
                                    <input
                                        type="hidden"
                                        name="filter[ownCriteria][<?php echo $_i ?>][type]"
                                        value="criteria" />
                                    <input
                                        type="hidden"
                                        name="filter[ownCriteria][<?php echo $_i ?>][id]"
                                        value="<?php echo $_criteria->getId() ?>" />
                                    <input
                                        type="hidden"
                                        name="filter[ownCriteria][<?php echo $_i ?>][op]"
                                        value="<?php echo htmlspecialchars($_criteria->op) ?>" />
                                    <input
                                        type="hidden"
                                        name="filter[ownCriteria][<?php echo $_i ?>][tag]"
                                        value="<?php echo htmlspecialchars($_criteria->tag) ?>" />
                                    <input
                                        type="hidden"
                                        name="filter[ownCriteria][<?php echo $_i ?>][attribute]"
                                        value="<?php echo htmlspecialchars($_criteria->attribute) ?>" />

                                    <?php if ($_criteria->tag == 'bool') : ?>
                                        <select
                                            class="filter-select"
                                            name="filter[ownCriteria][<?php echo $_i ?>][value]">
                                            <option
                                                value="">
                                                <?php echo I18n::__('filter_placeholder_any') ?>
                                            </option>
                                            <?php foreach (
                                                array(
                                                    0 => I18n::__('bool_false'),
                                                    1 => I18n::__('bool_true')
                                                ) as $_bool_val => $_bool_text
                                            ) : ?>
                                                <option
                                                    value="<?php echo $_bool_val ?>"
                                                    <?php echo ($_criteria->value != '' && $_bool_val == (int)$_criteria->value) ? 'selected="selected"' : '' ?>>
                                                    <?php echo $_bool_text ?>
                                                </option>
                                            <?php endforeach ?>
                                        </select>
                                    <?php
                                    elseif ($_criteria->tag == 'in') :
                                        $_values = explode(', ', $_criteria->value);
                                    ?>

                                        <input
                                            type="hidden"
                                            name="filter[ownCriteria][<?php echo $_i ?>][postvar]"
                                            value="<?php echo htmlspecialchars($_criteria->postvar) ?>">
                                        <input
                                            type="hidden"
                                            name="filter[ownCriteria][<?php echo $_i ?>][value]"
                                            value="<?php echo htmlspecialchars($_criteria->value) ?>">
                                        <select
                                            name="<?php echo $_attribute['filter']['postvar'] ?>[]"
                                            class="autowidth"
                                            size="4"
                                            multiple="multiple">
                                            <?php foreach (R::find($_attribute['filter']['options']['bean']) as $_in_id => $_in_opt) : ?>
                                                <option value="<?php echo $_in_opt->{$_attribute['filter']['options']['id']} ?>" <?php echo (in_array($_in_opt->getId(), $_values)) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_in_opt->{$_attribute['filter']['options']['label']}) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php
                                    elseif ($_criteria->tag == 'select') :
                                    ?>
                                        <select
                                            class="autowidth"
                                            name="filter[ownCriteria][<?php echo $_i ?>][value]">
                                            <option value=""><?php echo I18n::__('scaffold_filter_select_none') ?></option>
                                            <?php
                                            if (isset($_attribute['filter']['values'])) :
                                                $_values = $_attribute['filter']['values'];
                                            elseif (isset($_attribute['filter']['sql'])) :
                                                $_values = $record->{$_attribute['filter']['sql']}();
                                            else :
                                                $_values = [
                                                    '' => I18n::__('scaffold_filter_select_pleasedefinesource')
                                                ];
                                            endif;
                                            foreach ($_values as $_value => $_label) :
                                            ?>
                                                <option value="<?php echo $_value ?>" <?php echo $_criteria->value == $_value ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_label) ?></option>
                                            <?php
                                            endforeach;
                                            ?>
                                        </select>
                                    <?php else : ?>
                                        <input
                                            type="text"
                                            class="filter <?php echo (isset($_attribute['class']) ? $_attribute['class'] : '') ?>"
                                            name="filter[ownCriteria][<?php echo $_i ?>][value]"
                                            value="<?php echo htmlspecialchars($_criteria->value) ?>"
                                            placeholder="<?php echo I18n::__('filter_placeholder_any') ?>" />
                                    <?php endif ?>

                                <?php else : ?>
                                    &nbsp;
                                <?php endif ?>
                            </th>
                        <?php endforeach ?>
                    </tr>
                <?php endif ?>

            </thead>

            <tfoot>
                <tr>
                    <td colspan="<?php echo count($_attributes) + 2 ?>">
                        &nbsp;
                    </td>
                </tr>
            </tfoot>

            <tbody>
                <?php $offset = 0 ?>
                <?php foreach ($records as $id => $_record) : ?>
                    <?php $offset++ ?>
                    <tr
                        id="<?php echo $_record->getMeta('type') ?>-<?php echo $_record->getId() ?>"
                        data-type="<?php echo $_record->getMeta('type') ?>"
                        data-id="<?php echo $_record->getId() ?>"
                        data-href="<?php echo Url::build('/admin/%s/edit/%d/%d/%d/%d/%s/', array($_record->getMeta('type'), $_record->getId(), $offset, $order, $dir, $layout)) ?>"
                        <?php echo $_record->scaffoldStyle() ?>
                        class="bean bean-<?php echo $_record->getMeta('type') ?> <?php echo (isset($_record->invalid) && $_record->invalid) ? 'invalid' : '' ?>">
                        <!-- table cells of the real bean -->
                        <td>
                            <a
                                class="ir action action-edit"
                                href="<?php echo Url::build('/admin/%s/edit/%d/%d/%d/%d/%s/', array($_record->getMeta('type'), $_record->getId(), $offset, $order, $dir, $layout)) ?>">
                                <?php echo I18n::__('scaffold_action_edit') ?>
                            </a>
                        </td>
                        <td>
                            <input
                                type="checkbox"
                                class="selector"
                                name="selection[<?php echo $_record->getMeta('type') ?>][<?php echo $_record->getId() ?>]"
                                value="1"
                                <?php echo (isset($selection[$_record->getMeta('type')][$_record->getId()]) && $selection[$_record->getMeta('type')][$_record->getId()]) ? 'checked="checked"' : '' ?> />
                        </td>

                        <!-- body attributes -->
                        <?php foreach ($_attributes as $_attribute) :
                        ?>
                            <td
                                class="<?php echo (isset($_attribute['class'])) ? $_attribute['class'] : '' ?>"
                                data-field="<?php echo $_attribute['name'] ?>" data-title="<?php echo (isset($_attribute['label'])) ? $_attribute['label'] : I18n::__($record->getMeta('type') . '_label_' . $_attribute['name']) ?>"><?php
                                                                                                                                                                                                                                        Flight::render('scaffold/table/datavalue', [
                                                                                                                                                                                                                                            'attribute' => $_attribute,
                                                                                                                                                                                                                                            'record' => $_record
                                                                                                                                                                                                                                        ]); ?></td>
                        <?php
                        endforeach ?>
                        <!-- end of body attributes -->

                    </tr>
                <?php endforeach ?>
            </tbody>

        </table>
    </figure>
    <?php if (!$hasRecords) : ?>
        <div class="scaffold-filter-finds-no-records">
            <p><?php echo I18n::__('scaffold_no_records_found_w_filter') ?></p>
        </div>
    <?php endif; ?>
    <!-- End of scaffold table -->
</div>