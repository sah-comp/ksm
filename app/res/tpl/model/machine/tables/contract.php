<?php
/**
 * List all contracts.
 */
Flight::render('script/datatable_config');
// load our contracts from which the machines are taken
$_contracts = R::find('contract', "machine_id = ? ORDER BY @joined.person.name", [$record->getId()]);
?>
<table class="datatable">
    <thead>
        <tr>
            <th><?php echo I18n::__('contract_label_contracttype') ?></th>
            <th><?php echo I18n::__('contract_label_number') ?></th>
            <th><?php echo I18n::__('contract_label_person') ?></th>
            <th><?php echo I18n::__('contract_label_startdate') ?></th>
            <th><?php echo I18n::__('contract_label_enddate') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach ($_contracts as $_contract_id => $_contract):
        $_contracttype = $_contract->getContracttype();
        $_person = $_contract->getPerson();
        $_location = $_contract->getLocation();
    ?>
        <tr>
            <td
                data-order="<?php echo $_contracttype->name ?>">
                <a
                    href="<?php echo Url::build('/admin/%s/edit/%d/', [$_contracttype->getMeta('type'), $_contracttype->getId()]) ?>"
                    class="in-table">
                    <?php echo htmlspecialchars($_contracttype->name) ?>
                </a>
            </td>
            <td
                data-order="<?php echo $_contract->number ?>">
                <a
                    href="<?php echo Url::build('/admin/%s/edit/%d/', [$_contract->getMeta('type'), $_contract->getId()]) ?>"
                    class="in-table">
                    <?php echo htmlspecialchars($_contract->number) ?>
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
                data-order="<?php echo $_contract->startdate ?>">
                <?php echo $_contract->localizedDate('startdate') ?>
            </td>
            <td
                data-order="<?php echo $_contract->enddate ?>">
                <?php echo $_contract->localizedDate('enddate') ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
