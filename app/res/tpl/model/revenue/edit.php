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
<!-- revenue edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('revenue_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('fy')) ? 'error' : ''; ?>">
        <label
            for="revenue-fy">
            <?php echo I18n::__('revenue_label_fy') ?>
        </label>
        <input
            id="revenue-fy"
            type="text"
            name="dialog[fy]"
            value="<?php echo htmlspecialchars($record->fy) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('month')) ? 'error' : ''; ?>">
        <label
            for="revenue-month">
            <?php echo I18n::__('revenue_label_month') ?>
        </label>
        <select
            id="revenue-month"
            name="dialog[month]">
            <?php foreach ($record->getMonths() as $_month): ?>
            <option
                value="<?php echo $_month ?>"
                <?php echo ($record->month == $_month) ? 'selected="selected"' : '' ?>><?php echo I18n::__('month_label_' . $_month) ?>
            </option>
            <?php endforeach ?>
        </select>
    </div>
    <div class="row <?php echo ($record->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="revenue-name">
            <?php echo I18n::__('revenue_label_name') ?>
        </label>
        <input
            id="revenue-name"
            type="text"
            name="dialog[name]"
            value="<?php echo htmlspecialchars($record->name) ?>"
            required="required" />
    </div>
</fieldset>
<?php
if ($record->getId()):
    $_report = $record->report();
    Flight::render('model/revenue/report/month', [
        'costunittypes' => $_report['costunittypes'],
        'records' => $_report['revenues'],
        'totals' => $_report['totals']
    ]);
endif;
?>
<!-- end of revenue edit form -->
