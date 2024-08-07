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
<!-- transaction edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('transaction_legend') ?></legend>
    <div class="row nomargins">
        <div class="span3">&nbsp;</div>
        <div class="span3">
            <label
                for="transaction-number"
                class="<?php echo ($record->hasError('number')) ? 'error' : ''; ?>">
                <?php echo I18n::__('transaction_label_number') ?>
            </label>
        </div>
        <div class="span3">&nbsp;</div>
        <div class="span3">
            <label
                for="transaction-bookingdate"
                class="<?php echo ($record->hasError('bookingdate')) ? 'error' : ''; ?>">
                <?php echo I18n::__('transaction_label_bookingdate') ?>
            </label>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            <select
                id="transaction-contracttype"
                class="autowidth"
                name="dialog[contracttype_id]"
                disabled="disabled"
                required="required">
                <option value=""><?php echo I18n::__('transaction_contracttype_none') ?></option>
                <?php foreach (R::find('contracttype', "enabled = 1 AND ledger = 1 ORDER BY name") as $_id => $_contracttype): ?>
                <option
                    value="<?php echo $_contracttype->getId() ?>"
                    <?php echo ($record->contracttype_id == $_contracttype->getId()) ? 'selected="selected"' : '' ?>><?php echo $_contracttype->name ?>
                </option>
                <?php endforeach?>
            </select>
        </div>
        <div class="span3">
            <input
                id="transaction-number"
                class="autowidth"
                type="text"
                readonly="readonly"
                name="dialog[number]"
                required="required"
                value="<?php echo htmlspecialchars($record->number ?? '') ?>" />
            <?php if ($_parent = $record->hasParent()): ?>
            <p class="info"><?php echo I18n::__('transaction_info_parent', null, [$_parent->getId(), $_parent->getContracttype()->name, $_parent->number]) ?></p>
            <?php endif;?>
        </div>
        <div class="span3">
            &nbsp;
        </div>
        <div class="span3">
            <input
                id="transaction-bookingdate"
                class="autowidth"
                type="date"
                name="dialog[bookingdate]"
                required="required"
                placeholder="<?php echo I18n::__('placeholder_intl_date') ?>"
                value="<?php echo htmlspecialchars($record->bookingdate ?? '') ?>" />
                <?php if (Flight::get('user')->isBooking()): ?>
                <div class="alert alert-warning">
                    <?php echo Flight::textile(I18n::__('transaction_booking_in_progress')) ?>
                </div>
                <?php endif?>
        </div>
    </div>
    <div class="row                                                                             <?php echo ($record->hasError('person_id')) ? 'error' : ''; ?>">
        <label
            for="transaction-person-name">
            <a href="<?php echo Url::build('/admin/%s/edit/%d', [$record->getPerson()->getMeta('type'), $record->getPerson()->getId()]) ?>" class="ir in-form"><?php echo I18n::__('form_link_related') ?></a>
            <?php echo I18n::__('transaction_label_person') ?>
        </label>
        <input
            type="hidden"
            id="transaction-person-id-shadow"
            name="dialog[person_id]"
            value="<?php echo $record->getPerson()->getId() ?>" />
        <input
            type="hidden"
            name="dialog[person][type]"
            value="person" />
        <input
            id="transaction-person-id"
            type="hidden"
            name="dialog[person][id]"
            value="<?php echo $record->getPerson()->getId() ?>" />
        <input
            type="text"
            id="transaction-person-name"
            name="dialog[customername]"
            class="autocomplete"
            data-target="person-dependent"
            data-extra="transaction-person-id"
            data-source="<?php echo Url::build('/autocomplete/person/name/?callback=?') ?>"
            data-dynamic="<?php echo Url::build('/transaction/%d/person/changed/?callback=?', [$record->getId()]) ?>"
            data-spread='<?php
                             echo json_encode([
                                 'transaction-person-name'          => 'value',
                                 'transaction-person-id'            => 'id',
                                 'transaction-person-id-shadow'     => 'id',
                                 'transaction-postaladdress'        => 'postaladdress',
                                 'transaction-billingemail'         => 'billingemail',
                                 'transaction-dunningemail'         => 'dunningemail',
                                 'transaction-duedays'              => 'duedays',
                                 'transaction-discount-id'          => 'discount_id',
                                 'transaction-payhourly'            => 'payhourly',
                                 'transaction-paydrive'             => 'paydrive',
                             'transaction-paydriveperkilometer' => 'paydriveperkilometer',
                         ]); ?>'
            value="<?php echo htmlspecialchars($record->customername ?? '') ?>" />
            <a
                href="#scratch-item"
                title="<?php echo I18n::__('scaffold_action_scratch_title') ?>"
                data-clear="transaction-person-name"
                data-scratch="transaction-person-id-shadow"
                class="ir scratch"><?php echo I18n::__('scaffold_action_scratch_linktext') ?></a>
            <p class="info"><?php echo I18n::__('transaction_info_person') ?></p>
    </div>
        <div class="row                                                                                             <?php echo ($record->hasError('payhourly')) ? 'error' : ''; ?>">
            <label
                for="transaction-payhourly">
                <?php echo I18n::__('person_label_payhourly') ?>
            </label>
            <input
                id="transaction-payhourly"
                type="text"
                name="dialog[payhourly]"
                value="<?php echo htmlspecialchars($record->decimal('payhourly') ?? '') ?>" />
        </div>

        <div class="row ">
            <label
                for="transaction-paydrive">
                <?php echo I18n::__('person_label_paydrive') ?>
            </label>
            <select
                id="transaction-paydrive"
                name="dialog[paydrive]">
                <option value=""><?php echo I18n::__('person_paydrive_select') ?></option>
                <?php foreach ($record->getPerson()->getPaydriveTypes() as $_paydrive): ?>
                <option
                    value="<?php echo $_paydrive ?>"
                    <?php echo ($record->paydrive == $_paydrive) ? 'selected="selected"' : '' ?>>
                    <?php echo I18n::__('person_paydrive_type_' . $_paydrive) ?>
                </option>
                <?php endforeach?>
            </select>
        </div>
        <div class="row                                                                                             <?php echo ($record->hasError('paydriveperkilometer')) ? 'error' : ''; ?>">
            <label
                for="transaction-paydriveperkilometer">
                <?php echo I18n::__('person_label_paydriveperkilometer') ?>
            </label>
            <input
                id="transaction-paydriveperkilometer"
                type="text"
                name="dialog[paydriveperkilometer]"
                value="<?php echo htmlspecialchars($record->decimal('paydriveperkilometer') ?? '') ?>" />
        </div>
