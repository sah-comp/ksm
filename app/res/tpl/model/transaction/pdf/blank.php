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
    <htmlpageheader name="ksmheader-firstpage" style="display: none;">
    </htmlpageheader>
    <htmlpageheader name="ksmheader" style="display: none;">
    </htmlpageheader>
    <htmlpagefooter name="ksmfooter" style="display: none;">
    </htmlpagefooter>
    <sethtmlpageheader name="ksmheader-firstpage" value="on" show-this-page="1" />
    <sethtmlpageheader name="ksmheader" page="ALL" value="on" />
    <sethtmlpagefooter name="ksmfooter" page="ALL" value="on" />
    mpdf-->

    <table width="100%">
        <tr>
            <td style="width: 60mm; vertical-align: top;">
                <div class="senderline">
                    <br /><br />
                </div>
                <div class="name postal">
                    <?php echo Flight::textile($record->postaladdress) ?>
                </div>
            </td>
            <td style="width: 65mm; vertical-align: top;">
                <table class="info" width="100%">
                    <tr>
                        <td>&nbsp;<br /><br /></td>
                    </tr>
                    <tr>
                        <td style="vertical-align: center;" class="label"><?php echo $record->getContracttype()->name ?></td>
                        <td class="value emphasize"><?php echo $record->number ?></td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo I18n::__('transaction_label_bookingdate') ?></td>
                        <td class="value"><?php echo $record->localizedDate('bookingdate') ?></td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo I18n::__('transaction_label_account') ?></td>
                        <td class="value"><?php echo $record->getPerson()->account ?></td>
                    </tr>
                    <tr>
                        <td class="label"><span class="payment-conditions"><?php echo I18n::__('transaction_label_duedate') ?></span></td>
                        <td class="value"><span class="payment-conditions"><?php echo htmlspecialchars($record->localizedDate('duedate')) ?></span></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <?php
    Flight::render('model/transaction/pdf/payload', [
        'record' => $record
    ]);
    ?>

</body>
</html>
