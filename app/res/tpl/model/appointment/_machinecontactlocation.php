<div class="row <?php echo ($record->getMachine()->hasError()) ? 'error' : ''; ?>">
    <label
        for="appointment-machine-name">
        <a href="<?php echo Url::build('/admin/%s/edit/%d', [$record->getMachine()->getMeta('type'), $record->getMachine()->getId()]) ?>" class="ir in-form"><?php echo I18n::__('form_link_related') ?></a>
        <?php echo I18n::__('appointment_label_machine') ?>
    </label>
    <input
        type="hidden"
        name="dialog[machine][type]"
        value="machine" />
    <input
        id="appointment-machine-id"
        type="hidden"
        name="dialog[machine][id]"
        value="<?php echo $record->getMachine()->getId() ?>" />
    <input
        type="text"
        id="appointment-machine-name"
        name="dialog[machine][name]"
        class="autocomplete"
        data-source="<?php echo Url::build('/autocomplete/machine/name/?callback=?') ?>"
        data-extra="appointment-person-id"
        data-spread='<?php
            echo json_encode([
                'appointment-machine-name' => 'value',
                'appointment-machine-id' => 'id'
            ]); ?>'
        value="<?php echo htmlspecialchars($record->getMachine()->name) ?>" />
</div>

<div class="row <?php echo ($record->hasError('contact_id')) ? 'error' : ''; ?>">
    <label
        for="appointment-contact-name">
        <?php echo I18n::__('appointment_label_contact') ?>
    </label>
    <input
        type="hidden"
        name="dialog[contact][type]"
        value="contact" />
    <input
        id="appointment-contact-id"
        type="hidden"
        name="dialog[contact][id]"
        value="<?php echo $record->getContact()->getId() ?>" />
    <input
        type="text"
        id="appointment-contact-name"
        name="dialog[contact][name]"
        class="autocomplete"
        data-source="<?php echo Url::build('/autocomplete/contact/name/?callback=?') ?>"
        data-spread='<?php
            echo json_encode([
                'appointment-contact-name' => 'value',
                'appointment-contact-id' => 'id'
            ]); ?>'
        value="<?php echo htmlspecialchars($record->getContact()->name) ?>" />
</div>

<div class="row <?php echo ($record->hasError('location_id')) ? 'error' : ''; ?>">
    <label
        for="appointment-location-name">
        <?php echo I18n::__('appointment_label_location') ?>
    </label>
    <input
        type="hidden"
        name="dialog[location][type]"
        value="location" />
    <input
        id="appointment-location-id"
        type="hidden"
        name="dialog[location][id]"
        value="<?php echo $record->getLocation()->getId() ?>" />
    <input
        type="text"
        id="appointment-location-name"
        name="dialog[location][name]"
        class="autocomplete"
        data-source="<?php echo Url::build('/autocomplete/location/name/?callback=?') ?>"
        data-spread='<?php
            echo json_encode([
                'appointment-location-name' => 'value',
                'appointment-location-id' => 'id'
            ]); ?>'
        value="<?php echo htmlspecialchars($record->getLocation()->name) ?>" />
</div>
