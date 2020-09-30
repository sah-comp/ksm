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
<?php
if (! $_installedpart->article) {
    $_installedpart->article = R::dispense('article');
}
?>
<!-- installedpart edit subform -->
<fieldset
    id="machine-<?php echo $record->getId() ?>-owninstalledpart-<?php echo $index ?>">
    <legend class="verbose"><?php echo I18n::__('machine_legend_installedpart') ?></legend>
    <a
    	href="<?php echo Url::build(sprintf('/admin/machine/detach/installedpart/%d', $_installedpart->getId())) ?>"
    	class="ir detach"
    	title="<?php echo I18n::__('scaffold_detach') ?>"
    	data-target="machine-<?php echo $record->getId() ?>-owninstalledpart-<?php echo $index ?>">
    		<?php echo I18n::__('scaffold_detach') ?>
    </a>
    <a
    	href="<?php echo Url::build(sprintf('/admin/machine/attach/own/installedpart/%d', $record->getId())) ?>"
    	class="ir attach"
    	title="<?php echo I18n::__('scaffold_attach') ?>"
    	data-target="machine-<?php echo $record->getId() ?>-installedpart-container">
    		<?php echo I18n::__('scaffold_attach') ?>
    </a>
    <div>
        <input
            type="hidden"
            name="dialog[ownInstalledpart][<?php echo $index ?>][type]"
            value="<?php echo $_installedpart->getMeta('type') ?>" />
        <input
            type="hidden"
            name="dialog[ownInstalledpart][<?php echo $index ?>][id]"
            value="<?php echo $_installedpart->getId() ?>" />
        <input
            type="hidden"
            name="dialog[ownInstalledpart][<?php echo $index ?>][machine_id]"
            value="<?php echo $record->getId() ?>" />
        <input
            type="hidden"
            id="machine-<?php echo $record->getId() ?>-installedpart-<?php echo $index ?>-article_id"
            name="dialog[ownInstalledpart][<?php echo $index ?>][article_id]"
            value="<?php echo $_installedpart->article->getId() ?>" />
    </div>
    <div class="row nomargins">
        <div class="span3">
            &nbsp;
        </div>
        <div class="span3">
            <input
                type="text"
                id="machine-<?php echo $record->getId() ?>-installedpart-<?php echo $index ?>-clairvoyant"
                name="dialog[ownInstalledpart][<?php echo $index ?>][clairvoyant]"
                class="autowidth autocomplete"
                data-source="<?php echo Url::build('/autocomplete/article/number/?callback=?') ?>"
                data-spread='<?php
                    echo json_encode(
    [
                            'machine-' . $record->getId() . '-installedpart-' . $index . '-clairvoyant' => 'value',
                            'machine-' . $record->getId() . '-installedpart-' . $index . '-article_id' => 'id',
                            'machine-' . $record->getId() . '-installedpart-' . $index . '-purchaseprice' => 'purchaseprice',
                            'machine-' . $record->getId() . '-installedpart-' . $index . '-salesprice' => 'salesprice'
                        ]
) ?>'
                value="<?php echo htmlspecialchars(trim($_installedpart->article->number . ' ' . $_installedpart->article->description)) ?>" />
        </div>
        <div class="span2">
            <input
                type="text"
                id="machine-<?php echo $record->getId() ?>-installedpart-<?php echo $index ?>-stamp"
                name="dialog[ownInstalledpart][<?php echo $index ?>][stamp]"
                value="<?php echo htmlspecialchars($_installedpart->stamp) ?>" />
        </div>
        <div class="span2">
            <input
                type="text"
                id="machine-<?php echo $record->getId() ?>-installedpart-<?php echo $index ?>-purchaseprice"
                class="number"
                name="dialog[ownInstalledpart][<?php echo $index ?>][purchaseprice]"
                value="<?php echo htmlspecialchars(number_format((float)$_installedpart->purchaseprice, 2, ',', '.')) ?>" />
        </div>
        <div class="span2">
            <input
                type="text"
                id="machine-<?php echo $record->getId() ?>-installedpart-<?php echo $index ?>-salesprice"
                class="number"
                name="dialog[ownInstalledpart][<?php echo $index ?>][salesprice]"
                value="<?php echo htmlspecialchars(number_format((float)$_installedpart->salesprice, 2, ',', '.')) ?>" />
        </div>
    </div>
</fieldset>
<!-- /installedpart edit subform -->
