<?php echo I18n::__('transaction_text_mail_invoice', null, [
    $record->getContracttype()->name,
    $record->number,
    $record->localizedDate('bookingdate')
]) ?>
--
<?php echo htmlspecialchars($company->legalname) ?>
<?php echo htmlspecialchars($company->website) ?>
<?php echo htmlspecialchars($company->street) ?>
<?php echo htmlspecialchars($company->zip) ?> <?php echo htmlspecialchars($company->city) ?>
Telefon <?php echo htmlspecialchars($company->phone) ?>
Fax <?php echo htmlspecialchars($company->fax) ?>
Email <?php echo htmlspecialchars($company->emailnoreply) ?>
