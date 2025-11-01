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
 * @category    Button Config / Basic Tab
 *
 ********************************* Button Config / Basic Tab ********************************* */
?>
<!-- Basic Configurations -->
<form method="post" action="options.php">
    <?php settings_errors(); ?>
    <?php settings_fields('wa-order-settings-group-button-config'); ?>
    <?php do_settings_sections('wa-order-settings-group-button-config'); ?>
    <!-- Basic Configuration tab -->
    <h2 class="section_wa_order"><?php esc_html_e('Confirmation', 'oneclick-wa-order'); ?></h2>
    <p>
        <?php
        /* translators: 1. opening <a> tag with strong tag for "set it here", 2. closing </a> tag, 3. opening <a> tag with strong tag for "Learn more", 4. closing </a> tag. */
        echo sprintf(
            /* translators: 1: "set it here" link, 2: "Learn more" link */
            esc_html__('Make sure that you have added at least one WhatsApp number to dismiss the admin notice. Please %1$sset it here%2$s to get started. %3$sLearn more%4$s.', 'oneclick-wa-order'),
            '<a href="edit.php?post_type=wa-order-numbers"><strong>',
            '</strong></a>',
            '<a href="' . esc_url(oskit_url('https://walterpinem.me/projects/oneclick-chat-to-order-mutiple-numbers-feature/')) . '" target="_blank"><strong>',
            '</strong></a>'
        );
        ?>
        <br />
    </p>
    <table class="form-table">
        <tbody>
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Dismiss Notice', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_dismiss_notice_confirmation" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_dismiss_notice_confirmation'), 'yes'); ?>>
                    <?php esc_html_e('Check this if you have added at least one WhatsApp number.', 'oneclick-wa-order'); ?>
                    <br>
                </td>
            </tr>
        </tbody>
    </table>
    <hr>
    <?php /** Re-activated in version 1.0.7. */ ?>
    <h2 class="section_wa_order"><?php esc_html_e('WhatsApp Base URL', 'oneclick-wa-order'); ?></h2>
    <p class="description">
        <?php esc_html_e('If you or your customers are having trouble opening the WhatsApp link on a mobile device or desktop, don\'t worry - you can simply configure the base URL for each device type here.', 'oneclick-wa-order'); ?>
    </p>
    <hr>
    <table class="form-table">
        <tbody>
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_remove_btn_label" for="wa_order_force_use_wa_me">
                        <strong><?php esc_html_e('Force Using wa.me?', 'oneclick-wa-order'); ?></strong>
                    </label>
                </th>
                <td>
                    <select name="wa_order_force_use_wa_me" id="wa_order_force_use_wa_me" class="wa_order-admin-select2">
                        <option value="no" <?php selected(get_option('wa_order_force_use_wa_me', 'no'), 'no'); ?>><?php esc_html_e('No, Don\'t Use wa.me', 'oneclick-wa-order'); ?></option>
                        <option value="yes" <?php selected(get_option('wa_order_force_use_wa_me'), 'yes'); ?>><?php esc_html_e('Yes, Use wa.me', 'oneclick-wa-order'); ?></option>
                    </select>
                    <p class="description">
                        <?php esc_html_e('When enabled, all WhatsApp links will use https://wa.me/ regardless of the mobile/desktop base URL settings below.', 'oneclick-wa-order'); ?>
                        <br>
                        <?php esc_html_e('This option overrides both "Base URL for Mobile" and "Base URL for Desktop" settings below.', 'oneclick-wa-order'); ?>
                    </p>
                    <br>
                </td>
            </tr>
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_remove_btn_label" for="wa_order_whatsapp_base_url">
                        <strong><?php esc_html_e('Base URL for Mobile', 'oneclick-wa-order'); ?></strong>
                    </label>
                </th>
                <td>
                    <select name="wa_order_whatsapp_base_url" id="wa_order_whatsapp_base_url" class="wa_order-admin-select2">
                        <option value="api" <?php selected(get_option('wa_order_whatsapp_base_url'), 'api'); ?>><?php esc_html_e('api - api.whatsapp.com (default)', 'oneclick-wa-order'); ?></option>
                        <option value="protocol" <?php selected(get_option('wa_order_whatsapp_base_url'), 'protocol'); ?>><?php esc_html_e('protocol - whatsapp://send', 'oneclick-wa-order'); ?></option>
                    </select>
                    <p class="description">
                        - <code><?php esc_html_e('whatsapp://send', 'oneclick-wa-order'); ?></code> <?php esc_html_e('is ideal for mobile devices with WhatsApp already installed, offering a faster and more direct experience by bypassing the browser.', 'oneclick-wa-order'); ?>
                        <br>
                    </p>
                    <p class="description">
                        - <code><?php esc_html_e('api.whatsapp.com', 'oneclick-wa-order'); ?></code> <?php esc_html_e('is good for a universal experience that works across mobile device environments, but might involve some extra steps on mobile browsers.', 'oneclick-wa-order'); ?>
                    </p>
                    <br>
                </td>
            </tr>
            <!-- For Desktop -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_remove_btn_label" for="wa_order_whatsapp_base_url_desktop">
                        <strong><?php esc_html_e('Base URL for Desktop', 'oneclick-wa-order'); ?></strong>
                    </label>
                </th>
                <td>
                    <select name="wa_order_whatsapp_base_url_desktop" id="wa_order_whatsapp_base_url_desktop" class="wa_order-admin-select2">
                        <option value="web" <?php selected(get_option('wa_order_whatsapp_base_url_desktop'), 'web'); ?>><?php esc_html_e('web - web.whatsapp.com (default)', 'oneclick-wa-order'); ?></option>
                        <option value="api" <?php selected(get_option('wa_order_whatsapp_base_url_desktop'), 'api'); ?>><?php esc_html_e('api - api.whatsapp.com', 'oneclick-wa-order'); ?></option>
                        <option value="protocol" <?php selected(get_option('wa_order_whatsapp_base_url_desktop'), 'protocol'); ?>><?php esc_html_e('protocol - whatsapp://send', 'oneclick-wa-order'); ?></option>
                    </select>
                    <p class="description">
                        - <?php esc_html_e('Using', 'oneclick-wa-order'); ?> <code><?php esc_html_e('api', 'oneclick-wa-order'); ?></code> <?php esc_html_e('as the base URL, the customers will be prompted to open WhatsApp desktop app if installed.', 'oneclick-wa-order'); ?>
                        <br>
                    </p>
                    <p class="description">
                        - <?php esc_html_e('Whereas using', 'oneclick-wa-order'); ?> <code><?php esc_html_e('web', 'oneclick-wa-order'); ?></code> <?php esc_html_e('as the base URL, the customers will be immediately redirected to the WhatsApp web on the browser.', 'oneclick-wa-order'); ?>
                        <br>
                    </p>
                    <p class="description">
                        - <?php esc_html_e('Using the protocol', 'oneclick-wa-order'); ?> <code><?php esc_html_e('whatsapp://send', 'oneclick-wa-order'); ?></code> <?php esc_html_e('will immediately prompt the customers to open the WhatsApp desktop app, bypassing the browser interaction.', 'oneclick-wa-order'); ?>
                        <br>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
    <hr>
    <table class="form-table">
        <tbody>
            <h2 class="section_wa_order"><?php esc_html_e('Single Product Page', 'oneclick-wa-order'); ?></h2>
            <p>
                <?php esc_html_e('These configurations will be only effective on single product page.', 'oneclick-wa-order'); ?>
                <br />
            </p>
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Display Button?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_enable_single_product" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_enable_single_product'), 'yes'); ?>>
                    <?php esc_html_e('This will display WhatsApp button on single product page', 'oneclick-wa-order'); ?>
                    <br>
                </td>
            </tr>
            <!-- Dropdown WA Number -->
            <tr>
                <th scope="row">
                    <label>
                        <?php esc_html_e('WhatsApp Number', 'oneclick-wa-order') ?>
                    </label>
                </th>
                <td>
                    <?php wa_order_phone_numbers_dropdown(
                        array(
                            'name'      => 'wa_order_selected_wa_number_single_product',
                            'selected'  => (get_option('wa_order_selected_wa_number_single_product')),
                        )
                    )
                    ?>
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
            <!-- END - Dropdown WA Number -->
            <!-- Link Type Option -->
            <tr>
                <th scope="row">
                    <label for="wa_order_single_product_link_type"><?php esc_html_e('Button Link Type', 'oneclick-wa-order') ?></label>
                </th>
                <td>
                    <select name="wa_order_single_product_link_type" id="wa_order_single_product_link_type" class="wa_order-admin-select2">
                        <option value="link" <?php selected(get_option('wa_order_single_product_link_type', 'link'), 'link'); ?>><?php esc_html_e('<a> Link Tag', 'oneclick-wa-order'); ?></option>
                        <option value="onclick" <?php selected(get_option('wa_order_single_product_link_type'), 'onclick'); ?>><?php esc_html_e('Inline onClick Event', 'oneclick-wa-order'); ?></option>
                    </select>
                    <p class="description">
                        <?php esc_html_e('Choose how the WhatsApp button should handle clicks on single product pages.', 'oneclick-wa-order'); ?>
                        <br>
                        <?php esc_html_e('Use "Inline onClick Event" if you experience issues with Ajax Add to Cart plugins or themes.', 'oneclick-wa-order'); ?>
                    </p>
                </td>
            </tr>
            <!-- END - Link Type Option -->
            <!-- Dropdown Button Position -->
            <tr>
                <th scope="row">
                    <label for="wa_order_single_product_button_position"><?php echo esc_html_e('Button Position', 'oneclick-wa-order') ?></label>
                </th>
                <td>
                    <select name="wa_order_single_product_button_position" id="wa_order_single_product_button_position" class="wa_order-admin-select2">
                        <option value="after_atc" <?php selected(get_option('wa_order_single_product_button_position'), 'after_atc'); ?>><?php esc_html_e('After Add to Cart Button (Default)', 'oneclick-wa-order'); ?></option>
                        <option value="under_atc" <?php selected(get_option('wa_order_single_product_button_position'), 'under_atc'); ?>><?php esc_html_e('Under Add to Cart Button', 'oneclick-wa-order'); ?></option>
                        <option value="after_shortdesc" <?php selected(get_option('wa_order_single_product_button_position'), 'after_shortdesc'); ?>><?php esc_html_e('After Short Description', 'oneclick-wa-order'); ?></option>
                        <option value="after_single_product_summary" <?php selected(get_option('wa_order_single_product_button_position'), 'after_single_product_summary'); ?>><?php esc_html_e('After Single Product Summary', 'oneclick-wa-order'); ?></option>
                        <option value="around_share_area" <?php selected(get_option('wa_order_single_product_button_position'), 'around_share_area'); ?>><?php esc_html_e('Around Product Share Area', 'oneclick-wa-order'); ?></option>
                    </select>
                    <p class="description">
                        <?php esc_html_e('Choose where to put the WhatsApp button on single product page.', 'oneclick-wa-order'); ?>
                    </p>
                </td>
            </tr>
            <!-- END - Dropdown Button Position -->

            <!-- Force Full-Width -->
            <tr class="wa_order_price" id="force_fullwidth_container">
                <th scope="row">
                    <label class="wa_order_price_label" for="wa_order_price"><b><?php esc_html_e('Force Full-Width?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_single_force_fullwidth" id="wa_order_single_force_fullwidth" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_single_force_fullwidth'), 'yes'); ?>>
                    <?php esc_html_e('Yes, force the button to be full width.', 'oneclick-wa-order'); ?>
                    <br>
                </td>
            </tr>
            <!-- END - Force Full-Width -->

            <tr class="wa_order_message">
                <th scope="row">
                    <label class="wa_order_message_label" for="message_owo"><b><?php esc_html_e('Custom Message', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <textarea name="wa_order_option_message" class="wa_order_input_areatext" rows="5" placeholder="<?php esc_html_e('e.g. Hello, I want to buy:', 'oneclick-wa-order'); ?>"><?php echo esc_textarea(get_option('wa_order_option_message')); ?></textarea>
                    <p class="description">
                        <?php
                        /* translators: 1. example custom message wrapped in <code> tag */
                        echo sprintf(
                            /* translators: 1. example custom message wrapped in <code> tag */
                            esc_html__('Fill this form with a custom message, e.g. %1$sHello, I want to buy:%2$s', 'oneclick-wa-order'),
                            '<code>', // opening <code> tag
                            '</code>' // closing <code> tag
                        );
                        ?>
                    </p>
                </td>
            </tr>
            <tr class="wa_order_btn_text">
                <th scope="row">
                    <label class="wa_order_btn_txt_label" for="text_button"><b><?php esc_html_e('Text on Button', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="text" name="wa_order_option_text_button" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_text_button')); ?>" placeholder="<?php esc_html_e('e.g. Order via WhatsApp', 'oneclick-wa-order'); ?>">
                </td>
            </tr>
            <!-- Show Regular & Sale Price -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_target_label" for="wa_order_option_single_show_regular_sale_prices"><b><?php esc_html_e('Show Regular & Sale Prices?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_single_show_regular_sale_prices" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_single_show_regular_sale_prices'), 'yes'); ?>>
                    <?php esc_html_e('Check to show both regular and sale prices in the WhatsApp message.', 'oneclick-wa-order'); ?>
                    <br>
                </td>
            </tr>
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_target_label" for="wa_order_target"><b><?php esc_html_e('Open in New Tab?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_target" class="wa_order_input_check" value="_blank" <?php checked(get_option('wa_order_option_target'), '_blank'); ?>>
                    <?php esc_html_e('Yes, Open in New Tab', 'oneclick-wa-order'); ?>
                    <br>
                </td>
            </tr>
        </tbody>
    </table>
    <hr>
    <table class="form-table">
        <tbody>
            <h2 class="section_wa_order"><?php esc_html_e('Exclusion', 'oneclick-wa-order'); ?></h2>
            <p>
                <?php
                /* translators: 1. opening <a> tag with href to "Display Options" tab, 2. closing </a> tag */
                echo sprintf(
                    /* translators: 1. opening <a> tag with href to "Display Options" tab */
                    /* translators: 2. closing </a> tag */
                    esc_html__('The following option is only for the output message you\'ll receive on WhatsApp. To hide some elements, please go to the %1$sDisplay Options%2$s tab.', 'oneclick-wa-order'),
                    '<a href="admin.php?page=wa-order&tab=display_option"><strong>', // opening <a> and <strong> tag
                    '</strong></a>' // closing <a> and <strong> tag
                );
                ?>
            </p>

            <tr class="wa_order_price">
                <th scope="row">
                    <label class="wa_order_price_label" for="wa_order_price"><b><?php esc_html_e('Exclude Price?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_exclude_price" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_exclude_price'), 'yes'); ?>>
                    <?php esc_html_e('Yes, exclude price in WhatsApp message.', 'oneclick-wa-order'); ?>
                    <br>
                </td>
            </tr>
            <tr class="wa_order_price">
                <th scope="row">
                    <label class="wa_order_price_label" for="wa_order_price"><b><?php esc_html_e('Remove Product URL?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_exclude_product_url" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_exclude_product_url'), 'yes'); ?>>
                    <?php esc_html_e('This will remove product URL from WhatsApp message sent from single product page.', 'oneclick-wa-order'); ?>
                    <br>
                </td>
            </tr>
        </tbody>
    </table>
    <hr>
    <table class="form-table">
        <tbody>
            <h2 class="section_wa_order"><?php esc_html_e('Text Translations', 'oneclick-wa-order'); ?></h2>
            <p><?php esc_html_e('You can translate the following strings which will be included in the sent message. By default, the labels are used in the message. You can translate or change them below accordingly.', 'oneclick-wa-order'); ?></p>
            <tr class="wa_order_btn_text">
                <th scope="row">
                    <label class="wa_order_btn_txt_label" for="text_button"><b><?php esc_html_e('Quantity', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="text" name="wa_order_option_quantity_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_quantity_label', 'Quantity')); ?>" placeholder="<?php esc_html_e('e.g. Quantity', 'oneclick-wa-order'); ?>">
                </td>
            </tr>
            <tr class="wa_order_btn_text">
                <th scope="row">
                    <label class="wa_order_btn_txt_label" for="text_button"><b><?php esc_html_e('Price', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="text" name="wa_order_option_price_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_price_label', 'Price')); ?>" placeholder="<?php esc_html_e('e.g. Price', 'oneclick-wa-order'); ?>">
                </td>
            </tr>
            <tr class="wa_order_btn_text">
                <th scope="row">
                    <label class="wa_order_btn_txt_label" for="text_button"><b><?php esc_html_e('URL', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="text" name="wa_order_option_url_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_url_label', 'URL')); ?>" placeholder="<?php esc_html_e('e.g. Link', 'oneclick-wa-order'); ?>">
                </td>
            </tr>
            <tr class="wa_order_btn_text">
                <th scope="row">
                    <label class="wa_order_btn_txt_label" for="text_button"><b><?php esc_html_e('Total Amount', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="text" name="wa_order_option_total_amount_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_total_amount_label', 'Total Price')); ?>" placeholder="<?php esc_html_e('e.g. Total Amount', 'oneclick-wa-order'); ?>">
                </td>
            </tr>
            <tr class="wa_order_btn_text">
                <th scope="row">
                    <label class="wa_order_btn_txt_label" for="text_button"><b><?php esc_html_e('Total Discount', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="text" name="wa_order_option_total_discount_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_total_discount_label', 'Total Discount')); ?>" placeholder="<?php esc_html_e('e.g. Total Discount', 'oneclick-wa-order'); ?>">
                </td>
            </tr>
            <tr class="wa_order_btn_text">
                <th scope="row">
                    <label class="wa_order_btn_txt_label" for="text_button"><b><?php esc_html_e('Payment Method', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="text" name="wa_order_option_payment_method_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_payment_method_label', 'Payment Method')); ?>" placeholder="<?php esc_html_e('e.g. Payment via', 'oneclick-wa-order'); ?>">
                </td>
            </tr>
            <tr class="wa_order_btn_text">
                <th scope="row">
                    <label class="wa_order_btn_txt_label" for="text_button"><b><?php esc_html_e('Thank you!', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="text" name="wa_order_option_thank_you_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_thank_you_label', 'Thank you!')); ?>" placeholder="<?php esc_html_e('e.g. Thank you in advance!', 'oneclick-wa-order'); ?>">
                </td>
            </tr>
            <!-- Tax Label -->
            <tr class="wa_order_btn_text">
                <th scope="row">
                    <label for="wa_order_option_tax_label"><?php echo esc_html__('Tax Label', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <input type="text" name="wa_order_option_tax_label" id="wa_order_option_tax_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_option_tax_label')); ?>" placeholder="<?php echo esc_attr__('e.g. Tax', 'oneclick-wa-order'); ?>">
                </td>
            </tr>
        </tbody>
    </table>
    <hr>
    <!-- Plugin Data Management -->
    <h2 class="section_wa_order"><?php esc_html_e('Plugin Data Management', 'oneclick-wa-order'); ?></h2>
    <p class="description">
        <?php esc_html_e('Configure what happens to your plugin data when the plugin is uninstalled.', 'oneclick-wa-order'); ?>
    </p>
    <table class="form-table">
        <tbody>
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_target_label" for="wa_order_delete_data_on_uninstall">
                        <strong><?php esc_html_e('Delete Plugin Data on Uninstall?', 'oneclick-wa-order'); ?></strong>
                    </label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_delete_data_on_uninstall" id="wa_order_delete_data_on_uninstall" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_delete_data_on_uninstall'), 'yes'); ?>>
                    <?php esc_html_e('Check this to completely remove all plugin data when the plugin is deleted.', 'oneclick-wa-order'); ?>
                    <br>
                    <p class="description" style="color: #d63638; font-weight: 500;">
                        <strong><?php esc_html_e('Warning:', 'oneclick-wa-order'); ?></strong>
                        <?php esc_html_e('This will permanently delete all WhatsApp numbers, settings, and configurations. This action cannot be undone.', 'oneclick-wa-order'); ?>
                    </p>
                    <p class="description">
                        <?php esc_html_e('Data that will be deleted includes:', 'oneclick-wa-order'); ?>
                    </p>
                    <ul style="margin-left: 20px; list-style-type: disc;">
                        <li><?php esc_html_e('All WhatsApp numbers and their configurations', 'oneclick-wa-order'); ?></li>
                        <li><?php esc_html_e('All plugin settings and customizations', 'oneclick-wa-order'); ?></li>
                        <li><?php esc_html_e('Product-specific WhatsApp button settings', 'oneclick-wa-order'); ?></li>
                        <li><?php esc_html_e('Floating button configurations', 'oneclick-wa-order'); ?></li>
                        <li><?php esc_html_e('GDPR settings and custom messages', 'oneclick-wa-order'); ?></li>
                        <li><?php esc_html_e('All cached data and transients', 'oneclick-wa-order'); ?></li>
                    </ul>
                    <p class="description">
                        <?php esc_html_e('If unchecked, all data will be preserved and can be restored if you reinstall the plugin later.', 'oneclick-wa-order'); ?>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
    <hr>
    <?php submit_button(); ?>
</form>