<?php
/**
 * Special buttons for scaffold form.
 */
?>
<select name="next_action">
    <?php foreach ($actions[$current_action] as $action): ?>
    <option
        value="<?php echo $action ?>"
        <?php echo ($next_action == $action) ? 'selected="selected"' : '' ?>><?php echo I18n::__("action_{$action}_select") ?></option>
    <?php endforeach ?>
</select>
<input
    type="text"
    name="user-input"
    class="lesswidth"
    value="" />
<input
    type="submit"
    name="submit"
    accesskey="s"
    value="<?php echo I18n::__('appointment_submit_apply_action') ?>" />
