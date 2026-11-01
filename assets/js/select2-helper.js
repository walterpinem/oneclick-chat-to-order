/**
 * Select2 for the plugin's own fields only (Display and Floating tabs).
 * Before 1.2.0 this turned every <select> on the screen into Select2.
 */
jQuery(function($) {
    if (!$.fn.select2) {
        return;
    }
    var l10n = window.waOrderSelect2 || {};

    $('select.octo-category-filter').select2({
        placeholder: l10n.term || 'Search and pick a term...'
    });
    $('select.octo-post-filter').select2({
        placeholder: l10n.post || 'Search and pick a post...'
    });
    $('select.octo-page-filter').select2({
        placeholder: l10n.page || 'Search and pick a page...'
    });
    $('select.wa_order-admin-select2').not('.octo-category-filter, .octo-post-filter, .octo-page-filter').select2();
});
