<?php
/**
 * List all appointments
 */
Flight::render('script/datatable_config');
// load our contracts from which the machines are taken
$_artifacts = R::find('artifact', "machine_id = ? ORDER BY name DESC", [$record->getId()]);
?>
<?php if ($record->getId()): ?>
<div class="row <?php echo ($record->hasError('file')) ? 'error' : ''; ?>">
    <label
        for="machine-file">
        <?php echo I18n::__('machine_label_file') ?>
    </label>
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo Flight::get('max_upload_size') ?>" />
    <input
        id="machine-file"
        type="file"
        name="file"
        value="" />
    <p class="info"><?php echo I18n::__('machine_info_artifact') ?></p>
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
        <tr>
            <td
                data-order="<?php echo $_artifact->name ?>">
                <a
                    href="<?php echo Url::build('/upload/%s', [$_artifact->filename]) ?>"
                    target="_blank"
                    class="in-table">
                    <?php echo htmlspecialchars($_artifact->name) ?>
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>