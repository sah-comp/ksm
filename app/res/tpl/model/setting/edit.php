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
<!-- setting form -->
<?php
$domains = R::findAll('domain');
$appointmenttypes = R::findAll('appointmenttype');
?>
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
    <input type="hidden" name="dialog[installed]" value="<?php echo $record->installed ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('setting_legend_fiscalyear') ?></legend>
    <div class="row <?php echo ($record->hasError('fiscalyear')) ? 'error' : ''; ?>">
        <label
            for="setting-fiscalyear">
            <?php echo I18n::__('setting_label_fiscalyear') ?>
        </label>
        <input
            id="setting-fiscalyear"
            type="number"
            step="1"
            name="dialog[fiscalyear]"
            value="<?php echo htmlspecialchars($record->fiscalyear) ?>"
            required="required" />
    </div>

    <div class="row <?php echo ($record->hasError('companyyear')) ? 'error' : ''; ?>">
        <label
            for="setting-companyyear">
            <?php echo I18n::__('setting_label_companyyear') ?>
        </label>
        <input
            id="setting-companyyear"
            type="number"
            step="1"
            name="dialog[companyyear]"
            value="<?php echo htmlspecialchars($record->companyyear) ?>"
            required="required" />
        <p class="info"><?php echo I18n::__('setting_info_companyyear') ?></p>
    </div>

    <div class="row">
        <label
            for="setting-appointmenttypeservice"
            class="<?php echo ($record->hasError('appointmenttypeservice')) ? 'error' : ''; ?>">
            <?php echo I18n::__('setting_label_appointmenttypeservice') ?>
        </label>
        <select
            id="setting-appointmenttypeservice"
            name="dialog[appointmenttypeservice]">
            <?php foreach ($appointmenttypes as $_id => $_appointmenttype): ?>
            <option
                value="<?php echo $_appointmenttype->getId() ?>"
                <?php echo ($record->appointmenttypeservice == $_appointmenttype->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_appointmenttype->name) ?></option>
            <?php endforeach ?>
        </select>
        <p class="info"><?php echo I18n::__('setting_info_appointmenttypeservice') ?></p>
    </div>
