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
