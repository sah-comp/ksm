<?php
/**
 * List all appointments
 */
Flight::render('script/datatable_config');
// load our contracts from which the machines are taken
$_appointments = R::find('appointment', "machine_id = ? ORDER BY date", [$record->getId()]);
?>
<table class="datatable">
    <thead>
        <tr>
            <th><?php echo I18n::__('appointment_label_date') ?></th>
            <th><?php echo I18n::__('appointment_label_appointmenttype') ?></th>
            <th><?php echo I18n::__('appointment_label_person') ?></th>
            <th><?php echo I18n::__('appointment_label_location') ?></th>
            <th><?php echo I18n::__('appointment_label_note') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach ($_appointments as $_appointment_id => $_appointment):
        $_appointmenttype = $_appointment->getAppointmenttype();
        $_person = $_appointment->getPerson();
        $_location = $_appointment->getLocation();
    ?>
        <tr>
            <td>
                <a
                    href="<?php echo Url::build('/admin/%s/edit/%d/', [$_appointment->getMeta('type'), $_appointment->getId()]) ?>"
                    class="in-table">
                    <?php echo $_appointment->localizedDate('date') ?>
                </a>
            </td>
            <td>
                <a
                    href="<?php echo Url::build('/admin/%s/edit/%d/', [$_appointmenttype->getMeta('type'), $_appointmenttype->getId()]) ?>"
                    class="in-table">
                    <?php echo $_appointmenttype->name ?>
                </a>
            </td>
            <td>
                <a
                    href="<?php echo Url::build('/admin/%s/edit/%d/', [$_person->getMeta('type'), $_person->getId()]) ?>"
                    class="in-table">
                    <?php echo $_person->name ?>
                </a>
            </td>
            <td>
                <a
                    href="<?php echo Url::build('/admin/%s/edit/%d/', [$_location->getMeta('type'), $_location->getId()]) ?>"
                    class="in-table">
                    <?php echo $_location->name ?>
                </a>
            </td>
            <td>
                <?php echo htmlspecialchars($_appointment->note) ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
