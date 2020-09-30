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
<!-- installedpart edit subform -->
<fieldset
    id="article-<?php echo $record->getId() ?>-owninstalledpart-<?php echo $index ?>">
    <legend class="verbose"><?php echo I18n::__('article_legend_installedpart') ?></legend>
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
            name="dialog[ownInstalledpart][<?php echo $index ?>][article_id]"
            value="<?php echo $record->getId() ?>" />
    </div>
    <div class="row nomargins">
        <div class="span3">
            &nbsp;
        </div>
        <div class="span3">
            <select
                id="article-<?php echo $record->getId() ?>-installedpart-<?php echo $index ?>-label"
                name="dialog[ownInstalledpart][<?php echo $index ?>][machine_id]">
                <?php foreach (R::findAll('machine') as $_id => $_machine): ?>
                <option
                    value="<?php echo $_machine->getId() ?>"
                    <?php echo ($_installedpart->machine_id == $_machine->getId()) ? 'selected="selected"' : '' ?>><?php echo $_machine->name ?>
                </option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="span2">
            <input
                type="text"
                id="article-<?php echo $record->getId() ?>-installedpart-<?php echo $index ?>-stamp"
                name="dialog[ownInstalledpart][<?php echo $index ?>][stamp]"
                value="<?php echo htmlspecialchars($_installedpart->stamp) ?>" />
        </div>
        <div class="span2">
            <input
                type="text"
                id="article-<?php echo $record->getId() ?>-installedpart-<?php echo $index ?>-purchaseprice"
                class="number"
                name="dialog[ownInstalledpart][<?php echo $index ?>][purchaseprice]"
                value="<?php echo htmlspecialchars(number_format((float)$_installedpart->purchaseprice, 2, ',', '.')) ?>" />
        </div>
        <div class="span2">
            <input
                type="text"
                id="article-<?php echo $record->getId() ?>-installedpart-<?php echo $index ?>-salesprice"
                class="number"
                name="dialog[ownInstalledpart][<?php echo $index ?>][salesprice]"
                value="<?php echo htmlspecialchars(number_format((float)$_installedpart->salesprice, 2, ',', '.')) ?>" />
        </div>
    </div>
</fieldset>
<!-- /installedpart edit subform -->
