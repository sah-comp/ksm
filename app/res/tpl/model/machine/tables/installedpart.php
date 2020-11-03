<?php
/**
 * List all installed parts (articles).
 */
Flight::render('script/datatable_config');
// load our contracts from which the machines are taken
$_ip = R::find('installedpart', "machine_id = ? ORDER BY @joined.article.description", [$record->getId()]);
?>
<table class="datatable">
    <thead>
        <tr>
            <th><?php echo I18n::__('article_label_number') ?></th>
            <th><?php echo I18n::__('article_label_description') ?></th>
            <th><?php echo I18n::__('article_label_isoriginal') ?></th>
            <th class="number"><?php echo I18n::__('article_label_purchaseprice') ?></th>
            <th class="number"><?php echo I18n::__('article_label_salesprice') ?></th>
            <th><?php echo I18n::__('article_label_stamp') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach ($_ip as $_ip_id => $_installedpart):
        $_article = $_installedpart->getArticle();
    ?>
        <tr>
            <td>
                <a
                    href="<?php echo Url::build('/admin/%s/edit/%d/', [$_article->getMeta('type'), $_article->getId()]) ?>"
                    class="in-table">
                    <?php echo htmlspecialchars($_article->number) ?>
                </a>
            </td>
            <td>
                <a
                    href="<?php echo Url::build('/admin/%s/edit/%d/', [$_article->getMeta('type'), $_article->getId()]) ?>"
                    class="in-table">
                    <?php echo htmlspecialchars($_article->description) ?>
                </a>
            </td>
            <td>
                <?php echo htmlspecialchars($_article->boolean('isoriginal')) ?>
            </td>
            <td class="number">
                <?php echo htmlspecialchars($_installedpart->decimal('purchaseprice')) ?>
            </td>
            <td class="number">
                <?php echo htmlspecialchars($_installedpart->decimal('salesprice')) ?>
            </td>
            <td><?php echo htmlspecialchars($_installedpart->localizedDate('stamp')) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
