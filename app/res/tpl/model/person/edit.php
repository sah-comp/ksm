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
$_personkinds = $record->sharedPersonkind;
?>
<!-- person edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
    <?php if ($record->email): ?>
    <img
    	src="<?php echo Gravatar::src($record->email, 72) ?>"
    	class="gravatar-account circular no-shadow"
    	width="72"
    	height="72"
    	alt="<?php echo htmlspecialchars($record->name) ?>" />
    <?php endif ?>
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('person_legend') ?></legend>
    <!-- grid based header -->
    <div class="row nomargins">
        <div class="span3">&nbsp;</div>
        <div class="span2">
            <label
                for="person-nickname"
                class="<?php echo ($record->hasError('nickname')) ? 'error' : ''; ?>">
                <?php echo I18n::__('person_label_nickname') ?>
            </label>
        </div>
        <div class="span2">
            <label
                for="person-account"
                class="<?php echo ($record->hasError('account')) ? 'error' : ''; ?>">
                <?php echo I18n::__('person_label_account') ?>
            </label>
        </div>
        <div class="span2">
            <label
                for="person-vatid"
                class="<?php echo ($record->hasError('vatid')) ? 'error' : ''; ?>">
                <?php echo I18n::__('person_label_vatid') ?>
            </label>
        </div>
        <div class="span1">
            <label
                for="person-personkind"
                class="<?php echo ($record->hasError('personkind_id')) ? 'error' : ''; ?>">
                <?php echo I18n::__('person_label_personkind') ?>
            </label>
        </div>
        <div class="span2">
            <label
                for="person-language"
                class="<?php echo ($record->hasError('language')) ? 'error' : ''; ?>">
                <?php echo I18n::__('person_label_language') ?>
            </label>
        </div>
    </div>
    <!-- end of grid based header -->
    <!-- grid based data -->
    <div class="row nomargins">
        <div class="span3">&nbsp;</div>
        <div class="span2">
            <input
                type="hidden"
                name="dialog[enabled]"
                value="0" />
            <input
                id="person-enabled"
                type="checkbox"
                name="dialog[enabled]"
                title="<?php echo I18n::__('person_label_enabled') ?>"
                <?php echo ($record->enabled) ? 'checked="checked"' : '' ?>
                value="1" />
            <input
                id="person-nickname"
                class="autowidth"
                type="text"
                name="dialog[nickname]"
                placeholder="<?php echo I18n::__('person_placeholder_nickname') ?>"
                value="<?php echo htmlspecialchars($record->nickname) ?>"
                required="required" />
        </div>
        <div class="span2">
            <input
                id="person-account"
                class="autowidth"
                type="text"
                name="dialog[account]"
                value="<?php echo htmlspecialchars($record->account) ?>" />
        </div>
        <div class="span2">
            <input
                id="person-vatid"
                class="autowidth"
                type="text"
                name="dialog[vatid]"
                value="<?php echo htmlspecialchars($record->vatid) ?>" />
        </div>
        <div class="span1">
            <select
                id="person-personkind"
                class="autowidth"
                name="dialog[personkind_id]">
                <option value=""><?php echo I18n::__('person_personkind_select') ?></option>
                <?php foreach (R::findAll('personkind') as $_pk_id => $_pk): ?>
                <option
                    value="<?php echo $_pk->getId() ?>"
                    <?php echo ($record->personkind_id == $_pk->getId()) ? 'selected="selected"' : '' ?>>
                    <?php echo htmlspecialchars($_pk->name) ?>
                </option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="span2">
            <select
                id="person-language"
                class="autowidth"
                name="dialog[language]">
                <option value=""><?php echo I18n::__('person_language_select') ?></option>
                <?php foreach (R::findAll('language') as $_lang_id => $_lang): ?>
                <option
                    value="<?php echo $_lang->iso ?>"
                    <?php echo ($record->language == $_lang->iso) ? 'selected="selected"' : '' ?>>
                    <?php echo htmlspecialchars($_lang->name) ?>
                </option>
                <?php endforeach ?>
            </select>
        </div>
    </div>
    <!-- end of grid based data -->
