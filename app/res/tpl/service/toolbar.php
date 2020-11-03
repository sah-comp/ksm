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
			href="<?php echo Url::build("/service") ?>">
			<?php echo I18n::__('action_reload_service') ?>
            <span
                id="service-badge-container"
                class="heartbeat badge-container"
                data-container="service-badge-container"
                data-delay="60000"
                data-href="<?php echo Url::build('/service/recheck') ?>"></span>
		</a>
	</li>
	<li>
		<a
			href="<?php echo Url::build("/admin/appointment/add/table/?goto=" . urlencode('/service')) ?>"
			accesskey="+">
			<?php echo I18n::__('action_add_nav') ?>
		</a>
	</li>
    <li>
		<a
			href="<?php echo Url::build("/appointment/pdf") ?>">
			<?php echo I18n::__('appointment_action_pdf') ?>
		</a>
	</li>
</ul>
