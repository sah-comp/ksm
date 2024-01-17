<?php
/**
 * File inspector template.
 *
 * Information about a selected file is displayed.
 */
?>
<p>Info about <?php echo htmlspecialchars($record->file) ?></ >
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
            <textarea
                name="dialog[desc]"
                rows="8"
                cols="60"
                placeholder="<?php echo I18n::__('file_text_placeholder_desc') ?>"><?php echo htmlspecialchars($record->desc) ?></textarea>
        </div>
    </fieldset>
    <div class="buttons">
        <input
            id="file-<?php echo $record->getId() ?>-update"
            type="submit"
            name="submit"
            value="<?php echo I18n::__('file_submit') ?>" />            
        <!-- Ajax does not send the submit button value, so we transport with hidden field -->
        <input
            id="file-<?php echo $record->getId() ?>-delete"
            type="hidden"
            name="delete"
            value="0" />
        <input
            type="submit"
            onclick="$('#file-<?php echo $record->getId() ?>-delete').val('1');"
            class="danger"
            name="submit"
            value="<?php echo I18n::__('file_submit_delete') ?>" />
        <!-- End of hidden field to solve missing submit button when ajax(ed) -->
    </div>
</form>
<!--
<table>
    <thead>
        <tr>
            <th>
                Property
            </th>
            <th>
                Value
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                Filesize
            </td>
            <td>
                23.3 MB
            </td>
        </tr>
        <tr>
            <td>
                Erstellt
            </td>
            <td>
                Vorgestern 10:13 Uhr
            </td>
        </tr>
        <tr>
            <td>
                Geändert
            </td>
            <td>
                Gestern 17:11 Uhr
            </td>
        </tr>
        <tr>
            <td>
                Geöffnet
            </td>
            <td>
                Heute 23:19 Uhr
            </td>
        </tr>
        <tr>
            <td>
                Anzahl
            </td>
            <td>
                23
            </td>
        </tr>
        <tr>
            <td>
                Gerät
            </td>
            <td>
                Still FM-X14N, S/N 511908F00032
            </td>
        </tr>
        <tr>
            <td>
                Notiz
            </td>
            <td>
                Abschreibung läuft seit 2009, nachdem das Gerät gebraucht erworben wurde.
            </td>
        </tr>
    </tbody>
</table>
-->