<?php
/**
 *
 */
?>
<a data-ident="<?php echo $record->id ?>" class="inspector" data-intrinsic="<?php echo $href ?>" href="<?php echo Url::build('/filer/inspector/%s', [$record->ident]) ?>" title=""><?php echo htmlspecialchars($record->file) ?></a>
