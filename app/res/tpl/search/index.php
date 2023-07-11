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
        <p class="none-found"><?php echo I18n::__('gsearch_no_records_found') ?></p>    
        <?php else : ?>
            <?php foreach ($records as $_type => $_beans) : ?>
                <?php
                if ($_lastType !== $_type) :
                    $_lastType = $_type;
                    ?>
                    <?php $total_records = count($_beans); ?>
                    <?php if ($total_records) :
                                $_firstbean = reset($_beans);
                        ?>
                        <h2 class="some-found"><?php echo I18n::__($_type . '_h1') ?></h2>
                        <table class="scaffold gsearch">
                            <caption>
                            <?php echo I18n::__('scaffold_caption_index', null, array($total_records)) ?>
                            </caption>
                            <thead>
                                <tr>
                                    <th class="edit"></th>
                                    <?php echo $_firstbean->defaultTableHead() ?>
                                </tr>
                            </thead>
                            <tbody>
                        <?php foreach ($_beans as $_id => $_bean) : ?>
                                <tr
                                    data-href="<?php echo Url::build('/admin/%s/edit/%d', [$_bean->getMeta('type'), $_bean->getId()]) ?>">
                                    <td>
                                        <a 
                                            class="ir action action-edit" 
                                            href="<?php echo Url::build('/admin/%s/edit/%d', [$_bean->getMeta('type'), $_bean->getId()]) ?>">
                                        </a>
                                    </td>
                                    <?php echo $_bean->defaultTableBody() ?>
                                </tr>
                        <?php endforeach ?>
                            </tbody>
                        </table>
                    <?php endif ?>
                <?php endif ?>
            <?php endforeach ?>
        <?php endif ?>
    </div>
</article>
