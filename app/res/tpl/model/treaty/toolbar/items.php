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
    <?php if ($record->hasEmail()): ?>
<li>
    <a
        href="<?php echo Url::build("/treaty/mail/%d", [$record->getId()]) ?>"
        class="confirm mail <?php echo $record->wasEmailed() ?>">
        <?php echo I18n::__('treaty_action_mail') ?>
    </a>
</li>
    <?php endif; ?>
<li>
    <form
        id="pform"
        name="pform"
        class="pform"
        method="GET"
        action="<?php echo Url::build('/treaty/copy/%d/', [$record->getId()]) ?>"
        accept-charset="utf-8"
        autocomplete="off"
        enctype="multipart/form-data">
        <input type="hidden" name="token" value="<?php echo Security::getCSRFToken() ?>" />
        <select
            name="copyas"
            required="required">
            <option value=""><?php echo I18n::__('treaty_copy_as') ?></option>
            <?php foreach (R::find('contracttype', ' enabled = 1 AND service = 1 ORDER BY name') as $_id => $_contracttype): ?>
            <option value="<?php echo $_id ?>" <?php echo $record->getTreatygroup()->getContracttype()->getId() == $_id ? 'selected="selected"' : '' ?>><?php echo $_contracttype->name ?></option>
            <?php endforeach; ?>
        </select>
        <input
            name="submit"
            type="submit"
            value="<?php echo I18n::__('treaty_action_copy_as') ?>" />
    </form>
</li>
<li>
    <a
        href="<?php echo Url::build("/{$type}/form/{$record->getId()}") ?>"
        accesskey="f">
        <?php echo I18n::__('treaty_action_form') ?>
    </a>
</li>
<li>
    <a
        href="<?php echo Url::build("/{$type}/pdf/{$record->getId()}") ?>"
        accesskey="p">
        <?php echo I18n::__('treaty_action_pdf') ?>
    </a>
</li>
<?php endif; ?>
