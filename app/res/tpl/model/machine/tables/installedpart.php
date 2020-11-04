<?php
/**
 * List all installed parts (articles).
 */
Flight::render('script/datatable_config');
// load our contracts from which the machines are taken
$_ip = R::find('installedpart', "machine_id = ? ORDER BY stamp DESC, @joined.article.description", [$record->getId()]);

Flight::render('model/machine/loose/installedpart', [
    'record' => $record
]);
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
    <tbody id="installedparts">
    <?php
    foreach ($_ip as $_ip_id => $_installedpart):
        $_article = $_installedpart->getArticle();
        Flight::render('model/machine/tables/installedpart/datarow', [
            'record' => $record,
            '_article' => $_article,
            '_installedpart' => $_installedpart
        ]);
    endforeach; ?>
    </tbody>
</table>
