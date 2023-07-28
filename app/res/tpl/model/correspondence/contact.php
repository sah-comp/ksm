<div class="row <?php echo ($record->getContact()->hasError()) ? 'error' : ''; ?>">
    <label
        for="correspondence-contact-name">
        <a href="<?php echo Url::build('/admin/%s/edit/%d', [$record->getPerson()->getMeta('type'), $record->getPerson()->getId()]) ?>" class="ir in-form"><?php echo I18n::__('form_link_related') ?></a>
        <?php echo I18n::__('correspondence_label_contact') ?>
    </label>
    <select
        id="correspondence-contact-name"
        name="dialog[contact_id]">
        <?php if ($record->getPerson()->hasEmail()) : ?>
        <option value=""><?php echo htmlspecialchars($record->getPerson()->name . ' ' . $record->getPerson()->email) ?></option>
        <?php else : ?>
        <option value=""><?php echo I18n::__('correspondence_contact_select') ?></option>
        <?php endif; ?>
        <?php foreach ($contacts as $_id => $_contact) : ?>
        <option
            value="<?php echo $_contact->getId() ?>"
            <?php echo ($record->contact_id == $_contact->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_contact->name . ' ' . $_contact->getContactinfo()) ?>
        </option>
        <?php endforeach ?>
    </select>
</div>
