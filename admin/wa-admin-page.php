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
 * @category    Admin Page
 */

// WA Number Post Type Submenu
function wa_order_add_number_submenu()
{
    add_submenu_page('wa-order', __('OneClick Chat to Order Options', 'oneclick-whatsapp-order'), __('Global Settings', 'oneclick-whatsapp-order'), 'manage_options', 'admin.php?page=wa-order&tab=welcome');
    add_submenu_page('wa-order', __('WhatsApp Numbers', 'oneclick-whatsapp-order'), __('WhatsApp Numbers', 'oneclick-whatsapp-order'), 'manage_options', 'edit.php?post_type=wa-order-numbers');
    add_submenu_page('wa-order', __('Add Number', 'oneclick-whatsapp-order'), __('Add New Number', 'oneclick-whatsapp-order'), 'manage_options', 'post-new.php?post_type=wa-order-numbers');
};
add_action('admin_menu', 'wa_order_add_number_submenu');
// Build plugin admin setting page
function wa_order_add_admin_page()
{
    // Generate Chat to Order Admin Page
    add_menu_page(__('OneClick Chat to Order Options', 'oneclick-whatsapp-order'), __('Chat to Order', 'oneclick-whatsapp-order'), 'manage_options', 'wa-order', 'wa_order_create_admin_page', OCTO_URL . 'assets/images/wa-icon.svg', 98);
    // Begin building
    add_action('admin_init', 'wa_order_register_settings');
}
add_action('admin_menu', 'wa_order_add_admin_page');
// Array mapping for better customizations
// Since version 1.0.5
function wa_order_register_settings()
{
    $settings = wa_order_get_settings();
    foreach ($settings as $group => $group_settings) {
        foreach ($group_settings as $setting_name => $sanitization_callback) {
            register_setting($group, $setting_name, $sanitization_callback);
        }
    }
}
// Sanitization callback that can handle arrays of data properly
// Since version 1.0.5
function wa_order_sanitize_array($input)
{
    if (is_array($input)) {
        return array_map('sanitize_text_field', $input);
    }
    return [];
}

// Sanitization callback for checkbox fields
// Returns 'yes' if checked, empty string if not.
// Renamed from the unprefixed sanitize_checkbox() in 1.2.0 to avoid
// "Cannot redeclare" fatal errors with themes or plugins using that name.
function wa_order_sanitize_checkbox($input)
{
    return ($input === 'yes') ? 'yes' : '';
}

// Sanitization callback for "Open in New Tab?" checkboxes.
// Returns '_blank' if checked, empty string if not.
// Since version 1.2.0
function wa_order_sanitize_target($input)
{
    return ($input === '_blank') ? '_blank' : '';
}
// Sanitization callback function for WP color picker
// Since version 1.0.5
function wa_order_sanitize_rgba_color($color)
{
    // Handling RGBA color format
    if (preg_match('/^rgba\(\d{1,3},\s?\d{1,3},\s?\d{1,3},\s?(0|1|0?\.\d+)\)$/', trim($color))) {
        return $color;
    }
    // Handling RGB color format (convert to HEX)
    elseif (preg_match('/^rgb\(\d{1,3},\s?\d{1,3},\s?\d{1,3}\)$/', trim($color))) {
        return wa_order_rgb_to_hex($color);
    }
    // Handling HEX color format
    return sanitize_hex_color($color);
}
function wa_order_rgb_to_hex($rgb)
{
    // Convert RGB to HEX color format
    list($r, $g, $b) = sscanf($rgb, "rgb(%d, %d, %d)");
    return sprintf("#%02x%02x%02x", $r, $g, $b);
}

