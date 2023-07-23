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
<!-- contracttype edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('contracttypetype_legend') ?></legend>

    <div class="row <?php echo ($record->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="contracttype-name">
            <?php echo I18n::__('contracttype_label_name') ?>
        </label>
        <input
            id="contracttype-name"
            type="text"
            name="dialog[name]"
            value="<?php echo htmlspecialchars($record->name) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('bookable')) ? 'error' : ''; ?>">
        <input
            type="hidden"
            name="dialog[bookable]"
            value="0" />
        <input
            id="contracttype-bookable"
            type="checkbox"
            name="dialog[bookable]"
            <?php echo ($record->bookable) ? 'checked="checked"' : '' ?>
            value="1" />
        <label
            for="contracttype-bookable"
            class="cb">
            <?php echo I18n::__('contracttype_label_bookable') ?>
        </label>
    </div>
    <div class="row <?php echo ($record->hasError('nickname')) ? 'error' : ''; ?>">
        <label
            for="contracttype-nickname">
            <?php echo I18n::__('contracttype_label_nickname') ?>
        </label>
        <input
            id="contracttype-nickname"
            type="text"
            name="dialog[nickname]"
            value="<?php echo htmlspecialchars($record->nickname) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('nextnumber')) ? 'error' : ''; ?>">
        <label
            for="contracttype-nextnumber">
            <?php echo I18n::__('contracttype_label_nextnumber') ?>
        </label>
        <input
            id="contracttype-nextnumber"
            type="number"
            name="dialog[nextnumber]"
            value="<?php echo htmlspecialchars($record->nextnumber) ?>"
            required="required" />
    </div>

    <div class="row <?php echo ($record->hasError('service')) ? 'error' : ''; ?>">
        <input
            type="hidden"
            name="dialog[service]"
            value="0" />
        <input
            id="contracttype-service"
            type="checkbox"
            name="dialog[service]"
            <?php echo ($record->service) ? 'checked="checked"' : '' ?>
            value="1" />
        <label
            for="contracttype-service"
            class="cb">
            <?php echo I18n::__('contracttype_label_service') ?>
        </label>
    </div>

    <div class="row <?php echo ($record->hasError('ledger')) ? 'error' : ''; ?>">
        <input
            type="hidden"
            name="dialog[ledger]"
            value="0" />
        <input
            id="contracttype-ledger"
            type="checkbox"
            name="dialog[ledger]"
            <?php echo ($record->ledger) ? 'checked="checked"' : '' ?>
            value="1" />
        <label
            for="contracttype-ledger"
            class="cb">
            <?php echo I18n::__('contracttype_label_ledger') ?>
        </label>
    </div>

    <div class="row <?php echo ($record->hasError('enabled')) ? 'error' : ''; ?>">
        <input
            type="hidden"
            name="dialog[enabled]"
            value="0" />
        <input
            id="contracttype-enabled"
            type="checkbox"
            name="dialog[enabled]"
            <?php echo ($record->enabled) ? 'checked="checked"' : '' ?>
            value="1" />
        <label
            for="contracttype-enabled"
            class="cb">
            <?php echo I18n::__('contracttype_label_enabled') ?>
        </label>
    </div>
    <div class="row <?php echo ($record->hasError('hidesome')) ? 'error' : ''; ?>">
        <input
            type="hidden"
            name="dialog[hidesome]"
            value="0" />
        <input
            id="contracttype-hidesome"
            type="checkbox"
            name="dialog[hidesome]"
            <?php echo ($record->hidesome) ? 'checked="checked"' : '' ?>
            value="1" />
        <label
            for="contracttype-hidesome"
            class="cb">
            <?php echo I18n::__('contracttype_label_hidesome') ?>
        </label>
    </div>
    <div class="row <?php echo ($record->hasError('hidetotal')) ? 'error' : ''; ?>">
        <input
            type="hidden"
            name="dialog[hidetotal]"
            value="0" />
        <input
            id="contracttype-hidetotal"
            type="checkbox"
            name="dialog[hidetotal]"
            <?php echo ($record->hidetotal) ? 'checked="checked"' : '' ?>
            value="1" />
        <label
            for="contracttype-hidetotal"
            class="cb">
            <?php echo I18n::__('contracttype_label_hidetotal') ?>
        </label>
    </div>
    <div class="row <?php echo ($record->hasError('hideall')) ? 'error' : ''; ?>">
        <input
            type="hidden"
            name="dialog[hideall]"
            value="0" />
        <input
            id="contracttype-hideall"
            type="checkbox"
            name="dialog[hideall]"
            <?php echo ($record->hideall) ? 'checked="checked"' : '' ?>
            value="1" />
        <label
            for="contracttype-hideall"
            class="cb">
            <?php echo I18n::__('contracttype_label_hideall') ?>
        </label>
    </div>
    <div class="row <?php echo ($record->hasError('note')) ? 'error' : ''; ?>">
        <label
            for="contracttype-note">
            <?php echo I18n::__('contracttype_label_note') ?>
        </label>
        <textarea
            id="contracttype-note"
            name="dialog[note]"
            rows="5"
            cols="60"><?php echo htmlspecialchars($record->note) ?></textarea>
        <p class="info"><?php echo I18n::__('contracttype_info_note') ?></p>
    </div>
