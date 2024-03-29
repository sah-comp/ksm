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
$_teams = $record->sharedTeam;
$_roles = $record->sharedRole;
?>
<!-- edit user form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
    <input type="hidden" name="dialog[pw]" value="<?php echo htmlspecialchars($record->pw ?? '') ?>" />

    <?php if ($record->email) : ?>
    <img
        src="<?php echo Gravatar::src($record->email, 72) ?>"
        class="gravatar-account circular no-shadow"
        width="72"
        height="72"
        alt="<?php echo htmlspecialchars($record->getName() ?? '') ?>" />
    <?php endif ?>

</div>
<fieldset>
    <legend><?php echo I18n::__('user_legend') ?></legend>
    <div
        class="row <?php echo $record->hasError('name') ? 'error' : '' ?>">
        <label
            for="user-name">
            <?php echo I18n::__('user_label_name') ?>
        </label>
        <input
            type="text"
            id="user-name"
            name="dialog[name]"
            value="<?php echo htmlspecialchars($record->name ?? '') ?>"
            required="required" />
    </div>
    <div
        class="row <?php echo $record->hasError('email') ? 'error' : '' ?>">
        <label
            for="user-email">
            <?php echo I18n::__('user_label_email') ?>
        </label>
        <input
            type="email"
            id="user-email"
            name="dialog[email]"
            value="<?php echo htmlspecialchars($record->email ?? '') ?>"
            required="required" />
    </div>
    <div
        class="row <?php echo $record->hasError('shortname') ? 'error' : '' ?>">
        <label
            for="user-shortname">
            <?php echo I18n::__('user_label_shortname') ?>
        </label>
        <input
            type="text"
            id="user-shortname"
            name="dialog[shortname]"
            value="<?php echo htmlspecialchars($record->shortname ?? '') ?>"
            required="required" />
    </div>
    <div
        class="row <?php echo $record->hasError('maxlifetime') ? 'error' : '' ?>">
        <label
            for="user-maxlifetime">
            <?php echo I18n::__('user_label_maxlifetime') ?>
        </label>
        <input
            type="number"
            min="60"
            max="14400"
            step="60"
            id="user-maxlifetime"
            name="dialog[maxlifetime]"
            value="<?php echo htmlspecialchars($record->maxlifetime ?? '') ?>"
            required="required" />
        <p class="info"><?php echo I18n::__('user_info_maxlifetime') ?></p>
    </div>
    <div
        class="row <?php echo $record->hasError('isadmin') ? 'error' : '' ?>">
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

    <div class="row <?php echo ($record->hasError('startpage')) ? 'error' : ''; ?>">
        <label
            for="domain-parent">
            <?php echo I18n::__('user_label_startpage') ?>
        </label>
        <select
            id="user-startpage"
            name="dialog[startpage]">
            <?php foreach (R::findAll('domain') as $_id => $_domain) : ?>
            <option
                value="<?php echo $_domain->url ?>"
                <?php echo ($record->startpage == $_domain->url) ? 'selected="selected"' : '' ?>><?php echo $_domain->i18n(Flight::get('language'))->name ?></option>
            <?php endforeach ?>
        </select>
    </div>

