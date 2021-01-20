<!DOCTYPE html>
<html lang="<?php echo $language ?>" class="no-js">
<head>
    <meta charset="utf-8">
    <style>
    body {
                font-family: sans-serif;
    	        font-size: 10pt;
            }
            p {
                margin: 0pt 0pt 20pt 0pt;
            }
            .senderline {
                font-size: 6pt;
                border-bottom: 0.1mm solid #000000;
            }
            .name,
            .postal {
                font-size: 11pt;
            }
            .emphasize {
                font-weight: bold;
                font-size: 11pt;
            }
            .uberemphasize {
                font-size: 14pt;
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
            table.stock th,
            table.stock td {
                font-size: 8pt;
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
                <td class="bb moredinky" width="40%" style="text-align: right;"><?php echo I18n::__('treaty_header_info', null, array($record->number)) ?></td>
            </tr>
        </table>
    </htmlpageheader>
    <htmlpagefooter name="ksmfooter" style="display: none;">
        <div class="moredinky centered" style="border-top: 0.1mm solid #000000; padding-top: 3mm;">
            <?php echo I18n::__('treaty_text_page') ?> {PAGENO} <?php echo I18n::__('treaty_text_of') ?> {nbpg}
        </div>
    </htmlpagefooter>
    <sethtmlpageheader name="ksmheader-firstpage" value="on" show-this-page="1" />
    <sethtmlpageheader name="ksmheader" value="on" />
    <sethtmlpagefooter name="ksmfooter" value="on" />
    mpdf-->

    <div style="height: 25mm;"></div>

    <?php echo Flight::textile($text) ?>
</body>
</html>