</fieldset>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('person_legend_email') ?></legend>
    <div class="row <?php echo ($record->hasError('email')) ? 'error' : ''; ?>">
        <label
            for="person-email">
            <?php echo I18n::__('person_label_email') ?>
        </label>
        <input
            id="person-email"
            type="email"
            name="dialog[email]"
            value="<?php echo htmlspecialchars($record->email) ?>" />
    </div>
</fieldset>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('person_legend_name') ?></legend>
    <div class="row <?php echo ($record->hasError('attention')) ? 'error' : ''; ?>">
        <label
            for="person-attention">
            <?php echo I18n::__('person_label_attention') ?>
        </label>
        <input
            id="person-attention"
            type="text"
            name="dialog[attention]"
            value="<?php echo htmlspecialchars($record->attention) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('title')) ? 'error' : ''; ?>">
        <label
            for="person-title">
            <?php echo I18n::__('person_label_title') ?>
        </label>
        <input
            id="person-title"
            type="text"
            name="dialog[title]"
            value="<?php echo htmlspecialchars($record->title) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('firstname')) ? 'error' : ''; ?>">
        <label
            for="person-firstname">
            <?php echo I18n::__('person_label_firstname') ?>
        </label>
        <input
            id="person-firstname"
            type="text"
            name="dialog[firstname]"
            value="<?php echo htmlspecialchars($record->firstname) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('lastname')) ? 'error' : ''; ?>">
        <label
            for="person-lastname">
            <?php echo I18n::__('person_label_lastname') ?>
        </label>
        <input
            id="person-lastname"
            type="text"
            name="dialog[lastname]"
            value="<?php echo htmlspecialchars($record->lastname) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('suffix')) ? 'error' : ''; ?>">
        <label
            for="person-suffix">
            <?php echo I18n::__('person_label_suffix') ?>
        </label>
        <input
            id="person-suffix"
            type="text"
            name="dialog[suffix]"
            value="<?php echo htmlspecialchars($record->suffix) ?>" />
    </div>
</fieldset>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('person_legend_organization') ?></legend>
    <div class="row <?php echo ($record->hasError('organization')) ? 'error' : ''; ?>">
        <label
            for="person-organization">
            <?php echo I18n::__('person_label_organization') ?>
        </label>
        <textarea
            id="person-organization"
            name="dialog[organization]"
            rows="3"
            cols="60"><?php echo htmlspecialchars($record->organization) ?></textarea>
    </div>
    <div class="row <?php echo ($record->hasError('tabs')) ? 'error' : ''; ?>">
        <label
            for="person-company"
            class="cb">
            <?php echo I18n::__('person_label_company') ?>
        </label>
        <input
            type="hidden"
            name="dialog[company]"
            value="0" />
        <input
            id="person-company"
            type="checkbox"
            name="dialog[company]"
            <?php echo ($record->company) ? 'checked="checked"' : '' ?>
            value="1" />
    </div>
    <div class="row <?php echo ($record->hasError('owner')) ? 'error' : ''; ?>">
        <label
            for="person-owner">
            <?php echo I18n::__('person_label_owner') ?>
        </label>
        <input
            id="person-owner"
            type="text"
            name="dialog[owner]"
            value="<?php echo htmlspecialchars($record->owner) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('jobtitle')) ? 'error' : ''; ?>">
        <label
            for="person-jobtitle">
            <?php echo I18n::__('person_label_jobtitle') ?>
        </label>
        <input
            id="person-jobtitle"
            type="text"
            name="dialog[jobtitle]"
            value="<?php echo htmlspecialchars($record->jobtitle) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('department')) ? 'error' : ''; ?>">
        <label
            for="person-department">
            <?php echo I18n::__('person_label_department') ?>
        </label>
        <input
            id="person-department"
            type="text"
            name="dialog[department]"
            value="<?php echo htmlspecialchars($record->department) ?>" />
    </div>
