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
    <div class="row">
        <p><?php echo $_installedpart->machine->name ?></p>
    </div>
</fieldset>

<!-- /installedpart edit subform -->
