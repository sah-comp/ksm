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
    <div>
        <input
            type="hidden"
            name="dialog[ownContract][<?php echo $index ?>][type]"
            value="<?php echo $_contract->getMeta('type') ?>" />
        <input
            type="hidden"
            name="dialog[ownContract][<?php echo $index ?>][id]"
            value="<?php echo $_contract->getId() ?>" />
    </div>
    <div class="row nomargins">
        <div class="span3">
            &nbsp;
        </div>
        <div class="span2">
            <div>
                <input
                    type="hidden"
                    name="dialog[ownContract][<?php echo $index ?>][contracttype][type]"
                    value="<?php echo $_contract->getContracttype()->getMeta('type') ?>" />
                <input
                    type="hidden"
                    name="dialog[ownContract][<?php echo $index ?>][contracttype][id]"
                    value="<?php echo $_contract->getContracttype()->getId() ?>" />
            </div>
            <input
                type="text"
                id="machine-<?php echo $record->getId() ?>-contract-<?php echo $index ?>-contracttype-name"
                name="dialog[ownContract][<?php echo $index ?>][contracttype][name]"
                readonly="readonly"
                value="<?php echo htmlspecialchars($_contract->getContracttype()->name) ?>" />
        </div>
        <div class="span3">
            <div>
                <input
                    type="hidden"
                    name="dialog[ownContract][<?php echo $index ?>][person][type]"
                    value="<?php echo $_contract->getPerson()->getMeta('type') ?>" />
                <input
                    type="hidden"
                    name="dialog[ownContract][<?php echo $index ?>][person][id]"
                    value="<?php echo $_contract->getPerson()->getId() ?>" />
            </div>
            <input
                type="text"
                id="machine-<?php echo $record->getId() ?>-contract-<?php echo $index ?>-person-name"
                name="dialog[ownContract][<?php echo $index ?>][person][name]"
                readonly="readonly"
                value="<?php echo htmlspecialchars($_contract->getPerson()->name) ?>" />
        </div>
        <div class="span2">
            <input
                type="text"
                id="machine-<?php echo $record->getId() ?>-contract-<?php echo $index ?>-startdate"
                name="dialog[ownContract][<?php echo $index ?>][startdate]"
                readonly="readonly"
                placeholder="<?php echo I18n::__('placeholder_intl_date') ?>"
                value="<?php echo htmlspecialchars($_contract->startdate) ?>" />
        </div>
        <div class="span2">
            <input
                type="text"
                id="machine-<?php echo $record->getId() ?>-contract-<?php echo $index ?>-enddate"
                name="dialog[ownContract][<?php echo $index ?>][enddate]"
                readonly="readonly"
                placeholder="<?php echo I18n::__('placeholder_intl_date') ?>"
                value="<?php echo htmlspecialchars($_contract->enddate) ?>" />
        </div>
    </div>
</fieldset>
<!-- /contract edit subform -->
