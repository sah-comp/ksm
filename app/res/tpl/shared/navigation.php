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
<!-- navigation -->
<div id="hamburger" class="" data-target="page">
    <span></span>
    <span></span>
    <span></span>
</div>
<nav>
    <?php echo $navigation_account ?>
    <div id="main-menu">
        <?php echo $navigation_main ?>
    </div>
    <?php
    if (CINNEBAR_GLOBAL_SEARCH_FLAG) :
    ?>
        <form
            id="gsearch"
            name="gsearch"
            accept-charset="utf-8"
            enctype="multipart"
            method="GET"
            action="/search">
            <fieldset>
                <legend class="verbose"><?php echo I18n::__('gsearch_legend') ?></legend>
                <div class="row">
                    <label for="q">
                        <?php echo I18n::__('gsearch_searchtext') ?>
                    </label>
                    <input
                        type="text"
                        id="q"
                        name="q"
                        value="<?php echo isset($q) ? $q : '' ?>"
                        placeholder="<?php echo I18n::__('gsearch_placeholder') ?>" />
                </div>
                <input type="submit" hidden />
            </fieldset>
        </form>
    <?php
    endif;
    ?>
</nav>
<!-- End of navigation -->