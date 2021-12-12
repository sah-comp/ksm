<div style="height: 18mm;"></div>

<h1 class="emphasize">
    <?php echo $record->getContracttype()->name ?>
</h1>
<?php $_colspan = 5 ?>
<?php echo Flight::textile($record->header) ?>

<table class="position" width="100%">
    <thead>
        <tr>
            <th width="5%" class="bb"><?php echo I18n::__('position_label_sequence') ?></th>
            <th width="7%" class="bb"><?php echo I18n::__('position_label_product') ?></th>
            <th width="40%" class="bb"><?php echo I18n::__('position_label_product_desc') ?></th>
            <th width="8%" class="bb number cushion-right"><?php echo I18n::__('position_label_count') ?></th>
            <th width="8%" class="bb"><?php echo I18n::__('position_label_unit') ?></th>
            <th width="16%" class="bb number"><?php echo I18n::__('position_label_salesprice') ?></th>
            <th width="16%" class="bb number"><?php echo I18n::__('position_label_total') ?></th>
        </tr>
    </thead>
    <tfoot>
        <tr class="lofty">
            <td colspan="<?php echo $_colspan ?>" class="bt">&nbsp;</td>
            <td class="bt number"><?php echo I18n::__('transaction_label_total_net') ?></td>
            <td class="bt number"><?php echo htmlspecialchars($record->decimal('net')) ?></td>
        </tr>
        <?php if (!$record->getContracttype()->hidesome): ?>
        <?php $vats = $record->getVatSentences(); ?>
        <?php foreach ($vats as $_id => $_vat): ?>
        <tr class="lofty">
            <td colspan="<?php echo $_colspan ?>" class="number"></td>
            <td class="bt number"><?php echo I18n::__('transaction_label_vatcode', null, [$_vat['vatpercentage']]) ?></td>
            <td class="bt number"><?php echo htmlspecialchars(Flight::nformat($_vat['vatvalue'])) ?></td>
        </tr>
        <?php endforeach ?>
        <tr class="lofty">
            <td colspan="<?php echo $_colspan ?>" class="">&nbsp;</td>
            <td class="bt bb bold number"><?php echo I18n::__('transaction_label_total_gros') ?></td>
            <td class="bt bb bold number"><?php echo htmlspecialchars($record->decimal('gros')) ?></td>
        </tr>
        <?php endif; ?>
    </tfoot>
    <tbody>
        <?php
        /**
         * Output position beans of this transaction.
         */
        $_subtotal = 0;//adding up each (real) position (which is not alt.) to output a subtotal if wanted
        ?>
        <?php foreach ($record->with(' ORDER BY currentindex ASC ')->ownPosition as $_id => $_position): ?>
        <tr class="<?php echo $_position->isAlternative() ? 'alternative' : '' ?> <?php echo $_position->kindAsCss() ?>">
            <?php
            switch ($_position->kind):
                case Model_Position::KIND_SUBTOTAL:
                    ?>
                    <td class="" colspan="5">&nbsp;</td>
                    <td class="bt bb number"><?php echo Flight::textile($_position->desc) ?></td>
                    <td class="bt bb number"><?php echo Flight::nformat($_subtotal) ?></td>
                    <?php
                    $_subtotal = 0;//reset subtotal memory to zero (0), allowing the next one to come up
                    break;

                case Model_Position::KIND_FREETEXT:
                    ?>
                    <td colspan="5"><?php echo Flight::textile($_position->desc) ?></td>
                    <td colspan="2">&nbsp;</td>
                    <?php
                    break;

                case Model_Position::KIND_POSITION:
                    if (!$_position->isAlternative()) {
                        $_subtotal += $_position->total;//adding up this position total to our subtotal, in case
                    }
                    ?>
                    <td class="nan"><?php echo htmlspecialchars($_position->decimal('sequence', 0)) ?></td>
                    <td><?php echo htmlspecialchars($_position->getProduct()->number) ?></td>
                    <td><?php echo Flight::textile($_position->desc) ?></td>
                    <td class="number cushion-right"><?php echo htmlspecialchars($_position->count) ?></td>
                    <td><?php echo htmlspecialchars($_position->unit) ?></td>
                    <td class="number"><?php echo htmlspecialchars($_position->decimal('salesprice')) ?></td>
                    <td class="number"><?php echo htmlspecialchars($_position->decimal('total')) ?></td>
                    <?php
                    break;
            endswitch;
            ?>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

<?php if (!$record->getContracttype()->hidesome): ?>
<div class="payment-conditions dinky">
    <?php echo Flight::textile($record->paymentConditions()) ?>
</div>
<?php else: ?>
<div class="transaction-conditions dinky">
    <?php echo Flight::textile($record->transactionConditions()) ?>
</div>
<?php endif; ?>

<?php echo Flight::textile($record->footer) ?>
