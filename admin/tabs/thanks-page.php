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
 * @category    Checkout Tab
 *
 ********************************* Checkout Tab ********************************* */
?>
<form method="post" action="options.php">
    <?php settings_errors(); ?>
    <?php settings_fields('wa-order-settings-group-order-completion'); ?>
    <?php do_settings_sections('wa-order-settings-group-order-completion'); ?>

    <h2 class="section_wa_order"><?php echo esc_html__('Thank You Page Customization', 'oneclick-wa-order'); ?></h2>
    <p>
        <?php echo esc_html__('Add a WhatsApp button on Thank You / Order Received page. If enabled, it will add a new section under the Order Received or Thank You title and override default text by using below data, including adding a WhatsApp button to send order details.', 'oneclick-wa-order'); ?>
        <br />
        <strong><?php echo esc_html__('Tip:', 'oneclick-wa-order'); ?></strong> <?php echo esc_html__('You can use this to make it quick for your customers to send their own order receipt to you via WhatsApp.', 'oneclick-wa-order'); ?>
    </p>

    <table class="form-table">
        <tbody>
            <tr class="wa_order_target">
                <th scope="row">
                    <label for="wa_order_option_enable_button_thank_you"><?php echo esc_html__('Enable Setting?', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_enable_button_thank_you" id="wa_order_option_enable_button_thank_you" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_option_enable_button_thank_you')), 'yes'); ?>>
                    <?php echo esc_html__('This will override default appearance and add a WhatsApp button.', 'oneclick-wa-order'); ?>
                </td>
            </tr>
            <!-- WhatsApp Number Dropdown -->
            <tr>
                <th scope="row">
                    <label><?php echo esc_html__('WhatsApp Number', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <?php wa_order_phone_numbers_dropdown(
                        array(
                            'name'      => 'wa_order_selected_wa_number_thanks',
                            'selected'  => get_option('wa_order_selected_wa_number_thanks'),
                        )
                    ); ?>
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
            <!-- Text on Button -->
            <tr class="wa_order_btn_text">
                <th scope="row">
                    <label for="wa_order_option_custom_thank_you_button_text"><?php echo esc_html__('Text on Button', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <input type="text" name="wa_order_option_custom_thank_you_button_text" id="wa_order_option_custom_thank_you_button_text" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_custom_thank_you_button_text')); ?>" placeholder="<?php echo esc_attr__('e.g. Send Order Details', 'oneclick-wa-order'); ?>">
                    <p class="description">
                        <?php echo esc_html__('Enter the text on WhatsApp button. e.g. Send Order Details', 'oneclick-wa-order'); ?>
                    </p>
                </td>
            </tr>
            <!-- Custom Message -->
            <tr class="wa_order_message">
                <th scope="row">
                    <label for="wa_order_option_custom_thank_you_custom_message"><?php echo esc_html__('Custom Message', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <textarea name="wa_order_option_custom_thank_you_custom_message" id="wa_order_option_custom_thank_you_custom_message" class="wa_order_input_areatext" rows="5" placeholder="<?php echo esc_attr__('e.g. Hello, here\'s my order details:', 'oneclick-wa-order'); ?>"><?php echo esc_textarea(get_option('wa_order_option_custom_thank_you_custom_message')); ?></textarea>
                    <p class="description">
                        <?php echo esc_html__('Enter custom message to send along with order details. e.g. Hello, here\'s my order details:', 'oneclick-wa-order'); ?>
                    </p>
                </td>
            </tr>
            <!-- Custom Title -->
            <tr class="wa_order_btn_text">
                <th scope="row">
                    <label for="wa_order_option_custom_thank_you_title"><?php echo esc_html__('Custom Title', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <input type="text" name="wa_order_option_custom_thank_you_title" id="wa_order_option_custom_thank_you_title" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_custom_thank_you_title')); ?>" placeholder="<?php echo esc_attr__('e.g. Thanks and You\'re Awesome', 'oneclick-wa-order'); ?>">
                    <p class="description">
                        <?php echo esc_html__('You can personalize the title by changing it here. This will be shown like this: [your custom title], [customer\'s first name]. e.g. Thanks and You\'re Awesome, Igor!', 'oneclick-wa-order'); ?>
                    </p>
                </td>
            </tr>
            <!-- Custom Subtitle -->
            <tr class="wa_order_message">
                <th scope="row">
                    <label for="wa_order_option_custom_thank_you_subtitle"><?php echo esc_html__('Custom Subtitle', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <textarea name="wa_order_option_custom_thank_you_subtitle" id="wa_order_option_custom_thank_you_subtitle" class="wa_order_input_areatext" rows="5" placeholder="<?php echo esc_attr__('e.g. For faster response, send your order details by clicking below button.', 'oneclick-wa-order'); ?>"><?php echo esc_textarea(get_option('wa_order_option_custom_thank_you_subtitle')); ?></textarea>
                    <p class="description">
                        <?php echo esc_html__('Enter custom subtitle. e.g. For faster response, send your order details by clicking below button.', 'oneclick-wa-order'); ?>
                    </p>
                </td>
            </tr>
            <!-- Customer Details Label -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label for="wa_order_option_custom_thank_you_customer_details_label"><?php echo esc_html__('Customer Details Label', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <input type="text" name="wa_order_option_custom_thank_you_customer_details_label" id="wa_order_option_custom_thank_you_customer_details_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_custom_thank_you_customer_details_label')); ?>" placeholder="<?php echo esc_attr__('e.g. Customer Details', 'oneclick-wa-order'); ?>">
                    <p class="description">
                        <?php echo esc_html__('Enter a label for customer details. e.g. Customer Details', 'oneclick-wa-order'); ?>
                    </p>
                </td>
            </tr>

            <!-- Total Products Label -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label for="wa_order_option_custom_thank_you_total_products_label"><?php echo esc_html__('Total Products Label', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <input type="text" name="wa_order_option_custom_thank_you_total_products_label" id="wa_order_option_custom_thank_you_total_products_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_custom_thank_you_total_products_label')); ?>" placeholder="<?php echo esc_attr__('e.g. Total Products', 'oneclick-wa-order'); ?>">
                    <p class="description">
                        <?php echo esc_html__('Enter a label for the total number of products. This field is optional and can be left blank to disable it. e.g. Total Products', 'oneclick-wa-order'); ?>
                    </p>
                </td>
            </tr>

            <!-- Include Coupon Discount -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label for="wa_order_option_custom_thank_you_inclue_coupon"><?php echo esc_html__('Include Coupon Discount?', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_custom_thank_you_inclue_coupon" id="wa_order_option_custom_thank_you_inclue_coupon" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_option_custom_thank_you_inclue_coupon')), 'yes'); ?>>
                    <?php echo esc_html__('This includes a coupon code and its associated deduction amount, along with a label if it is enabled.', 'oneclick-wa-order'); ?>
                </td>
            </tr>

            <!-- Coupon Label -->
            <tr class="wa_order_btn_text">
                <th scope="row">
                    <label for="wa_order_option_custom_thank_you_coupon_label"><?php echo esc_html__('Coupon Label', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <input type="text" name="wa_order_option_custom_thank_you_coupon_label" id="wa_order_option_custom_thank_you_coupon_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_custom_thank_you_coupon_label')); ?>" placeholder="<?php echo esc_attr__('e.g. Voucher Code', 'oneclick-wa-order'); ?>">
                    <p class="description">
                        <?php echo esc_html__('Enter a label for the coupon code. e.g. Voucher Code', 'oneclick-wa-order'); ?>
                    </p>
                </td>
            </tr>

            <!-- Include Order Summary Link -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label for="wa_order_option_thank_you_order_summary_link"><?php echo esc_html__('Include Order Summary Link?', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_thank_you_order_summary_link" id="wa_order_option_thank_you_order_summary_link" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_option_thank_you_order_summary_link')), 'yes'); ?>>
                    <?php echo esc_html__('Include an Order Summary link in the message.', 'oneclick-wa-order'); ?>
                </td>
            </tr>

            <!-- Order Summary Label -->
            <tr class="wa_order_btn_text">
                <th scope="row">
                    <label for="wa_order_option_thank_you_order_summary_label"><?php echo esc_html__('Order Summary Label', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <input type="text" name="wa_order_option_thank_you_order_summary_label" id="wa_order_option_thank_you_order_summary_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_thank_you_order_summary_label')); ?>" placeholder="<?php echo esc_attr__('e.g. Check Order Summary:', 'oneclick-wa-order'); ?>">
                    <p class="description">
                        <?php echo esc_html__('Enter a label for the order summary. e.g. Check Order Summary:', 'oneclick-wa-order'); ?>
                    </p>
                </td>
            </tr>

            <!-- Include Payment Link -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label for="wa_order_option_thank_you_payment_link"><?php echo esc_html__('Include Payment Link?', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_thank_you_payment_link" id="wa_order_option_thank_you_payment_link" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_option_thank_you_payment_link')), 'yes'); ?>>
                    <?php echo esc_html__('Include the Payment Link in the message.', 'oneclick-wa-order'); ?>
                </td>
            </tr>

            <!-- Payment Link Label -->
            <tr class="wa_order_btn_text">
                <th scope="row">
                    <label for="wa_order_option_thank_you_payment_link_label"><?php echo esc_html__('Payment Link Label', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <input type="text" name="wa_order_option_thank_you_payment_link_label" id="wa_order_option_thank_you_payment_link_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_thank_you_payment_link_label')); ?>" placeholder="<?php echo esc_attr__('e.g. Payment Link:', 'oneclick-wa-order'); ?>">
                    <p class="description">
                        <?php echo esc_html__('Enter a label for the payment link. e.g. Payment Link:', 'oneclick-wa-order'); ?>
                    </p>
                </td>
            </tr>

            <!-- Include View Order Link -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label for="wa_order_option_thank_you_view_order_link"><?php echo esc_html__('Include View Order Link?', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_thank_you_view_order_link" id="wa_order_option_thank_you_view_order_link" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_option_thank_you_view_order_link')), 'yes'); ?>>
                    <?php echo esc_html__('Note: It only works if a customer already has an account.', 'oneclick-wa-order'); ?>
                </td>
            </tr>

            <!-- View Order Label -->
            <tr class="wa_order_btn_text">
                <th scope="row">
                    <label for="wa_order_option_thank_you_view_order_label"><?php echo esc_html__('View Order Label', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <input type="text" name="wa_order_option_thank_you_view_order_label" id="wa_order_option_thank_you_view_order_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_thank_you_view_order_label')); ?>" placeholder="<?php echo esc_attr__('e.g. View Order:', 'oneclick-wa-order'); ?>">
                    <p class="description">
                        <?php echo esc_html__('Enter a label for the view order. e.g. View Order:', 'oneclick-wa-order'); ?>
                    </p>
                </td>
            </tr>

            <!-- Include Order Number -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label for="wa_order_option_custom_thank_you_order_number"><?php echo esc_html__('Include Order Number?', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_custom_thank_you_order_number" id="wa_order_option_custom_thank_you_order_number" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_option_custom_thank_you_order_number')), 'yes'); ?>>
                    <?php echo esc_html__('The order number will include a label, if enabled.', 'oneclick-wa-order'); ?>
                </td>
            </tr>

            <!-- Order Number Label -->
            <tr class="wa_order_btn_text">
                <th scope="row">
                    <label for="wa_order_option_custom_thank_you_order_number_label"><?php echo esc_html__('Order Number Label', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <input type="text" name="wa_order_option_custom_thank_you_order_number_label" id="wa_order_option_custom_thank_you_order_number_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_custom_thank_you_order_number_label')); ?>" placeholder="<?php echo esc_attr__('e.g. Order Number:', 'oneclick-wa-order'); ?>">
                    <p class="description">
                        <?php echo esc_html__('Enter a label for the order number. e.g. Order Number:', 'oneclick-wa-order'); ?>
                    </p>
                </td>
            </tr>

            <!-- Include Product SKU -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label for="wa_order_option_custom_thank_you_include_sku"><?php echo esc_html__('Include Product SKU?', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_custom_thank_you_include_sku" id="wa_order_option_custom_thank_you_include_sku" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_option_custom_thank_you_include_sku')), 'yes'); ?>>
                    <?php echo esc_html__('Yes, Include Product SKU', 'oneclick-wa-order'); ?>
                </td>
            </tr>
            <!-- Include Tax -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label for="wa_order_option_custom_thank_you_include_tax"><?php echo esc_html__('Include Tax?', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_custom_thank_you_include_tax" id="wa_order_option_custom_thank_you_include_tax" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_option_custom_thank_you_include_tax')), 'yes'); ?>>
                    <?php echo esc_html__('Yes, Include Tax', 'oneclick-wa-order'); ?>
                </td>
            </tr>

            <!-- Include Shipping -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label for="wa_order_option_custom_thank_you_include_shipping"><?php echo esc_html__('Include Shipping?', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_custom_thank_you_include_shipping" id="wa_order_option_custom_thank_you_include_shipping" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_option_custom_thank_you_include_shipping', 'yes')), 'yes'); ?>>
                    <?php echo esc_html__('Yes, Include Shipping Information', 'oneclick-wa-order'); ?>
                </td>
            </tr>

            <!-- Shipping Label -->
            <tr class="wa_order_btn_text">
                <th scope="row">
                    <label for="wa_order_option_custom_thank_you_shipping_label"><?php echo esc_html__('Shipping Label', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <input type="text" name="wa_order_option_custom_thank_you_shipping_label" id="wa_order_option_custom_thank_you_shipping_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_custom_thank_you_shipping_label')); ?>" placeholder="<?php echo esc_attr__('e.g. Shipping', 'oneclick-wa-order'); ?>">
                    <p class="description">
                        <?php echo esc_html__('Enter a label for shipping information. e.g. Shipping', 'oneclick-wa-order'); ?>
                    </p>
                </td>
            </tr>

            <!-- Include Order Date -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label for="wa_order_option_custom_thank_you_include_order_date"><?php echo esc_html__('Include Order Date?', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_custom_thank_you_include_order_date" id="wa_order_option_custom_thank_you_include_order_date" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_option_custom_thank_you_include_order_date')), 'yes'); ?>>
                    <?php echo esc_html__('Yes, Include Order Date', 'oneclick-wa-order'); ?>
                </td>
            </tr>

            <!-- Open in New Tab -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label for="wa_order_option_custom_thank_you_open_new_tab"><?php echo esc_html__('Open in New Tab?', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_custom_thank_you_open_new_tab" id="wa_order_option_custom_thank_you_open_new_tab" class="wa_order_input_check" value="_blank" <?php checked(esc_attr(get_option('wa_order_option_custom_thank_you_open_new_tab')), '_blank'); ?>>
                    <?php echo esc_html__('Yes, Open in New Tab', 'oneclick-wa-order'); ?>
                </td>
            </tr>
        </tbody>
    </table>
    <hr>
    <?php submit_button(); ?>
</form>