// Revamped admin settings registrations
// Since version 1.0.5
function wa_order_get_settings()
{
    return [
        /*
        ******************************************** Basic tab options ****************************************
        */
        'wa-order-settings-group-button-config' => [
            'wa_order_selected_wa_number_single_product' => 'sanitize_text_field',
            'wa_order_option_dismiss_notice_confirmation' => 'wa_order_sanitize_checkbox',
            'wa_order_whatsapp_base_url' => 'sanitize_text_field', // Reactivated in version 1.0.7
            'wa_order_whatsapp_base_url_desktop' => 'sanitize_text_field',
            'wa_order_force_use_wa_me' => 'sanitize_text_field',
            'wa_order_single_product_link_type' => 'sanitize_text_field',
            'wa_order_single_product_button_position' => 'sanitize_text_field',
            'wa_order_option_enable_single_product' => 'wa_order_sanitize_checkbox',
            'wa_order_option_message' => 'sanitize_textarea_field',
            'wa_order_option_text_button' => 'sanitize_text_field',
            'wa_order_option_target' => 'wa_order_sanitize_target',
            'wa_order_exclude_price' => 'wa_order_sanitize_checkbox',
            'wa_order_exclude_product_url' => 'wa_order_sanitize_checkbox',
            'wa_order_option_quantity_label' => 'sanitize_text_field',
            'wa_order_option_price_label' => 'sanitize_text_field',
            'wa_order_option_url_label' => 'sanitize_text_field',
            'wa_order_option_total_amount_label' => 'sanitize_text_field',
            'wa_order_option_total_discount_label' => 'sanitize_text_field',
            'wa_order_option_payment_method_label' => 'sanitize_text_field',
            'wa_order_option_thank_you_label' => 'sanitize_text_field',
            'wa_order_option_tax_label' => 'sanitize_text_field',
            'wa_order_single_force_fullwidth' => 'wa_order_sanitize_checkbox',
            'wa_order_option_single_show_regular_sale_prices' => 'wa_order_sanitize_checkbox',
            'wa_order_delete_data_on_uninstall' => 'wa_order_sanitize_checkbox',
            'wa_order_option_show_without_price' => 'wa_order_sanitize_checkbox', // Since 1.2.0
            'wa_order_option_show_out_of_stock' => 'wa_order_sanitize_checkbox', // Since 1.2.0
        ],
        /*
        ******************************************** Display tab options ****************************************
        */
        'wa-order-settings-group-display-options' => [
            'wa_order_bg_color' => 'wa_order_sanitize_rgba_color',
            'wa_order_bg_hover_color' => 'wa_order_sanitize_rgba_color',
            'wa_order_txt_color' => 'wa_order_sanitize_rgba_color',
            'wa_order_txt_hover_color' => 'wa_order_sanitize_rgba_color',
            'wa_order_btn_box_shdw' => 'wa_order_sanitize_rgba_color',
            'wa_order_bshdw_horizontal' => 'sanitize_text_field',
            'wa_order_bshdw_vertical' => 'sanitize_text_field',
            'wa_order_bshdw_blur' => 'sanitize_text_field',
            'wa_order_bshdw_spread' => 'sanitize_text_field',
            'wa_order_bshdw_position' => 'sanitize_text_field',
            'wa_order_btn_box_shdw_hover' => 'wa_order_sanitize_rgba_color',
            'wa_order_bshdw_horizontal_hover' => 'sanitize_text_field',
            'wa_order_bshdw_vertical_hover' => 'sanitize_text_field',
            'wa_order_bshdw_blur_hover' => 'sanitize_text_field',
            'wa_order_bshdw_spread_hover' => 'sanitize_text_field',
            'wa_order_bshdw_position_hover' => 'sanitize_text_field',
            'wa_order_option_remove_btn' => 'wa_order_sanitize_checkbox',
            'wa_order_option_remove_btn_mobile' => 'wa_order_sanitize_checkbox',
            'wa_order_option_remove_price' => 'wa_order_sanitize_checkbox',
            'wa_order_option_remove_cart_btn' => 'wa_order_sanitize_checkbox',
            'wa_order_option_remove_quantity' => 'wa_order_sanitize_checkbox',
            'wa_order_option_exlude_single_product_cats' => 'wa_order_sanitize_array',
            'wa_order_option_exlude_single_product_tags' => 'wa_order_sanitize_array',
            'wa_order_single_button_margin_top' => 'sanitize_text_field',
            'wa_order_single_button_margin_right' => 'sanitize_text_field',
            'wa_order_single_button_margin_bottom' => 'sanitize_text_field',
            'wa_order_single_button_margin_left' => 'sanitize_text_field',
            'wa_order_single_button_padding_top' => 'sanitize_text_field',
            'wa_order_single_button_padding_right' => 'sanitize_text_field',
            'wa_order_single_button_padding_bottom' => 'sanitize_text_field',
            'wa_order_single_button_padding_left' => 'sanitize_text_field',
            'wa_order_display_option_shop_loop_hide_desktop' => 'wa_order_sanitize_checkbox',
            'wa_order_display_option_shop_loop_hide_mobile' => 'wa_order_sanitize_checkbox',
            'wa_order_option_exlude_shop_product_cats' => 'wa_order_sanitize_array',
            'wa_order_exlude_shop_product_cats_archive' => 'wa_order_sanitize_checkbox',
            'wa_order_option_exlude_shop_product_tags' => 'wa_order_sanitize_array',
            'wa_order_exlude_shop_product_tags_archive' => 'wa_order_sanitize_checkbox',
            'wa_order_display_option_cart_hide_desktop' => 'wa_order_sanitize_checkbox',
            'wa_order_display_option_cart_hide_mobile' => 'wa_order_sanitize_checkbox',
            'wa_order_display_option_checkout_hide_desktop' => 'wa_order_sanitize_checkbox',
            'wa_order_display_option_checkout_hide_mobile' => 'wa_order_sanitize_checkbox',
            'wa_order_option_convert_phone_order_details' => 'wa_order_sanitize_checkbox',
            'wa_order_option_custom_message_backend_order_details' => 'sanitize_text_field',
            'wa_order_option_hide_button_icon' => 'wa_order_sanitize_checkbox', // Since 1.2.0
        ],
        /*
    ******************************************** GDPR tab options ****************************************
    */
        'wa-order-settings-group-gdpr' => [
            'wa_order_gdpr_status_enable' => 'wa_order_sanitize_checkbox',
            'wa_order_gdpr_message' => 'sanitize_textarea_field',
            'wa_order_gdpr_privacy_page' => 'sanitize_text_field'
        ],
        /*
    ******************************************** Floating Button tab options ****************************************
    */
        'wa-order-settings-group-floating' => [
            'wa_order_selected_wa_number_floating' => 'sanitize_text_field',
            'wa_order_floating_button' => 'wa_order_sanitize_checkbox',
            'wa_order_floating_button_position' => 'sanitize_text_field',
            'wa_order_floating_message' => 'sanitize_textarea_field',
            'wa_order_floating_target' => 'wa_order_sanitize_target',
            'wa_order_floating_tooltip_enable' => 'wa_order_sanitize_checkbox',
            'wa_order_floating_tooltip' => 'sanitize_text_field',
            'wa_order_floating_hide_mobile' => 'wa_order_sanitize_checkbox',
            'wa_order_floating_hide_desktop' => 'wa_order_sanitize_checkbox',
            'wa_order_floating_source_url' => 'wa_order_sanitize_checkbox',
            'wa_order_floating_source_url_label' => 'sanitize_text_field',
            'wa_order_floating_hide_all_single_posts' => 'wa_order_sanitize_checkbox',
            'wa_order_floating_hide_all_single_pages' => 'wa_order_sanitize_checkbox',
            'wa_order_floating_hide_specific_posts' => 'wa_order_sanitize_array',
            'wa_order_floating_hide_specific_pages' => 'wa_order_sanitize_array',
            'wa_order_floating_hide_product_cats' => 'wa_order_sanitize_array',
            'wa_order_floating_hide_product_tags' => 'wa_order_sanitize_array',
            'wa_order_floating_button_margin_top' => 'sanitize_text_field',
            'wa_order_floating_button_margin_right' => 'sanitize_text_field',
            'wa_order_floating_button_margin_bottom' => 'sanitize_text_field',
            'wa_order_floating_button_margin_left' => 'sanitize_text_field',
            'wa_order_floating_button_padding_top' => 'sanitize_text_field',
            'wa_order_floating_button_padding_right' => 'sanitize_text_field',
            'wa_order_floating_button_padding_bottom' => 'sanitize_text_field',
            'wa_order_floating_button_padding_left' => 'sanitize_text_field',
            'wa_order_floating_button_icon_margin_top' => 'sanitize_text_field',
            'wa_order_floating_button_icon_margin_right' => 'sanitize_text_field',
            'wa_order_floating_button_icon_margin_bottom' => 'sanitize_text_field',
            'wa_order_floating_button_icon_margin_left' => 'sanitize_text_field',
            'wa_order_floating_button_icon_padding_top' => 'sanitize_text_field',
            'wa_order_floating_button_icon_padding_right' => 'sanitize_text_field',
            'wa_order_floating_button_icon_padding_bottom' => 'sanitize_text_field',
            'wa_order_floating_button_icon_padding_left' => 'sanitize_text_field'
        ],
        /*
    ******************************************** Shortcode tab options ****************************************
    */
        'wa-order-settings-group-shortcode' => [
            'wa_order_selected_wa_number_shortcode' => 'sanitize_text_field',
            'wa_order_shortcode_message' => 'sanitize_textarea_field',
            'wa_order_shortcode_text_button' => 'sanitize_text_field',
            'wa_order_shortcode_target' => 'wa_order_sanitize_target'
        ],
        /*
    ******************************************** Cart page tab options ****************************************
    */
        'wa-order-settings-group-cart-options' => [
            'wa_order_selected_wa_number_cart' => 'sanitize_text_field',
            'wa_order_option_add_button_to_cart' => 'wa_order_sanitize_checkbox',
            'wa_order_option_cart_custom_message' => 'sanitize_textarea_field',
            'wa_order_option_cart_button_text' => 'sanitize_text_field',
            'wa_order_option_cart_hide_checkout' => 'wa_order_sanitize_checkbox',
            'wa_order_option_cart_hide_product_url' => 'wa_order_sanitize_checkbox',
            'wa_order_option_cart_open_new_tab' => 'wa_order_sanitize_target',
            'wa_order_option_cart_enable_variations' => 'wa_order_sanitize_checkbox',
            'wa_order_option_cart_include_tax' => 'wa_order_sanitize_checkbox',
            'wa_order_option_cart_exclude_price' => 'wa_order_sanitize_checkbox', // Since 1.2.0
            'wa_order_option_cart_include_item_data' => 'wa_order_sanitize_checkbox', // Since 1.2.0
        ],
        /*
    ******************************************** Thank You page tab options ****************************************
    */
        'wa-order-settings-group-order-completion' => [
            'wa_order_selected_wa_number_thanks' => 'sanitize_text_field',
            'wa_order_option_thank_you_redirect_checkout' => 'wa_order_sanitize_checkbox',
            'wa_order_option_enable_button_thank_you' => 'wa_order_sanitize_checkbox',
            'wa_order_option_custom_thank_you_title' => 'sanitize_text_field',
            'wa_order_option_custom_thank_you_subtitle' => 'sanitize_text_field',
            'wa_order_option_custom_thank_you_button_text' => 'sanitize_text_field',
            'wa_order_option_custom_thank_you_custom_message' => 'sanitize_textarea_field',
            'wa_order_option_custom_thank_you_include_order_date' => 'wa_order_sanitize_checkbox',
            'wa_order_option_custom_thank_you_order_number' => 'wa_order_sanitize_checkbox',
            'wa_order_option_custom_thank_you_order_number_label' => 'sanitize_text_field',
            'wa_order_option_thank_you_order_summary_link' => 'wa_order_sanitize_checkbox',
            'wa_order_option_thank_you_order_summary_label' => 'sanitize_text_field',
            'wa_order_option_thank_you_payment_link' => 'wa_order_sanitize_checkbox',
            'wa_order_option_thank_you_payment_link_label' => 'sanitize_text_field',
            'wa_order_option_thank_you_view_order_link' => 'wa_order_sanitize_checkbox',
            'wa_order_option_thank_you_view_order_label' => 'sanitize_text_field',
            'wa_order_option_custom_thank_you_open_new_tab' => 'wa_order_sanitize_target',
            'wa_order_option_custom_thank_you_customer_details_label' => 'sanitize_text_field',
            'wa_order_option_custom_thank_you_total_products_label' => 'sanitize_text_field',
            'wa_order_option_custom_thank_you_include_sku' => 'wa_order_sanitize_checkbox',
            'wa_order_option_custom_thank_you_include_tax' => 'wa_order_sanitize_checkbox',
            'wa_order_option_custom_thank_you_inclue_coupon' => 'wa_order_sanitize_checkbox',
            'wa_order_option_custom_thank_you_coupon_label' => 'sanitize_text_field',
            'wa_order_option_custom_thank_you_include_shipping' => 'wa_order_sanitize_checkbox',
            'wa_order_option_custom_thank_you_shipping_label' => 'sanitize_text_field'
        ],
        /*
    ******************************************** Shop page tab options ****************************************
    */
        'wa-order-settings-group-shop-loop' => [
            'wa_order_selected_wa_number_shop' => 'sanitize_text_field',
            'wa_order_option_enable_button_shop_loop' => 'wa_order_sanitize_checkbox',
            'wa_order_option_hide_atc_shop_loop' => 'wa_order_sanitize_checkbox',
            'wa_order_option_button_text_shop_loop' => 'sanitize_text_field',
            'wa_order_option_custom_message_shop_loop' => 'sanitize_textarea_field',
            'wa_order_option_shop_loop_hide_product_url' => 'wa_order_sanitize_checkbox',
            'wa_order_option_shop_loop_exclude_price' => 'wa_order_sanitize_checkbox',
            'wa_order_option_shop_loop_open_new_tab' => 'wa_order_sanitize_target',
            'wa_order_option_shop_loop_show_regular_sale_prices' => 'wa_order_sanitize_checkbox'
        ],
    ];
}
// Note: settings are never deleted on deactivation. Cleanup happens only in
// uninstall.php, and only when "Delete data on uninstall" is checked.
// Begin Building the Admin Tabs
function wa_order_create_admin_page()
{
    // Define the valid tabs
    $valid_tabs = [
        'button_config',
        'floating_button',
        'display_option',
        'shop_page',
        'cart_button',
        'thanks_page',
        'gdpr_notice',
        'generate_shortcode',
        'tutorial_support',
        'addons',
        'welcome'
    ];
    // Sanitize and validate the 'tab' parameter
    $active_tab = isset($_GET['tab']) ? sanitize_text_field(wp_unslash($_GET['tab'])) : 'welcome';
    if (!in_array($active_tab, $valid_tabs, true)) {
        $active_tab = 'welcome'; // default to the 'welcome' tab
    }
?>
    <div class="wrap OCWAORDER_pluginpage_title">
        <h1><?php esc_html_e('OneClick Chat to Order', 'oneclick-whatsapp-order'); ?></h1>
        <hr>
        <h2 class="nav-tab-wrapper">
            <a href="?page=wa-order&tab=welcome" class="nav-tab <?php echo esc_attr('welcome' === $active_tab ? 'nav-tab-active' : ''); ?>"><?php esc_html_e('Welcome', 'oneclick-whatsapp-order'); ?></a>
            <a href="edit.php?post_type=wa-order-numbers" class="nav-tab <?php echo esc_attr('phone-numbers' === $active_tab ? 'nav-tab-active' : ''); ?>"><?php esc_html_e('Numbers', 'oneclick-whatsapp-order'); ?></a>
            <a href="?page=wa-order&tab=button_config" class="nav-tab <?php echo esc_attr('button_config' === $active_tab ? 'nav-tab-active' : ''); ?>"><?php esc_html_e('Basic', 'oneclick-whatsapp-order'); ?></a>
            <a href="?page=wa-order&tab=shop_page" class="nav-tab <?php echo esc_attr('shop_page' === $active_tab ? 'nav-tab-active' : ''); ?>"><?php esc_html_e('Shop', 'oneclick-whatsapp-order'); ?></a>
            <a href="?page=wa-order&tab=cart_button" class="nav-tab <?php echo esc_attr('cart_button' === $active_tab ? 'nav-tab-active' : ''); ?>"><?php esc_html_e('Cart', 'oneclick-whatsapp-order'); ?></a>
            <a href="?page=wa-order&tab=thanks_page" class="nav-tab <?php echo esc_attr('thanks_page' === $active_tab ? 'nav-tab-active' : ''); ?>"><?php esc_html_e('Checkout', 'oneclick-whatsapp-order'); ?></a>
            <a href="?page=wa-order&tab=floating_button" class="nav-tab <?php echo esc_attr('floating_button' === $active_tab ? 'nav-tab-active' : ''); ?>"><?php esc_html_e('Floating', 'oneclick-whatsapp-order'); ?></a>
            <a href="?page=wa-order&tab=display_option" class="nav-tab <?php echo esc_attr('display_option' === $active_tab ? 'nav-tab-active' : ''); ?>"><?php esc_html_e('Display', 'oneclick-whatsapp-order'); ?></a>
            <a href="?page=wa-order&tab=gdpr_notice" class="nav-tab <?php echo esc_attr('gdpr_notice' === $active_tab ? 'nav-tab-active' : ''); ?>"><?php esc_html_e('GDPR', 'oneclick-whatsapp-order'); ?></a>
            <a href="?page=wa-order&tab=generate_shortcode" class="nav-tab <?php echo esc_attr('generate_shortcode' === $active_tab ? 'nav-tab-active' : ''); ?>"><?php esc_html_e('Shortcode', 'oneclick-whatsapp-order'); ?></a>
            <a href="?page=wa-order&tab=addons" class="nav-tab <?php echo esc_attr('addons' === $active_tab ? 'nav-tab-active' : ''); ?>"><?php esc_html_e('Add-ons', 'oneclick-whatsapp-order'); ?></a>
            <a href="?page=wa-order&tab=tutorial_support" class="nav-tab <?php echo esc_attr('tutorial_support' === $active_tab ? 'nav-tab-active' : ''); ?>"><?php esc_html_e('Support', 'oneclick-whatsapp-order'); ?></a>
        </h2>
        <?php if ($active_tab == 'generate_shortcode') {
            // Render content for the active tab
            $generate_shortcode = OCTO_DIR . 'admin/tabs/generate-shortcode.php';
            if (file_exists($generate_shortcode)) {
                include_once $generate_shortcode;
            } else {
                echo '<div class="error"><p>' . esc_html__('Error: Settings file not found.', 'oneclick-whatsapp-order') . '</p></div>';
            }
        } elseif ($active_tab == 'button_config') {
            // Render content for the active tab
            $button_config = OCTO_DIR . 'admin/tabs/button-config.php';
            if (file_exists($button_config)) {
                include_once $button_config;
            } else {
                echo '<div class="error"><p>' . esc_html__('Error: Settings file not found.', 'oneclick-whatsapp-order') . '</p></div>';
            }
        ?>


        <?php } elseif ($active_tab == 'floating_button') {
            // Render content for the active tab
            $floating_button = OCTO_DIR . 'admin/tabs/floating-button.php';
            if (file_exists($floating_button)) {
                include_once $floating_button;
            } else {
                echo '<div class="error"><p>' . esc_html__('Error: Settings file not found.', 'oneclick-whatsapp-order') . '</p></div>';
            }
        ?>
        <?php } elseif ($active_tab == 'display_option') { ?>
            <?php
            // Render content for the active tab
            $display_option = OCTO_DIR . 'admin/tabs/display-option.php';
            if (file_exists($display_option)) {
                include_once $display_option;
            } else {
                echo '<div class="error"><p>' . esc_html__('Error: Settings file not found.', 'oneclick-whatsapp-order') . '</p></div>';
            }
            ?>
        <?php } elseif ($active_tab == 'shop_page') {
            // Render content for the active tab
            $shop_page = OCTO_DIR . 'admin/tabs/shop-page.php';
            if (file_exists($shop_page)) {
                include_once $shop_page;
            } else {
                echo '<div class="error"><p>' . esc_html__('Error: Settings file not found.', 'oneclick-whatsapp-order') . '</p></div>';
            }
        ?>
        <?php } elseif ($active_tab == 'cart_button') {
            // Render content for the active tab
            $cart_button = OCTO_DIR . 'admin/tabs/cart-button.php';
            if (file_exists($cart_button)) {
                include_once $cart_button;
            } else {
                echo '<div class="error"><p>' . esc_html__('Error: Settings file not found.', 'oneclick-whatsapp-order') . '</p></div>';
            }
        ?>
        <?php } elseif ($active_tab == 'thanks_page') {
            // Render content for the active tab
            $thanks_page = OCTO_DIR . 'admin/tabs/thanks-page.php';
            if (file_exists($thanks_page)) {
                include_once $thanks_page;
            } else {
                echo '<div class="error"><p>' . esc_html__('Error: Settings file not found.', 'oneclick-whatsapp-order') . '</p></div>';
            }
        ?>
        <?php } elseif ($active_tab == 'gdpr_notice') {
            // Render content for the active tab
            $gdpr_notice = OCTO_DIR . 'admin/tabs/gdpr-notice.php';
            if (file_exists($gdpr_notice)) {
                include_once $gdpr_notice;
            } else {
                echo '<div class="error"><p>' . esc_html__('Error: Settings file not found.', 'oneclick-whatsapp-order') . '</p></div>';
            }
        ?>

        <?php
        } elseif ($active_tab == 'addons') {
            // Render content for the active tab
            $addons = OCTO_DIR . 'admin/tabs/add-ons.php';
            if (file_exists($addons)) {
                include_once $addons;
            } else {
                echo '<div class="error"><p>' . esc_html__('Error: Settings file not found.', 'oneclick-whatsapp-order') . '</p></div>';
            }
        ?>
        <?php } elseif ($active_tab == 'tutorial_support') {
            // Render content for the active tab
            $tutorial_support = OCTO_DIR . 'admin/tabs/tutorial-support.php';
            if (file_exists($tutorial_support)) {
                include_once $tutorial_support;
            } else {
                echo '<div class="error"><p>' . esc_html__('Error: Settings file not found.', 'oneclick-whatsapp-order') . '</p></div>';
            }
        ?>
        <?php } elseif ($active_tab == 'welcome') {
            // Render content for the active tab
            $welcome = OCTO_DIR . 'admin/tabs/welcome.php';
            if (file_exists($welcome)) {
                include_once $welcome;
            } else {
                echo '<div class="error"><p>' . esc_html__('Error: Settings file not found.', 'oneclick-whatsapp-order') . '</p></div>';
            }
        ?>
<?php
        }
?>
    </div>
<?php
    }

    // Donate box for the plugin's own admin tabs.
    // Since 1.2.0 this is no longer registered as a [donate] shortcode, which
    // printed the author's PayPal button on any store page using [donate].
    function wa_order_donate_button_shortcode()
    {
        ob_start();
?>
<center>
    <div class="donate-container">
        <p><?php esc_html_e('To keep this plugin free, I spent cups of coffee building it. If you love it and find it super helpful for your business, you can always', 'oneclick-whatsapp-order'); ?></p>
        <a href="https://www.paypal.me/WalterPinem" target="_blank" rel="noopener noreferrer" class="donatebutton">
            ☕ <?php esc_html_e('Buy Me a Coffee', 'oneclick-whatsapp-order'); ?>
        </a>
    </div>
</center>
<?php
        return ob_get_clean();
    }