</fieldset>
<div class="tab-container">
    <?php Flight::render('shared/navigation/tabs', array(
        'tab_id' => 'contracttype-tabs',
        'tabs' => array(
            'contracttype-detail' => I18n::__('contracttype_detail_tab'),
            'contracttype-limb' => I18n::__('contracttype_limb_tab'),
            'contracttype-style' => I18n::__('contracttype_style_tab'),
            'contracttype-wording' => I18n::__('contracttype_wording_tab'),
            'contracttype-email' => I18n::__('contracttype_email_tab')
        ),
                        'default_tab' => 'contracttype-detail'
    )) ?>
    <fieldset
        id="contracttype-detail"
        class="tab"
        style="display: block;">
        <legend class="verbose"><?php echo I18n::__('contracttype_detail_legend') ?></legend>
        <div class="row <?php echo ($record->hasError('text')) ? 'error' : ''; ?>">
            <label
                for="contracttype-text">
                <?php echo I18n::__('contracttype_label_text') ?>
            </label>
            <textarea
                id="contracttype-text"
                name="dialog[text]"
                rows="23"
                cols="60"><?php echo htmlspecialchars($record->text) ?></textarea>
            <p class="info"><?php echo I18n::__('contracttype_info_text') ?></p>
        </div>
    </fieldset>
    <fieldset id="contracttype-style" class="tab" style="display: none;">
        <legend class="verbose"><?php echo I18n::__('contracttype_style_legend') ?></legend>
        <div class="row <?php echo ($record->hasError('css')) ? 'error' : ''; ?>">
            <label
                for="contracttype-css">
                <?php echo I18n::__('contracttype_label_css') ?>
            </label>
            <textarea
                id="contracttype-css"
                name="dialog[css]"
                rows="5"
                cols="60"><?php echo htmlspecialchars($record->css) ?></textarea>
            <p class="info"><?php echo I18n::__('contracttype_info_css') ?></p>
        </div>
    </fieldset>
    <fieldset id="contracttype-wording" class="tab" style="display: none;">
        <legend class="verbose"><?php echo I18n::__('contracttype_wording_legend') ?></legend>
        <div class="row <?php echo ($record->hasError('wordgros')) ? 'error' : ''; ?>">
            <label
                for="contracttype-wordgros">
                <?php echo I18n::__('contracttype_label_wordgros') ?>
            </label>
            <input
                id="contracttype-wordgros"
                name="dialog[wordgros]"
                type="text"
                value="<?php echo htmlspecialchars($record->wordgros) ?>">
            <p class="info"><?php echo I18n::__('contracttype_info_wordgros') ?></p>
        </div>
        <div class="row <?php echo ($record->hasError('resetheader')) ? 'error' : ''; ?>">
            <input
                type="hidden"
                name="dialog[resetheader]"
                value="0" />
            <input
                id="contracttype-resetheader"
                type="checkbox"
                name="dialog[resetheader]"
                <?php echo ($record->resetheader) ? 'checked="checked"' : '' ?>
                value="1" />
            <label
                for="contracttype-resetheader"
                class="cb">
                <?php echo I18n::__('contracttype_label_resetheader') ?>
            </label>
        </div>
        <div class="row <?php echo ($record->hasError('resetfooter')) ? 'error' : ''; ?>">
            <input
                type="hidden"
                name="dialog[resetfooter]"
                value="0" />
            <input
                id="contracttype-resetfooter"
                type="checkbox"
                name="dialog[resetfooter]"
                <?php echo ($record->resetfooter) ? 'checked="checked"' : '' ?>
                value="1" />
            <label
                for="contracttype-resetfooter"
                class="cb">
                <?php echo I18n::__('contracttype_label_resetfooter') ?>
            </label>
        </div>
        <div class="row <?php echo ($record->hasError('closeonarchive')) ? 'error' : ''; ?>">
            <input
                type="hidden"
                name="dialog[closeonarchive]"
                value="0" />
            <input
                id="contracttype-closeonarchive"
                type="checkbox"
                name="dialog[closeonarchive]"
                <?php echo ($record->closeonarchive) ? 'checked="checked"' : '' ?>
                value="1" />
            <label
                for="contracttype-closeonarchive"
                class="cb">
                <?php echo I18n::__('contracttype_label_closeonarchive') ?>
            </label>
        </div>
    </fieldset>
    <fieldset id="contracttype-email" class="tab" style="display: none;">
        <legend class="verbose"><?php echo I18n::__('contracttype_emailtext_legend') ?></legend>
        <div class="row <?php echo ($record->hasError('css')) ? 'error' : ''; ?>">
            <label
                for="contracttype-emailtext">
                <?php echo I18n::__('contracttype_label_emailtext') ?>
            </label>
            <textarea
                id="contracttype-emailtext"
                name="dialog[emailtext]"
                rows="10"
                cols="60"><?php echo htmlspecialchars($record->emailtext) ?></textarea>
            <p class="info"><?php echo I18n::__('contracttype_info_emailtext') ?></p>
        </div>
    </fieldset>
    <fieldset
        id="contracttype-limb"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('contracttype_limb_legend') ?></legend>
        <div class="row">
            <div class="span1">
                &nbsp;
            </div>
            <div class="span1">
                <?php echo I18n::__('limb_label_active') ?>
            </div>
            <div class="span1" title="<?php echo I18n::__('limb_title_list') ?>">
                <?php echo I18n::__('limb_label_list') ?>
            </div>
            <div class="span1">
                <?php echo I18n::__('limb_label_sequence') ?>
            </div>
            <div class="span3">
                <?php echo I18n::__('limb_label_name') ?>
            </div>
            <div class="span2">
                <?php echo I18n::__('limb_label_placeholder') ?>
            </div>
            <div class="span1">
                <?php echo I18n::__('limb_label_tag') ?>
            </div>
            <div class="span2">
                <?php echo I18n::__('limb_label_stub') ?>
            </div>
        </div>
        <div
            id="contracttype-<?php echo $record->getId() ?>-limb-container"
            class="container attachable detachable sortable">
            <?php $_limbs = $record->with(' ORDER BY sequence ASC ')->ownLimb ?>
            <?php if (count($_limbs) == 0) :
                $_limbs[] = R::dispense('limb');
            endif; ?>
        <?php $index = 0 ?>
        <?php foreach ($_limbs as $_limb_id => $_limb) : ?>
            <?php $index++ ?>
            <?php Flight::render('model/contracttype/own/limb', array(
            'record' => $record,
            '_limb' => $_limb,
            'index' => $index
        )) ?>
        <?php endforeach ?>
        </div>
    </fieldset>
</div>
<!-- end of contracttype edit form -->
