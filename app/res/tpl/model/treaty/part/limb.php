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
<!-- limb, which is a virtual field, defined in contracttype -->
<div class="row">
    <label><?php echo $_limb->name ?></label>
    <input
        type="text"
        name="limb[<?php echo $_limb->stub ?>]"
        placeholder="<?php echo $_limb->placeholder ?>"
        value="<?php echo $_payload[$_limb->stub] ?>">
</div>
<!-- end of limb part -->
