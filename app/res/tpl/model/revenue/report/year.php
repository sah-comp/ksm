<?php
/**
 * revenue table pivoting cost unit types.
 */
?>
<table class="revenue">
    <caption><?php echo I18n::__('revenue_head_title_year') ?></caption>
    <colgroup>
        <col class="grey" span="5" />
        <?php foreach ($costunittypes as $_id => $_cut): ?>
        <col style="background-color: <?php echo $_cut->color ?>;" span="2" />
        <?php endforeach; ?>
    </colgroup>
    <thead>
        <tr>
            <th style="width: 30rem;" colspan="3"><?php echo I18n::__('revenue_th_monthname') ?></th>
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
                <td class="number"><?php echo htmlspecialchars(Flight::nformat($totals['cut'][$_cut->getId()]['totalnet'])) ?></td>
                <td class="number"><?php echo htmlspecialchars(Flight::nformat($totals['cut'][$_cut->getId()]['totalgros'])) ?></td>
            <?php endforeach; ?>
        </tr>
    </tfoot>

    <tbody>
        <?php foreach ($months as $month): ?>
        <tr>
            <td colspan="3"><?php echo I18n::__('month_label_' . $month) ?></td>
            <td class="number"><?php echo htmlspecialchars(Flight::nformat($totals['month'][$month]['totalnet'])) ?></td>
            <td class="number"><?php echo htmlspecialchars(Flight::nformat($totals['month'][$month]['totalgros'])) ?></td>
            <?php foreach ($costunittypes as $_id => $_cut): ?>
            <td class="number"><?php echo htmlspecialchars(Flight::nformat($totals['month'][$month][$_cut->getId()]['totalnet'])) ?></td>
            <td class="number"><?php echo htmlspecialchars(Flight::nformat($totals['month'][$month][$_cut->getId()]['totalgros'])) ?></td>
            <?php endforeach; ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
