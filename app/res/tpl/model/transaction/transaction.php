<!DOCTYPE html>
<html lang="<?php echo $language ?>" class="no-js">
<head>
    <meta charset="utf-8">
    <title><?php echo $title ?></title>
    <style>
    body, pre {
        font-family: sans-serif;
        font-size: 9pt;
    }
    p, ul, ol {
        margin: 2.5mm 0;
        padding: 0;
    }
    .senderline {
        font-size: 6pt;
        border-bottom: 0.1mm solid #000000;
    }
    tr.alternative th,
    tr.alternative td {
        font-style: italic;
    }
    .name,
    .postal,
    .postal-address {
        font-size: 10pt;
    }
    .emphasize {
        font-weight: bold;
        font-size: 11pt;
    }
    .uberemphasize {
        font-size: 12pt;
        font-weight: bold;
    }
    .dinky {
        font-size: 8pt;
    }
    .moredinky {
        font-size: 6pt;
    }
    .centered {
        text-align: center;
    }
    table {
        border-collapse: collapse;
    }
    th, td {
        vertical-align: top;
    }
    th {
        text-align: left;
    }
    th.bt,
    td.bt {
        border-top: 0.1mm solid #000000;
    }
    th.br,
    td.br {
        border-right: 0.1mm solid #000000;
    }
    th.bb,
    td.bb {
        border-bottom: 0.1mm solid #000000;
    }
    th.number,
    td.number {
        text-align: right;
    }
    table.info td.label,
    table.info td.value {
        text-align: right;
    }
    table.pageheader td.label,
    table.pageheader td.value {
        font-size: 6pt;
    }
    table.pageheader td.label {
        text-align: right;
    }
    table.pageheader td.value {
        text-align: left;
    }
    .page-break {
        page-break-after: always;
    }
    @page {
        header: ksmheader;
        footer: ksmfooter;
    }
    @page :first {
        header: ksmheader-firstpage;
        footer: ksmfooter-firstpage;
    }
    </style>
</head>
<body>
    <!--mpdf
    <htmlpageheader name="ksmheader-firstpage" style="display: none;">
        <table width="100%">
            <tr>
                <td class="centered" style="vertical-align: top; width: 100%;">
                    <img src="/img/<?php echo Flight::setting()->logo ?>" width="<?php echo Flight::setting()->logowidth ?>px" height="<?php echo Flight::setting()->logoheight ?>px" alt="" />
                </td>
            </tr>
        </table>
    </htmlpageheader>
    <htmlpageheader name="ksmheader" style="display: none;">
        <table width="100%">
            <tr>
                <td class="bb moredinky" width="60%" style="text-align: left;"><?php echo htmlspecialchars($company->legalname) ?></td>
                <td class="bb moredinky" width="40%" style="text-align: right;"><?php echo I18n::__('transaction_header_info', null, array($record->number)) ?></td>
            </tr>
        </table>
    </htmlpageheader>
    <htmlpagefooter name="ksmfooter-firstpage" style="display: none;">
        <div>
            <p>first page footer</p>
        </div>
    </htmlpagefooter>
    <htmlpagefooter name="ksmfooter" style="display: none;">
        <div class="moredinky centered" style="border-top: 0.1mm solid #000000; padding-top: 3mm;">
            <?php echo I18n::__('transaction_text_page') ?> {PAGENO} <?php echo I18n::__('transaction_text_of') ?> {nbpg}
        </div>
    </htmlpagefooter>
    <sethtmlpageheader name="ksmheader-firstpage" value="on" show-this-page="1" />
    <sethtmlpageheader name="ksmheader" value="on" />
    <sethtmlpagefooter name="ksmfooter-firstpage" value="on" show-this-page="1" />
    <sethtmlpagefooter name="ksmfooter" value="on" />
    mpdf-->

    <div style="height: 25mm;"></div>
    <table width="100%">
        <tr>
            <td style="width: 95mm; vertical-align: top;">
                <div class="senderline">
                    <?php echo htmlspecialchars($company->getSenderline()) ?>
                    <br /><br />
                </div>
                <div class="name">
                    <?php echo htmlspecialchars($record->getPerson()->name) ?>
                </div>
                <div class="postal">
                    <p>
                        <?php echo nl2br(htmlspecialchars($record->getPerson()->getAddress('billing')->getFormattedAddress())) ?>
                    </p>
                </div>
            </td>
            <td style="width: 65mm; vertical-align: top;">
                <table class="info" width="100%">
                    <tr>
                        <td class="label"><?php echo I18n::__('transaction_label_serial') ?></label>
                        <td class="value emphasize"><?php echo $record->number ?></label>
                    </tr>
                    <tr>
                        <td class="label"><?php echo I18n::__('transaction_label_bookingdate') ?></label>
                        <td class="value"><?php echo $record->localizedDate('bookingdate') ?></label>
                    </tr>
                    <tr>
                        <td class="label"><?php echo I18n::__('person_label_account') ?></label>
                        <td class="value"><?php echo $record->getPerson()->account ?></label>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div style="height: 18mm;"></div>

    <?php echo Flight::textile($record->header) ?>

    <table class="position" width="100%">
        <thead>
            <tr>
                <th width="8%" class="bb"><?php echo I18n::__('position_label_product') ?></th>
                <th width="36%" class="bb"><?php echo I18n::__('position_label_product_desc') ?></th>
                <th width="8%" class="bb number"><?php echo I18n::__('position_label_count') ?></th>
                <th width="8%" class="bb"><?php echo I18n::__('position_label_unit') ?></th>
                <th width="20%" class="bb number"><?php echo I18n::__('position_label_salesprice') ?></th>
                <th width="20%" class="bb number"><?php echo I18n::__('position_label_total') ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="4" class="bt emphasize">&nbsp;</td>
                <td class="bt emphasize number"><?php echo I18n::__('transaction_label_total_net') ?></td>
                <td class="bt emphasize number"><?php echo htmlspecialchars($record->decimal('net', 2)) ?></td>
            </tr>
            <?php $vats = $record->getVatSentences(); ?>
            <?php foreach ($vats as $_id => $_vat): ?>
            <tr>
                <td colspan="4" class="number"></td>
                <td class="bt number"><?php echo I18n::__('transaction_label_vatcode', null, [$_vat['vatpercentage']]) ?></td>
                <td class="bt number"><?php echo htmlspecialchars(number_format($_vat['vatvalue'], 2, ',', '.')) ?></td>
            </tr>
            <?php endforeach ?>
            <tr>
                <td colspan="4" class="">&nbsp;</td>
                <td class="bt bb emphasize number"><?php echo I18n::__('transaction_label_total_gros') ?></td>
                <td class="bt bb emphasize number"><?php echo htmlspecialchars($record->decimal('gros', 2)) ?></td>
            </tr>
        </tfoot>
        <tbody>
            <?php foreach ($record->with(' ORDER BY currentindex ASC ')->ownPosition as $_id => $_position): ?>
            <tr class="<?php echo $_position->isAlternative() ? 'alternative' : '' ?>">
                <td><?php echo htmlspecialchars($_position->getProduct()->number) ?></td>
                <td><?php echo htmlspecialchars($_position->desc) ?></td>
                <td class="number"><?php echo htmlspecialchars($_position->count) ?></td>
                <td><?php echo htmlspecialchars($_position->unit) ?></td>
                <td class="number"><?php echo htmlspecialchars($_position->decimal('salesprice', 2)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_position->decimal('total', 2)) ?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <?php echo Flight::textile($record->footer) ?>

</body>
</html>
