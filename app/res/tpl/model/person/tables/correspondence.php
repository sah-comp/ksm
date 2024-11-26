<?php
/**
 * List all machines of this person (client)
 */
Flight::render('script/datatable_config');
// load our contracts from which the machines are taken
$_correspondences = R::find('correspondence', "person_id = ? ORDER BY stamp", [$record->getId()]);
?>
<table class="datatable">
    <thead>
        <tr>
            <th><?php echo I18n::__('correspondence_label_writtenon') ?></th>
            <th><?php echo I18n::__('correspondence_label_confidential') ?></th>
            <th><?php echo I18n::__('correspondence_label_subject') ?></th>
            <th><?php echo I18n::__('correspondence_label_payload') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach ($_correspondences as $_correspondence_id => $_correspondence):
    ?>
        <tr>
            <td
                data-order="<?php echo htmlspecialchars($_correspondence->writtenon ?? '') ?>">
                <a
                    href="<?php echo Url::build('/admin/%s/edit/%d/', [$_correspondence->getMeta('type'), $_correspondence->getId()]) ?>"
                    class="in-table">
                    <?php echo htmlspecialchars($_correspondence->localizedDate('writtenon') ?? '') ?>
                </a>
            </td>
            <td
                data-order="<?php echo htmlspecialchars($_correspondence->confidential ?? '') ?>">
                <?php echo htmlspecialchars($_correspondence->confidential ?? '') ?>
            </td>
            <td
                data-order="<?php echo htmlspecialchars($_correspondence->subject ?? '') ?>">
                <?php echo htmlspecialchars($_correspondence->subject ?? '') ?>
            </td>
            <td
                data-order="<?php echo htmlspecialchars($_correspondence->payload ?? '') ?>">
                <?php echo htmlspecialchars($_correspondence->stripHTML($_correspondence->payload) ?? '') ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
