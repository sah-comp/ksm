<?php
/**
 * Template to select a billing email address for a transaction.
 */
?>
<select
    id="transaction-billingemail"
    name="dialog[billingemail]">
    <option value=""><?php echo I18n::__('transaction_billingemail_select') ?></option>
    <?php if ($record->getPerson()->billingemail) : ?>
    <option value="<?php echo htmlspecialchars($record->getPerson()->billingemail) ?>" <?php echo ($record->billingemail == $record->getPerson()->billingemail) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($record->getPerson()->billingemail) ?></option>
    <?php endif; ?>
    <?php foreach ($contacts as $_id => $_contact) : ?>
    <option
        value="<?php echo $_contact->getEmailaddress() ?>"
        <?php echo ($record->billingemail == $_contact->getEmailaddress()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_contact->name . ' ' . $_contact->getContactinfo()) ?>
    </option>
    <?php endforeach ?>
</select>