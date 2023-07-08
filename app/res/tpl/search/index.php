<?php
/**
 * Template to present all global search results, grouped by type.
 */
?>
<?php
$_lastType = null;
?>
<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo $title ?></h1>
        <nav>
            <?php echo $toolbar ?>
        </nav>
    </header>
    <div class="panel">
        <?php if (empty($records)) : ?>
        <p><?php echo I18n::__('gsearch_no_records_found') ?></p>    
        <?php else : ?>
            <?php foreach ($records as $_type => $_beans) : ?>
                <?php
                if ($_lastType !== $_type) :
                    $_lastType = $_type;
                    ?>
        <h2><?php echo $_type ?></h2>
                    <?php if (count($_beans)) : ?>
                        <?php foreach ($_beans as $_id => $_bean) : ?>
        <p><a href="<?php echo Url::build('/admin/%s/edit/%d', [$_bean->getMeta('type'), $_bean->getId()]) ?>"><?php echo $_bean->shortDescriptiveTitle() ?></a></p>
                        <?php endforeach ?>
                    <?php else : ?>
        <p><?php echo I18n::__('gsearch_no_records_of_type_found') ?></p>    
                    <?php endif ?>
                <?php endif ?>
            <?php endforeach ?>
        <?php endif ?>
    </div>
</article>
