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
<?php
$_value = "";
if (isset($payload[$limb->stub])) :
    $_value = $payload[$limb->stub];
endif;
?>
<!-- limb, which is a virtual field, defined in contracttype -->
<div class="row">
    <label>
        <?php echo $limb->name ?>
    </label>
<?php
switch ($limb->tag) :
    case 'textarea':
        ?>
        <textarea
            id="<?php echo $limb->stub ?>"
            name="limb[<?php echo $limb->stub ?>]"
            placeholder="<?php echo htmlspecialchars($limb->placeholder) ?>"
            rows="5"
            cols="60"><?php echo htmlspecialchars($_value) ?></textarea>
        <?php
        break;

    default:
        ?>
        <input
            id="<?php echo $limb->stub ?>"
            type="text"
            name="limb[<?php echo $limb->stub ?>]"
            placeholder="<?php echo htmlspecialchars($limb->placeholder) ?>"
            value="<?php echo htmlspecialchars($_value) ?>">
        <?php
        break;
endswitch;
?>
</div>
<!-- end of limb part -->
