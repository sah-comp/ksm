<?php
/**
 * List all appointments
 */
Flight::render('script/datatable_config');
// load our contracts from which the machines are taken
$_appointments = R::find('appointment', "machine_id = ? ORDER BY date DESC", [$record->getId()]);
?>
<table class="datatable">
    <thead>
        <tr>
            <th><?php echo I18n::__('appointment_label_date') ?></th>
            <th><?php echo I18n::__('appointment_label_appointmenttype') ?></th>
            <th><?php echo I18n::__('appointment_label_person') ?></th>
            <th><?php echo I18n::__('appointment_label_location') ?></th>
            <th><?php echo I18n::__('appointment_label_note') ?></th>
            <th><?php echo I18n::__('appointment_label_transaction') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach ($_appointments as $_appointment_id => $_appointment) :
        $_appointmenttype = $_appointment->getAppointmenttype();
        $_person = $_appointment->getPerson();
        $_location = $_appointment->getLocation();
        $_transaction = $_appointment->getTransaction();
        ?>
        <tr>
            <td
                data-order="<?php echo $_appointment->date ?>">
                <a
                    href="<?php echo Url::build('/admin/%s/edit/%d/', [$_appointment->getMeta('type'), $_appointment->getId()]) ?>"
                    class="in-table">
                    <?php echo htmlspecialchars($_appointment->localizedDate('date')) ?>
                </a>
            </td>
            <td
                data-order="<?php echo $_appointmenttype->name ?>">
                <a
                    href="<?php echo Url::build('/admin/%s/edit/%d/', [$_appointmenttype->getMeta('type'), $_appointmenttype->getId()]) ?>"
                    class="in-table">
                    <?php echo htmlspecialchars($_appointmenttype->name) ?>
                </a>
            </td>
            <td
                data-order="<?php echo $_person->name ?>">
                <a
                    href="<?php echo Url::build('/admin/%s/edit/%d/', [$_person->getMeta('type'), $_person->getId()]) ?>"
                    class="in-table">
                    <?php echo htmlspecialchars($_person->name) ?>
                </a>
            </td>
            <td
                data-order="<?php echo $_location->name ?>">
                <?php echo htmlspecialchars($_location->name) ?>
            </td>
            <td>
                <?php echo htmlspecialchars($_appointment->note) ?>
            </td>
            <td
                data-order="<?php echo $_transaction->number ?>">
                <a
                    href="<?php echo Url::build('/admin/%s/edit/%d/', [$_transaction->getMeta('type'), $_transaction->getId()]) ?>"
                    class="in-table">
                    <?php echo htmlspecialchars($_transaction->number) ?>
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
