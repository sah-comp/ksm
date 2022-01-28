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
<!-- treaty add form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('treaty_legend') ?></legend>
    <div class="row nomargins">
        <div class="span3">&nbsp;</div>
        <div class="span9">
            <p class="alert alert-info"><?php echo I18n::__('treaty_contracttype_intro') ?></p>
            <label
                for="treaty-contracttype"
                class="<?php echo ($record->getContracttype()->hasError()) ? 'error' : ''; ?>">
                <?php echo I18n::__('treaty_label_contracttype') ?>
            </label>
        </div>
    </div>
    <div class="row">
        <div class="span3">&nbsp;</div>
        <div class="span3">
            <select
                id="treaty-contracttype"
                class="autowidth"
                name="dialog[contracttype_id]"
                required="required">
                <option value=""><?php echo I18n::__('treaty_contracttype_none') ?></option>
                <?php foreach (R::find('contracttype', "enabled = 1 AND service = 1 ORDER BY name") as $_id => $_contracttype): ?>
                <option
                    value="<?php echo $_contracttype->getId() ?>"
                    <?php echo ($record->contracttype_id == $_contracttype->getId()) ? 'selected="selected"' : '' ?>><?php echo $_contracttype->name ?>
                </option>
                <?php endforeach ?>
            </select>
            <p class="info"><?php echo I18n::__('treaty_contracttype_info') ?></p>
        </div>
    </div>
</fieldset>
<!-- end of treaty add form -->
