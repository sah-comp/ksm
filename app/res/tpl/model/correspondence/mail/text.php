<?php echo nl2br(strip_tags($record->payload)) ?>
--
<?php echo htmlspecialchars($company->legalname) ?>
<?php echo htmlspecialchars($company->street) ?>
<?php echo htmlspecialchars($company->zip) ?> <?php echo htmlspecialchars($company->city) ?>

Telefon <?php echo htmlspecialchars($company->phone) ?>
Fax <?php echo htmlspecialchars($company->fax) ?>
Email <?php echo htmlspecialchars($company->email) ?>
<?php echo htmlspecialchars($company->website) ?>

<?php echo Flight::textile(I18n::__('ksm_transaction_signature', null, [
    $company->taxid,
    $company->vatid
])) ?>
