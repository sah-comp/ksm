$('body').ready(function() {
    /**
     * Check for select inputs to be handled with select2 plugin
     * @see https://select2.org
     */
    initSelect2();
});

function initSelect2() {
    $('.select2basic').select2({
        'dropdownCssClass': 'select2smallfont'
    });
}