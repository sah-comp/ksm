<?php
/**
 * List all machines of this person (client)
 */
Flight::render('script/datatable_config');
// load our contracts from which the machines are taken
$_contracts = R::find('contract', "person_id = ? ORDER BY @joined.machine.name, @joined.machine.serialnumber, @joined.machine.lastservice", [$record->getId()]);
?>
<table class="datatable">
    <thead>
        <tr>
            <th><?php echo I18n::__('machine_label_machinebrand') ?></th>
            <th><?php echo I18n::__('machine_label_name') ?></th>
            <th><?php echo I18n::__('machine_label_serialnumber') ?></th>
            <th><?php echo I18n::__('machine_label_lastservice') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach ($_contracts as $_contract_id => $_contract):
        $_machine = $_contract->getMachine();
        if (!$_machine->getId()) {
            continue;
        }
        $_machinebrand = $_machine->getMachinebrand();
    ?>
        <tr>
            <td>
                <a
                    href="<?php echo Url::build('/admin/%s/edit/%d/', [$_machinebrand->getMeta('type'), $_machinebrand->getId()]) ?>"
                    class="in-table">
                    <?php echo $_machine->getMachinebrand()->name ?>
                </a>
            </td>
            <td>
                <a
                    href="<?php echo Url::build('/admin/%s/edit/%d/', [$_machine->getMeta('type'), $_machine->getId()]) ?>"
                    class="in-table">
                    <?php echo $_machine->name ?>
                </a>
            </td>
            <td><?php echo $_machine->serialnumber ?></td>
            <td><?php echo $_machine->localizedDate('lastservice') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
