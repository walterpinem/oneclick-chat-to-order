(function($) {
    var initialized = false;
    function disableAjaxOnWaButtons() {
        if (initialized) return; // Skip if already run
        initialized = true;
        console.log('WA Order: Searching for buttons...');

        // Target standard WA button
        $('.wa-order-button.single_add_to_cart_button').each(function() {
            console.log('WA Order: Found standard button, setting up handler');
            $(this).off('click'); // Unbind existing handlers (WooCommerce/Woodmart)
            $(this).on('click', function(e) {
                e.preventDefault(); // Prevent any default form submit/AJAX
                e.stopPropagation(); // Stop bubbling to other handlers
                var link = $(this).closest('a');
                var href = link.attr('href'); // Get parent <a> href
                var target = link.attr('target'); // Get parent <a> target
                if (href) {
                    // redirect logic
                } else {
                    console.warn('WA Order: No href found for button');
                }
                if (target === '_blank') {
                    window.open(href, '_blank');
                } else {
                    window.location.href = href;
                }
            });
        });

        // Target GDPR WA button
        $('.gdpr_wa_button_input.single_add_to_cart_button').each(function() {
            console.log('WA Order: Found GDPR button, setting up handler');
            $(this).off('click'); // Unbind existing handlers
            $(this).on('click', function(e) {
                if (!$(this).prop('disabled')) {
                    e.preventDefault();
                    e.stopPropagation();
                    var link = $(this).closest('a');
                    var href = link.attr('href');
                    var target = link.attr('target');
                    if (href) {
                        console.log('WA Order: GDPR button clicked - redirecting to ' + href + ' with target ' + target);
                        if (target === '_blank') {
                            window.open(href, '_blank');
                        } else {
                            window.location.href = href;
                        }
                    }
                }
            });
        });
    }

    // Run on ready, after variation form, and on Woodmart-specific events
    $(document).ready(disableAjaxOnWaButtons);
    $(document).on('wc_variation_form', disableAjaxOnWaButtons);
    $(document).on('woodmart_pquick_view_loaded woodmart_quick_shop_success flatsome_quickview_loaded', disableAjaxOnWaButtons); // Add Flatsome or others
})(jQuery);