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
        href="<?php echo Url::build("/{$type}/completed/{$record->getId()}") ?>"
        class="<?php echo($record->isCompleted() ? 'appointment-completed' : 'appointment-incomplete') ?>">
        <?php echo I18n::__('appointment_action_completed') ?>
    </a>
</li>
<?php else: ?>
<li>
    <a
        href="<?php echo Url::build("/appointment/pdf") ?>">
        <?php echo I18n::__('appointment_action_pdf') ?>
    </a>
</li>
<?php endif; ?>