</fieldset>
<div class="tab-container">
    <?php Flight::render('shared/navigation/tabs', [
            'tab_id'      => 'transaction-tabs',
            'tabs'        => [
                'transaction-head'     => I18n::__('transaction_tab_head'),
                'transaction-position' => I18n::__('transaction_tab_position'),
                'transaction-foot'     => I18n::__('transaction_tab_foot'),
                'transaction-booking'  => I18n::__('transaction_tab_booking'),
                'transaction-email'    => I18n::__('transaction_tab_email'),
            ],
            'default_tab' => 'transaction-head',
    ])?>
    <fieldset
        id="transaction-head"
        class="tab"
        style="display: block;">
        <legend class="verbose"><?php echo I18n::__('transaction_legend_head') ?></legend>
        <div class="row                                                                                             <?php echo ($record->hasError('postaladdress')) ? 'error' : ''; ?>">
            <label
                for="transaction-postaladdress">
                <?php echo I18n::__('transaction_label_postaladdress') ?>
            </label>
            <textarea
                id="transaction-postaladdress"
                name="dialog[postaladdress]"
                rows="5"
                cols="60"
                required="required"><?php echo htmlspecialchars($record->postaladdress ?? '') ?></textarea>
        </div>
        <div class="row                                                                                             <?php echo ($record->hasError('header')) ? 'error' : ''; ?>">
            <label
                for="transaction-header">
                <?php echo I18n::__('transaction_label_header') ?>
            </label>
            <textarea
                id="transaction-header"
                name="dialog[header]"
                class="autocomplete"
                data-source="<?php echo Url::build('/autocomplete/headfoot/name/?callback=?') ?>"
                data-spread='<?php
                                 echo json_encode([
                                 'transaction-header' => 'value',
                             ]); ?>'
                rows="10"
                cols="60"><?php echo htmlspecialchars($record->header ?? '') ?></textarea>
            <p class="info"><?php echo I18n::__('transaction_info_header') ?></p>
        </div>
    </fieldset>
    <fieldset
        id="transaction-foot"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('transaction_legend_foot') ?></legend>
        <div class="row                                                                                             <?php echo ($record->hasError('duedays')) ? 'error' : ''; ?>">
            <label
                for="transaction-duedays">
                <?php echo I18n::__('transaction_label_duedays') ?>
            </label>
            <input
                type="text"
                id="transaction-duedays"
                name="dialog[duedays]"
                value="<?php echo htmlspecialchars($record->duedays ?? '') ?>" />
        </div>
        <div class="row">
            <label
                for="transaction-discount-id">
                <?php echo I18n::__('transaction_label_discount') ?>
            </label>
            <select
                id="transaction-discount-id"
                name="dialog[discount_id]">
                <option value=""><?php echo I18n::__('person_discount_please_select') ?></option>
                <?php foreach (R::find('discount', ' ORDER BY name') as $_id => $_discount): ?>
                <option
                    value="<?php echo $_discount->getId() ?>"
                    <?php echo ($record->discount_id == $_discount->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_discount->name ?? '') ?></option>
                <?php endforeach?>
            </select>
        </div>
        <div class="row                                                                                             <?php echo ($record->hasError('footer')) ? 'error' : ''; ?>">
            <label
                for="transaction-footer">
                <?php echo I18n::__('transaction_label_footer') ?>
            </label>
            <textarea
                id="transaction-footer"
                name="dialog[footer]"
                class="autocomplete"
                data-source="<?php echo Url::build('/autocomplete/headfoot/name/?callback=?') ?>"
                data-spread='<?php
                                 echo json_encode([
                                 'transaction-footer' => 'value',
                             ]); ?>'
                rows="10"
                cols="60"><?php echo htmlspecialchars($record->footer ?? '') ?></textarea>
            <p class="info"><?php echo I18n::__('transaction_info_footer') ?></p>
        </div>
    </fieldset>
    <fieldset
        id="transaction-position"
        class="tab smaller-font"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('transaction_legend_position') ?></legend>
        <div class="row">
            <div class="span1">
                &nbsp;
            </div>
            <div class="span1">
                <label><?php echo I18n::__('position_label_kind_pos_alt') ?></label>
            </div>
            <div class="span1">
                <label><?php echo I18n::__('position_label_product') ?></label>
            </div>
            <div class="span3">
                <label><?php echo I18n::__('position_label_product_desc') ?></label>
            </div>
            <div class="span1 tar">
                <label><?php echo I18n::__('position_label_count') ?></label>
            </div>
            <div class="span1">
                <label><?php echo I18n::__('position_label_unit') ?></label>
            </div>
            <div class="span1">
                <label><?php echo I18n::__('position_label_cut_and_vat') ?></label>
            </div>
            <div class="span1 tar">
                <label><?php echo I18n::__('position_label_salesprice') ?></label>
            </div>
            <div class="span1 tar">
                <label><?php echo I18n::__('position_label_adjustment') ?></label>
            </div>
            <div class="span1 tar">
                <label><?php echo I18n::__('position_label_total') ?></label>
            </div>
        </div>
        <div
            id="transaction-<?php echo $record->getId() ?>-position-container"
            data-href="<?php echo Url::build('/transaction/sortable/position/position/') ?>"
            data-container="transaction-<?php echo $record->getId() ?>-position-container"
            data-variable="position"
            class="container attachable detachable sortable ui-sortable">
            <?php $_positions = $record->with(' ORDER BY currentindex ASC ')->ownPosition?>
