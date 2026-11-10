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
// Since 1.3.0 the screen follows the Online Store Kit house layout: a brand
// band, icon tabs and card stacks. Every option name, settings group and
// `?tab=` key is unchanged, so saved settings and bookmarks keep working.

/**
 * The settings tabs: key => array( dashicon, label, file under admin/tabs/ ).
 *
 * "numbers" is a link to the WhatsApp Numbers list, not a tab of its own.
 *
 * @since 1.3.0
 * @return array
 */
function wa_order_admin_tabs()
{
    return [
        'welcome'            => ['admin-home', __('Welcome', 'oneclick-whatsapp-order'), 'welcome.php'],
        'numbers'            => ['phone', __('Numbers', 'oneclick-whatsapp-order'), ''],
        'button_config'      => ['admin-generic', __('Basic', 'oneclick-whatsapp-order'), 'button-config.php'],
        'shop_page'          => ['store', __('Shop', 'oneclick-whatsapp-order'), 'shop-page.php'],
        'cart_button'        => ['cart', __('Cart', 'oneclick-whatsapp-order'), 'cart-button.php'],
        'thanks_page'        => ['money-alt', __('Checkout', 'oneclick-whatsapp-order'), 'thanks-page.php'],
        'floating_button'    => ['format-chat', __('Floating', 'oneclick-whatsapp-order'), 'floating-button.php'],
        'display_option'     => ['admin-appearance', __('Display', 'oneclick-whatsapp-order'), 'display-option.php'],
        'gdpr_notice'        => ['privacy', __('GDPR', 'oneclick-whatsapp-order'), 'gdpr-notice.php'],
        'generate_shortcode' => ['shortcode', __('Shortcode', 'oneclick-whatsapp-order'), 'generate-shortcode.php'],
        'addons'             => ['admin-plugins', __('Add-ons', 'oneclick-whatsapp-order'), 'add-ons.php'],
        'tutorial_support'   => ['sos', __('Support', 'oneclick-whatsapp-order'), 'tutorial-support.php'],
    ];
}

/**
 * Open a settings card: surface, header with dashicon and title, an optional
 * description, and the content well. Close it with wa_order_card_close().
 *
 * @since 1.3.0
 * @param string $icon  Dashicon slug without the "dashicons-" prefix.
 * @param string $title Card title, already translated.
 * @param string $desc  Optional description, already translated. Limited HTML.
 * @param string $class Extra classes for the card.
 */
function wa_order_card_open($icon, $title, $desc = '', $class = '')
{
?>
    <div class="octo-settings-card<?php echo '' !== $class ? ' ' . esc_attr($class) : ''; ?>">
        <div class="octo-card-header">
            <span class="dashicons dashicons-<?php echo esc_attr($icon); ?>" aria-hidden="true"></span>
            <h2><?php echo esc_html($title); ?></h2>
        </div>
        <div class="octo-card-body">
            <?php if ('' !== $desc) : ?>
                <p class="octo-card-description"><?php echo wp_kses($desc, wa_order_card_kses()); ?></p>
            <?php endif; ?>
<?php
}

/**
 * Close a card opened with wa_order_card_open().
 *
 * @since 1.3.0
 */
function wa_order_card_close()
{
    echo '</div></div>';
}

/**
 * Tags allowed in card descriptions.
 *
 * @since 1.3.0
 * @return array
 */
function wa_order_card_kses()
{
    return [
        'a'      => ['href' => [], 'target' => [], 'rel' => []],
        'b'      => [],
        'br'     => [],
        'code'   => [],
        'strong' => [],
    ];
}

/**
 * The save bar under a settings form.
 *
 * @since 1.3.0
 */
function wa_order_save_bar()
{
?>
    <div class="octo-settings-footer">
        <button type="submit" class="octo-button-primary"><?php esc_html_e('Save Changes', 'oneclick-whatsapp-order'); ?></button>
    </div>
<?php
}

/**
 * Render the settings screen.
 */
