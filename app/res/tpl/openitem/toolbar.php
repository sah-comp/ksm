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
    <li>
		<a
			href="<?php echo Url::build("/openitem/index") ?>">
			<?php echo I18n::__('action_list_nav') ?>
		</a>
	</li>
    <?php if ($hasRecords): ?>
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
            <select
                id="pday"
                name="person_id"
                required="required">
                <option value=""><?php echo I18n::__('openitem_select_customer_for_statement') ?></option>
                <?php foreach ($record->getCustomersWithOpenItems() as $_id => $_name): ?>
                <option value="<?php echo $_id ?>" <?php echo ($person_id == $_id) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_name) ?></option>
                <?php endforeach; ?>
            </select>
            <input
                name="submit"
                type="submit"
                value="<?php echo I18n::__('openitem_action_print_statement') ?>" />
        </form>
	</li>
    <li>
		<a
			href="<?php echo Url::build("/openitem/pdf") ?>"
			accesskey="p">
			<?php echo I18n::__('openitem_action_pdf') ?>
		</a>
	</li>
    <?php endif; ?>
</ul>
