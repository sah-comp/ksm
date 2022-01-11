<div style="height: 18mm;"></div>

<h1 class="emphasize">
    <?php echo $record->getDunning()->name ?>
</h1>
<?php $_colspan_lft = 4 ?>
<?php $_colspan_rtg = 2 ?>
<?php echo Flight::textile($record->getDunning()->head) ?>


<table class="transaction" width="100%">
    <thead>
        <tr>
            <th class="bb" width="10%"><?php echo I18n::__('transaction_label_number') ?></th>
            <th class="bb" width="10%"><?php echo I18n::__('transaction_label_bookingdate') ?></th>
            <th class="bb" width="10%"><?php echo I18n::__('transaction_label_duedate') ?></th>
            <th class="bb" width="40%"><?php echo I18n::__('transaction_label_dunning_level') ?></th>
            <th class="bb number" width="10%"><?php echo I18n::__('transaction_label_gros') ?></th>
            <th class="bb number" width="10%"><?php echo I18n::__('transaction_label_totalpaid') ?></th>
            <th class="bb number" width="10%"><?php echo I18n::__('transaction_label_balance') ?></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td class="bt" colspan="<?php echo $_colspan_lft ?>"><?php echo I18n::__('dunning_label_totals') ?></td>
            <td class="bt number"><?php echo Flight::nformat($totals['totalgros']) ?></td>
            <td class="bt number"><?php echo Flight::nformat($totals['totalpaid']) ?></td>
            <td class="bt number"><?php echo Flight::nformat($totals['totalbalance']) ?></td>
        </tr>
    </tfoot>
    <tbody>
<?php foreach ($records as $_id => $_record): ?>
        <tr>
            <td><?php echo htmlspecialchars($_record->number) ?></td>
            <td><?php echo htmlspecialchars($_record->localizedDate('bookingdate')) ?></td>
            <td><?php echo htmlspecialchars($_record->localizedDate('duedate')) ?></td>
            <td><?php echo htmlspecialchars($_record->getDunning()->name) ?></td>
            <td class="number"><?php echo htmlspecialchars($_record->decimal('gros')) ?></td>
            <td class="number"><?php echo htmlspecialchars($_record->decimal('totalpaid')) ?></td>
            <td class="number"><?php echo htmlspecialchars($_record->decimal('balance')) ?></td>
        </tr>
<?php endforeach; ?>
    </tbody>
</table>

<?php echo Flight::textile($record->getDunning()->foot) ?>
