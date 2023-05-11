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
<li>
    <a
        href="<?php echo Url::build("/admin/contract/add/table/?machine_id=%d", [$record->getId()]) ?>">
        <?php echo I18n::__('action_add_contract_with_me') ?>
    </a>
</li>
<?php
if ($record->countOwn('contract') == 1) {
    $_contract = reset($record->ownContract);
    ?>
<li>
    <a href="<?php echo Url::build("/admin/appointment/add/table/?machine_id=%d&person_id=%d", [$record->getId(), $_contract->person->getId()]) ?>"><?php echo I18n::__('action_add_appointment_with_me') ?></a>
</li>
    <?php
}
?>
