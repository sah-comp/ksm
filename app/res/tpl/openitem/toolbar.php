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
<ul class="panel-navigation">
    <li>
		<a
			href="<?php echo Url::build("/openitem/index") ?>">
			<?php echo I18n::__('action_list_nav') ?>
		</a>
	</li>
    <?php if ($hasRecords): ?>
    <li>
		<a
			href="<?php echo Url::build("/openitem/pdf") ?>"
			accesskey="p">
			<?php echo I18n::__('openitem_action_pdf') ?>
		</a>
	</li>
    <?php endif; ?>
</ul>