<?php if (count($_positions) == 0):
    $_positions[] = R::dispense('position');
endif;?>
<?php $index                = 0?>
<?php $_SESSION['subtotal'] = 0?>
<?php foreach ($_positions as $_position_id => $_position): ?>
<?php $index++?>
<?php if ( ! $_position->isAlternative()) {
        $_SESSION['subtotal'] += $_position->total; //adding up this position total to our subtotal, in case
    }
?>
<?php Flight::render('model/transaction/own/' . $_position->kindAsCss(), [
        'record'    => $record,
        '_position' => $_position,
        'index'     => $index,
        '_subtotal' => $_SESSION['subtotal'],
])?>
<?php endforeach?>
        </div>
        <div class="row">
            <div class="span1">
                &nbsp;
            </div>
            <div class="span9 tar">
                <?php echo I18n::__('transaction_label_total_net') ?>
            </div>
            <div class="span2">
                <input
                    type="text"
                    class="number"
                    name="dialog[net]"
                    readonly="readonly"
                    value="<?php echo htmlspecialchars($record->decimal('net') ?? '') ?>">
            </div>
        </div>
        <?php if ($record->getContracttype()->hidesome): ?>
        <div class="row">
            <div class="span9">
                &nbsp;
            </div>
            <div class="span3">
                <select
                    id="transaction-donthidesome"
                    class="autowidth"
                    name="dialog[donthidesome]">
                    <option
                        value="0"
                        <?php echo ( ! $record->donthidesome) ? 'selected="selected"' : '' ?>>
                        <?php echo I18n::__('transaction_hidesome_default') ?>
                    </option>
                    <option
                        value="1"
                        <?php echo ($record->donthidesome) ? 'selected="selected"' : '' ?>>
                        <?php echo I18n::__('transaction_hidesome_override') ?>
                    </option>
                </select>
            </div>
        </div>
        <?php endif?>
