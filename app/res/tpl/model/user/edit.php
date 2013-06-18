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
<!-- user form details -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
    <input type="hidden" name="dialog[pw]" value="<?php echo htmlspecialchars($record->pw) ?>" />
</div>
<fieldset>
    <legend><?php echo I18n::__('user_legend') ?></legend>
    <div
        class="row<?php echo $record->hasError('name') ? ' error' : '' ?>">
        <label
            for="user-name"
            class="<?php echo $record->hasError('name') ? 'error' : '' ?>">
            <?php echo I18n::__('user_name_label') ?>
        </label>
        <input
            type="text"
            id="user-name"
            name="dialog[name]"
            placeholder="<?php echo I18n::__('user_name_placeholder') ?>"
            value="<?php echo htmlspecialchars($record->name) ?>"
            required="required" />
    </div>
    <div
        class="row<?php echo $record->hasError('email') ? ' error' : '' ?>">
        <label
            for="user-email"
            class="<?php echo $record->hasError('email') ? 'error' : '' ?>">
            <?php echo I18n::__('user_email_label') ?>
        </label>
        <input
            type="email"
            id="user-email"
            name="dialog[email]"
            placeholder="<?php echo I18n::__('user_email_placeholder') ?>"
            value="<?php echo htmlspecialchars($record->email) ?>"
            required="required" />
    </div>
    <div
        class="row<?php echo $record->hasError('shortname') ? ' error' : '' ?>">
        <label
            for="user-shortname"
            class="<?php echo $record->hasError('shortname') ? 'error' : '' ?>">
            <?php echo I18n::__('user_shortname_label') ?>
        </label>
        <input
            type="text"
            id="user-shortname"
            name="dialog[shortname]"
            placeholder="<?php echo I18n::__('user_shortname_placeholder') ?>"
            value="<?php echo htmlspecialchars($record->shortname) ?>"
            required="required" />
    </div>
    <div
        class="row<?php echo $record->hasError('isadmin') ? ' error' : '' ?>">
        <input
            type="hidden"
            name="dialog[isadmin]"
            value="0" />
        <label
            for="user-isadmin"
            class="cb">
            <?php echo I18n::__('user_label_isadmin') ?>
        </label>
        <input
            id="user-isadmin"
            type="checkbox"
            name="dialog[isadmin]"
            <?php echo ($record->isadmin) ? 'checked="checked"' : '' ?>
            value="1" />
    </div>
</fieldset>
<!-- End of user form details -->
