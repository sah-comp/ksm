<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo $title ?></h1>
        <nav>
            <?php echo $toolbar ?>
        </nav>
    </header>
    <div class="panel">
        <table class="scaffold service">
            <caption>
                <?php echo I18n::__('scaffold_caption_index', null, [count($records)]) ?>
            </caption>
            <thead>
                <tr>
                    <th class="edit">&nbsp;</th>
                    <th class="date"><?php echo I18n::__('appointment_label_date') ?></th>
                    <th class="time"><?php echo I18n::__('appointment_label_starttime') ?></th>
                    <th class="week number"><?php echo I18n::__('appointment_label_woy') ?></th>
                    <th class="fix"><?php echo I18n::__('appointment_label_fix') ?></th>
                    <th class="person"><?php echo I18n::__('appointment_label_person') ?></th>
                    <th class="location"><?php echo I18n::__('appointment_label_location') ?></th>
                    <th class="machine"><?php echo I18n::__('appointment_label_machine') ?></th>
                    <th class="machine-serialnumber"><?php echo I18n::__('appointment_label_machine_serialnumber') ?></th>
                    <th class="machine-internalnumber"><?php echo I18n::__('appointment_label_machine_internalnumber') ?></th>
                    <th class="failure"><?php echo I18n::__('appointment_label_failure') ?></th>
                    <th class="note"><?php echo I18n::__('appointment_label_note') ?></th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($records as $_id => $_record):
            $_type = $_record->getMeta('type');
            $_person = $_record->getPerson();
            $_machine = $_record->getMachine();
            $_location = $_record->getLocation();
        ?>
                <tr
                    id="bean-<?php echo $_record->getId() ?>"
                    data-sort="<?php echo $_record->sortorder() ?>">
                    <td>
                        <a
                            class="ir action action-edit"
                        	href="<?php echo Url::build('/admin/%s/edit/%d/?goto=%s', [$_record->getMeta('type'), $_record->getId(), '/service/#bean-' . $_record->getId()]) ?>">
                            <?php echo I18n::__('scaffold_action_edit') ?>
                        </a>
                    </td>
                    <td>
                        <input
                            id="<?php echo $_type ?>-<?php echo $_id ?>-date"
                            name="date"
                            placeholder="<?php echo I18n::__('placeholder_intl_date') ?>"
                            type="date"
                            class="enpassant"
                            required="required"
                            data-url="<?php echo Url::build('/enpassant/%s/%d/%s/?callback=?', [$_record->getMeta('type'), $_record->getId(), 'date']) ?>"
                            value="<?php echo htmlspecialchars($_record->date) ?>" />
                    </td>
                    <td>
                        <input
                            id="<?php echo $_type ?>-<?php echo $_id ?>-time"
                            name="time"
                            placeholder="<?php echo I18n::__('placeholder_intl_time') ?>"
                            type="time"
                            class="enpassant"
                            data-url="<?php echo Url::build('/enpassant/%s/%d/%s/?callback=?', [$_record->getMeta('type'), $_record->getId(), 'starttime']) ?>"
                            value="<?php echo htmlspecialchars($_record->starttime) ?>" />
                    </td>
                    <td class="number" id="week-bean-<?php echo $_record->getId() ?>">
                        <?php echo htmlspecialchars($_record->localizedDate('date', '%V')) ?>
                    </td>
                    <td>
                        <input
                            id="<?php echo $_type ?>-<?php echo $_id ?>-fix"
                            name="fix"
                            type="checkbox"
                            class="enpassant"
                            data-url="<?php echo Url::build('/enpassant/%s/%d/%s/?callback=?', [$_record->getMeta('type'), $_record->getId(), 'fix']) ?>"
                            value="1"
                            <?php echo ($_record->fix) ? 'checked="checked"' : '' ?> />
                    </td>
                    <td>
                        <a
                            href="<?php echo Url::build('/admin/%s/edit/%d/?goto=%s', [$_person->getMeta('type'), $_person->getId(), '/service/#bean-' . $_record->getId()]) ?>"
                            class="in-table">
                            <?php echo htmlspecialchars($_person->name) ?>
                        </a>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($_location->name) ?>
                    </td>
                    <td>
                        <a
                            href="<?php echo Url::build('/admin/%s/edit/%d/?goto=%s', [$_machine->getMeta('type'), $_machine->getId(), '/service/#bean-' . $_record->getId()]) ?>"
                            class="in-table">
                            <?php echo htmlspecialchars($_machine->name) ?>
                        </a>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($_machine->serialnumber) ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($_machine->internalnumber) ?>
                    </td>
                    <td>
                        <input
                            id="<?php echo $_type ?>-<?php echo $_id ?>-failure"
                            name="failure"
                            type="text"
                            class="enpassant"
                            data-url="<?php echo Url::build('/enpassant/%s/%d/%s/?callback=?', [$_record->getMeta('type'), $_record->getId(), 'failure']) ?>"
                            value="<?php echo htmlspecialchars($_record->failure) ?>" />
                    </td>
                    <td>
                        <input
                            id="<?php echo $_type ?>-<?php echo $_id ?>-note"
                            name="note"
                            type="text"
                            class="enpassant"
                            data-url="<?php echo Url::build('/enpassant/%s/%d/%s/?callback=?', [$_record->getMeta('type'), $_record->getId(), 'note']) ?>"
                            value="<?php echo htmlspecialchars($_record->note) ?>" />
                    </td>
                </tr>
        <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</article>