function wa_order_create_admin_page()
{
    $tabs = wa_order_admin_tabs();
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only tab navigation.
    $active_tab = isset($_GET['tab']) ? sanitize_key(wp_unslash($_GET['tab'])) : 'welcome';
    if (!isset($tabs[$active_tab]) || '' === $tabs[$active_tab][2]) {
        $active_tab = 'welcome';
    }
?>
    <div class="wrap octo-settings-wrap">

        <?php // Core moves admin notices to this marker, above the header. ?>
        <hr class="wp-header-end" style="margin:0;border:0;">

        <div class="octo-settings-header">
            <div class="octo-settings-header-content">
                <div class="octo-settings-header-left">
                    <?php // Swap the dashicon for the brand mark once OneClick's new mark is drawn. ?>
                    <div class="octo-settings-icon"><span class="dashicons dashicons-whatsapp" aria-hidden="true"></span></div>
                    <div>
                        <h1><?php esc_html_e('OneClick Chat to Order', 'oneclick-whatsapp-order'); ?></h1>
                        <p class="octo-settings-subtitle"><?php esc_html_e('Let customers order and ask through WhatsApp with one click.', 'oneclick-whatsapp-order'); ?></p>
                    </div>
                </div>
                <span class="octo-version-badge">v<?php echo esc_html(OCTO_VERSION); ?></span>
            </div>
        </div>

        <nav class="octo-tabs" aria-label="<?php esc_attr_e('OneClick Chat to Order settings', 'oneclick-whatsapp-order'); ?>">
            <?php foreach ($tabs as $tab_key => $tab) : ?>
                <?php
                $tab_url   = 'numbers' === $tab_key
                    ? admin_url('edit.php?post_type=wa-order-numbers')
                    : add_query_arg(['page' => 'wa-order', 'tab' => $tab_key], admin_url('admin.php'));
                $is_active = ($tab_key === $active_tab);
                ?>
                <a href="<?php echo esc_url($tab_url); ?>" class="octo-tabs__tab<?php echo $is_active ? ' octo-tabs__tab--active' : ''; ?>" <?php echo $is_active ? 'aria-current="page"' : ''; ?>>
                    <span class="dashicons dashicons-<?php echo esc_attr($tab[0]); ?>" aria-hidden="true"></span><?php echo esc_html($tab[1]); ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="octo-settings-content">
            <?php
            $tab_file = OCTO_DIR . 'admin/tabs/' . $tabs[$active_tab][2];
            if (file_exists($tab_file)) {
                include_once $tab_file;
            } else {
                echo '<div class="octo-notice-error"><span class="dashicons dashicons-dismiss" aria-hidden="true"></span><p>' . esc_html__('Error: Settings file not found.', 'oneclick-whatsapp-order') . '</p></div>';
            }
            ?>
        </div>
    </div>
<?php
}

/**
 * Donate box for the plugin's own admin tabs.
 *
 * Since 1.2.0 this is no longer registered as a [donate] shortcode, which
 * printed the author's PayPal button on any store page using [donate].
 *
 * @return string
 */
function wa_order_donate_button_shortcode()
{
    ob_start();
?>
    <div class="donate-container">
        <p><?php esc_html_e('To keep this plugin free, I spent cups of coffee building it. If you love it and find it super helpful for your business, you can always', 'oneclick-whatsapp-order'); ?></p>
        <a href="https://www.paypal.me/WalterPinem" target="_blank" rel="noopener noreferrer" class="octo-button-secondary">
            ☕ <?php esc_html_e('Buy Me a Coffee', 'oneclick-whatsapp-order'); ?>
        </a>
    </div>
<?php
    return ob_get_clean();
}

/**
 * The credit line under the Welcome and Support tabs.
 *
 * @since 1.3.0
 */
function wa_order_credit_line()
{
    printf(
        '<p class="octo-credit">%s</p>',
        wp_kses_post(
            sprintf(
                /* translators: %s: link to the author's website */
                __('Made with ❤ and ☕ in Central Jakarta, Indonesia by %s', 'oneclick-whatsapp-order'),
                '<a href="' . esc_url(oskit_url('https://walterpinem.com/')) . '" target="_blank" rel="noopener"><strong>Walter Pinem</strong></a>'
            )
        )
    );
}
