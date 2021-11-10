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
			href="<?php echo Url::build('/revenue/csv') ?>">
			<?php echo I18n::__('revenue_action_csv') ?>
		</a>
	</li>
    <li>
		<a
			href="<?php echo Url::build('/revenue/pdf') ?>">
			<?php echo I18n::__('revenue_action_pdf') ?>
		</a>
	</li>
</ul>
