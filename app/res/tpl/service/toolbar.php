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
			href="<?php echo Url::build("/admin/appointment/add/table/?goto=" . urlencode('/service')) ?>"
			accesskey="+">
			<?php echo I18n::__('action_add_nav') ?>
		</a>
	</li>
	<?php
    if (isset($record) && $record->getId() && $record->hasMenu()):
        Flight::render("model/{$type}/toolbar/items.php", [
            'record' => $record,
            'type' => $type,
            'base_url' => $base_url,
            'layout' => $layout,
            'order' => $order,
            'dir' => $dir
        ]);
    endif;
    ?>
</ul>
