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
        href="<?php echo Url::build("/{$type}/pdf/{$record->getId()}") ?>"
        accesskey="p">
        <?php echo I18n::__('contract_action_pdf') ?>
    </a>
</li>
