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
        th, td.th {
            text-align: left;
        }
        td.th {
            font-weight: bold;
        }
        td.bt {
            border-top: 0.1mm solid #000000;
        }
        td.bl {
            border-left: 0.1mm solid #000000;
        }
        td.br {
            border-right: 0.1mm solid #000000;
        }
        th,
        td.bb {
            border-bottom: 0.1mm solid #000000;
        }
        th.number,
        td.number,
        td.th.number {
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
                <td width="40%" style="text-align: right;"><?php echo I18n::__('ledger_list_header_info', null, [$record->fy, $record->monthname(), $record->name]) ?></td>
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

    <table class="ledger" width="100%">
        <caption>
            <h1><?php echo htmlspecialchars($record->name) ?></h1>
            <h2><?php echo htmlspecialchars($record->monthname() . ' ' . $record->fy) ?></h2>
        </caption>
        <tbody>
            <tr>
                <td class="" colspan="3">&nbsp;</td>
                <td class="bt bb bl th number uberemphasize" colspan="3"><?php echo I18n::__('ledger_label_cash') ?></td>
                <td class="bt bb br th number uberemphasize" colspan="2"><?php echo htmlspecialchars($record->decimal('cash')) ?></td>
            </tr>
            <tr>
                <td class="th bt bb" width="10%"><?php echo I18n::__('ledgeritem_label_bookingdate') ?></th>
                <td class="th bt bb" width="30%"><?php echo I18n::__('ledgeritem_label_desc') ?></th>
                <td class="th bt bb number" width="10%" ><?php echo I18n::__('ledgeritem_label_taking') ?></th>
                <td class="th bt bb number" width="10%"><?php echo I18n::__('ledgeritem_label_expense') ?></th>
                <td class="th bt bb number" width="10%"><?php echo I18n::__('ledgeritem_label_vat') ?></th>
                <td class="th bt bb number" width="10%"><?php echo I18n::__('ledgeritem_label_vat_taking') ?></th>
                <td class="th bt bb number" width="10%"><?php echo I18n::__('ledgeritem_label_vat_expense') ?></th>
                <td class="th bt bb number" width="10%"><?php echo I18n::__('ledgeritem_label_balance') ?></th>
            </tr>
        </tbody>
        <tbody>
        <?php
        /**
         * Sum up taking, expense grouped by vat sentences
         */
        $vats = [];// array
        ?>
        <?php foreach ($record->with("ORDER BY bookingdate")->ownLedgeritem as $_id => $_ledgeritem):
            if (!isset($vats[$_ledgeritem->vat])) {
                $vats[$_ledgeritem->vat] = [
                    'taking' => 0,
                    'expense' => 0,
                    'vattaking' => 0,
                    'vatexpense' => 0
                ];
            }
            $vats[$_ledgeritem->vat]['taking'] += $_ledgeritem->taking;
            $vats[$_ledgeritem->vat]['expense'] += $_ledgeritem->expense;
            $vats[$_ledgeritem->vat]['vattaking'] += $_ledgeritem->vattaking;
            $vats[$_ledgeritem->vat]['vatexpense'] += $_ledgeritem->vatexpense;
        ?>
            <tr>
                <td><?php echo htmlspecialchars($_ledgeritem->localizedDate('bookingdate')) ?></td>
                <td><?php echo htmlspecialchars($_ledgeritem->desc) ?></td>
                <td class="number"><?php echo htmlspecialchars($_ledgeritem->decimal('taking')) ?></td>
                <td class="number"><?php echo htmlspecialchars($_ledgeritem->decimal('expense')) ?></td>
                <td class="number"><?php echo htmlspecialchars($_ledgeritem->decimal('vat')) ?></td>
                <td class="number"><?php echo htmlspecialchars($_ledgeritem->decimal('vattaking')) ?></td>
                <td class="number"><?php echo htmlspecialchars($_ledgeritem->decimal('vatexpense')) ?></td>
                <td class="number"><?php echo htmlspecialchars($_ledgeritem->decimal('balance')) ?></td>
            </tr>
        <?php endforeach ?>
            <tr>
                <td class="bt bb number" colspan="2"><?php echo I18n::__('ledger_vat_code_title') ?></td>
                <td class="bt bb" colspan="6">&nbsp;</td>

            </tr>
        <?php
            ksort($vats);
            foreach ($vats as $_vatpercentage => $_vat): ?>
            <tr>
                <td class="bt bb">&nbsp;</td>
                <td class="bt bb number"><?php echo I18n::__('ledger_vat_code', null, [Flight::nformat($_vatpercentage, 2, true)]) ?></td>
                <td class="bt bb number"><?php echo Flight::nformat($_vat['taking']) ?></td>
                <td class="bt bb number"><?php echo Flight::nformat($_vat['expense']) ?></td>
                <td class="bt bb">&nbsp;</td>
                <td class="bt bb number"><?php echo Flight::nformat($_vat['vattaking']) ?></td>
                <td class="bt bb number"><?php echo Flight::nformat($_vat['vatexpense']) ?></td>
                <td class="bt bb">&nbsp;</td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td class="bt bb emphasize number" colspan="2"><?php echo I18n::__('ledger_label_totals') ?></td>
            <td class="bt bb emphasize number"><?php echo htmlspecialchars($record->decimal('totaltaking')) ?></td>
            <td class="bt bb emphasize number"><?php echo htmlspecialchars($record->decimal('totalexpense')) ?></td>
            <td class="bt bb">&nbsp;</td>
            <td class="bt bb emphasize number"><?php echo htmlspecialchars($record->decimal('totalvattaking')) ?></td>
            <td class="bt bb emphasize number"><?php echo htmlspecialchars($record->decimal('totalvatexpense')) ?></td>
            <td class="bt bb">&nbsp;</td>
        </tr>
        <tr>
            <td class="" colspan="3">&nbsp;</td>
            <td class="bt bb bl th number uberemphasize" colspan="3"><?php echo I18n::__('ledger_label_balance') ?></td>
            <td class="bt bb br th number uberemphasize" colspan="2"><?php echo htmlspecialchars($record->decimal('balance')) ?></td>
        </tr>
        </tbody>
    </table>

</body>
</html>
