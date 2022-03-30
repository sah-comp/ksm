<?php echo Flight::textile(I18n::__('transaction_text_mail_invoice', null, [
    $record->getContracttype()->name,
    $record->number,
    $record->localizedDate('bookingdate')
])) ?>
--<br />
<strong><?php echo htmlspecialchars($company->legalname) ?></strong><br />
<a href="<?php echo $company->website ?>"><?php echo htmlspecialchars($company->website) ?></a><br />
<?php echo htmlspecialchars($company->street) ?><br />
<?php echo htmlspecialchars($company->zip) ?> <?php echo htmlspecialchars($company->city) ?><br />
Telefon <?php echo htmlspecialchars($company->phone) ?><br />
Fax <?php echo htmlspecialchars($company->fax) ?><br />
Email <a href="mailto:<?php echo $company->emailnoreply ?>"><?php echo htmlspecialchars($company->emailnoreply) ?></a>
</p>
