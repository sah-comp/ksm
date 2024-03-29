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
    <?php if (isset($goto) && $goto): ?>
    <li>
        <a
            href="<?php echo Url::build($goto) ?>"
            class="volver">
            <?php echo I18n::__('action_return_to_where_you_came_from') ?>
        </a>
    </li>
    <?php endif; ?>
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
    if (isset($record) && $record->hasMenu()):
        Flight::render("model/{$type}/toolbar/items.php", [
            'hasRecords' => $hasRecords,
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
