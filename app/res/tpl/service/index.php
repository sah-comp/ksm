<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo I18n::__('service_head_title') ?></h1>
        <nav>
            <?php echo $toolbar ?>
        </nav>
    </header>
    <div class="panel">
        <table class="scaffold service">
            <caption>
                <?php echo I18n::__('scaffold_caption_index', null, array(count($records))) ?>
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
        ?>
                <tr id="bean-<?php echo $_record->getId() ?>">
                    <td>
                        <a
                            class="ir action action-edit"
                        	href="<?php echo Url::build('/admin/%s/edit/%d/?goto=%s', array($_record->getMeta('type'), $_record->getId(), '/service')) ?>">
                            <?php echo I18n::__('scaffold_action_edit') ?>
                        </a>
                    </td>
                    <td>
                        <input
                            id="<?php echo $_type ?>-<?php echo $_id ?>-date"
                            name="date"
                            type="date"
                            class="enpassant"
                            value="<?php echo htmlspecialchars($_record->localizedDate('date')) ?>" />
                    </td>
                    <td>
                        <input
                            id="<?php echo $_type ?>-<?php echo $_id ?>-time"
                            name="time"
                            type="time"
                            class="enpassant"
                            value="<?php echo htmlspecialchars($_record->localizedTime('starttime', '%H:%M')) ?>" />
                    </td>
                    <td class="number">
                        <?php echo htmlspecialchars($_record->localizedDate('date', '%V')) ?>
                    </td>
                    <td>
                        <input
                            id="<?php echo $_type ?>-<?php echo $_id ?>-fix"
                            name="fix"
                            type="checkbox"
                            class="enpassant"
                            value="1"
                            <?php echo ($_record->fix) ? 'checked="checked"' : '' ?> />
                    </td>
                    <td>
                        <?php echo htmlspecialchars($_record->getPerson()->name) ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($_record->getLocation()->name) ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($_record->getMachine()->name) ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($_record->getMachine()->serialnumber) ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($_record->getMachine()->internalnumber) ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($_record->failure) ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($_record->note) ?>
                    </td>
                </tr>
        <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</article>
