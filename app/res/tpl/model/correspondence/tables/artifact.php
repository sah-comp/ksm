<?php
/**
 * List all artifacts
 */
Flight::render('script/datatable_config');
$_artifacts = R::find('artifact', "correspondence_id = ? ORDER BY name DESC", [$record->getId()]);
?>
<?php if ($record->getId()): ?>
<div class="row <?php echo ($record->hasError('file')) ? 'error' : ''; ?>">
    <label
        for="correspondence-file">
        <?php echo I18n::__('correspondence_label_file') ?>
    </label>
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo Flight::get('max_upload_size') ?>" />
    <input
        id="correspondence-file"
        type="file"
        name="file"
        value="" />
    <p class="info"><?php echo I18n::__('correspondence_info_artifact') ?></p>
</div>
<?php endif; ?>
<table class="datatable">
    <thead>
        <tr>
            <th><?php echo I18n::__('artifact_label_name') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach ($_artifacts as $_artifact_id => $_artifact):
    ?>
        <tr id="correspondence-<?php echo $record->getId() ?>-artifact-<?php echo $_artifact->getId() ?>">
            <td
                data-order="<?php echo $_artifact->name ?>">
                <a
                    href="<?php echo Url::build('/upload/%s', [$_artifact->filename]) ?>"
                    target="_blank"
                    class="in-table float-left">
                    <?php echo htmlspecialchars($_artifact->name) ?>
                </a>
                <a
                    class="ir action action-delete"
                    href="<?php echo Url::build('/admin/artifact/kill/%d', [$_artifact->getId()]) ?>"
                    title="<?php echo I18n::__('action_tooltip_delete') ?>"
                    data-target="correspondence-<?php echo $record->getId() ?>-artifact-<?php echo $_artifact->getId() ?>">
                    <?php echo I18n::__('action_installedpart_delete') ?>
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
