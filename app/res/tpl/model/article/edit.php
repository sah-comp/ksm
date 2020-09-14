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
<!-- article edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('article_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('number')) ? 'error' : ''; ?>">
        <label
            for="article-number">
            <?php echo I18n::__('article_label_number') ?>
        </label>
        <input
            id="article-number"
            type="text"
            name="dialog[number]"
            value="<?php echo htmlspecialchars($record->number) ?>"
            required="required" />
    </div>
</fieldset>
<!-- end of article edit form -->
