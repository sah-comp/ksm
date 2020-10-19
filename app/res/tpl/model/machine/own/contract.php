<?php
/**
 * Cinnebar.
 *
 * @package Cinnebar
 * @subpackage Template
 * @author $Author$
 * @version $Id$
 */
?>
<!-- contract edit subform -->
<fieldset
    id="machine-<?php echo $record->getId() ?>-owncontract-<?php echo $index ?>">
    <legend class="verbose"><?php echo I18n::__('machine_legend_contract') ?></legend>
    <div class="row nomargins">
        <div class="span3">
            &nbsp;
        </div>
        <div class="span2">
            <?php echo htmlspecialchars($_contract->getContracttype()->name) ?>&nbsp;
        </div>
        <div class="span3">
            <?php echo htmlspecialchars($_contract->getPerson()->name) ?>&nbsp;
        </div>
        <div class="span2">
            <?php echo htmlspecialchars($_contract->startdate) ?>&nbsp;
        </div>
        <div class="span2">
            <?php echo htmlspecialchars($_contract->enddate) ?>&nbsp;
        </div>
    </div>
</fieldset>
<!-- /contract edit subform -->
