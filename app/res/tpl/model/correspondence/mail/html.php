<style>
<?php Flight::render('model/correspondence/style/css', ['record' => $record]) ?>
</style>
<?php echo $record->payload ?>
<div class="footer">
<?php
Flight::render('shared/mail/signature', [
    'company' => $company,
    'user' => $user
]);
?>
</div>
