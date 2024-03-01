<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo $title ?></h1>
        <nav>
            <?php echo $toolbar ?>
        </nav>
    </header>
    <div id="directory">
        <div class="panel tree">
            <?php
            //echo $record->dir(DMS_PATH);
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
