<?php
/**
 * Output a form with a select that will set a certain attribute in the
 * current filter to allow quickly preselecting certain records.
 *
 * Example: List of transactions and users likes to view only invoice transactions
 * without typing invoice in the filter field.
 *
 */
?>
<form
    id="quickfilter"
    name="quickfilter"
    class="quickfilter"
    method="POST"
    accept-charset="utf-8"
    autocomplete="off"
    enctype="multipart/form-data">

    <input type="hidden" name="token" value="<?php echo Security::getCSRFToken() ?>" />

    <select name="qf_value" class="xsubmitOnChange" onchange="document.createElement('form').submit.call(document.quickfilter)">
        <option value=""><?php echo I18n::__('scaffold_quickfilter_choose_value') ?></option>
        <?php foreach ($record->getQuickFilterValues() as $_id => $_bean): ?>
        <option value="<?php echo $record->getQuickFilterOptionValue($_bean) ?>" <?php echo ($quickfilter_value == $record->getQuickFilterOptionValue($_bean)) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($record->getQuickFilterLabel($_bean)) ?></option>
        <?php endforeach; ?>
    </select>

    <input class="visuallyhidden" type="text" name="submit" value="<?php echo I18n::__('scaffold_quickfilter_submit_refresh') ?>">

</form>
