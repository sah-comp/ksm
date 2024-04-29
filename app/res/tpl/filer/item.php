<?php
    /**
     * File item template used in app/Model/File.php::listFiles()
     */
?>
<a id="file-<?php echo $record->ident ?>" data-ident="<?php echo $record->id ?>" class="inspector<?php echo $record->isTemplate() ? ' template' : '' ?><?php echo $record->hasMachine() ? ' hasmachine' : '' ?>" data-intrinsic="<?php echo $href ?>" href="<?php echo Url::build('/filer/inspector/%s', [$record->ident]) ?>" title="<?php echo I18n::__('file_dblclick_to_open') ?>"><?php echo htmlspecialchars($record->filename ?? '') ?></a>