</fieldset>
<div class="tab-container">
    <?php Flight::render('shared/navigation/tabs', array(
        'tab_id' => 'user-tabs',
        'tabs' => array(
            'user-setting' => I18n::__('user_setting_tab'),
            'user-role' => I18n::__('user_role_tab'),
            'user-team' => I18n::__('user_team_tab'),
            'user-editor' => I18n::__('user_editor_tab'),
            'user-signature' => I18n::__('user_signature_tab')
        ),
        'default_tab' => 'user-setting'
    )) ?>
    <fieldset
        id="user-setting"
        class="tab"
        style="display: block;">
        <legend><?php echo I18n::__('user_legend_setting') ?></legend>
        <div
            class="row <?php echo $record->hasError('isbanned') ? 'error' : '' ?>">
            <input
                type="hidden"
                name="dialog[isbanned]"
                value="0" />
            <label
                for="user-isbanned"
                class="cb">
                <?php echo I18n::__('user_label_isbanned') ?>
            </label>
            <input
                id="user-isbanned"
                type="checkbox"
                name="dialog[isbanned]"
                <?php echo ($record->isbanned) ? 'checked="checked"' : '' ?>
                value="1" />
        </div>
        <div
            class="row <?php echo $record->hasError('isdeleted') ? 'error' : '' ?>">
            <input
                type="hidden"
                name="dialog[isdeleted]"
                value="0" />
            <label
                for="user-isdeleted"
                class="cb">
                <?php echo I18n::__('user_label_isdeleted') ?>
            </label>
            <input
                id="user-isdeleted"
                type="checkbox"
                name="dialog[isdeleted]"
                <?php echo ($record->isdeleted) ? 'checked="checked"' : '' ?>
                value="1" />
        </div>
    </fieldset>
    <fieldset
        id="user-team"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('user_legend_team') ?></legend>
        <?php foreach (R::findAll('team') as $_id => $_team) : ?>
        <div class="row">
            <input
                type="hidden"
                name="dialog[sharedTeam][<?php echo $_team->getId() ?>][type]"
                value="team" />
            <input
                type="hidden"
                name="dialog[sharedTeam][<?php echo $_team->getId() ?>][id]"
                value="0" />
            <label
                for="user-team-<?php echo $_team->getId() ?>"
                class="cb"><?php echo htmlspecialchars($_team->i18n(Flight::get('language'))->name ?? '') ?></label>
            <input
                type="checkbox"
                id="user-team-<?php echo $_team->getId() ?>"
                name="dialog[sharedTeam][<?php echo $_team->getId() ?>][id]"
                value="<?php echo $_team->getId() ?>"
                <?php echo (isset($_teams[$_team->getId()])) ? 'checked="checked"' : '' ?> />
        </div>
        <?php endforeach ?>
    </fieldset>
    <fieldset
        id="user-role"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('user_legend_role') ?></legend>
        <?php foreach (R::findAll('role') as $_id => $_role) : ?>
        <div class="row">
            <input
                type="hidden"
                name="dialog[sharedRole][<?php echo $_role->getId() ?>][type]"
                value="role" />
            <input
                type="hidden"
                name="dialog[sharedRole][<?php echo $_role->getId() ?>][id]"
                value="0" />
            <label
                for="user-role-<?php echo $_role->getId() ?>"
                class="cb"><?php echo htmlspecialchars($_role->i18n(Flight::get('language'))->name ?? '') ?></label>
            <input
                type="checkbox"
                id="user-role-<?php echo $_role->getId() ?>"
                name="dialog[sharedRole][<?php echo $_role->getId() ?>][id]"
                value="<?php echo $_role->getId() ?>"
                <?php echo (isset($_roles[$_role->getId()])) ? 'checked="checked"' : '' ?> />
        </div>
        <?php endforeach ?>
    </fieldset>
    <fieldset
        id="user-editor"
        class="tab"
        style="display: none;">
        <legend><?php echo I18n::__('user_legend_editor') ?></legend>
        <div
            class="row <?php echo $record->hasError('foxylisteditor') ? 'error' : '' ?>">
            <input
                type="hidden"
                name="dialog[foxylisteditor]"
                value="0" />
            <label
                for="user-foxylisteditor"
                class="cb">
                <?php echo I18n::__('user_label_foxylisteditor') ?>
            </label>
            <input
                id="user-foxylisteditor"
                type="checkbox"
                name="dialog[foxylisteditor]"
                <?php echo ($record->foxylisteditor) ? 'checked="checked"' : '' ?>
                value="1" />
        </div>
        <div
            class="row <?php echo $record->hasError('allrecordsperpage') ? 'error' : '' ?>">
            <input
                type="hidden"
                name="dialog[allrecordsperpage]"
                value="0" />
            <label
                for="user-allrecordsperpage"
                class="cb">
                <?php echo I18n::__('user_label_allrecordsperpage') ?>
            </label>
            <input
                id="user-allrecordsperpage"
                type="checkbox"
                name="dialog[allrecordsperpage]"
                <?php echo ($record->allrecordsperpage) ? 'checked="checked"' : '' ?>
                value="1" />
        </div>
        <div
            class="row <?php echo $record->hasError('recordsperpage') ? 'error' : '' ?>">
            <label
                for="user-recordsperpage">
                <?php echo I18n::__('user_label_recordsperpage') ?>
            </label>
            <input
                type="number"
                min="17"
                max="500"
                step="1"
                id="user-recordsperpage"
                name="dialog[recordsperpage]"
                value="<?php echo htmlspecialchars($record->recordsperpage ?? '') ?>" />
            <p class="info"><?php echo I18n::__('user_info_recordsperpage') ?></p>
        </div>
    </fieldset>
    <fieldset
        id="user-signature"
        class="tab"
        style="display: none;">
        <legend><?php echo I18n::__('user_legend_signature') ?></legend>
        <div class="row <?php echo ($record->hasError('mailsig')) ? 'error' : ''; ?>">
            <label
             for="user-mailsig"><?php echo I18n::__('user_label_mailsig') ?>
            </label>
            <textarea
              id="user-mailsig"
               name="dialog[mailsig]"
               rows="12"
               cols="60"><?php echo htmlspecialchars($record->mailsig ?? '') ?></textarea>
            <p class="info"><?php echo I18n::__('user_info_mailsig') ?></p>
        </div>
    </fieldset>
</div>
<!-- End of edit user form -->
