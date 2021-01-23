<!DOCTYPE html>
<html lang="<?php echo $language ?>" class="no-js">
<head>
    <meta charset="utf-8">
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
    body {
                font-family: sans-serif;
    	        font-size: 10pt;
                width: calc(210mm - 30mm);
                padding: 10mm 15mm;
                margin: 0 auto;
                border-left: 1px solid silver;
                border-right: 1px solid silver;
            }
            input[type=text],
            input[type=password],
            input[type=email],
            input[type=tel],
            input[type=url],
            input[type=number],
            input[type=date],
            input[type=time] {
                width: auto;
                margin: 0.2em 0;
            }
            input[type=submit] {
                margin: 2em;
                float: right;
            }
            p {
                margin: 0pt 0pt 10pt 0pt;
            }
            .senderline {
                font-size: 6pt;
                border-bottom: 0.1mm solid #000000;
            }
            .name,
            .postal,
            .postal-address {
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
            td {
                vertical-align: middle;
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
            table.stock th,
            table.stock td {
                font-size: 8pt;
            }
    </style>
</head>
<body>

    <table width="100%">
        <tr>
            <td class="centered" style="vertical-align: top; width: 100%;">
                <img src="/img/<?php echo Flight::setting()->logo ?>" width="<?php echo Flight::setting()->logowidth ?>px" height="<?php echo Flight::setting()->logoheight ?>px" alt="" />
            </td>
        </tr>
    </table>

    <form
        id="form-treaty"
        method="POST"
        accept-charset="utf-8"
        autocomplete="off"
        enctype="multipart/form-data">

        <input type="hidden" name="token" value="<?php echo Security::getCSRFToken() ?>" />

        <?php echo $text ?>

        <div class="row">
            <input
                type="submit"
                name="submit"
                accesskey="s"
                value="<?php echo I18n::__('treaty_submit') ?>" />
        </div>

    </form>

</body>
</html>
