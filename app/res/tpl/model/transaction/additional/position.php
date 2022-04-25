<div class="tooltip open">
    <h1><?php echo $record->getContracttype()->name . ' ' . $record->number ?></h1>
    <p class="spacer">&nbsp;</p>
    <?php
    Flight::render('model/transaction/pdf/table', [
        'record' => $record
    ]);
    ?>
    <a
        class="ir empty-container"
        data-container="additional-info-container"
        href="#close">
        <?php echo I18n::__('tooltip_close') ?>
    </a>

</div>
