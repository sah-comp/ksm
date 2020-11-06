<div class="row <?php echo ($record->getContact()->hasError()) ? 'error' : ''; ?>">
    <label
        for="appointment-contact-name">
        <a href="<?php echo Url::build('/admin/%s/edit/%d', [$record->getPerson()->getMeta('type'), $record->getPerson()->getId()]) ?>" class="ir in-form"><?php echo I18n::__('form_link_related') ?></a>
        <?php echo I18n::__('appointment_label_contact') ?>
    </label>
    <select
        id="appointment-contact-name"
        name="dialog[contact_id]">
        <option value=""><?php echo I18n::__('appointment_contact_select') ?></option>
        <?php foreach ($contacts as $_id => $_contact): ?>
        <option
            value="<?php echo $_contact->getId() ?>"
            <?php echo ($record->contact_id == $_contact->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_contact->name . ' ' . $_contact->getContactinfo()) ?>
        </option>
        <?php endforeach ?>
    </select>
</div>

<div class="row <?php echo ($record->getMachine()->hasError()) ? 'error' : ''; ?>">
    <label
        for="appointment-machine-name">
        <a href="<?php echo Url::build('/admin/%s/edit/%d', [$record->getMachine()->getMeta('type'), $record->getMachine()->getId()]) ?>" class="ir in-form"><?php echo I18n::__('form_link_related') ?></a>
        <?php echo I18n::__('appointment_label_machine') ?>
    </label>
    <select
        id="appointment-machine-name"
        class="<?php echo (!$record->machine_id) ? 'set-location-on-change' : ''  ?>"
        data-target="appointment-location-name"
        data-extra="appointment-machine-name"
        data-url="<?php echo Url::build('/appointment/set/location/person/%d/?callback=?', [$person->getId()]) ?>"
        name="dialog[machine_id]">
        <option value=""><?php echo I18n::__('appointment_machine_select') ?></option>
        <?php foreach ($machines as $_id => $_machine): ?>
        <option
            value="<?php echo $_machine->getId() ?>"
            <?php echo ($record->machine_id == $_machine->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_machine->name . ' (' . trim($_machine->serialnumber . ' ' . $_machine->internalnumber) . ')') ?>
        </option>
        <?php endforeach ?>
    </select>
</div>

<div class="row <?php echo ($record->getLocation()->hasError()) ? 'error' : ''; ?>">
    <label
        for="appointment-location-name">
        <a href="<?php echo Url::build('/admin/%s/edit/%d', [$record->getLocation()->getMeta('type'), $record->getLocation()->getId()]) ?>" class="ir in-form"><?php echo I18n::__('form_link_related') ?></a>
        <?php echo I18n::__('appointment_label_location') ?>
    </label>
    <select
        id="appointment-location-name"
        name="dialog[location_id]">
        <option value=""><?php echo I18n::__('appointment_location_select') ?></option>
        <?php foreach ($locations as $_id => $_location): ?>
        <option
            value="<?php echo $_location->getId() ?>"
            <?php echo ($record->location_id == $_location->getId()) ? 'selected="selected"' : '' ?>><?php echo $_location->name ?>
        </option>
        <?php endforeach ?>
    </select>
</div>