<?php $vats = $record->getVatSentences();?>
<?php foreach ($vats as $_id => $_vat): ?>
        <div class="row">
            <div class="span1">
                &nbsp;
            </div>
            <div class="span7 tar">
                <?php echo I18n::__('transaction_label_vatcode', null, [$_vat['vatpercentage']]) ?>
            </div>
            <div class="span2 tar">
                <input
                    type="text"
                    class="number"
                    name="vatnet[]"
                    readonly="readonly"
                    value="<?php echo htmlspecialchars(Flight::nformat($_vat['net']) ?? '') ?>">
            </div>
            <div class="span2">
                <input
                    type="text"
                    class="number"
                    name="vatvalue[]"
                    readonly="readonly"
                    value="<?php echo htmlspecialchars(Flight::nformat($_vat['vatvalue']) ?? '') ?>">
            </div>
        </div>
        <?php endforeach;?>
        <div class="row">
            <div class="span1">
                &nbsp;
            </div>
            <div class="span9 tar">
                <?php echo $record->getWordingGros() ?>
            </div>
            <div class="span2">
                <input
                    type="text"
                    class="number"
                    name="dialog[gros]"
                    readonly="readonly"
                    value="<?php echo htmlspecialchars($record->decimal('gros') ?? '') ?>">
            </div>
        </div>
    </fieldset>
    <fieldset
        id="transaction-email"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('transaction_legend_email') ?></legend>
        <div class="row                                                                                             <?php echo ($record->hasError('billingemail')) ? 'error' : ''; ?>">
            <label
                for="transaction-billingemail">
                <?php echo I18n::__('transaction_label_billingemail') ?>
            </label>
            <div id="person-dependent" class="autodafe">
            <?php
                if ($record->getPerson()->getId()):
                // The customer of this appointment is already set. No autodafe needed.
                $_dependents = $record->getDependents($record->getPerson());
                Flight::render('model/transaction/billingmail', [
                    'person'   => $record->getPerson(),
                    'record'   => $record,
                    'contacts' => $_dependents['contacts'],
                ]);
                else:
                // lazy load, after hunting that heretic.
            ?>
            <div class="heretic"><?php echo I18n::__('transaction_person_select_before_me') ?></div>
            <?php endif;?>
            </div>
        </div>
        <div class="row                                                                                             <?php echo ($record->hasError('dunningemail')) ? 'error' : ''; ?>">
            <label
                for="transaction-dunningemail">
                <?php echo I18n::__('transaction_label_dunningemail') ?>
            </label>
            <input
                id="transaction-dunningemail"
                type="text"
                name="dialog[dunningemail]"
                value="<?php echo htmlspecialchars($record->dunningemail ?? '') ?>">
        </div>
    </fieldset>
    <fieldset
        id="transaction-booking"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('transaction_legend_booking') ?></legend>
        <div class="row">
            <div class="span1">
                &nbsp;
            </div>
            <div class="span2">
                <label><?php echo I18n::__('payment_label_bookingdate') ?></label>
            </div>
            <div class="span4">
                <label><?php echo I18n::__('payment_label_desc') ?></label>
            </div>
            <div class="span2">
                <label><?php echo I18n::__('payment_label_statement') ?></label>
            </div>
            <div class="span1">
                <label><?php echo I18n::__('payment_label_closingpayment') ?></label>
            </div>
            <div class="span2 tar">
                <label><?php echo I18n::__('payment_label_amount') ?></label>
            </div>
        </div>
        <div
            id="transaction-<?php echo $record->getId() ?>-payment-container"
            class="container attachable detachable sortable">
            <?php $_payments = $record->with(' ORDER BY bookingdate ASC ')->ownPayment?>
