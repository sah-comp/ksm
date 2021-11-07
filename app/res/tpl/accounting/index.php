<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo $title ?></h1>
        <nav>
            <?php echo $toolbar ?>
        </nav>
    </header>
    <div class="panel">
        <div class="scaffold accounting clearfix">
        <?php foreach ($records as $_id => $_record): ?>
            <div class="domain">
                <h1><a href="<?php echo Url::build('/' . $_record->url) ?>"><?php echo htmlspecialchars(I18n::__($_record->name . '_h1')) ?></a></h1>
                <p class="the-count"><?php echo R::count($_record->name) ?></p>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</article>
