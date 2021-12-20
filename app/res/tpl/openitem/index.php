<?php
Flight::render('script/datatable_config');
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
                    <th><?php echo I18n::__('openitem_th_number') ?></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($records as $_id => $_record):
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
                            href="<?php echo Url::build(sprintf('/openitem/paid/%d', $_record->getId())) ?>"
                            class="ir action action-finish finish"
                            title="<?php echo I18n::__('scaffold_action_finish') ?>"
                            data-target="bean-<?php echo $_record->getId() ?>">
                            <?php echo I18n::__('scaffold_action_finish') ?>
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
