<!DOCTYPE html>
<html lang="<?php echo $language ?>" class="no-js">
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
	        font-size: 9pt;
        }
        .emphasize {
            font-weight: bold;
            font-size: 10pt;
        }
        .uberemphasize {
            font-size: 12pt;
            font-weight: bold;
        }
        table {
            border-collapse: collapse;
        }
        caption {
            font-weight: bold;
            padding-bottom: 3mm;
        }
        tr.invoice-kind-1 td {
            color: #666;
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
                <td width="60%" style="text-align: left;"><?php echo htmlspecialchars($company->legalname) ?></td>
                <td width="40%" style="text-align: right;"><?php echo I18n::__('transaction_list_header_info') ?></td>
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

    <table class="transaction" width="100%">
        <thead>
            <tr>
                <th width="10%"><?php echo I18n::__('transaction_label_number') ?></th>
                <th width="10%"><?php echo I18n::__('transaction_label_contracttype') ?></th>
                <th width="10%"><?php echo I18n::__('transaction_label_bookingdate') ?></th>
                <th width="40%"><?php echo I18n::__('transaction_label_account') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('transaction_label_net') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('transaction_label_vat') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('transaction_label_gros') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($records as $_id => $_record): ?>
            <tr>
                <td><?php echo htmlspecialchars($_record->number) ?></td>
                <td><?php echo htmlspecialchars($_record->getContracttype()->name) ?></td>
                <td><?php echo htmlspecialchars($_record->localizedDate('bookingdate')) ?></td>
                <td><?php echo htmlspecialchars($_record->getPerson()->name) ?></td>
                <td class="number"><?php echo htmlspecialchars($_record->decimal('net')) ?></td>
                <td class="number"><?php echo htmlspecialchars($_record->decimal('vat')) ?></td>
                <td class="number"><?php echo htmlspecialchars($_record->decimal('gros')) ?></td>
            </tr>
        <?php endforeach ?>
            <tr>
                <td class="bt bb emphasize" colspan="4"><?php echo I18n::__('transaction_label_totals') ?></td>
                <td class="bb bt number emphasize"><?php echo htmlspecialchars(Flight::nformat($totals['totalnet'])) ?></td>
                <td class="bb bt number emphasize"><?php echo htmlspecialchars(Flight::nformat($totals['totalvat'])) ?></td>
                <td class="bb bt number emphasize"><?php echo htmlspecialchars(Flight::nformat($totals['totalgros'])) ?></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
