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
        href="<?php echo Url::build("/admin/contract/add/table/?person_id=%d", [$record->getId()]) ?>">
        <?php echo I18n::__('action_add_contract_with_me') ?>
    </a>
</li>
<li>
    <a
        href="<?php echo Url::build("/admin/appointment/add/table/?person_id=%d", [$record->getId()]) ?>">
        <?php echo I18n::__('action_add_appointment_with_me') ?>
    </a>
</li>
