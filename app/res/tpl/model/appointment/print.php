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
            font-size: 8pt;
        }
        th {
            text-align: left;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        td.bt {
            border-top: 0.1mm solid #000000;
        }
        td.br {
            border-right: 0.1mm solid #000000;
        }
        th,
        td {
            vertical-align: top;
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

    </style>
</head>
<body>

    <!--mpdf
    <htmlpageheader name="ksmheader" style="display: none;">
        <table width="100%">
            <tr>
                <td width="60%" style="text-align: left;"><?php echo htmlspecialchars($company_name ?? '') ?></td>
                <td width="40%" style="text-align: right;"><?php echo $pdf_headline ?></td>
            </tr>
        </table>
    </htmlpageheader>
    <htmlpagefooter name="ksmfooter" style="display: none;">
        <div style="border-top: 0.1mm solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm;">
            <?php echo I18n::__('service_text_page') ?> {PAGENO}<?php echo I18n::__('service_text_of') ?> {nbpg}
        </div>
    </htmlpagefooter>
    <sethtmlpageheader name="ksmheader" value="on" show-this-page="1" />
    <sethtmlpagefooter name="ksmfooter" value="on" />
    mpdf-->

    <table width="100%">
        <thead>
            <tr>
                <th width="7%"><?php echo I18n::__('appointment_plabel_date') ?></th>
                <th width="3%"><?php echo I18n::__('appointment_plabel_starttime') ?></th>
                <th width="3%" class="number"><?php echo I18n::__('appointment_plabel_woy') ?></th>
                <th width="3%"><?php echo I18n::__('appointment_plabel_fix') ?></th>
                <th width="5%"><?php echo I18n::__('appointment_plabel_receipt') ?></th>
                <th width="5%"><?php echo I18n::__('appointment_plabel_appointmenttype') ?></th>
                <th width="5%"><?php echo I18n::__('appointment_plabel_worker') ?></th>
                <th width="5%" class="number"><?php echo I18n::__('appointment_plabel_duration') ?></th>
                <th width="10%"><?php echo I18n::__('appointment_plabel_person') ?></th>
                <th width="8%"><?php echo I18n::__('appointment_plabel_location') ?></th>
                <th width="8%"><?php echo I18n::__('appointment_plabel_machinebrand') ?></th>
                <th width="8%"><?php echo I18n::__('appointment_plabel_machine') ?></th>
                <th width="5%"><?php echo I18n::__('appointment_plabel_machine_serialnumber') ?></th>
                <th width="5%"><?php echo I18n::__('appointment_plabel_machine_internalnumber') ?></th>
                <th width="20%"><?php echo I18n::__('appointment_plabel_note') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($records as $_id => $_record):
                $_type            = $_record->getMeta('type');
                $_person          = $_record->getPerson();
                $_machine         = $_record->getMachine();
                $_user            = $_record->getUser();
                $_appointmenttype = $_record->getAppointmenttype();
                $_location        = $_record->getLocation();
                if ($_location->getId()):
                    $_loca_name = $_location->name;
                else:
                    $_loca_name = $_person->postalAddress();
                endif;
                $_timecheck = $_record->isOverdue();
            ?>
			        <tr>
			            <td><?php echo htmlspecialchars($_record->localizedDate('date') ?? '') ?></td>
			            <td><?php echo htmlspecialchars($_record->localizedTime('starttime', 'H:i') ?? '') ?></td>
			            <td class="number"><?php echo htmlspecialchars($_record->localizedDate('date', 'W') ?? '') ?></td>
			            <td><?php echo ($_record->fix) ? I18n::__('bool_true') : '' ?></td>
			            <td><?php echo htmlspecialchars($_record->localizedDate('receipt') ?? '') ?></td>
			            <td><?php echo htmlspecialchars($_appointmenttype->name ?? '') ?></td>
			            <td><?php echo htmlspecialchars($_user->getName() ?? '') ?></td>
			            <td class="number"><?php echo htmlspecialchars($_record->decimal('duration') ?? '') ?></td>
			            <td><?php echo htmlspecialchars($_person->name ?? '') ?></td>
			            <td><?php echo htmlspecialchars($_loca_name ?? '') ?></td>
			            <td><?php echo htmlspecialchars($_machine->machinebrandName() ?? '') ?></td>
			            <td><?php echo htmlspecialchars($_machine->name ?? '') ?></td>
			            <td><?php echo htmlspecialchars($_machine->serialnumber ?? '') ?></td>
			            <td><?php echo htmlspecialchars($_machine->internalnumber ?? '') ?></td>
			            <td><?php echo htmlspecialchars($_record->note ?? '') ?></td>
			        </tr>
			        <?php endforeach;?>
        </tbody>
    </table>

</body>
</html>
