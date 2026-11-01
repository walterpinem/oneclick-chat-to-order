/**
 * OneClick Chat to Order - Single Product Button JavaScript
 *
 * @package     OneClick Chat to Order
 * @author      Walter Pinem <hello@walterpinem.me>
 * @link        https://walterpinem.me/
 * @copyright   Copyright (c) 2019 - 2026, Walter Pinem | Online Store Kit
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * @version     1.2.0
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        handleGDPRCheckbox();
        preventWooCommerceConflicts();
    });

    /**
     * Each GDPR checkbox controls only the buttons in its own group
     * (data-wa-gdpr-group), so two consent boxes on one page work independently.
     * The inline script printed with each button does the same; this is a
     * fallback for markup rendered by older add-ons without a group.
     */
    function handleGDPRCheckbox() {
        $(document).on('change', '.wa-order-gdprchk input[type="checkbox"]', function() {
            var group = $(this).attr('data-wa-gdpr-group');
            var isChecked = $(this).is(':checked');
            if (group) {
                $('button[data-wa-gdpr-group="' + group + '"]').prop('disabled', !isChecked);
            } else {
                $('.gdpr_wa_button_input').prop('disabled', !isChecked);
            }
        });
    }

    /**
     * Prevent WooCommerce from interfering with WhatsApp onClick buttons.
     */
    function preventWooCommerceConflicts() {
        $(document).on('click', 'button[data-wa-button="true"]', function(e) {
            // Stop WooCommerce from processing this click; the button's own
            // capture-phase handler has already opened WhatsApp.
            e.stopImmediatePropagation();
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