</fieldset>
<div
    class="tab-container">
    <?php Flight::render('shared/navigation/tabs', array(
        'tab_id' => 'setting-tabs',
        'tabs' => array(
            'setting-folder' => I18n::__('setting_folder_tab'),
            'setting-currency' => I18n::__('setting_currency_tab'),
            'setting-logo' => I18n::__('setting_logo_tab'),
            'setting-csv' => I18n::__('setting_csv_tab')
        ),
        'default_tab' => 'setting-folder'
    )) ?>
    <fieldset
        id="setting-folder"
        class="tab">
        <legend class="verbose"><?php echo I18n::__('setting_legend_folder') ?></legend>
        <div class="row">
            <label
                for="setting-blessedfolder"
                class="<?php echo ($record->hasError('blessedfolder')) ? 'error' : ''; ?>">
                <?php echo I18n::__('setting_label_blessedfolder') ?>
            </label>
            <select
                id="setting-blessedfolder"
                name="dialog[blessedfolder]">
                <?php foreach ($domains as $_id => $_domain): ?>
                <option
                    value="<?php echo $_domain->getId() ?>"
                    <?php echo ($record->blessedfolder == $_domain->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_domain->i18n(Flight::get('language'))->name) ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="row <?php echo ($record->hasError('sitesfolder')) ? 'error' : ''; ?>">
            <label
                for="setting-sitesfolder">
                <?php echo I18n::__('setting_label_sitesfolder') ?>
            </label>
            <select
                id="setting-sitesfolder"
                name="dialog[sitesfolder]">
                <?php foreach ($domains as $_id => $_domain): ?>
                <option
                    value="<?php echo $_domain->getId() ?>"
                    <?php echo ($record->sitesfolder == $_domain->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_domain->i18n(Flight::get('language'))->name) ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="row <?php echo ($record->hasError('homepage')) ? 'error' : ''; ?>">
            <label
                for="setting-homepage">
                <?php echo I18n::__('setting_label_homepage') ?>
            </label>
            <select
                id="setting-homepage"
                name="dialog[homepage]">
                <option value="0"><?php echo I18n::__('setting_homepage_none') ?></value>
                <?php foreach ($domains as $_id => $_domain): ?>
                <option
                    value="<?php echo $_domain->getId() ?>"
                    <?php echo ($record->homepage == $_domain->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_domain->i18n(Flight::get('language'))->name) ?></option>
                <?php endforeach ?>
            </select>
            <p class="info"><?php echo I18n::__('setting_homepage_info') ?></p>
        </div>
    </fieldset>
    <fieldset
        id="setting-currency"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('setting_legend_currency') ?></legend>
        <div class="row <?php echo ($record->hasError('basecurrency')) ? 'error' : ''; ?>">
            <label
                for="setting-basecurrency">
                <?php echo I18n::__('setting_label_basecurrency') ?>
            </label>
            <select
                id="setting-basecurrency"
                name="dialog[basecurrency]">
                <?php foreach (R::findAll('currency') as $_id => $_currency): ?>
                <option
                    value="<?php echo $_currency->getId() ?>"
                    <?php echo ($record->basecurrency == $_currency->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_currency->name) ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div
            class="row <?php echo $record->hasError('exchangerateservice') ? 'error' : '' ?>">
            <label
                for="setting-exchangerateservice">
                <?php echo I18n::__('setting_label_exchangerateservice') ?>
            </label>
            <input
                type="text"
                id="setting-exchangerateservice"
                name="dialog[exchangerateservice]"
                value="<?php echo htmlspecialchars($record->exchangerateservice) ?>" />
        </div>
        <div class="row">
            <label
                for="setting-loadexchangerates">
                <?php echo I18n::__('setting_label_loadexchangerates') ?>
            </label>
            <select
                id="setting-loadexchangerates"
                name="loadexchangerates">
                <option value="0"><?php echo I18n::__('setting_loadexchangerates_no', null, array($record->exchangeratelastupd)) ?></value>
                <option value="1"><?php echo I18n::__('setting_loadexchangerates_yes') ?></value>
            </select>
        </div>
    </fieldset>
    <fieldset
        id="setting-logo"
        class="tab">
        <legend class="verbose"><?php echo I18n::__('setting_legend_logo') ?></legend>
        <div class="row">
            <label
                for="setting-logo"
                class="<?php echo ($record->hasError('logo')) ? 'error' : ''; ?>">
                <?php echo I18n::__('setting_label_logo') ?>
            </label>
            <input
                id="setting-logo"
                type="text"
                name="dialog[logo]"
                value="<?php echo htmlspecialchars($record->logo) ?>"
                required="required" />
        </div>
        <div class="row <?php echo ($record->hasError('logowidth')) ? 'error' : ''; ?>">
            <label
                for="setting-logowidth">
                <?php echo I18n::__('setting_label_logowidth') ?>
            </label>
            <input
                id="setting-logowidth"
                type="text"
                name="dialog[logowidth]"
                value="<?php echo htmlspecialchars($record->logowidth) ?>"
                required="required" />
        </div>
        <div class="row <?php echo ($record->hasError('logoheight')) ? 'error' : ''; ?>">
            <label
                for="setting-logoheight">
                <?php echo I18n::__('setting_label_logoheight') ?>
            </label>
            <input
                id="setting-logoheight"
                type="text"
                name="dialog[logoheight]"
                value="<?php echo htmlspecialchars($record->logoheight) ?>"
                required="required" />
        </div>
    </fieldset>
    <fieldset
        id="setting-csv"
        class="tab">
        <legend class="verbose"><?php echo I18n::__('setting_legend_csv') ?></legend>
        <div class="row <?php echo ($record->hasError('encodinginput')) ? 'error' : ''; ?>">
            <label
                for="setting-encodinginput">
                <?php echo I18n::__('setting_label_encodinginput') ?>
            </label>
            <input
                id="setting-encodinginput"
                type="text"
                name="dialog[encodinginput]"
                value="<?php echo htmlspecialchars($record->encodinginput) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('encodingoutput')) ? 'error' : ''; ?>">
            <label
                for="setting-encodingoutput">
                <?php echo I18n::__('setting_label_encodingoutput') ?>
            </label>
            <input
                id="setting-encodingoutput"
                type="text"
                name="dialog[encodingoutput]"
                value="<?php echo htmlspecialchars($record->encodingoutput) ?>" />
        </div>
    </fieldset>
</div>
<!-- end of setting form -->
