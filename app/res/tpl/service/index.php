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
        id="form-service"
        class="panel"
        method="POST"
        accept-charset="utf-8"
        autocomplete="off"
        enctype="multipart/form-data">

        <input type="hidden" name="token" value="<?php echo Security::getCSRFToken() ?>" />

        <table
            class="scaffold service datatable"
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
                    <th class="date"><?php echo I18n::__('appointment_label_date') ?></th>
                    <th class="time"><?php echo I18n::__('appointment_label_starttime') ?></th>
                    <th class="week number"><?php echo I18n::__('appointment_label_woy') ?></th>
                    <th class="fix"><?php echo I18n::__('appointment_label_fix') ?></th>
                    <th class="receipt"><?php echo I18n::__('appointment_label_receipt') ?></th>
                    <th class="worker"><?php echo I18n::__('appointment_label_worker') ?></th>
                    <th class="duration number"><?php echo I18n::__('appointment_label_duration') ?></th>
                    <th class="person"><?php echo I18n::__('appointment_label_person') ?></th>
                    <th class="contact"><?php echo I18n::__('appointment_label_contact') ?></th>
                    <th class="location"><?php echo I18n::__('appointment_label_location') ?></th>
                    <th class="machinebrand"><?php echo I18n::__('appointment_label_machinebrand') ?></th>
                    <th class="machine"><?php echo I18n::__('appointment_label_machine') ?></th>
                    <th class="machine-serialnumber"><?php echo I18n::__('appointment_label_machine_serialnumber') ?></th>
                    <th class="machine-internalnumber"><?php echo I18n::__('appointment_label_machine_internalnumber') ?></th>
                    <th id="my-notes" class="note"><?php echo I18n::__('appointment_label_note') ?></th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($records as $_id => $_record):
            $_type = $_record->getMeta('type');
            $_person = $_record->getPerson();
            $_contact = $_record->getContact();
            $_machine = $_record->getMachine();
            $_location = $_record->getLocation();
            $_timecheck = $_record->isOverdue();
        ?>
                <tr
                    id="bean-<?php echo $_record->getId() ?>"
                    data-sort="<?php echo $_record->sortorder() ?>"
                    data-href="<?php echo Url::build('/admin/%s/edit/%d/?goto=%s', [$_record->getMeta('type'), $_record->getId(), '/service/#bean-' . $_record->getId()]) ?>"
                    class="<?php echo $_timecheck ?>">
                    <td>
                        <a
                            class="ir action action-edit"
                        	href="<?php echo Url::build('/admin/%s/edit/%d/?goto=%s', [$_record->getMeta('type'), $_record->getId(), '/service/#bean-' . $_record->getId()]) ?>">
                            <?php echo I18n::__('scaffold_action_edit') ?>
                        </a>
                    </td>
                    <td>
                        <a
                        	href="<?php echo Url::build(sprintf('/appointment/completed/%d', $_record->getId())) ?>"
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
                    <td
                        data-filter="<?php echo htmlspecialchars($_record->localizedDate('date')) ?>">
                        <input
                            id="<?php echo $_type ?>-<?php echo $_id ?>-date"
                            name="date"
                            placeholder="<?php echo I18n::__('placeholder_intl_date') ?>"
                            type="date"
                            class="enpassant"
                            data-url="<?php echo Url::build('/enpassant/%s/%d/%s/?callback=?', [$_record->getMeta('type'), $_record->getId(), 'date']) ?>"
                            value="<?php echo htmlspecialchars($_record->date) ?>" />
                    </td>
                    <td
                        data-filter="<?php echo htmlspecialchars($_record->localizedTime('starttime')) ?>">
                        <input
                            id="<?php echo $_type ?>-<?php echo $_id ?>-time"
                            name="time"
                            placeholder="<?php echo I18n::__('placeholder_intl_time') ?>"
                            type="time"
                            class="enpassant"
                            data-url="<?php echo Url::build('/enpassant/%s/%d/%s/?callback=?', [$_record->getMeta('type'), $_record->getId(), 'starttime']) ?>"
                            value="<?php echo htmlspecialchars($_record->starttime) ?>" />
                    </td>
                    <td
                        class="number"
                        id="week-bean-<?php echo $_record->getId() ?>"
                        data-filter="<?php echo htmlspecialchars($_record->localizedDate('date', '%V')) ?>">
                        <?php echo htmlspecialchars($_record->localizedDate('date', '%V')) ?>
                    </td>
                    <td
                        data-filter="<?php echo ($_record->fix) ? 'fix' : '' ?>">
                        <input
                            id="<?php echo $_type ?>-<?php echo $_id ?>-fix"
                            name="fix"
                            type="checkbox"
                            class="enpassant"
                            data-url="<?php echo Url::build('/enpassant/%s/%d/%s/?callback=?', [$_record->getMeta('type'), $_record->getId(), 'fix']) ?>"
                            value="1"
                            <?php echo ($_record->fix) ? 'checked="checked"' : '' ?> />
                    </td>
                    <td
                        class="minor"
                        data-filter="<?php echo htmlspecialchars($_record->localizedDate('receipt')) ?>">
                        <?php echo htmlspecialchars($_record->localizedDate('receipt', '%d.%m.%y')) ?>
                    </td>
                    <td
                        data-filter="<?php echo htmlspecialchars($_record->getUser()->getName()) ?>">
                        <select
                            id="<?php echo $_type ?>-<?php echo $_id ?>-worker"
                            name="worker"
                            class="enpassant autowidth"
                            data-url="<?php echo Url::build('/enpassant/%s/%d/%s/?callback=?', [$_record->getMeta('type'), $_record->getId(), 'user_id']) ?>">
                            <option value=""><?php echo I18n::__('appointment_worker_select') ?></option>
                            <?php foreach ($users as $_user_id => $_user): ?>
                            <option value="<?php echo $_user_id ?>" <?php echo ($_record->user_id == $_user_id) ? 'selected="selected"' : '' ?>><?php echo $_user->getName() ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td
                        data-filter="<?php echo htmlspecialchars($_record->decimal('duration')) ?>">
                        <input
                            id="<?php echo $_type ?>-<?php echo $_id ?>-duration"
                            name="duration"
                            type="text"
                            class="enpassant num"
                            data-url="<?php echo Url::build('/enpassant/%s/%d/%s/?callback=?', [$_record->getMeta('type'), $_record->getId(), 'duration']) ?>"
                            value="<?php echo htmlspecialchars($_record->decimal('duration')) ?>" />
                    </td>
                    <td
                        data-filter="<?php echo htmlspecialchars($_person->name) ?>">
                        <a href="<?php echo Url::build(sprintf('/admin/appointment/additional/%d/contactinfo', $_record->getId())) ?>" class="additional-info ir contactinfo" data-target="additional-info-container">CI</a>
                        <a
                            href="<?php echo Url::build('/admin/%s/edit/%d/?goto=%s', [$_person->getMeta('type'), $_person->getId(), '/service/#bean-' . $_record->getId()]) ?>"
                            title="<?php echo htmlspecialchars($_person->name . ' ' . $_person->account) ?>"
                            class="in-table">
                            <?php echo htmlspecialchars($_person->name) ?>
                        </a>
                    </td>
                    <td
                        data-filter="<?php echo htmlspecialchars($_contact->name) ?>">
                        <a
                            href="<?php echo Url::build('/admin/%s/edit/%d/?goto=%s', [$_person->getMeta('type'), $_person->getId(), '/service/#bean-' . $_record->getId()]) ?>"
                            title="<?php echo htmlspecialchars(trim($_contact->name . ' ' . $_contact->getContactinfo())) ?>"
                            class="in-table">
                            <?php echo htmlspecialchars($_contact->name) ?>
                        </a>
                    </td>
                    <td
                        data-filter="<?php echo htmlspecialchars($_location->name) ?>"
                        class="minor">
                        <?php if ($_location->getId()): ?>
                        <span title="<?php echo htmlspecialchars($_location->name) ?>"><?php echo htmlspecialchars($_location->name) ?></span>
                        <?php else: ?>
                        <span title="<?php echo htmlspecialchars($_person->postalAddressService()) ?>"><?php echo htmlspecialchars($_person->postalAddressService()) ?></span>
                        <?php endif; ?>
                    </td>
                    <td
                        data-filter="<?php echo htmlspecialchars($_machine->machinebrandName()) ?>"
                        class="minor">
                        <span title="<?php echo htmlspecialchars($_machine->machinebrandName()) ?>"><?php echo htmlspecialchars($_machine->machinebrandName()) ?></span>
                    </td>
                    <td
                        data-filter="<?php echo htmlspecialchars($_machine->name) ?>">
                        <a
                            href="<?php echo Url::build('/admin/%s/edit/%d/?goto=%s', [$_machine->getMeta('type'), $_machine->getId(), '/service/#bean-' . $_record->getId()]) ?>"
                            title="<?php echo htmlspecialchars($_machine->name) ?>"
                            class="in-table <?php echo ($_machine->masterdata) ? 'yes-master' : 'no-master' ?>">
                            <?php echo htmlspecialchars($_machine->name) ?>
                        </a>
                    </td>
                    <td
                        data-filter="<?php echo htmlspecialchars($_machine->serialnumber) ?>"
                        class="minor">
                        <span title="<?php echo htmlspecialchars($_machine->serialnumber) ?>"><?php echo htmlspecialchars($_machine->serialnumber) ?></span>
                    </td>
                    <td
                        data-filter="<?php echo htmlspecialchars($_machine->internalnumber) ?>"
                        class="minor">
                        <span title="<?php echo htmlspecialchars($_machine->internalnumber) ?>"><?php echo htmlspecialchars($_machine->internalnumber) ?></span>
                    </td>
                    <td
                        data-filter="<?php echo htmlspecialchars($_record->note) ?>">
                        <input
                            id="<?php echo $_type ?>-<?php echo $_id ?>-note"
                            title="<?php echo htmlspecialchars($_record->note) ?>"
                            name="note"
                            type="text"
                            class="enpassant _blow-me-up"
                            data-url="<?php echo Url::build('/enpassant/%s/%d/%s/?callback=?', [$_record->getMeta('type'), $_record->getId(), 'note']) ?>"
                            value="<?php echo htmlspecialchars($_record->note) ?>" />
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
