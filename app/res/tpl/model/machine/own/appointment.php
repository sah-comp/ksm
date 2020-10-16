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
            <input
                type="text"
                id="machine-<?php echo $record->getId() ?>-appointment-<?php echo $index ?>-date"
                name="dialog[ownAppointment][<?php echo $index ?>][date]"
                readonly="readonly"
                placeholder="<?php echo I18n::__('placeholder_intl_date') ?>"
                value="<?php echo htmlspecialchars($_appointment->date) ?>" />
        </div>
        <div class="span2">
            <div>
                <input
                    type="hidden"
                    name="dialog[ownAppointment][<?php echo $index ?>][appointmenttype][type]"
                    value="<?php echo $_appointment->getAppointmenttype()->getMeta('type') ?>" />
                <input
                    type="hidden"
                    name="dialog[ownAppointment][<?php echo $index ?>][appointmenttype][id]"
                    value="<?php echo $_appointment->getAppointmenttype()->getId() ?>" />
            </div>
            <input
                type="text"
                id="machine-<?php echo $record->getId() ?>-appointment-<?php echo $index ?>-appointmenttype-name"
                name="dialog[ownAppointment][<?php echo $index ?>][appointmenttype][name]"
                readonly="readonly"
                value="<?php echo htmlspecialchars($_appointment->getAppointmenttype()->name) ?>" />
        </div>
        <div class="span2">
            <div>
                <input
                    type="hidden"
                    name="dialog[ownAppointment][<?php echo $index ?>][person][type]"
                    value="<?php echo $_appointment->getPerson()->getMeta('type') ?>" />
                <input
                    type="hidden"
                    name="dialog[ownAppointment][<?php echo $index ?>][person][id]"
                    value="<?php echo $_appointment->getPerson()->getId() ?>" />
            </div>
            <input
                type="text"
                id="machine-<?php echo $record->getId() ?>-appointment-<?php echo $index ?>-person-name"
                name="dialog[ownAppointment][<?php echo $index ?>][person][name]"
                readonly="readonly"
                value="<?php echo htmlspecialchars($_appointment->getPerson()->name) ?>" />
        </div>
        <div class="span1">
            <div>
                <input
                    type="hidden"
                    name="dialog[ownAppointment][<?php echo $index ?>][location][type]"
                    value="<?php echo $_appointment->getLocation()->getMeta('type') ?>" />
                <input
                    type="hidden"
                    name="dialog[ownAppointment][<?php echo $index ?>][location][id]"
                    value="<?php echo $_appointment->getLocation()->getId() ?>" />
            </div>
            <input
                type="text"
                id="machine-<?php echo $record->getId() ?>-appointment-<?php echo $index ?>-location-name"
                name="dialog[ownAppointment][<?php echo $index ?>][location][name]"
                readonly="readonly"
                value="<?php echo htmlspecialchars($_appointment->getLocation()->name) ?>" />
        </div>
        <div class="span2">
            <input
                type="text"
                id="machine-<?php echo $record->getId() ?>-appointment-<?php echo $index ?>-note"
                name="dialog[ownAppointment][<?php echo $index ?>][note]"
                readonly="readonly"
                value="<?php echo htmlspecialchars($_appointment->note) ?>" />
        </div>
    </div>
</fieldset>
<!-- /appointment edit subform -->
