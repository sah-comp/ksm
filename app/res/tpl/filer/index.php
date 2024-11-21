<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo $title ?></h1>
        <details open name="toolbar" class="toolbar">
            <summary><?php echo I18n::__('toolbar_details_title') ?></summary>
            <?php echo $toolbar ?>
        </details>
    </header>
    <div id="directory">
        <div class="panel tree">
            <?php
            //echo $record->dir(DMS_PATH);
            $record->deactiveFiles(DMS_PATH);
            $record->listFiles(DMS_PATH);
            ?>
        </div>
    </div>
    <div id="sidebar">
        <div class="panel">
            <div id="inspector">
                <div class="initally-empty">
                    <p><?php echo I18n::__('file_inspector_select_one') ?></p>
                </div>
            </div>
        </div>
    </div>
    <div id="footer" class="clearfix">
        &nbsp;
    </div>
</article>