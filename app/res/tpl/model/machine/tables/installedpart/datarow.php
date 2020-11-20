<?php
/**
 * One table row of an installedpart bean within machine/installedpart
 */
$_type = $_installedpart->getMeta('type');
$_id = $_installedpart->getId();
$_ip_allowed = Permission::validate(Flight::get('user'), 'machine', 'pricing');
?>
<tr id="machine-<?php echo $record->getId() ?>-installedpart-<?php echo $_installedpart->getId() ?>">
    <td
        data-sort="<?php echo $_article->number ?>">
        <a
            href="<?php echo Url::build('/admin/%s/edit/%d/', [$_article->getMeta('type'), $_article->getId()]) ?>"
            class="in-table">
            <?php echo htmlspecialchars($_article->number) ?>
        </a>
    </td>
    <td
        data-sort="<?php echo $_article->description ?>">
        <a
            href="<?php echo Url::build('/admin/%s/edit/%d/', [$_article->getMeta('type'), $_article->getId()]) ?>"
            class="in-table">
            <?php echo htmlspecialchars($_article->description) ?>
        </a>
    </td>
    <td
        data-sort="<?php echo htmlspecialchars($_article->getSupplier()->name) ?>">
        <?php echo htmlspecialchars($_article->getSupplier()->name) ?>
    </td>
    <td
        class="number"
        data-sort="<?php echo $_installedpart->decimal('purchaseprice') ?>">

        <input
            id="<?php echo $_type ?>-<?php echo $_id ?>-date"
            name="installedpart[purchaseprice]"
            type="text"
            class="enpassant num"
            <?php echo ($_ip_allowed) ? '' : 'readonly="readonly"' ?>
            data-url="<?php echo Url::build('/enpassant/%s/%d/%s/?callback=?', [$_type, $_id, 'purchaseprice']) ?>"
            value="<?php echo htmlspecialchars($_installedpart->decimal('purchaseprice')) ?>" />

    </td>
    <td
        class="number"
        data-sort="<?php echo $_installedpart->decimal('salesprice') ?>">

        <input
            id="<?php echo $_type ?>-<?php echo $_id ?>-date"
            name="installedpart[salesprice]"
            type="text"
            class="enpassant num"
            <?php echo ($_ip_allowed) ? '' : 'readonly="readonly"' ?>
            data-url="<?php echo Url::build('/enpassant/%s/%d/%s/?callback=?', [$_type, $_id, 'salesprice']) ?>"
            value="<?php echo htmlspecialchars($_installedpart->decimal('salesprice')) ?>" />

    </td>
    <td
        data-sort="<?php echo $_installedpart->stamp ?>">

        <input
            id="<?php echo $_type ?>-<?php echo $_id ?>-date"
            name="installedpart[stamp]"
            type="date"
            class="enpassant"
            <?php echo ($_ip_allowed) ? '' : 'readonly="readonly"' ?>
            placeholder="<?php echo I18n::__('placeholder_intl_date') ?>"
            data-url="<?php echo Url::build('/enpassant/%s/%d/%s/?callback=?', [$_type, $_id, 'stamp']) ?>"
            value="<?php echo htmlspecialchars($_installedpart->stamp) ?>" />
        <?php if ($_ip_allowed): ?>
        <a
            class="ir action action-delete"
            href="<?php echo Url::build('/admin/installedpart/kill/%d', [$_installedpart->getId()]) ?>"
            title="<?php echo I18n::__('action_tooltip_delete') ?>"
            data-target="machine-<?php echo $record->getId() ?>-installedpart-<?php echo $_installedpart->getId() ?>">
            <?php echo I18n::__('action_installedpart_delete') ?>
        </a>
        <?php endif; ?>
    </td>
</tr>
