<?php
/**
 * Special buttons for transaction form.
 */
?>
<select name="next_action">
    <?php foreach ($actions[$current_action] as $action) : ?>
    <option
        value="<?php echo $action ?>"
        <?php echo ($next_action == $action) ? 'selected="selected"' : '' ?>><?php echo I18n::__("action_{$action}_select") ?></option>
    <?php endforeach ?>
</select>
<input
    type="submit"
    name="submit"
    class="trans <?php echo (Flight::get('user')->current()->isBooking() && $record->getContracttype()->nickname == 'RE') ? 'confirm' : '' ?>"
    accesskey="s"
    value="<?php echo I18n::__('scaffold_submit_apply_action') ?>" />
