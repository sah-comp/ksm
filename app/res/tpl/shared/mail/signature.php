<?php
/**
 * Global E-Mail Signature template.
 *
 * When user has a personal signature it will be used. If not, a global signature is used.
 */
?>
--<br />
<?php
if ($user->mailsig) :
    echo Flight::textile($user->mailsig);
else :
    ?>
<p>
<img src="cid:ksm-mascot" alt="<?php echo I18n::__('ksm_mascot') ?>" /><br />
<strong><?php echo htmlspecialchars($company->legalname) ?></strong><br />
    <?php echo htmlspecialchars($company->street) ?><br />
    <?php echo htmlspecialchars($company->zip) ?> <?php echo htmlspecialchars($company->city) ?><br />
<br />
Telefon <?php echo htmlspecialchars($company->phone) ?><br />
Fax <?php echo htmlspecialchars($company->fax) ?><br />
Email <a href="mailto:<?php echo $company->email ?>"><?php echo htmlspecialchars($company->email) ?></a><br />
Web <a href="<?php echo $company->website ?>"><?php echo htmlspecialchars($company->website) ?></a>
</p>
    <?php echo Flight::textile(I18n::__('ksm_transaction_signature', null, [
    $company->taxid,
    $company->vatid
    ])) ?>
    <?php
endif;
?>