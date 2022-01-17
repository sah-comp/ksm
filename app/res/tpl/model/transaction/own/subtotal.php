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
<!-- position edit subtotal -->
<fieldset
    id="transaction-<?php echo $record->getId() ?>-ownPosition-<?php echo $index ?>">
    <legend class="verbose"><?php echo I18n::__('transaction_legend_position') ?></legend>
    <a
    	href="<?php echo Url::build(sprintf('/admin/transaction/detach/position/%d', $_position->getId())) ?>"
    	class="ir detach"
    	title="<?php echo I18n::__('scaffold_detach') ?>"
    	data-target="transaction-<?php echo $record->getId() ?>-ownPosition-<?php echo $index ?>">
    		<?php echo I18n::__('scaffold_detach') ?>
    </a>
    <a
    	href="<?php echo Url::build(sprintf('/admin/transaction/attach/own/position/%d', $record->getId())) ?>"
    	class="ir attach"
    	title="<?php echo I18n::__('scaffold_attach') ?>"
    	data-target="transaction-<?php echo $record->getId() ?>-position-container">
    		<?php echo I18n::__('scaffold_attach') ?>
    </a>

    <div>
        <input
            type="hidden"
            name="dialog[ownPosition][<?php echo $index ?>][type]"
            value="<?php echo $_position->getMeta('type') ?>" />
        <input
            type="hidden"
            name="dialog[ownPosition][<?php echo $index ?>][id]"
            value="<?php echo $_position->getId() ?>" />
        <input
            type="hidden"
            class="currentindex"
            name="dialog[ownPosition][<?php echo $index ?>][currentindex]"
            value="<?php echo $index ?>" />
    </div>

    <div class="row">

        <div class="span1">
            &nbsp;
        </div>
        <div class="span1">
            <div class="row flex-center">
                <div class="span12">
                    <h2 class="ir drag-handle"><?php echo I18n::__('ui_action_drag_handle') ?></h2>
                    <select
                        id="transaction-<?php echo $record->getId() ?>-position-<?php echo $index ?>-kind"
                        name="dialog[ownPosition][<?php echo $index ?>][kind]">
                        <option
                            value="<?php echo Model_Position::KIND_POSITION ?>"
                            <?php echo ($_position->kind == Model_Position::KIND_POSITION) ? 'selected="selected"' : '' ?>><?php echo I18n::__('position_kind_position') ?></option>
                        <option
                            value="<?php echo Model_Position::KIND_SUBTOTAL ?>"
                            <?php echo ($_position->kind == Model_Position::KIND_SUBTOTAL) ? 'selected="selected"' : '' ?>><?php echo I18n::__('position_kind_subtotal') ?></option>
                        <option
                            value="<?php echo Model_Position::KIND_FREETEXT ?>"
                            <?php echo ($_position->kind == Model_Position::KIND_FREETEXT) ? 'selected="selected"' : '' ?>><?php echo I18n::__('position_kind_freetext') ?></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="span1">
            &nbsp;
        </div>
        <div class="span3">
            <textarea
                id="transaction-<?php echo $record->getId() ?>-position-<?php echo $index ?>-product-desc"
                name="dialog[ownPosition][<?php echo $index ?>][desc]"
                rows="2"
                cols="60"><?php echo htmlspecialchars($_position->desc) ?></textarea>
        </div>
        <div class="span4">
            &nbsp;
        </div>
        <div class="span2 tar">
            <input
                type="text"
                class="number"
                readonly="readonly"
                name="subtotal[<?php echo $index ?>]"
                value="<?php echo htmlspecialchars(Flight::nformat($_subtotal)) ?>">
            <?php $_SESSION['subtotal'] = 0 ?>
        </div>
    </div>
</fieldset>
<!-- /position edit subtotal -->
