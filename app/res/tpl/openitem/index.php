<?php
Flight::render('script/datatable_config');
$_colspan = 8;
?>
<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo $title ?></h1>
        <nav>
            <?php echo $toolbar ?>
        </nav>
    </header>

    <form
        id="form-openitem"
        class="panel"
        method="POST"
        accept-charset="utf-8"
        autocomplete="off"
        enctype="multipart/form-data">

        <input type="hidden" name="token" value="<?php echo Security::getCSRFToken() ?>" />

        <table
            class="scaffold openitem datatable"
            data-ordering="false">
            <caption>
                <?php echo I18n::__('scaffold_caption_index', null, [count($records)]) ?>
            </caption>
            <thead>
                <tr>
                    <th class="edit">&nbsp;</th>
                    <th class="edit">&nbsp;</th>
                    <th class="switch">
                        <input
                            class="all"
                            type="checkbox"
                            name="void"
                            value="1"
                            title="<?php echo I18n::__('scaffold_select_all') ?>" />
                    </th>
                    <th class="transaction-number"><?php echo I18n::__('openitem_th_number') ?></th>
                    <th class="date"><?php echo I18n::__('openitem_th_bookingdate') ?></th>
                    <th class="date"><?php echo I18n::__('openitem_th_duedate') ?></th>
                    <th class="duedays"><?php echo I18n::__('openitem_th_overdueindays') ?></th>
                    <th class="person"><?php echo I18n::__('openitem_th_person') ?></th>
                    <th class="gros number"><?php echo I18n::__('openitem_th_gros') ?></th>
                    <th class="paid number"><?php echo I18n::__('openitem_th_paid') ?></th>
                    <th class="balance number"><?php echo I18n::__('openitem_th_balance') ?></th>
                    <th class="dunninglevel"><?php echo I18n::__('openitem_th_dunninglevel') ?></th>
                    <th class="accumulate"><?php echo I18n::__('openitem_th_accumulate') ?></th>
                    <th class="pdf">&nbsp;</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="<?php echo $_colspan ?>" class="tar"><?php echo I18n::__('openitem_label_sums') ?></td>
                    <td class="number"><?php echo htmlspecialchars(Flight::nformat($totals['totalgros'])) ?></td>
                    <td class="number"><?php echo htmlspecialchars(Flight::nformat($totals['totalpaid'])) ?></td>
                    <td class="number"><?php echo htmlspecialchars(Flight::nformat($totals['totalbalance'])) ?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </tfoot>
            <tbody>
            <?php foreach ($records as $_id => $_record):
                $_type = $_record->getMeta('type');
            ?>
                <tr
                    id="bean-<?php echo $_record->getId() ?>">
                    <td>
                        <a
                            class="ir action action-edit"
                            href="<?php echo Url::build('/admin/%s/edit/%d/?goto=%s', [$_record->getMeta('type'), $_record->getId(), '/openitem/#bean-' . $_record->getId()]) ?>">
                            <?php echo I18n::__('scaffold_action_edit') ?>
                        </a>
                    </td>
                    <td>
                        <a
                            href="<?php echo Url::build(sprintf('/transaction/bookaspaid/%d', $_record->getId())) ?>"
                            class="ir action action-finish finish"
                            title="<?php echo I18n::__('transaction_action_pay') ?>"
                            data-target="bean-<?php echo $_record->getId() ?>">
                            <?php echo I18n::__('transaction_action_pay') ?>
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
                    <td><?php echo htmlspecialchars($_record->number) ?></td>
                    <td><?php echo htmlspecialchars($_record->localizedDate('bookingdate')) ?></td>
                    <td><?php echo htmlspecialchars($_record->localizedDate('duedate')) ?></td>
                    <td class="duedays"><?php echo htmlspecialchars($_record->getOverdueDays()) ?></td>
                    <td><?php echo htmlspecialchars($_record->getPerson()->name) ?></td>
                    <td class="number"><?php echo htmlspecialchars($_record->decimal('gros')) ?></td>
                    <td class="number"><?php echo htmlspecialchars($_record->decimal('totalpaid')) ?></td>
                    <td class="number"><?php echo htmlspecialchars($_record->decimal('balance')) ?></td>
                    <td>
                        <select
                            id="<?php echo $_type ?>-<?php echo $_id ?>-dunning-id"
                            data-url="<?php echo Url::build('/enpassant/%s/%d/%s/?callback=?', [$_type, $_id, 'dunning_id']) ?>"
                            class="enpassant autowidth"
                            name="dunning_id">
                            <option value=""><?php echo I18n::__('transaction_dunning_none') ?></option>
                            <?php foreach ($_record->getDunnings() as $_id_level => $_level): ?>
                            <option value="<?php echo $_id_level ?>" <?php echo $_record->dunning_id == $_id_level ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_level->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <input
                            id="<?php echo $_type ?>-<?php echo $_id ?>-accumulate"
                            name="accumulate"
                            type="checkbox"
                            class="enpassant"
                            title="<?php echo I18n::__('transaction_title_accumulate') ?>"
                            data-url="<?php echo Url::build('/enpassant/%s/%d/%s/?callback=?', [$_record->getMeta('type'), $_record->getId(), 'accumulate']) ?>"
                            value="1"
                            <?php echo ($_record->accumulate) ? 'checked="checked"' : '' ?> />
                    </td>
                    <td>
                        PDF
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="buttons">
            <select name="next_action">
                <?php foreach ($actions[$current_action] as $action): ?>
                <option
                    value="<?php echo $action ?>"><?php echo I18n::__("action_{$action}_select") ?></option>
                <?php endforeach ?>
            </select>
            <input
                type="submit"
                name="submit"
                accesskey="s"
                value="<?php echo I18n::__('scaffold_submit_apply_action') ?>" />
        </div>
    </form>

</article>
