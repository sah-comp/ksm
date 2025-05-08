<?php
/**
 * Person contact beans with contactinfo beans as additional information.
 *
 * This template is used on the service appointment page.
 * @todo unify with model/person/tooltip/contactinfo
 */
$contacts = $record->with("ORDER BY name")->ownContact;
?>
<div
    id="additonal-info-<?php echo $record->getId() ?>"
    class="tooltip open">
    <h1><?php echo htmlspecialchars($record->name ?? '') ?></h1>
    <p class="spacer"><?php echo Flight::textile($record->note) ?></p>
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
    <h2><?php echo I18n::__('person_contact_tab') ?></h2>
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

    <a
        class="ir empty-container"
        data-container="additional-info-container"
        href="#close">
        <?php echo I18n::__('tooltip_close') ?>
    </a>

</div>
