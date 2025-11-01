<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * OneClick Chat to Order Admin Settings Page
 *
 * @package     OneClick Chat to Order
 * @author      Walter Pinem <hello@walterpinem.me>
 * @link        https://walterpinem.me/
 * @link        https://www.onlinestorekit.com/oneclick-chat-to-order/
 * @copyright   Copyright (c) 2019 - 2025, Walter Pinem | Online Store Kit
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * @category    Cart Tab
 *
 ********************************* Cart Tab ********************************* */
?>
<form method="post" action="options.php">
    <?php settings_errors(); ?>
    <?php settings_fields('wa-order-settings-group-cart-options'); ?>
    <?php do_settings_sections('wa-order-settings-group-cart-options'); ?>
    <h2 class="section_wa_order"><?php esc_html_e('WhatsApp Button on Cart Page', 'oneclick-wa-order'); ?></h2>
    <p>
        <?php
        /* translators: 1. opening <strong> tag for "Cart", 2. closing </strong> tag, 3. opening <strong> tag for "Proceed to Checkout", 4. closing </strong> tag */
        echo sprintf(
            /* translators: 1. opening <strong> tag for "Cart" */
            /* translators: 2. closing </strong> tag */
            /* translators: 3. opening <strong> tag for "Proceed to Checkout" */
            /* translators: 4. closing </strong> tag */
            esc_html__('Add custom WhatsApp button on %1$sCart%2$s page right under the %3$sProceed to Checkout%4$s button.', 'oneclick-wa-order'),
            '<strong>', // opening <strong> tag for "Cart"
            '</strong>', // closing <strong> tag for "Cart"
            '<strong>', // opening <strong> tag for "Proceed to Checkout"
            '</strong>' // closing <strong> tag for "Proceed to Checkout"
        );
        ?>
        <br />
    </p>
    <table class="form-table">
        <tbody>
            <h2 class="section_wa_order"><?php esc_html_e('Cart Page', 'oneclick-wa-order'); ?></h2>
            <p><?php esc_html_e('The following options will be only effective on cart page.', 'oneclick-wa-order'); ?></p>

            <!-- Display Button on Cart Page -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Display button on Cart page?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_add_button_to_cart" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_add_button_to_cart'), 'yes'); ?>>
                    <?php esc_html_e('This will display WhatsApp button on Cart page', 'oneclick-wa-order'); ?>
                </td>
            </tr>

            <!-- WhatsApp Number Dropdown -->
            <tr>
                <th scope="row">
                    <label><?php esc_html_e('WhatsApp Number', 'oneclick-wa-order') ?></label>
                </th>
                <td>
                    <?php wa_order_phone_numbers_dropdown(
                        array(
                            'name'      => 'wa_order_selected_wa_number_cart',
                            'selected'  => get_option('wa_order_selected_wa_number_cart'),
                        )
                    ) ?>
                    <p class="description">
                        <?php
                        /* translators: 1. opening <strong> tag with inline red color style for "required", 2. closing </strong> tag, 3. opening <a> tag for "Numbers", 4. closing </a> tag */
                        echo wp_kses(
                            sprintf(
                                /* translators: 1. opening <strong> tag with inline red color style for "required", 2. closing </strong> tag, 3. opening <a> tag for "Numbers", 4. closing </a> tag */
                                __('WhatsApp number is %1$srequired%2$s. Please set it on the %3$sNumbers%4$s tab.', 'oneclick-wa-order'),
                                '<strong style="color:red;">', // opening <strong> tag with red color style
                                '</strong>', // closing <strong> tag
                                '<a href="edit.php?post_type=wa-order-numbers"><strong>', // opening <a> and <strong> tag
                                '</strong></a>' // closing <a> and <strong> tag
                            ),
                            array(
                                'strong' => array('style' => array()), // Allow strong with style attribute
                                'a' => array('href' => array(), 'target' => array()) // Allow a tag with href and target attributes
                            )
                        );
                        ?>
                    </p>
                </td>
            </tr>

            <!-- Hide Proceed to Checkout Button -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Hide Proceed to Checkout button?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_cart_hide_checkout" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_cart_hide_checkout'), 'yes'); ?>>
                    <?php
                    /* translators: 1. <code> tag for "Proceed to Checkout" */
                    echo sprintf(
                        /* translators: 1. <code> tag for "Proceed to Checkout" */
                        esc_html__('This will only display WhatsApp button and hide the %1$sProceed to Checkout%2$s button', 'oneclick-wa-order'),
                        '<code>', // opening <code> tag
                        '</code>' // closing <code> tag
                    );
                    ?>
                </td>
            </tr>

            <!-- Text on Button -->
            <tr class="wa_order_btn_text">
                <th scope="row">
                    <label class="wa_order_btn_txt_label" for="text_button"><b><?php esc_html_e('Text on Button', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="text" name="wa_order_option_cart_button_text" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_cart_button_text')); ?>" placeholder="<?php esc_html_e('e.g. Complete Order via WhatsApp', 'oneclick-wa-order'); ?>">
                </td>
            </tr>

            <!-- Custom Message -->
            <tr class="wa_order_message">
                <th scope="row">
                    <label class="wa_order_message_label" for="message_wbw"><b><?php esc_html_e('Custom Message', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <textarea name="wa_order_option_cart_custom_message" class="wa_order_input_areatext" rows="5" placeholder="<?php esc_html_e('e.g. Hello, I want to purchase the item(s) below:', 'oneclick-wa-order'); ?>"><?php echo esc_textarea(get_option('wa_order_option_cart_custom_message')); ?></textarea>
                    <p class="description">
                        <?php
                        /* translators: 1. <code> tag for the example message */
                        echo sprintf(
                            /* translators: 1. <code> tag for the example message */
                            esc_html__('Enter custom message, e.g. %1$sHello, I want to purchase the item(s) below:%2$s', 'oneclick-wa-order'),
                            '<code>', // opening <code> tag
                            '</code>' // closing <code> tag
                        );
                        ?>
                    </p>
                </td>
            </tr>

            <!-- Remove Product URL Option -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Remove Product URL?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_cart_hide_product_url" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_cart_hide_product_url'), 'yes'); ?>>
                    <?php esc_html_e('This will remove product URL from WhatsApp message sent from Cart page.', 'oneclick-wa-order'); ?>
                </td>
            </tr>

            <!-- Include Product Variation Option -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Include Product Variation?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_cart_enable_variations" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_cart_enable_variations'), 'yes'); ?>>
                    <?php esc_html_e('The product variation will be included in the message if it is stored by WooCommerce, might not all.', 'oneclick-wa-order'); ?>
                </td>
            </tr>

            <!-- Include Tax Option -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Include Tax?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_cart_include_tax" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_cart_include_tax'), 'yes'); ?>>
                    <?php esc_html_e('This will include the tax in the message.', 'oneclick-wa-order'); ?>
                </td>
            </tr>

            <!-- Open in New Tab Option -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Open in New Tab?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_cart_open_new_tab" class="wa_order_input_check" value="_blank" <?php checked(get_option('wa_order_option_cart_open_new_tab'), '_blank'); ?>>
                    <?php esc_html_e('Yes, Open in New Tab', 'oneclick-wa-order'); ?>
                </td>
            </tr>
        </tbody>
    </table>
    <hr>
    <?php submit_button(); ?>
</form>