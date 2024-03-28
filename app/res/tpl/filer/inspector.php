<?php
/**
 * File inspector template.
 *
 * Information about a selected file is displayed.
 */
?>
<h3><?php echo htmlspecialchars($record->filename ?? '') ?></h3>
<form
    id="<?php echo $record->getId() ?>"
    data-container="inspector"
    class="inline"
    method="POST"
    action="<?php echo Url::build('/filer/edit/%d/', array($record->getId())) ?>"
    accept-charset="utf-8"
    enctype="multipart/form-data">
    <div>
        <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
        <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
    </div>
    <fieldset>
        <legend class="verbose"><?php echo I18n::__('file_legend_desc') ?></legend>
        <div class="row">
            <input
                type="hidden"
                name="dialog[template]"
                value="0" />
            <input
                id="file-template"
                type="checkbox"
                name="dialog[template]"
                <?php echo ($record->template) ? 'checked="checked"' : '' ?>
                value="1" />
            <label
                for="file-template"
                class="cb">
                <?php echo I18n::__('file_label_template') ?>
            </label>
        </div>
        <div class="row">
            <label for="file-desc">
                <?php echo I18n::__('file_desc') ?>
            </label>
            <textarea
                id="file-desc"
                name="dialog[desc]"
                rows="8"
                cols="60"
                placeholder="<?php echo I18n::__('file_text_placeholder_desc') ?>"><?php echo htmlspecialchars($record->desc ?? '') ?></textarea>
        </div>
    </fieldset>
    <fieldset>
        <legend class="verbose"><?php echo I18n::__('file_legend_machine') ?></legend>
        <div class="row <?php echo ($record->getMachine()->hasError()) ? 'error' : ''; ?>">
            <label
                for="file-machine-name">
                <a href="<?php echo Url::build('/admin/%s/edit/%d', [$record->getMachine()->getMeta('type'), $record->getMachine()->getId()]) ?>" class="ir in-form"><?php echo I18n::__('form_link_related') ?></a>
                <?php echo I18n::__('file_label_machine') ?>
            </label>
            <input
                type="hidden"
                name="dialog[machine][type]"
                value="machine" />
            <input
                id="file-machine-id"
                type="hidden"
                name="dialog[machine][id]"
                value="<?php echo $record->getMachine()->getId() ?>" />
            <input
                type="text"
                id="file-machine-name"
                name="dialog[machinename]"
                class="autocomplete"
                data-source="<?php echo Url::build('/autocomplete/machine/name/?callback=?') ?>"
                data-extra="file-person-id"
                data-spread='<?php
                    echo json_encode([
                        'file-machine-name' => 'label',
                        'file-machine-id' => 'id'
                    ]); ?>'
                value="<?php echo htmlspecialchars($record->machinename ?? '') ?>" />
        </div>
    </fieldset>
    <fieldset>
        <legend class="verbose"><?php echo I18n::__('file_legend_details') ?></legend>
        <div class="row">
            <label>
                <?php echo I18n::__('file_filemtime') ?>
            </label>
            <input type="datetime" class="" name="dialog[filemtime]" readonly="readonly" value="<?php echo date('d.m.Y H:i:s', strtotime($record->filemtime)) ?>">
        </div>
        <div class="row">
            <label><?php echo I18n::__('file_label_path') ?></label>
            <input type="text" name="path" readonly="readonly" value="<?php echo htmlspecialchars($record->getShortHref() ?? '') ?>">
        </div>
        <div class="row">
            <label>
                <?php echo I18n::__('file_size') ?>
            </label>
            <input type="text" class="number" name="dialog[size]" readonly="readonly" value="<?php echo $record->size ?>">
        </div>
    </fieldset>
    <div class="buttons">
        <input
            id="file-<?php echo $record->getId() ?>-update"
            type="submit"
            name="submit"
            value="<?php echo I18n::__('file_submit') ?>" />            
        <!-- Ajax does not send the submit button value, so we transport with hidden field -->
        <?php if ($permission_delete) : ?>
        <input
            id="file-<?php echo $record->getId() ?>-delete"
            type="hidden"
            name="delete"
            value="0" />
        <input
            type="submit"
            onclick="$('#file-<?php echo $record->getId() ?>').hide(); $('#file-<?php echo $record->getId() ?>-delete').val('1');"
            class="danger confirm"
            name="submit"
            value="<?php echo I18n::__('file_submit_delete') ?>" />
            <!-- End of hidden field to solve missing submit button when ajax(ed) -->
        <?php endif ?>
    </div>
</form>
<script type="text/javascript">
    initAutocompletes();
</script>
