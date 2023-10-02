<!DOCTYPE html>
<html lang="<?php echo $language ?>" class="no-js">
<head>
    <meta charset="utf-8">
    <title><?php echo $title ?></title>
    <style>
    <?php Flight::render('model/transaction/style/css', ['record' => $record]) ?>
    /* Extra styles are coming in dynamicly, depending on the transaction type */
    <?php echo $record->getContracttype()->css ?>
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
                    <table class="pagefooter legal" width="100%">
                        <tr>
                            <td style="vertical-align: top;">
                                <?php echo Flight::textile(I18n::__('transaction_footer_block_legal')) ?>
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
                <div class="name">
                    <?php echo htmlspecialchars($record->customername) ?>
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
                        <td>&nbsp;<br /><br /></td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo I18n::__('dunning_label_printedon') ?></td>
                        <td class="value"><?php echo $record->localizedDate('dunningprintedon') ?></td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo I18n::__('dunning_label_account') ?></td>
                        <td class="value"><?php echo $record->getPerson()->account ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <?php
    Flight::render('model/transaction/pdf/statement', [
        'record' => $record
    ]);
    ?>

</body>
</html>
