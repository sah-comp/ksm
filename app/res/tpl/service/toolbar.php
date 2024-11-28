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
<ul class="panel-navigation">
    <li class="pday">
        <form
            id="pform"
            name="pform"
            class="pform"
            method="POST"
            accept-charset="utf-8"
            autocomplete="off"
            enctype="multipart/form-data">
            <input type="hidden" name="token" value="<?php echo Security::getCSRFToken() ?>" />
            <input
                id="pday"
                type="text"
                name="pday"
                class="pday"
                placeholder="<?php echo I18n::__('placeholder_intl_date') ?>"
                required="required"
                value="<?php echo date('d.m.Y', strtotime($pdate)) ?>" />
            <input
                name="submit"
                type="submit"
                value="<?php echo I18n::__('service_action_print_day') ?>" />
        </form>
    </li>
    <li>
        <a
            href="<?php echo Url::build("/service") ?>">
            <?php echo I18n::__('action_reload_service') ?>
            <span
                id="service-badge-container"
                class="heartbeat badge-container"
                data-container="service-badge-container"
                data-delay="60000"
                data-href="<?php echo Url::build('/service/recheck') ?>"></span>
        </a>
    </li>
    <li>
        <a
            href="<?php echo Url::build("/admin/appointment/add/table/?goto=" . urlencode('/service')) ?>"
            accesskey="+">
            <?php echo I18n::__('action_add_nav') ?>
        </a>
    </li>
</ul>