<?php if ($record->getId()): ?>
<li>
    <a
        href="<?php echo Url::build("/{$type}/csv/{$record->getId()}") ?>"
        accesskey="e">
        <?php echo I18n::__('revenue_action_csv') ?>
    </a>
</li>
<li>
    <a
        href="<?php echo Url::build("/{$type}/pdf/{$record->getId()}") ?>"
        accesskey="p">
        <?php echo I18n::__('revenue_action_pdf') ?>
    </a>
</li>
<?php endif; ?>
