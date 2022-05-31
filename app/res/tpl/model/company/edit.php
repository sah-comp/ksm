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
<!-- company edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('company_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="company-name">
            <?php echo I18n::__('company_label_name') ?>
        </label>
        <input
            id="company-name"
            type="text"
            name="dialog[name]"
            value="<?php echo htmlspecialchars($record->name) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('active')) ? 'error' : ''; ?>">
        <input
            type="hidden"
            name="dialog[active]"
            value="0" />
        <input
            id="company-active"
            type="checkbox"
            name="dialog[active]"
            <?php echo ($record->active) ? 'checked="checked"' : '' ?>
            value="1" />
        <label
            for="company-active"
            class="cb">
            <?php echo I18n::__('company_label_active') ?>
        </label>
    </div>
</fieldset>
<div class="tab-container">
    <?php Flight::render('shared/navigation/tabs', array(
        'tab_id' => 'company-tabs',
        'tabs' => array(
            'company-address' => I18n::__('company_address_tab'),
            'company-communication' => I18n::__('company_communication_tab'),
            'company-id' => I18n::__('company_id_tab'),
            'company-bankaccount' => I18n::__('company_bankaccount_tab'),
            'company-serial' => I18n::__('company_serial_tab')
        ),
        'default_tab' => 'company-address'
    )) ?>
    <fieldset
        id="company-address"
        class="tab"
        style="display: block;">
        <legend class="verbose"><?php echo I18n::__('company_legend_address_tab') ?></legend>
            <div class="row <?php echo ($record->hasError('legalname')) ? 'error' : ''; ?>">
                <label
                    for="company-legalname">
                    <?php echo I18n::__('company_label_legalname') ?>
                </label>
                <input
                    id="company-legalname"
                    type="text"
                    name="dialog[legalname]"
                    value="<?php echo htmlspecialchars($record->legalname) ?>" />
            </div>
            <div class="row <?php echo ($record->hasError('street')) ? 'error' : ''; ?>">
                <label
                    for="company-street">
                    <?php echo I18n::__('company_label_street') ?>
                </label>
                <input
                    id="company-street"
                    type="text"
                    name="dialog[street]"
                    value="<?php echo htmlspecialchars($record->street) ?>" />
            </div>
            <div class="row <?php echo ($record->hasError('zip')) ? 'error' : ''; ?>">
                <label
                    for="company-zip">
                    <?php echo I18n::__('company_label_zip') ?>
                </label>
                <input
                    id="company-zip"
                    type="text"
                    name="dialog[zip]"
                    value="<?php echo htmlspecialchars($record->zip) ?>" />
            </div>
            <div class="row <?php echo ($record->hasError('city')) ? 'error' : ''; ?>">
                <label
                    for="company-city">
                    <?php echo I18n::__('company_label_city') ?>
                </label>
                <input
                    id="company-city"
                    type="text"
                    name="dialog[city]"
                    value="<?php echo htmlspecialchars($record->city) ?>" />
            </div>
    </fieldset>
    <fieldset
        id="company-communication"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('company_legend_communication') ?></legend>
        <div class="row <?php echo ($record->hasError('phone')) ? 'error' : ''; ?>">
            <label
                for="company-phone">
                <?php echo I18n::__('company_label_phone') ?>
            </label>
            <input
                id="company-phone"
                type="text"
                name="dialog[phone]"
                value="<?php echo htmlspecialchars($record->phone) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('fax')) ? 'error' : ''; ?>">
            <label
                for="company-fax">
                <?php echo I18n::__('company_label_fax') ?>
            </label>
            <input
                id="company-fax"
                type="text"
                name="dialog[fax]"
                value="<?php echo htmlspecialchars($record->fax) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('email')) ? 'error' : ''; ?>">
            <label
                for="company-email">
                <?php echo I18n::__('company_label_email') ?>
            </label>
            <input
                id="company-email"
                type="email"
                name="dialog[email]"
                value="<?php echo htmlspecialchars($record->email) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('website')) ? 'error' : ''; ?>">
            <label
                for="company-website">
                <?php echo I18n::__('company_label_website') ?>
            </label>
            <input
                id="company-website"
                type="text"
                name="dialog[website]"
                value="<?php echo htmlspecialchars($record->website) ?>" />
        </div>
        <!-- Noreply email address and its smtp Server -->
        <hr />
        <div class="row <?php echo ($record->hasError('emailnoreply')) ? 'error' : ''; ?>">
            <label
                for="company-emailnoreply">
                <?php echo I18n::__('company_label_emailnoreply') ?>
            </label>
            <input
                id="company-emailnoreply"
                type="email"
                name="dialog[emailnoreply]"
                value="<?php echo htmlspecialchars($record->emailnoreply) ?>" />
            <p class="info"><?php echo I18n::__('company_info_emailnoreply') ?></p>
        </div>
        <div class="row <?php echo ($record->hasError('smtphost')) ? 'error' : ''; ?>">
            <label
                for="company-smtphost">
                <?php echo I18n::__('company_label_smtphost') ?>
            </label>
            <input
                id="company-smtphost"
                type="text"
                name="dialog[smtphost]"
                value="<?php echo htmlspecialchars($record->smtphost) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('smtpport')) ? 'error' : ''; ?>">
            <label
                for="company-smtpport">
                <?php echo I18n::__('company_label_smtpport') ?>
            </label>
            <input
                id="company-smtpport"
                type="text"
                name="dialog[smtpport]"
                value="<?php echo htmlspecialchars($record->smtpport) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('smtpauth')) ? 'error' : ''; ?>">
            <label
                for="company-smtpauth">
                <?php echo I18n::__('company_label_smtpauth') ?>
            </label>
            <input
                id="company-smtpauth"
                type="text"
                name="dialog[smtpauth]"
                value="<?php echo htmlspecialchars($record->smtpauth) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('smtpuser')) ? 'error' : ''; ?>">
            <label
                for="company-smtpuser">
                <?php echo I18n::__('company_label_smtpuser') ?>
            </label>
            <input
                id="company-smtpuser"
                type="text"
                name="dialog[smtpuser]"
                value="<?php echo htmlspecialchars($record->smtpuser) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('smtppwd')) ? 'error' : ''; ?>">
            <label
                for="company-smtppwd">
                <?php echo I18n::__('company_label_smtppwd') ?>
            </label>
            <input
                id="company-smtppwd"
                type="password"
                name="dialog[smtppwd]"
                value="<?php echo htmlspecialchars($record->smtppwd) ?>" />
        </div>
        <!-- /Noreply email address and its smtp Server -->
    </fieldset>
    <fieldset
        id="company-id"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('company_legend_id') ?></legend>
        <div class="row <?php echo ($record->hasError('taxoffice')) ? 'error' : ''; ?>">
            <label
                for="company-taxoffice">
                <?php echo I18n::__('company_label_taxoffice') ?>
            </label>
            <input
                id="company-taxoffice"
                type="text"
                name="dialog[taxoffice]"
                value="<?php echo htmlspecialchars($record->taxoffice) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('taxid')) ? 'error' : ''; ?>">
            <label
                for="company-taxid">
                <?php echo I18n::__('company_label_taxid') ?>
            </label>
            <input
                id="company-taxid"
                type="text"
                name="dialog[taxid]"
                value="<?php echo htmlspecialchars($record->taxid) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('vatid')) ? 'error' : ''; ?>">
            <label
                for="company-vatid">
                <?php echo I18n::__('company_label_vatid') ?>
            </label>
            <input
                id="company-vatid"
                type="text"
                name="dialog[vatid]"
                value="<?php echo htmlspecialchars($record->vatid) ?>" />
        </div>
    </fieldset>
    <fieldset
        id="company-bankaccount"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('company_legend_bankaccount_tab') ?></legend>
        <div class="row <?php echo ($record->hasError('bankname')) ? 'error' : ''; ?>">
            <label
                for="company-bankname">
                <?php echo I18n::__('company_label_bankname') ?>
            </label>
            <input
                id="company-bankname"
                type="text"
                name="dialog[bankname]"
                value="<?php echo htmlspecialchars($record->bankname) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('bankcode')) ? 'error' : ''; ?>">
            <label
                for="company-bankcode">
                <?php echo I18n::__('company_label_bankcode') ?>
            </label>
            <input
                id="company-bankcode"
                type="text"
                name="dialog[bankcode]"
                value="<?php echo htmlspecialchars($record->bankcode) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('bankaccount')) ? 'error' : ''; ?>">
            <label
                for="company-bankaccountfield">
                <?php echo I18n::__('company_label_bankaccount') ?>
            </label>
            <input
                id="company-bankaccountfield"
                type="text"
                name="dialog[bankaccount]"
                value="<?php echo htmlspecialchars($record->bankaccount) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('bic')) ? 'error' : ''; ?>">
            <label
                for="company-bic">
                <?php echo I18n::__('company_label_bic') ?>
            </label>
            <input
                id="company-bic"
                type="text"
                name="dialog[bic]"
                value="<?php echo htmlspecialchars($record->bic) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('iban')) ? 'error' : ''; ?>">
            <label
                for="company-iban">
                <?php echo I18n::__('company_label_iban') ?>
            </label>
            <input
                id="company-iban"
                type="text"
                name="dialog[iban]"
                value="<?php echo htmlspecialchars($record->iban) ?>" />
        </div>
    </fieldset>
    <fieldset
        id="company-serial"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('company_legend_serial_tab') ?></legend>
        <div class="row <?php echo ($record->hasError('conditiondiscount')) ? 'error' : ''; ?>">
            <label
                for="company-conditiondiscount">
                <?php echo I18n::__('company_label_conditiondiscount') ?>
            </label>
            <textarea
                id="company-conditiondiscount"
                class="scaleable"
                name="dialog[conditiondiscount]"
                cols="60"
                rows="2"><?php echo htmlspecialchars($record->conditiondiscount) ?></textarea>
            <p class="info"><?php echo I18n::__('company_info_conditiondiscount') ?></p>
        </div>
        <div class="row <?php echo ($record->hasError('conditionnodiscount')) ? 'error' : ''; ?>">
            <label
                for="company-conditionnodiscount">
                <?php echo I18n::__('company_label_conditionnodiscount') ?>
            </label>
            <textarea
                id="company-conditionnodiscount"
                class="scaleable"
                name="dialog[conditionnodiscount]"
                cols="60"
                rows="2"><?php echo htmlspecialchars($record->conditionnodiscount) ?></textarea>
            <p class="info"><?php echo I18n::__('company_info_conditionnodiscount') ?></p>
        </div>
        <div class="row <?php echo ($record->hasError('conditionimmediately')) ? 'error' : ''; ?>">
            <label
                for="company-conditionimmediately">
                <?php echo I18n::__('company_label_conditionimmediately') ?>
            </label>
            <textarea
                id="company-conditionimmediately"
                class="scaleable"
                name="dialog[conditionimmediately]"
                cols="60"
                rows="2"><?php echo htmlspecialchars($record->conditionimmediately) ?></textarea>
            <p class="info"><?php echo I18n::__('company_info_conditionimmediately') ?></p>
        </div>
        <div class="row <?php echo ($record->hasError('conditionother')) ? 'error' : ''; ?>">
            <label
                for="company-conditionother">
                <?php echo I18n::__('company_label_conditionother') ?>
            </label>
            <textarea
                id="company-conditionother"
                class="scaleable"
                name="dialog[conditionother]"
                cols="60"
                rows="2"><?php echo htmlspecialchars($record->conditionother) ?></textarea>
            <p class="info"><?php echo I18n::__('company_info_conditionother') ?></p>
        </div>

        <div class="row <?php echo ($record->hasError('dunningemailtext')) ? 'error' : ''; ?>">
            <label
                for="company-dunningemailtext">
                <?php echo I18n::__('company_label_dunningemailtext') ?>
            </label>
            <textarea
                id="company-dunningemailtext"
                class="scaleable"
                name="dialog[dunningemailtext]"
                cols="60"
                rows="2"><?php echo htmlspecialchars($record->dunningemailtext) ?></textarea>
            <p class="info"><?php echo I18n::__('company_info_dunningemailtext') ?></p>
        </div>
    </fieldset>
</div>
<!-- end of company edit form -->