</fieldset>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('person_legend_phone') ?></legend>
    <div class="row <?php echo ($record->hasError('phone')) ? 'error' : ''; ?>">
        <label
            for="person-phone">
            <?php echo I18n::__('person_label_phone') ?>
        </label>
        <input
            id="person-phone"
            type="text"
            name="dialog[phone]"
            value="<?php echo htmlspecialchars($record->phone) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('phonesec')) ? 'error' : ''; ?>">
        <label
            for="person-phonesec">
            <?php echo I18n::__('person_label_phonesec') ?>
        </label>
        <input
            id="person-phonesec"
            type="text"
            name="dialog[phonesec]"
            value="<?php echo htmlspecialchars($record->phonesec) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('cellphone')) ? 'error' : ''; ?>">
        <label
            for="person-cellphone">
            <?php echo I18n::__('person_label_cellphone') ?>
        </label>
        <input
            id="person-cellphone"
            type="text"
            name="dialog[cellphone]"
            value="<?php echo htmlspecialchars($record->cellphone) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('fax')) ? 'error' : ''; ?>">
        <label
            for="person-fax">
            <?php echo I18n::__('person_label_fax') ?>
        </label>
        <input
            id="person-fax"
            type="text"
            name="dialog[fax]"
            value="<?php echo htmlspecialchars($record->fax) ?>" />
    </div>
</fieldset>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('person_legend_url') ?></legend>
    <div class="row <?php echo ($record->hasError('url')) ? 'error' : ''; ?>">
        <label
            for="person-url">
            <?php echo I18n::__('person_label_url') ?>
        </label>
        <input
            id="person-url"
            type="text"
            name="dialog[url]"
            value="<?php echo htmlspecialchars($record->url) ?>" />
    </div>
</fieldset>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('person_legend_note') ?></legend>
    <div class="row <?php echo ($record->hasError('note')) ? 'error' : ''; ?>">
        <label
            for="person-note">
            <?php echo I18n::__('person_label_note') ?>
        </label>
        <textarea
            id="person-note"
            name="dialog[note]"
            rows="3"
            cols="60"><?php echo htmlspecialchars($record->note) ?></textarea>
    </div>
