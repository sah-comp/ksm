<?php
/**
 * revenue table pivoting cost unit types.
 */
?>
<table class="revenue">
    <caption><?php echo I18n::__('revenue_head_title') ?></caption>
    <colgroup>
        <col class="grey" span="5" />
        <?php foreach ($costunittypes as $_id => $_cut): ?>
        <col style="background-color: <?php echo $_cut->color ?>;" span="2" />
        <?php endforeach; ?>
    </colgroup>
    <thead>
        <tr>
            <th style="width: 8rem;"><?php echo I18n::__('revenue_th_date') ?></th>
            <th style="width: 10rem;"><?php echo I18n::__('revenue_th_number') ?></th>
            <th style="width: 12rem;"><?php echo I18n::__('revenue_th_account') ?></th>
            <th class="centered" colspan="2"><?php echo I18n::__('revenue_th_amount') ?></th>
            <?php foreach ($costunittypes as $_id => $_cut): ?>
            <th class="pastel centered" colspan="2" style="background-color: <?php echo $_cut->color ?>;"><?php echo $_cut->name ?></th>
            <?php endforeach; ?>
        </tr>
        <tr style="border-bottom: 1px solid gray;">
            <th colspan="3">&nbsp;</th>
            <th class="number"><?php echo I18n::__('revenue_th_net') ?></th>
            <th class="number"><?php echo I18n::__('revenue_th_gros') ?></th>
            <?php foreach ($costunittypes as $_id => $_cut): ?>
            <th class="pastel number" style="background-color: <?php echo $_cut->color ?>;"><?php echo I18n::__('revenue_th_net') ?></th>
            <th class="pastel number" style="background-color: <?php echo $_cut->color ?>;"><?php echo I18n::__('revenue_th_gros') ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td colspan="3"><?php echo I18n::__('revenue_totals') ?></td>
            <td class="number"><?php echo htmlspecialchars(Flight::nformat($totals['totalnet'])) ?></td>
            <td class="number"><?php echo htmlspecialchars(Flight::nformat($totals['totalgros'])) ?></td>
            <?php foreach ($costunittypes as $_id => $_cut): ?>
                <td class="number"><?php echo htmlspecialchars(Flight::nformat($totals[$_cut->getId()]['totalnet'])) ?></td>
                <td class="number"><?php echo htmlspecialchars(Flight::nformat($totals[$_cut->getId()]['totalgros'])) ?></td>
            <?php endforeach; ?>
        </tr>
    </tfoot>
    <tbody>
        <?php foreach ($records as $_id => $_record): ?>
        <tr <?php echo $_record->scaffoldStyle() ?>>
            <td><?php echo htmlspecialchars($_record->localizedDate('bookingdate')) ?></td>
            <td>
                <a href="<?php echo Url::build('/admin/transaction/edit/%d/', [$_record->getId()]) ?>" title="<?php echo htmlspecialchars($_record->number) ?>" class="in-table"><?php echo htmlspecialchars($_record->number) ?></a>
            </td>
            <td>
                <a href="<?php echo Url::build('/admin/person/edit/%d/', [$_record->getPerson()->getId()]) ?>" title="<?php echo htmlspecialchars($_record->getPerson()->name) ?>" class="in-table"><?php echo htmlspecialchars($_record->getPerson()->name) ?></a>
            </td>
            <td class="number"><?php echo htmlspecialchars($_record->decimal('net')) ?></td>
            <td class="number"><?php echo htmlspecialchars($_record->decimal('gros')) ?></td>
            <?php foreach ($costunittypes as $_id => $_cut): ?>
            <td class="number"><?php echo htmlspecialchars(Flight::nformat($_record->netByCostunit($_cut))) ?></td>
            <td class="number"><?php echo htmlspecialchars(Flight::nformat($_record->grosByCostunit($_cut))) ?></td>
            <?php endforeach; ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
