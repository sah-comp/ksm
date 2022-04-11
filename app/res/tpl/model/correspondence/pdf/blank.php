<!DOCTYPE html>
<html lang="<?php echo $language ?>" class="no-js">
<head>
    <meta charset="utf-8">
    <title><?php echo $title ?></title>
    <style>
    <?php Flight::render('model/correspondence/style/css', ['record' => $record]) ?>
    @page :first {
        margin-top: 50mm;
    }
    @page {
        margin-top: 55mm;
    }
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
                <div class="name">
                    <?php echo nl2br($record->getPerson()->name) ?>
                </div>
                <div class="postal">
                    <p>
                        <?php echo nl2br(htmlspecialchars($record->getPerson()->getAddress('billing')->getFormattedAddress())) ?>
                    </p>
                </div>
            </td>
        </tr>
    </table>

    <div style="height: 18mm;"></div>

    <table width="100%">
        <tr>
            <td style="width: 60%; vertical-align: top;">
                <?php echo $record->subject ?>
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
