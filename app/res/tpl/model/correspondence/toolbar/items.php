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
        href="<?php echo Url::build("/correspondence/mail/%d", [$record->getId()]) ?>"
        class="confirm mail <?php echo $record->wasEmailed() ?>">
        <?php echo I18n::__('correspondence_action_mail') ?>
    </a>
</li>
    <?php endif; ?>
<li>
    <form
        id="printform"
        name="printform"
        class="pform"
        method="GET"
        action="<?php echo Url::build('/correspondence/pdf/%d/', [$record->getId()]) ?>"
        accept-charset="utf-8"
        autocomplete="off"
        enctype="multipart/form-data">
        <select
            name="layout">
            <?php foreach ($record->getPrintLayouts() as $_layout => $_default): ?>
            <option value="<?php echo $_layout ?>" <?php echo ($_default) ? 'selected="selected"' : '' ?>><?php echo I18n::__('correspondence_layout_' . $_layout) ?></option>
            <?php endforeach; ?>
        </select>
        <input
            name="submit"
            type="submit"
            value="<?php echo I18n::__('correspondence_action_pdf') ?>" />
    </form>
</li>
<?php elseif ($hasRecords): ?>
<li>
    <a
        href="<?php echo Url::build("/correspondence/pdf") ?>">
        <?php echo I18n::__('correspondence_action_pdf_list') ?>
    </a>
</li>
<?php else: ?>
<!-- There are no records -->
<?php endif; ?>
