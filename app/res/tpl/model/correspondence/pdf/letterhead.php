<!DOCTYPE html>
<html lang="<?php echo $language ?>" class="no-js">
<head>
    <meta charset="utf-8">
    <title><?php echo $title ?></title>
    <style>
    <?php Flight::render('model/correspondence/style/css', ['record' => $record]) ?>
    </style>
</head>
<body>
    <!--mpdf
    <htmlpageheader name="ksmheader" style="display: none;">
        <table width="100%">
            <tr>
                <td class="centered" style="vertical-align: top; width: 100%;">
                    <img src="img/<?php echo Flight::setting()->logo ?>" width="<?php echo Flight::setting()->logowidth ?>px" height="<?php echo Flight::setting()->logoheight ?>px" alt="" />
                </td>
            </tr>
        </table>
    </htmlpageheader>
    <htmlpagefooter name="ksmfooter" style="display: none;">
        <table style="width: 100%">
            <tr>
                <td style="vertical-align: top;" width="25%">
                    <table class="pagefooter" width="100%">
                        <tr>
                            <td style="vertical-align: top;">
                                <?php echo Flight::textile(I18n::__('transaction_footer_block_1')) ?>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align: top;" width="25%">
                    <table class="pagefooter" width="100%">
                        <tr>
                            <td style="vertical-align: top;">
                                <?php echo Flight::textile(I18n::__('transaction_footer_block_2')) ?>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align: top;" width="25%">
                    <table class="pagefooter" width="100%">
                        <tr>
                            <td style="vertical-align: top;">
                                <?php echo Flight::textile(I18n::__('transaction_footer_block_3')) ?>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align: top;" width="25%">
                    <table class="pagefooter" width="100%">
                        <tr>
                            <td style="vertical-align: top;">
                                <?php echo Flight::textile(I18n::__('transaction_footer_block_4')) ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="vertical-align: top;" width="100%">
                    <table class="pagefooter pageno" width="100%">
                        <tr>
                            <td style="vertical-align: top;">
                                <?php echo I18n::__('transaction_text_page') ?> {PAGENO} <?php echo I18n::__('transaction_text_of') ?> {nbpg}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </htmlpagefooter>
    <sethtmlpageheader name="ksmheader" page="ALL" value="on" />
    <sethtmlpagefooter name="ksmfooter" page="ALL" value="on" />
    mpdf-->

    <table width="100%">
        <tr>
            <td style="width: 60mm; vertical-align: top;">
                <div class="senderline">
                    <?php echo htmlspecialchars($company->getSenderline()) ?>
                    <br /><br />
                </div>
                <div class="name postal">
                    <?php echo Flight::textile($record->postaladdress) ?>
                </div>
            </td>
        </tr>
    </table>

    <div style="height: 18mm;"></div>

    <table width="100%">
        <tr>
            <td style="width: 60%; vertical-align: top;">
                <b><?php echo $record->subject ?></b>
            </td>
            <td style="width: 40%; vertical-align: top; text-align: right;">
                <?php echo $record->localizedDate('writtenon') ?>
            </td>
        </tr>
    </table>

    <?php
    Flight::render('model/correspondence/pdf/payload', [
        'record' => $record
    ]);
    ?>

</body>
</html>