</fieldset>
<div class="tab-container">
    <?php Flight::render('shared/navigation/tabs', array(
        'tab_id' => 'person-tabs',
        'tabs' => array(
            'person-address' => I18n::__('person_address_tab'),
            'person-location' => I18n::__('person_location_tab'),
            'person-contact' => I18n::__('person_contact_tab'),
            'person-machine' => I18n::__('person_machine_tab'),
            'person-treaty' => I18n::__('person_treaty_tab'),
            'person-bankaccount' => I18n::__('person_bankaccount_tab'),
            'person-transaction' => I18n::__('person_transaction_tab'),
            'person-correspondence' => I18n::__('person_correspondence_tab')
        ),
        'default_tab' => 'person-address'
    )) ?>
    <fieldset
        id="person-address"
        class="tab"
        style="display: block;">
        <legend class="verbose"><?php echo I18n::__('person_legend_address_tab') ?></legend>
        <div
            id="person-<?php echo $record->getId() ?>-address-container"
            class="container attachable detachable sortable">
            <?php
            if (count($record->ownAddress) == 0):
                $record->ownAddress[] = R::dispense('address');
            endif;
            ?>
            <?php $index = 0 ?>
            <?php foreach ($record->ownAddress as $_address_id => $_address): ?>
            <?php $index++ ?>
            <?php Flight::render('model/person/own/address', array(
                'record' => $record,
                '_address' => $_address,
                'index' => $index
            )) ?>
            <?php endforeach ?>
        </div>
    </fieldset>
    <fieldset
        id="person-location"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('person_legend_location_tab') ?></legend>
        <div class="row nomargins">
            <div class="span3">
                &nbsp;
            </div>
            <div class="span9">
                <label>
                    <?php echo I18n::__('person_label_location_name') ?>
                </label>
            </div>
        </div>
        <div
            id="person-<?php echo $record->getId() ?>-location-container"
            class="container attachable detachable sortable">
            <?php
            if (count($record->ownLocation) == 0):
                $record->ownLocation[] = R::dispense('location');
            endif;
            ?>
            <?php $index = 0 ?>
            <?php foreach ($record->ownLocation as $_location_id => $_location): ?>
            <?php $index++ ?>
            <?php Flight::render('model/person/own/location', array(
                'record' => $record,
                '_location' => $_location,
                'index' => $index
            )) ?>
            <?php endforeach ?>
        </div>
    </fieldset>
    <fieldset
        id="person-contact"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('person_legend_contact_tab') ?></legend>
        <div
            id="person-<?php echo $record->getId() ?>-contact-container"
            class="container attachable detachable sortable">
            <?php
            if (count($record->ownContact) == 0):
                $record->ownContact[] = R::dispense('contact');
            endif;
            ?>
            <?php $index = 0 ?>
            <?php foreach ($record->ownContact as $_contact_id => $_contact): ?>
            <?php $index++ ?>
            <?php Flight::render('model/person/own/contact', array(
                'record' => $record,
                '_contact' => $_contact,
                'index' => $index
            )) ?>
            <?php endforeach ?>
        </div>
    </fieldset>
    <fieldset
        id="person-machine"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('person_legend_machine_tab') ?></legend>
        <div
            id="person-<?php echo $record->getId() ?>-machine-container"
            class="container attachable detachable sortable">
            <?php Flight::render('model/person/tables/machine', array(
                'record' => $record
            )) ?>
        </div>
    </fieldset>
    <fieldset
        id="person-treaty"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('person_legend_treaty_tab') ?></legend>
        <div
            id="person-<?php echo $record->getId() ?>-treaty-container"
            class="container attachable detachable sortable">
            <?php Flight::render('model/person/tables/treaty', array(
                'record' => $record
            )) ?>
        </div>
    </fieldset>
    <fieldset
        id="person-transaction"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('person_legend_transaction_tab') ?></legend>
        <div
            id="person-<?php echo $record->getId() ?>-transaction-container"
            class="container attachable detachable sortable">
            <?php Flight::render('model/person/tables/transaction', array(
                'record' => $record
            )) ?>
        </div>
    </fieldset>
    <fieldset
        id="person-correspondence"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('person_legend_correspondence_tab') ?></legend>
        <div
            id="person-<?php echo $record->getId() ?>-correspondence-container"
            class="container attachable detachable sortable">
            <?php Flight::render('model/person/tables/correspondence', array(
                'record' => $record
            )) ?>
        </div>
    </fieldset>
    <fieldset
        id="person-bankaccount"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('person_legend_bankaccount_tab') ?></legend>
        <!-- grid based header -->
        <div class="row nomargins">
            <div class="span3">&nbsp;</div>
            <div class="span3">
                <label
                    for="person-vat">
                    <?php echo I18n::__('person_label_vat') ?>
                </label>
            </div>
            <div class="span3">
                <label for="person-duedays"><?php echo I18n::__('person_label_duedays') ?></label>
            </div>
            <div class="span3">
            <label
                for="person-discount">
                <?php echo I18n::__('person_label_discount') ?>
            </label>
            </div>
        </div>
        <!-- end of grid based header -->
        <!-- grid based data -->
        <div class="row">
            <div class="span3">&nbsp;</div>
            <div class="span3">
                <select
                    id="person-vat"
                    class="autowidth"
                    name="dialog[vat_id]">
                    <option value=""><?php echo I18n::__('person_vat_please_select') ?></option>
                    <?php foreach (R::find('vat', ' ORDER BY name') as $_id => $_vat): ?>
                    <option
                        value="<?php echo $_vat->getId() ?>"
                        <?php echo ($record->vat_id == $_vat->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_vat->name) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="span3">
                <input
                    id="person-duedays"
                    type="text"
                    name="dialog[duedays]"
                    value="<?php echo htmlspecialchars($record->decimal('duedays', 0)) ?>" />
            </div>
            <div class="span3">
                <select
                    id="person-discount"
                    class="autowidth"
                    name="dialog[discount_id]">
                    <option value=""><?php echo I18n::__('person_discount_please_select') ?></option>
                    <?php foreach (R::find('discount', ' ORDER BY name') as $_id => $_discount): ?>
                    <option
                        value="<?php echo $_discount->getId() ?>"
                        <?php echo ($record->discount_id == $_discount->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_discount->name) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
        <!-- end of grid based data -->
        <div class="row <?php echo ($record->hasError('paymentnote')) ? 'error' : ''; ?>">
            <label
                for="person-paymentnote">
                <?php echo I18n::__('person_label_paymentnote') ?>
            </label>
            <textarea
                id="person-paymentnote"
                name="dialog[paymentnote]"
                placeholder="<?php echo I18n::__('person_placeholder_paymentnote') ?>"
                rows="3"
                cols="60"><?php echo htmlspecialchars($record->paymentnote) ?></textarea>
        </div>
        <div class="row nomargins">
            <div class="span3">&nbsp;</div>
            <div class="span4">
                <label
                    for="person-billingemail">
                    <?php echo I18n::__('person_label_billingemail') ?>
                </label>
            </div>
            <div class="span4">
                &nbsp;
            </div>
        </div>
        <div class="nomargins row <?php echo ($record->hasError('billingemail')) ? 'error' : ''; ?>">
            <div class="span3">
                &nbsp;
            </div>
            <div class="span4">
                <input
                    id="person-billingemail"
                    type="email"
                    name="dialog[billingemail]"
                    value="<?php echo htmlspecialchars($record->billingemail) ?>" />
            </div>
            <div class="span4 flexi">
                <input
                    type="hidden"
                    name="dialog[billingemailenabled]"
                    value="0" />
                <input
                    id="person-billingemailenabled"
                    type="checkbox"
                    name="dialog[billingemailenabled]"
                    <?php echo ($record->billingemailenabled) ? 'checked="checked"' : '' ?>
                    value="1" />
                <label
                    for="person-billingemailenabled">
                    <?php echo I18n::__('person_label_billingemailenabled') ?>
                </label>
            </div>
        </div>

        <div class="row nomargins">
            <div class="span3">&nbsp;</div>
            <div class="span4">
                <label
                    for="person-dunningemail">
                    <?php echo I18n::__('person_label_dunningemail') ?>
                </label>
            </div>
            <div class="span4">
                &nbsp;
            </div>
        </div>

        <div class="nomargins row <?php echo ($record->hasError('dunningemail')) ? 'error' : ''; ?>">
            <div class="span3">
                &nbsp;
            </div>
            <div class="span4">
                <input
                    id="person-dunningemail"
                    type="email"
                    name="dialog[dunningemail]"
                    value="<?php echo htmlspecialchars($record->dunningemail) ?>" />
            </div>
            <div class="span4 flexi">
                <input
                    type="hidden"
                    name="dialog[dunningemailenabled]"
                    value="0" />
                <input
                    id="person-dunningemailenabled"
                    type="checkbox"
                    name="dialog[dunningemailenabled]"
                    <?php echo ($record->dunningemailenabled) ? 'checked="checked"' : '' ?>
                    value="1" />
                <label
                    for="person-dunningemailenabled">
                    <?php echo I18n::__('person_label_dunningemailenabled') ?>
                </label>
            </div>
        </div>

        <div class="row <?php echo ($record->hasError('bankname')) ? 'error' : ''; ?>">
            <label
                for="person-bankname">
                <?php echo I18n::__('person_label_bankname') ?>
            </label>
            <input
                id="person-bankname"
                type="text"
                name="dialog[bankname]"
                value="<?php echo htmlspecialchars($record->bankname) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('bankcode')) ? 'error' : ''; ?>">
            <label
                for="person-bankcode">
                <?php echo I18n::__('person_label_bankcode') ?>
            </label>
            <input
                id="person-bankcode"
                type="text"
                name="dialog[bankcode]"
                value="<?php echo htmlspecialchars($record->bankcode) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('bankaccount')) ? 'error' : ''; ?>">
            <label
                for="person-bankaccountfield">
                <?php echo I18n::__('person_label_bankaccount') ?>
            </label>
            <input
                id="person-bankaccountfield"
                type="text"
                name="dialog[bankaccount]"
                value="<?php echo htmlspecialchars($record->bankaccount) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('bic')) ? 'error' : ''; ?>">
            <label
                for="person-bic">
                <?php echo I18n::__('person_label_bic') ?>
            </label>
            <input
                id="person-bic"
                type="text"
                name="dialog[bic]"
                value="<?php echo htmlspecialchars($record->bic) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('iban')) ? 'error' : ''; ?>">
            <label
                for="person-iban">
                <?php echo I18n::__('person_label_iban') ?>
            </label>
            <input
                id="person-iban"
                type="text"
                name="dialog[iban]"
                value="<?php echo htmlspecialchars($record->iban) ?>" />
        </div>
    </fieldset>
</div>
<!-- end of person edit form -->
