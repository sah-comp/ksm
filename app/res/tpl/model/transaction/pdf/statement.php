<div style="height: 18mm;"></div>

<h1 class="emphasize">
    <?php echo $record->getDunning()->name ?>
</h1>
<?php echo Flight::textile($record->getDunning()->head) ?>

<table class="transaction" width="100%">
    <thead>
        <tr>
            <th class="bb" width="14%"><?php echo I18n::__('dunning_label_number') ?></th>
            <th class="bb" width="10%"><?php echo I18n::__('dunning_label_bookingdate') ?></th>
            <th class="bb" width="10%"><?php echo I18n::__('dunning_label_duedate') ?></th>
            <th class="bb" width="12%"><?php echo I18n::__('dunning_label_level') ?></th>
            <th class="bb" width="10%"><?php echo I18n::__('dunning_label_grace') ?></th>
            <th class="bb number" width="8%"><?php echo I18n::__('dunning_label_fee') ?></th>
            <th class="bb number" width="12%"><?php echo I18n::__('dunning_label_gros') ?></th>
            <th class="bb number" width="12%"><?php echo I18n::__('dunning_label_totalpaid') ?></th>
            <th class="bb number" width="12%"><?php echo I18n::__('dunning_label_balance') ?></th>
        </tr>
    </thead>
    <tfoot>
        <tr class="lofty">
            <td class="bt" colspan="7">&nbsp;</td>
            <td class="bt number"><?php echo I18n::__('dunning_label_total_balance') ?></td>
            <td class="bt number"><?php echo Flight::nformat($totals['totalbalance']) ?></td>
        </tr>
        <tr class="lofty">
            <td class="" colspan="7">&nbsp;</td>
            <td class="bt number"><?php echo I18n::__('dunning_label_total_penaltyfee') ?></td>
            <td class="bt number"><?php echo Flight::nformat($totals['totalfee']) ?></td>
        </tr>
        <tr class="lofty">
            <td class="" colspan="7">&nbsp;</td>
            <td class="bt bb number bold"><?php echo I18n::__('dunning_label_total_payable') ?></td>
            <td class="bt bb number bold"><?php echo Flight::nformat($totals['totalpayable']) ?></td>
        </tr>
    </tfoot>
    <tbody>
<?php foreach ($records as $_id => $_record): ?>
        <tr>
            <td><?php echo htmlspecialchars($_record->number) ?></td>
            <td><?php echo htmlspecialchars($_record->localizedDate('bookingdate')) ?></td>
            <td><?php echo htmlspecialchars($_record->localizedDate('duedate')) ?></td>
            <td><?php echo htmlspecialchars($_record->getDunning()->level) ?></td>
            <td><?php echo htmlspecialchars($_record->localizedDate('dunningdate')) ?></td>
            <td class="number"><?php echo htmlspecialchars($_record->decimal('penaltyfee')) ?></td>
            <td class="number"><?php echo htmlspecialchars($_record->decimal('gros')) ?></td>
            <td class="number"><?php echo htmlspecialchars($_record->decimal('totalpaid')) ?></td>
            <td class="number"><?php echo htmlspecialchars($_record->decimal('balance')) ?></td>
        </tr>
<?php endforeach; ?>
    </tbody>
</table>

<?php echo Flight::textile($record->getDunning()->foot) ?>
