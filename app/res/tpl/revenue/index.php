<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo $title ?></h1>
        <nav>
            <?php echo $toolbar ?>
        </nav>
    </header>
    <form
        id="form-revenue-selector"
        class="panel"
        method="POST"
        accept-charset="utf-8"
        enctype="multipart/form-data">

        <input type="hidden" name="token" value="<?php echo Security::getCSRFToken() ?>" />

        <fieldset>
            <legend><?php echo I18n::__('revenue_legend_filter') ?></legend>
            <div class="row">
                <label
                    for="revenue-startdate">
                    <?php echo I18n::__('revenue_label_startdate') ?>
                </label>
                <input
                    id="revenue-startdate"
                    type="date"
        			placeholder="yyyy-mm-dd"
                    name="dialog[startdate]"
                    value="<?php echo htmlspecialchars($_SESSION['revenue']['startdate']) ?>"
                    required="required" />
            </div>
            <div class="row">
                <label
                    for="revenue-enddate">
                    <?php echo I18n::__('revenue_label_enddate') ?>
                </label>
                <input
                    id="revenue-enddate"
                    type="date"
        			placeholder="yyyy-mm-dd"
                    name="dialog[enddate]"
                    value="<?php echo htmlspecialchars($_SESSION['revenue']['enddate']) ?>"
                    required="required" />
            </div>
            <div class="row">
                <input
                    type="hidden"
                    name="dialog[unpaid]"
                    value="0" />
                <input
                    id="revenue-unpaid"
                    type="checkbox"
                    name="dialog[unpaid]"
                    <?php echo ($_SESSION['revenue']['unpaid']) ? 'checked="checked"' : '' ?>
                    value="1" />
                <label
                    for="revenue-unpaid"
                    class="cb">
                    <?php echo I18n::__('revenue_label_unpaid') ?>
                </label>
            </div>
        </fielset>
        <div class="buttons">
            <a
                href="<?php echo Url::build("/revenue/clearfilter") ?>"
                class="btn">
                <?php echo I18n::__('revenue_clearfilter') ?>
            </a>
            <input
                type="submit"
                name="submit"
                accesskey="s"
                value="<?php echo I18n::__('revenue_sel_submit') ?>" />
        </div>
    </form>
    <?php if ($records):
        Flight::render('revenue/table.php', [
            'record' => $record,
            'records' => $records,
            'totals' => $totals
        ]);
    ?>
    <?php else: ?>
    <p><?php echo I18n::__('revenue_no_records_found') ?></p>
    <?php endif; ?>
</article>
