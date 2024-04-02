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
    <form
        id="cloneform"
        name="cloneform"
        class="pform"
        method="POST"
        action=""
        accept-charset="utf-8"
        autocomplete="off"
        enctype="multipart/form-data">
        <input type="hidden" name="token" value="<?php echo Security::getCSRFToken() ?>" />
        <select
            name="clonefrom"
            required="required">
            <option value=""><?php echo I18n::__('file_clone_from') ?></option>
            <?php foreach (R::find('file', ' template = 1 ORDER BY filename') as $_id => $_filetemplate): ?>
            <option value="<?php echo $_id ?>"><?php echo $_filetemplate->filename ?></option>
            <?php endforeach;?>
        </select>
        <input
                id="clonename"
                type="text"
                name="clonename"
                class="pday"
                placeholder="<?php echo I18n::__('placeholder_file_clone') ?>"
                required="required"
                value="" />
        <input
            name="submit"
            type="submit"
            value="<?php echo I18n::__('file_action_clone_from') ?>" />
    </form>
</li>
    <li>
        <a
            href="<?php echo Url::build('/filer/') ?>">
            <?php echo I18n::__('filer_action_idle') ?>
        </a>
    </li>
</ul>
