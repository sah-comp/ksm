<?php
/**
 * One table row of an installedpart bean within machine/installedpart
 */
?>
<tr id="machine-<?php echo $record->getId() ?>-installedpart-<?php echo $_installedpart->getId() ?>">
    <td
        data-order="<?php echo $_article->number ?>">
        <a
            href="<?php echo Url::build('/admin/%s/edit/%d/', [$_article->getMeta('type'), $_article->getId()]) ?>"
            class="in-table">
            <?php echo htmlspecialchars($_article->number) ?>
        </a>
    </td>
    <td
        data-order="<?php echo $_article->description ?>">
        <a
            href="<?php echo Url::build('/admin/%s/edit/%d/', [$_article->getMeta('type'), $_article->getId()]) ?>"
            class="in-table">
            <?php echo htmlspecialchars($_article->description) ?>
        </a>
    </td>
    <td
        data-order="<?php echo $_article->isoriginal ?>">
        <?php echo htmlspecialchars($_article->boolean('isoriginal')) ?>
    </td>
    <td
        class="number"
        data-order="<?php echo $_installedpart->purchaseprice ?>">
        <?php echo htmlspecialchars($_installedpart->decimal('purchaseprice')) ?>
    </td>
    <td
        class="number"
        data-order="<?php echo $_installedpart->salesprice ?>">
        <?php echo htmlspecialchars($_installedpart->decimal('salesprice')) ?>
    </td>
    <td
        data-order="<?php echo $_installedpart->stamp ?>">
        <?php echo htmlspecialchars($_installedpart->localizedDate('stamp')) ?>
        <a
            class="ir action action-delete"
            href="<?php echo Url::build('/admin/installedpart/kill/%d', [$_installedpart->getId()]) ?>"
            title="<?php echo I18n::__('action_tooltip_delete') ?>"
            data-target="machine-<?php echo $record->getId() ?>-installedpart-<?php echo $_installedpart->getId() ?>">
            <?php echo I18n::__('action_installedpart_delete') ?>
        </a>
    </td>
</tr>
