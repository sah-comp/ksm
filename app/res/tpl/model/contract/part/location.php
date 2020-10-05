<?php
/**
 * Cinnebar.
 *
 * @package Cinnebar
 * @subpackage Template
 * @author $Author$
 * @version $Id$
 */
?>
<!-- location which changes on changing contracted person -->
<label
    for="contract-location">
    <?php echo I18n::__('contract_label_location') ?>
</label>
<select
    id="contract-location"
    name="dialog[location_id]">
    <option value=""><?php echo I18n::__('contract_location_none') ?></option>
    <?php foreach (R::find('location', "ORDER BY name") as $_id => $_location): ?>
    <option
        value="<?php echo $_location->getId() ?>"
        <?php echo ($record->location_id == $_location->getId()) ? 'selected="selected"' : '' ?>><?php echo $_location->name ?>
    </option>
    <?php endforeach ?>
</select>
<!-- /location which changes on changing contracted person -->
