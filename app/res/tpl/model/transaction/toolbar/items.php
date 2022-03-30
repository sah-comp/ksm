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
<?php if ($record->getId()): ?>
<li>
    <a
        href="<?php echo Url::build("/transaction/mail/%d", [$record->getId()]) ?>">
        <?php echo I18n::__('transaction_action_mail') ?>
    </a>
</li>
<li>
    <form
        id="copyform"
        name="copyform"
        class="pform"
        method="GET"
        action="<?php echo Url::build('/transaction/copy/%d/', [$record->getId()]) ?>"
        accept-charset="utf-8"
        autocomplete="off"
        enctype="multipart/form-data">
        <input type="hidden" name="token" value="<?php echo Security::getCSRFToken() ?>" />
        <select
            name="copyas"
            required="required">
            <option value=""><?php echo I18n::__('transaction_copy_as') ?></option>
            <?php foreach (R::find('contracttype', ' enabled = 1 AND ledger = 1 ORDER BY name') as $_id => $_contracttype): ?>
            <option value="<?php echo $_id ?>"><?php echo $_contracttype->name ?></option>
            <?php endforeach; ?>
        </select>
        <input
            name="submit"
            type="submit"
            value="<?php echo I18n::__('transaction_action_copy_as') ?>" />
    </form>
</li>
<li>
    <form
        id="printform"
        name="printform"
        class="pform"
        method="GET"
        action="<?php echo Url::build('/transaction/pdf/%d/', [$record->getId()]) ?>"
        accept-charset="utf-8"
        autocomplete="off"
        enctype="multipart/form-data">
        <select
            name="layout">
            <?php foreach ($record->getPrintLayouts() as $_layout => $_default): ?>
            <option value="<?php echo $_layout ?>" <?php echo ($_default) ? 'selected="selected"' : '' ?>><?php echo I18n::__('transaction_layout_' . $_layout) ?></option>
            <?php endforeach; ?>
        </select>
        <input
            name="submit"
            type="submit"
            value="<?php echo I18n::__('transaction_action_pdf') ?>" />
    </form>
</li>
<?php elseif ($hasRecords): ?>
<li>
    <a
        href="<?php echo Url::build("/transaction/pdf") ?>">
        <?php echo I18n::__('transaction_action_pdf_list') ?>
    </a>
</li>
<?php else: ?>
<!-- There are no records -->
<?php endif; ?>
