<?php
/**
 * Person contact beans with contactinfo beans.
 *
 * This may work as a tooltip (haha, may), it was made for it.
 */
$contacts = $record->with("ORDER BY name")->ownContact;
?>
<a
    class="ir tooltip-open"
    data-tooltip="tooltip-<?php echo $record->getId() ?>"
    href="#tooltip">
    <?php echo I18n::__('tooltip_open') ?>
</a>
<div
    id="tooltip-<?php echo $record->getId() ?>"
    class="tooltip">
    <h1><?php echo htmlspecialchars($record->name ?? '') ?></h1>
    <p class="spacer"><?php echo htmlspecialchars($record->note ?? '') ?></p>
    <table>
        <tbody>
            <tr>
                <td><?php echo I18n::__('person_label_phone') ?></td>
                <td><?php echo htmlspecialchars($record->phone ?? '') ?></td>
            </tr>
            <tr>
                <td><?php echo I18n::__('person_label_phonesec') ?></td>
                <td><?php echo htmlspecialchars($record->phonesec ?? '') ?></td>
            </tr>
            <tr>
                <td><?php echo I18n::__('person_label_email') ?></td>
                <td><?php echo htmlspecialchars($record->email ?? '') ?></td>
            </tr>
            <tr>
                <td><?php echo I18n::__('person_label_billingemail') ?></td>
                <td><?php echo htmlspecialchars($record->billingemail ?? '') ?></td>
            </tr>
            <tr>
                <td><?php echo I18n::__('person_label_dunningemail') ?></td>
                <td><?php echo htmlspecialchars($record->dunningemail ?? '') ?></td>
            </tr>
            <tr>
                <td><?php echo I18n::__('person_label_fax') ?></td>
                <td><?php echo htmlspecialchars($record->fax ?? '') ?></td>
            </tr>
            <tr>
                <td><?php echo I18n::__('person_label_url') ?></td>
                <td><?php echo htmlspecialchars($record->url ?? '') ?></td>
            </tr>
        </tbody>
    </table>
    <table>
        <tbody>
    <?php foreach ($contacts as $_id => $_contact) : ?>
            <tr>
                <td><?php echo htmlspecialchars($_contact->name ?? '') ?><br /><small><?php echo htmlspecialchars($_contact->jobdescription ?? '') ?></small></td>
                <td>
                    <table>
                        <tbody>
                            <?php foreach ($_contact->with("ORDER BY label DESC")->ownContactinfo as $_c_id => $_contactinfo) : ?>
                            <tr>
                                <td><?php echo I18n::__('contactinfo_label_' . $_contactinfo->label) ?></td>
                                <td><?php echo htmlspecialchars($_contactinfo->value ?? '') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </td>
            </tr>
    <?php endforeach; ?>
        </tbody>
    </table>
    <p class="spacer">
        <a
            class="tooltip-close"
            data-tooltip="tooltip-<?php echo $record->getId() ?>"
            href="#close">
            <?php echo I18n::__('tooltip_close') ?>
        </a>
    </p>
</div>