<?php if (count($_payments) == 0):
    $_payments[] = R::dispense('payment');
endif;?>
<?php $index = 0?>
<?php foreach ($_payments as $_payment_id => $_payment): ?>
<?php $index++?>
<?php Flight::render('model/transaction/own/payment', [
        'record'   => $record,
        '_payment' => $_payment,
        'index'    => $index,
])?>
<?php endforeach?>
        </div>
        <div class="row">
            <div class="span1">
                &nbsp;
            </div>
            <div class="span9 tar">
                <?php echo I18n::__('transaction_label_gros_to_pay') ?>
            </div>
            <div class="span2">
                <input
                    type="text"
                    class="number"
                    name="dialog[gros]"
                    readonly="readonly"
                    value="<?php echo htmlspecialchars($record->decimal('gros') ?? '') ?>">
            </div>
        </div>
        <div class="row">
            <div class="span1">
                &nbsp;
            </div>
            <div class="span9 tar">
                <?php echo I18n::__('transaction_label_totalpaid') ?>
            </div>
            <div class="span2">
                <input
                    type="text"
                    class="number"
                    name="dialog[totalpaid]"
                    readonly="readonly"
                    value="<?php echo htmlspecialchars($record->decimal('totalpaid') ?? '') ?>">
            </div>
        </div>
        <div class="row">
            <div class="span1">
                &nbsp;
            </div>
            <div class="span9 tar">
                <?php echo I18n::__('transaction_label_balance') ?>
            </div>
            <div class="span2">
                <input
                    type="text"
                    class="number"
                    name="dialog[balance]"
                    readonly="readonly"
                    value="<?php echo htmlspecialchars($record->decimal('balance') ?? '') ?>">
            </div>
        </div>
    </fieldset>
</div>
<!-- end of transaction edit form -->
