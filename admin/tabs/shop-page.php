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
 * @category    Shop Tab
 *
 ********************************* Shop Tab ********************************* */
?>
<form method="post" action="options.php">
    <?php settings_errors(); ?>
    <?php settings_fields('wa-order-settings-group-shop-loop'); ?>
    <?php do_settings_sections('wa-order-settings-group-shop-loop'); ?>
    <h2 class="section_wa_order"><?php esc_html_e('WhatsApp Button on Shop Page', 'oneclick-wa-order'); ?></h2>
    <p>
        <?php
        /* translators: 1. opening <strong> tag for "Shop", 2. closing </strong> tag, 3. opening <strong> tag for "Add to Cart", 4. closing </strong> tag */
        echo sprintf(
            /* translators: 1. opening <strong> tag for "Shop" */
            /* translators: 2. closing </strong> tag */
            /* translators: 3. opening <strong> tag for "Add to Cart" */
            /* translators: 4. closing </strong> tag */
            esc_html__('Add custom WhatsApp button on %1$sShop%2$s page or product loop page right under / besides the %3$sAdd to Cart%4$s button.', 'oneclick-wa-order'),
            '<strong>', // opening <strong> tag for "Shop"
            '</strong>', // closing <strong> tag for "Shop"
            '<strong>', // opening <strong> tag for "Add to Cart"
            '</strong>' // closing <strong> tag for "Add to Cart"
        );
        ?>
        <br />
    </p>
    <table class="form-table">
        <tbody>
            <h2 class="section_wa_order"><?php esc_html_e('Shop Loop Page', 'oneclick-wa-order'); ?></h2>
            <p><?php esc_html_e('The following options will be only effective on shop loop page.', 'oneclick-wa-order'); ?></p>

            <!-- Display Button on Shop Page -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Display button on Shop page?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_enable_button_shop_loop" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_enable_button_shop_loop'), 'yes'); ?>>
                    <?php esc_html_e('This will display WhatsApp button on Shop page', 'oneclick-wa-order'); ?>
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
                            'name'      => 'wa_order_selected_wa_number_shop',
                            'selected'  => get_option('wa_order_selected_wa_number_shop'),
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

            <!-- Hide Add to Cart Button -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Hide Add to Cart button?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_hide_atc_shop_loop" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_hide_atc_shop_loop'), 'yes'); ?>>
                    <?php
                    /* translators: 1. <code> tag for "Add to Cart" */
                    echo sprintf(
                        /* translators: 1. opening <code> tag for "Add to Cart" */
                        /* translators: 2. closing </code> tag */
                        esc_html__('This will only display the WhatsApp button and hide the %1$sAdd to Cart%2$s button.', 'oneclick-wa-order'),
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
                    <input type="text" name="wa_order_option_button_text_shop_loop" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_button_text_shop_loop')); ?>" placeholder="<?php esc_html_e('e.g. Buy via WhatsApp', 'oneclick-wa-order'); ?>">
                </td>
            </tr>

            <!-- Custom Message -->
            <tr class="wa_order_message">
                <th scope="row">
                    <label class="wa_order_message_label" for="message_wbw"><b><?php esc_html_e('Custom Message', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <textarea name="wa_order_option_custom_message_shop_loop" class="wa_order_input_areatext" rows="5" placeholder="<?php esc_html_e('e.g. Hello, I want to purchase:', 'oneclick-wa-order'); ?>"><?php echo esc_textarea(get_option('wa_order_option_custom_message_shop_loop')); ?></textarea>
                    <p class="description">
                        <?php
                        /* translators: 1. <code> tag for the example custom message */
                        echo sprintf(
                            /* translators: 1. opening <code> tag for example message */
                            /* translators: 2. closing </code> tag */
                            esc_html__('Enter custom message, e.g. %1$sHello, I want to purchase:%2$s', 'oneclick-wa-order'),
                            '<code>', // opening <code> tag
                            '</code>' // closing <code> tag
                        );
                        ?>
                    </p>
                </td>
            </tr>

            <!-- Exclude Price Option -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Exclude Price?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_shop_loop_exclude_price" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_shop_loop_exclude_price'), 'yes'); ?>>
                    <?php esc_html_e('This will remove product price from WhatsApp message sent from Shop loop page.', 'oneclick-wa-order'); ?>
                </td>
            </tr>

            <!-- Show Regular & Sale Prices Option -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_remove_btn_label" for="wa_order_option_shop_loop_show_regular_sale_prices"><b><?php esc_html_e('Show Regular & Sale Prices?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_shop_loop_show_regular_sale_prices" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_shop_loop_show_regular_sale_prices'), 'yes'); ?>>
                    <?php esc_html_e('This will show both regular and sale prices in strikethrough format (e.g., ~$20.00~ $15.00) when product is on sale.', 'oneclick-wa-order'); ?>
                </td>
            </tr>

            <!-- Hide Product URL Option -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Remove Product URL?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_shop_loop_hide_product_url" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_shop_loop_hide_product_url'), 'yes'); ?>>
                    <?php esc_html_e('This will remove product URL from WhatsApp message sent from Shop loop page.', 'oneclick-wa-order'); ?>
                </td>
            </tr>

            <!-- Open in New Tab Option -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Open in New Tab?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_shop_loop_open_new_tab" class="wa_order_input_check" value="_blank" <?php checked(get_option('wa_order_option_shop_loop_open_new_tab'), '_blank'); ?>>
                    <?php esc_html_e('Yes, Open in New Tab', 'oneclick-wa-order'); ?>
                </td>
            </tr>
        </tbody>
    </table>
    <hr>
    <?php submit_button(); ?>
</form>