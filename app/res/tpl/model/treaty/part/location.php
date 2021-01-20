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
<!-- location which changes on changing treatyed person -->
<label
    for="treaty-location">
    <?php echo I18n::__('treaty_label_location') ?>
</label>
<select
    id="treaty-location"
    name="dialog[location_id]">
    <option value=""><?php echo I18n::__('treaty_location_none') ?></option>
    <?php foreach (R::find('location', "ORDER BY name") as $_id => $_location): ?>
    <option
        value="<?php echo $_location->getId() ?>"
        <?php echo ($record->location_id == $_location->getId()) ? 'selected="selected"' : '' ?>><?php echo $_location->name ?>
    </option>
    <?php endforeach ?>
</select>
<!-- /location which changes on changing treatyed person -->
