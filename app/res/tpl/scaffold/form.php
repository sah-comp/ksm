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
<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo I18n::__("{$type}_h1") ?></h1>
        <?php if ($record->hasQuickFilter() && count($records)): ?>
            <?php
            Flight::render('scaffold/quickfilter', [
                'record' => $record
            ]);
            ?>
        <?php endif; ?>
        <div id="additional-info-container" class=""></div>
        <details name="toolbar" class="toolbar">
            <summary><?php echo I18n::__('toolbar_details_title') ?></summary>
            <?php echo $toolbar ?>
        </details>
    </header>
    <!-- scaffold edit form -->
    <form
        id="form-<?php echo $record->getMeta('type') ?>"
        class="checko panel panel-<?php echo $record->getMeta('type') ?> action-<?php echo $current_action ?>"
        method="POST"
        accept-charset="utf-8"
        autocomplete="off"
        enctype="multipart/form-data">

        <input type="hidden" name="token" value="<?php echo Security::getCSRFToken() ?>" />
        <input type="hidden" name="goto" value="<?php echo $goto ?>" />

        <!-- form details -->
        <?php echo $form_details ?>
        <!-- end of form details -->

        <!-- Scaffold buttons -->
        <div class="buttons">
            <?php
            if (isset($type) && $record->hasScaffoldButtons()):
                Flight::render("model/{$type}/scaffold/buttons.php", [
                    'record' => $record,
                    'type' => $type,
                    'base_url' => $base_url,
                    'layout' => $layout,
                    'order' => $order,
                    'dir' => $dir,
                    'actions' => $actions,
                    'next_action' => $next_action
                ]);
            else:
            ?>
                <select name="next_action">
                    <?php foreach ($actions[$current_action] as $action): ?>
                        <option
                            value="<?php echo $action ?>"
                            <?php echo ($next_action == $action) ? 'selected="selected"' : '' ?>><?php echo I18n::__("action_{$action}_select") ?></option>
                    <?php endforeach ?>
                </select>
                <input
                    type="submit"
                    name="submit"
                    accesskey="s"
                    value="<?php echo I18n::__('scaffold_submit_apply_action') ?>" />
            <?php
            endif;
            ?>
        </div>
        <!-- End of Scaffold buttons -->
    </form>
</article>