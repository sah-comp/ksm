<div style="height: 18mm;"></div>

<h1 class="emphasize">
    <?php echo $record->getContracttype()->name ?>
</h1>
<?php echo Flight::textile($record->header) ?>

<?php
Flight::render('model/transaction/pdf/table', [
    'record' => $record
]);
?>

<?php if ($record->getContracttype()->hideall): ?>
<?php elseif (!$record->getContracttype()->hidesome): ?>
<div class="payment-conditions">
    <?php echo Flight::textile($record->paymentConditions()) ?>
</div>
<?php else: ?>
<div class="transaction-conditions">
    <?php echo Flight::textile($record->transactionConditions()) ?>
</div>
<?php endif; ?>

<?php echo Flight::textile($record->footer) ?>
