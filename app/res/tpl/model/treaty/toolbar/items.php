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
        href="<?php echo Url::build("/{$type}/form/{$record->getId()}") ?>"
        accesskey="f">
        <?php echo I18n::__('treaty_action_form') ?>
    </a>
</li>
<li>
    <a
        href="<?php echo Url::build("/{$type}/pdf/{$record->getId()}") ?>"
        accesskey="p">
        <?php echo I18n::__('treaty_action_pdf') ?>
    </a>
</li>
<?php endif; ?>
