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
			href="<?php echo Url::build("{$base_url}/{$type}/{$layout}/1/{$order}/{$dir}") ?>">
			<?php echo I18n::__('action_list_nav') ?>
		</a>
	</li>
	<li>
		<a
			href="<?php echo Url::build("{$base_url}/{$type}/add/{$layout}/") ?>"
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
