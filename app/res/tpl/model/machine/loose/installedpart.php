<div class="mini-container">
    <p class="buttons"><a
        class="venetianblinds"
        data-target="ip-chamber"
        href="#venetianblinds">
        <?php echo I18n::__('installedpart_action_ventian') ?>
    </a></p>

    <div
        id="ip-chamber"
        class="mini"
        style="display: none;">

        <div class="row">
            <label
                for="ip-article-clairvoyant">
                <?php echo I18n::__('installedpart_label_article') ?>
            </label>
            <input
                id="ip-article-id"
                type="hidden"
                name="ip[article_id]"
                value="" />
            <input
                type="text"
                id="ip-article-clairvoyant"
                name="ip[clairvoyant]"
                class="autocomplete"
                data-source="<?php echo Url::build('/autocomplete/article/number/?callback=?') ?>"
                data-spread='<?php
                    echo json_encode([
                        'ip-article-id' => 'id',
                        'ip-article-clairvoyant' => 'value',
                        'ip-article-purchaseprice' => 'purchaseprice',
                        'ip-article-salesprice' => 'salesprice',
                        'ip-article-isoriginal' => 'isoriginal'
                    ]); ?>'
                value="" />
        </div>

        <div class="row">
            <label
                for="ip-article-isoriginal">
                <?php echo I18n::__('installedpart_label_isoriginal') ?>
            </label>
            <select
                id="ip-article-isoriginal"
                class=""
                name="ip[isoriginal]">
                <option value="0"><?php echo I18n::__('article_label_option_false') ?></option>
                <option value="1"><?php echo I18n::__('article_label_option_true') ?></option>
            </select>
        </div>

        <div class="row">
            <label
                for="ip-article-stamp">
                <?php echo I18n::__('installedpart_label_stamp') ?>
            </label>
            <input
                type="date"
                id="ip-article-stamp"
                name="ip[stamp]"
                class="date"
                placeholder="<?php echo I18n::__('placeholder_intl_date') ?>"
                value="" />
        </div>

        <div class="row">
            <label
                for="ip-article-purchaseprice">
                <?php echo I18n::__('installedpart_label_purchaseprice') ?>
            </label>
            <input
                type="text"
                id="ip-article-purchaseprice"
                name="ip[purchaseprice]"
                class="number"
                value="" />
        </div>

        <div class="row">
            <label
                for="ip-article-salesprice">
                <?php echo I18n::__('installedpart_label_salesprice') ?>
            </label>
            <input
                type="text"
                id="ip-article-salesprice"
                name="ip[salesprice]"
                class="number"
                value="" />
        </div>

        <div class="row">
            <label
                for="ip-article-adopt">
                <?php echo I18n::__('installedpart_label_adopt') ?>
            </label>
            <select
                id="ip-article-adopt"
                class=""
                name="ip[adopt]">
                <option value="0"><?php echo I18n::__('article_label_option_false') ?></option>
                <option value="1"><?php echo I18n::__('article_label_option_true') ?></option>
            </select>
            <p class="info"><?php echo I18n::__('installedpart_info_adopt') ?></p>
        </div>

        <div class="row center">
            <button
                class="within"
                data-target="installedparts"
                data-url="<?php echo Url::build('/article/install/into/machine/%d/?callback=?', [$record->getId()]) ?>"
                type="button"
                name="within-submit">
                <?php echo I18n::__('action_add_installedpart') ?>
            </button>
        </div>

    </div>
</div>
