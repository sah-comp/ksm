<?php
/**
 * List all machines of this person (client)
 */
Flight::render('script/datatable_config');
// load our contracts from which the machines are taken
$_transactions = R::find('transaction', "person_id = ? ORDER BY number", [$record->getId()]);
?>
<table class="datatable">
    <thead>
        <tr>
            <th><?php echo I18n::__('transaction_label_contracttype') ?></th>
            <th><?php echo I18n::__('transaction_label_number') ?></th>
            <th><?php echo I18n::__('transaction_label_bookingdate') ?></th>
            <th class="number"><?php echo I18n::__('transaction_label_net') ?></th>
            <th class="number"><?php echo I18n::__('transaction_label_vat') ?></th>
            <th class="number"><?php echo I18n::__('transaction_label_gros') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach ($_transactions as $_transaction_id => $_transaction):
    ?>
        <tr>
            <td
                data-order="<?php echo htmlspecialchars($_transaction->contracttype->name) ?>">
                <?php echo htmlspecialchars($_transaction->contracttype->name) ?>
            </td>
            <td
                data-order="<?php echo htmlspecialchars($_transaction->number) ?>">
                <a
                    href="<?php echo Url::build('/admin/%s/edit/%d/', [$_transaction->getMeta('type'), $_transaction->getId()]) ?>"
                    class="in-table">
                    <?php echo htmlspecialchars($_transaction->number) ?>
                </a>
            </td>
            <td
                data-order="<?php echo htmlspecialchars($_transaction->bookingdate) ?>">
                <?php echo htmlspecialchars($_transaction->localizedDate('bookingdate')) ?>
            </td>
            <td
                class="number"
                data-order="<?php echo htmlspecialchars($_transaction->decimal('net')) ?>">
                <?php echo htmlspecialchars($_transaction->decimal('net')) ?>
            </td>
            <td
                class="number"
                data-order="<?php echo htmlspecialchars($_transaction->decimal('vat')) ?>">
                <?php echo htmlspecialchars($_transaction->decimal('vat')) ?>
            </td>
            <td
                class="number"
                data-order="<?php echo htmlspecialchars($_transaction->decimal('gros')) ?>">
                <?php echo htmlspecialchars($_transaction->decimal('gros')) ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
