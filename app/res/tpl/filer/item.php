<?php
/**
 *
 */
?>
<a data-ident="<?php echo $record->id ?>" class="inspector <?php echo $record->isTemplate() ? 'template' : '' ?>" data-intrinsic="<?php echo $href ?>" href="<?php echo Url::build('/filer/inspector/%s', [$record->ident]) ?>" title="<?php echo I18n::__('file_dblclick_to_open') ?>"><?php echo htmlspecialchars($record->filename) ?></a>
