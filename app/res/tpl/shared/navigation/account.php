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
<!-- account menu -->
<ul class="account-navigation clearfix">
    <li>
        <a
            href="<?php echo Url::build('/account/') ?>">
            <img
                src="<?php echo Gravatar::src(Flight::get('user')->email, 16) ?>"
                width="16"
                height="16"
                alt="<?php echo htmlspecialchars(Flight::get('user')->getName()) ?>" />
            <span><?php echo htmlspecialchars(Flight::get('user')->getName()) ?></span>
        </a>
    </li>
    <li>
        <a
            class="logout" 
            href="<?php echo Url::build('/logout/') ?>">
            <span><?php echo I18n::__('account_logout_nav') ?></span>
        </a>
    </li>
</ul>
<!-- End of account menu -->