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
<!-- appointment edit subform -->
<fieldset
    id="machine-<?php echo $record->getId() ?>-ownappointment-<?php echo $index ?>">
    <legend class="verbose"><?php echo I18n::__('machine_legend_appointment') ?></legend>
    <div>
        <input
            type="hidden"
            name="dialog[ownAppointment][<?php echo $index ?>][type]"
            value="<?php echo $_appointment->getMeta('type') ?>" />
        <input
            type="hidden"
            name="dialog[ownAppointment][<?php echo $index ?>][id]"
            value="<?php echo $_appointment->getId() ?>" />
    </div>
    <div class="row nomargins">
        <div class="span3">
            &nbsp;
        </div>
        <div class="span2">
            <?php echo htmlspecialchars($_appointment->date) ?>&nbsp;
        </div>
        <div class="span2">
            <?php echo htmlspecialchars($_appointment->getAppointmenttype()->name) ?>&nbsp;
        </div>
        <div class="span2">
            <?php echo htmlspecialchars($_appointment->getPerson()->name) ?>&nbsp;
        </div>
        <div class="span1">
            <?php echo htmlspecialchars($_appointment->getLocation()->name) ?>&nbsp;
        </div>
        <div class="span2">
            <?php echo htmlspecialchars($_appointment->note) ?>&nbsp;
        </div>
    </div>
</fieldset>
<!-- /appointment edit subform -->
