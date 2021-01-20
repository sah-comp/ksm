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
<?php if ($record->getId()): ?>
<li>
    <a
        href="<?php echo Url::build("/admin/appointment/add/table/?machine_id=%d&person_id=%d&location_id=%d", [$record->getMachine()->getId(), $record->getPerson()->getId(), $record->getLocation()->getId()]) ?>">
        <?php echo I18n::__('action_add_appointment_with_me') ?>
    </a>
</li>
<?php endif; ?>
