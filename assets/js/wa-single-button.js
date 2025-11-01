/**
 * OneClick Chat to Order - Single Product Button JavaScript
 *
 * @package     OneClick Chat to Order
 * @author      Walter Pinem <hello@walterpinem.me>
 * @link        https://walterpinem.me/
 * @copyright   Copyright (c) 2019 - 2025, Walter Pinem | Online Store Kit
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * @version     1.0.8
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Initialize WhatsApp button functionality
        initWhatsAppButton();
    });

    /**
     * Initialize WhatsApp button functionality
     */
    function initWhatsAppButton() {
        // Handle GDPR checkbox functionality
        handleGDPRCheckbox();

        // Handle button click tracking (if needed)
        handleButtonTracking();
    }

    /**
     * Handle GDPR checkbox functionality
     */
    function handleGDPRCheckbox() {
        var gdprCheckbox = $('#gdprChkbx');
        if (gdprCheckbox.length) {
            gdprCheckbox.on('change', function() {
                var isChecked = $(this).is(':checked');
                $('.gdpr_wa_button_input').prop('disabled', !isChecked);
            });
        }
    }

    /**
     * Handle button click tracking and prevent WooCommerce conflicts
     */
    function handleButtonTracking() {
        // Only attach click handlers to buttons that don't have onclick attributes
        $('.wa-order-button:not([onclick]), .gdpr_wa_button_input:not([onclick])').on('click', function(e) {
            // Add any tracking or analytics code here if needed
            // This is a placeholder for future functionality
        });

        // Prevent WooCommerce from interfering with WhatsApp onClick buttons
        preventWooCommerceConflicts();
    }

    /**
     * Prevent WooCommerce from interfering with WhatsApp buttons
     */
    function preventWooCommerceConflicts() {
        // Override WooCommerce's add to cart button handler for WhatsApp buttons
        $(document).on('click', 'button[data-wa-button="true"]', function(e) {
            // Stop WooCommerce from processing this click
            e.stopImmediatePropagation();

            // Let the onclick attribute handle the WhatsApp functionality
            // The onclick attribute will be executed after this
        });

        // Also prevent form submission for WhatsApp buttons
        $('form.cart').on('submit', function(e) {
            var clickedButton = $(document.activeElement);
            if (clickedButton.attr('data-wa-button') === 'true') {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        });
    }

})(jQuery);