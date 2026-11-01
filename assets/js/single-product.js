(function($) {
    'use strict';

    // Open the WhatsApp link of a button nested inside an <a>, respecting its target.
    function openLink(link) {
        var href = link.attr('href');
        if (!href) {
            return;
        }
        if (link.attr('target') === '_blank') {
            window.open(href, '_blank');
        } else {
            window.location.href = href;
        }
    }

    // Some themes and Ajax add-to-cart scripts treat any .single_add_to_cart_button
    // as an Add to Cart button. When the WhatsApp button sits after Add to Cart it
    // carries that class for styling, so take over its click.
    // Each button is bound once; this runs again for quick views loaded later.
    function disableAjaxOnWaButtons() {
        $('.wa-order-button.single_add_to_cart_button').each(function() {
            var $button = $(this);
            if ($button.data('waOrderBound')) {
                return;
            }
            $button.data('waOrderBound', true);
            $button.off('click'); // Unbind existing handlers (WooCommerce/Woodmart)
            $button.on('click', function(e) {
                var link = $(this).closest('a');
                if (!link.length) {
                    return; // onClick link type handles itself.
                }
                e.preventDefault(); // Prevent any default form submit/AJAX
                e.stopPropagation(); // Stop bubbling to other handlers
                openLink(link);
            });
        });

        $('.gdpr_wa_button_input.single_add_to_cart_button').each(function() {
            var $button = $(this);
            if ($button.data('waOrderBound')) {
                return;
            }
            $button.data('waOrderBound', true);
            $button.off('click'); // Unbind existing handlers
            $button.on('click', function(e) {
                if ($(this).prop('disabled')) {
                    return;
                }
                var link = $(this).closest('a');
                if (!link.length) {
                    return;
                }
                e.preventDefault();
                e.stopPropagation();
                openLink(link);
            });
        });
    }

    // Run on ready, after variation form, and on theme quick-view events
    $(document).ready(disableAjaxOnWaButtons);
    $(document).on('wc_variation_form', disableAjaxOnWaButtons);
    $(document).on('woodmart_pquick_view_loaded woodmart_quick_shop_success flatsome_quickview_loaded', disableAjaxOnWaButtons);
})(jQuery);
