<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo $title ?></h1>
        <nav>
            <?php echo $toolbar ?>
        </nav>
    </header>
    <div class="panel tree">
        <?php
        //echo $record->dir(DMS_PATH);
        $record->listFiles(DMS_PATH);
        ?>
    </div>
</article>
