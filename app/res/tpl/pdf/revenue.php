<!DOCTYPE html>
<html lang="<?php echo $language ?>" class="no-js">
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
	        font-size: 8pt;
        }
        .emphasize {
            font-weight: bold;
            font-size: 9pt;
        }
        .uberemphasize {
            font-size: 11pt;
            font-weight: bold;
        }
        table {
            border-collapse: collapse;
        }
        caption {
            font-weight: bold;
            padding-bottom: 3mm;
        }
        td {
            vertical-align: top;
            white-space: nowrap;
        }
        th {
            text-align: left;
        }
        td.bt {
            border-top: 0.1mm solid #000000;
        }
        td.br {
            border-right: 0.1mm solid #000000;
        }
        th,
        td.bb {
            border-bottom: 0.1mm solid #000000;
        }
        th.number,
        td.number {
            text-align: right;
        }
    </style>
</head>
<body>
    <!--mpdf
    <htmlpageheader name="ksmheader" style="display: none;">
        <table width="100%">
            <tr>
                <td width="60%" style="text-align: left;"><?php echo htmlspecialchars($company_name) ?></td>
                <td width="40%" style="text-align: right;"><?php echo $pdf_headline ?></td>
            </tr>
        </table>
    </htmlpageheader>
    <htmlpagefooter name="ksmfooter" style="display: none;">
        <div style="border-top: 0.1mm solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm;">
            <?php echo I18n::__('transaction_text_page') ?> {PAGENO} <?php echo I18n::__('transaction_text_of') ?> {nbpg}
        </div>
    </htmlpagefooter>
    <sethtmlpageheader name="ksmheader" value="on" show-this-page="1" />
    <sethtmlpagefooter name="ksmfooter" value="on" />
    mpdf-->

    <table class="revenue" style="width: 100%;">
        <caption><?php echo I18n::__('revenue_head_title') ?></caption>
        <colgroup>
            <col class="grey" span="5" />
            <?php foreach ($costunittypes as $_id => $_cut): ?>
            <col style="background-color: <?php echo $_cut->color ?>;" span="2" />
            <?php endforeach; ?>
        </colgroup>
        <thead>
            <tr>
                <th class="bt" style="width: 8%;"><?php echo I18n::__('revenue_th_date') ?></th>
                <th class="bt" style="width: 8%;"><?php echo I18n::__('revenue_th_number') ?></th>
                <th class="bt" style="width: 23%;"><?php echo I18n::__('revenue_th_account') ?></th>
                <th class="bt" class="centered" colspan="2" style="text-align: center;"><?php echo I18n::__('revenue_th_amount') ?></th>
                <?php foreach ($costunittypes as $_id => $_cut): ?>
                <th class="bt pastel centered" colspan="2" style="text-align: center; background-color: <?php echo $_cut->color ?>;"><?php echo $_cut->name ?></th>
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
                <td class="bt bb emphasize" colspan="3"><?php echo I18n::__('revenue_totals') ?></td>
                <td class="bt bb emphasize number"><?php echo htmlspecialchars(Flight::nformat($totals['totalnet'])) ?></td>
                <td class="bt bb emphasize number"><?php echo htmlspecialchars(Flight::nformat($totals['totalgros'])) ?></td>
                <?php foreach ($costunittypes as $_id => $_cut): ?>
                    <td class="bt bb emphasize number"><?php echo htmlspecialchars(Flight::nformat($totals[$_cut->getId()]['totalnet'])) ?></td>
                    <td class="bt bb emphasize number"><?php echo htmlspecialchars(Flight::nformat($totals[$_cut->getId()]['totalgros'])) ?></td>
                <?php endforeach; ?>
            </tr>
        </tfoot>
        <tbody>
            <?php foreach ($records as $_id => $_record): ?>
            <tr>
                <td><?php echo htmlspecialchars($_record->localizedDate('bookingdate')) ?></td>
                <td><?php echo htmlspecialchars($_record->number) ?></td>
                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo htmlspecialchars($_record->getPerson()->name) ?></td>
                <td class="number"><?php echo htmlspecialchars($_record->decimal('net')) ?></td>
                <td class="number"><?php echo htmlspecialchars($_record->decimal('gros')) ?></td>
                <?php foreach ($costunittypes as $_id => $_cut): ?>
                <td class="number" style="background-color: <?php echo $_cut->color ?>;"><?php echo htmlspecialchars(Flight::nformat($_record->netByCostunit($_cut))) ?></td>
                <td class="number" style="background-color: <?php echo $_cut->color ?>;"><?php echo htmlspecialchars(Flight::nformat($_record->grosByCostunit($_cut))) ?></td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
