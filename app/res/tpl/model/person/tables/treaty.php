<?php
/**
 * List all machines of this person (client)
 */
Flight::render('script/datatable_config');
// load our contracts from which the machines are taken
$_treaties = R::find('treaty', "person_id = ? ORDER BY number", [$record->getId()]);
?>
<table class="datatable">
    <thead>
        <tr>
            <th><?php echo I18n::__('treaty_label_contracttype') ?></th>
            <th><?php echo I18n::__('treaty_label_number') ?></th>
            <th><?php echo I18n::__('treaty_label_startdate') ?></th>
            <th><?php echo I18n::__('treaty_label_enddate') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach ($_treaties as $_treaty_id => $_treaty):
    ?>
        <tr>
            <td
                data-order="<?php echo htmlspecialchars($_treaty->contracttype->name ?? '') ?>">
                <?php echo htmlspecialchars($_treaty->contracttype->name ?? '') ?>
            </td>
            <td
                data-order="<?php echo htmlspecialchars($_treaty->number ?? '') ?>">
                <a
                    href="<?php echo Url::build('/admin/%s/edit/%d/', [$_treaty->getMeta('type'), $_treaty->getId()]) ?>"
                    class="in-table">
                    <?php echo htmlspecialchars($_treaty->number ?? '') ?>
                </a>
            </td>
            <td
                data-order="<?php echo htmlspecialchars($_treaty->startdate ?? '') ?>">
                <?php echo htmlspecialchars($_treaty->localizedDate('startdate') ?? '') ?>
            </td>
            <td
                data-order="<?php echo htmlspecialchars($_treaty->enddate ?? '') ?>">
                <?php echo htmlspecialchars($_treaty->localizedDate('enddate') ?? '') ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
