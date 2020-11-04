<div class="row <?php echo ($record->getLocation()->hasError()) ? 'error' : ''; ?>">
    <label
        for="contract-location-name">
        <a href="<?php echo Url::build('/admin/%s/edit/%d', [$record->getLocation()->getMeta('type'), $record->getLocation()->getId()]) ?>" class="ir in-form"><?php echo I18n::__('form_link_related') ?></a>
        <?php echo I18n::__('contract_label_location') ?>
    </label>
    <select
        id="contract-location-name"
        name="dialog[location_id]">
        <option value=""><?php echo I18n::__('contract_location_select') ?></option>
        <?php foreach ($locations as $_id => $_location): ?>
        <option
            value="<?php echo $_location->getId() ?>"
            <?php echo ($record->location_id == $_location->getId()) ? 'selected="selected"' : '' ?>><?php echo $_location->name ?>
        </option>
        <?php endforeach ?>
    </select>
</div>
