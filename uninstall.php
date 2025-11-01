<?php

/**
 * OneClick Chat to Order Uninstall
 *
 * @package     OneClick Chat to Order
 * @author      Walter Pinem <hello@walterpinem.me>
 * @link        https://walterpinem.me/
 * @link        https://www.onlinestorekit.com/oneclick-chat-to-order/
 * @copyright   Copyright (c) 2019 - 2025, Walter Pinem | Online Store Kit
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * @category    Uninstall
 *
 ********************************* Uninstall ********************************* */

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Check if user wants to delete plugin data
$delete_data = get_option('wa_order_delete_data_on_uninstall', 'no');

if ($delete_data === 'yes') {
    wa_order_delete_all_plugin_data();
}

/**
 * Delete all plugin data including options, posts, meta, and transients
 */
function wa_order_delete_all_plugin_data()
{
    global $wpdb;

    // Delete all plugin options
    wa_order_delete_plugin_options();

    // Delete custom post type and related data
    wa_order_delete_custom_post_type_data();

    // Delete product meta data
    wa_order_delete_product_meta_data();

    // Delete transients and cache
    wa_order_delete_transients_and_cache();

    // Log the cleanup (if WP_DEBUG is enabled)
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('[OneClick Chat to Order] All plugin data has been deleted during uninstall.');
    }
}

/**
 * Delete all plugin options
 */
function wa_order_delete_plugin_options()
{
    // Basic Configuration Options
    delete_option('wa_order_selected_wa_number_single_product');
    delete_option('wa_order_option_dismiss_notice_confirmation');
    delete_option('wa_order_whatsapp_base_url');
    delete_option('wa_order_whatsapp_base_url_desktop');
    delete_option('wa_order_force_use_wa_me');
    delete_option('wa_order_single_product_link_type');
    delete_option('wa_order_single_product_button_position');
    delete_option('wa_order_option_enable_single_product');
    delete_option('wa_order_option_message');
    delete_option('wa_order_option_text_button');
    delete_option('wa_order_option_target');
    delete_option('wa_order_exclude_price');
    delete_option('wa_order_exclude_product_url');
    delete_option('wa_order_option_quantity_label');
    delete_option('wa_order_option_price_label');
    delete_option('wa_order_option_url_label');
    delete_option('wa_order_option_total_amount_label');
    delete_option('wa_order_option_total_discount_label');
    delete_option('wa_order_option_payment_method_label');
    delete_option('wa_order_option_thank_you_label');
    delete_option('wa_order_option_tax_label');
    delete_option('wa_order_single_force_fullwidth');
    delete_option('wa_order_option_single_show_regular_sale_prices');

    // Display Options
    delete_option('wa_order_bg_color');
    delete_option('wa_order_bg_hover_color');
    delete_option('wa_order_txt_color');
    delete_option('wa_order_txt_hover_color');
    delete_option('wa_order_btn_box_shdw');
    delete_option('wa_order_bshdw_horizontal');
    delete_option('wa_order_bshdw_vertical');
    delete_option('wa_order_bshdw_blur');
    delete_option('wa_order_bshdw_spread');
    delete_option('wa_order_bshdw_position');
    delete_option('wa_order_btn_box_shdw_hover');
    delete_option('wa_order_bshdw_horizontal_hover');
    delete_option('wa_order_bshdw_vertical_hover');
    delete_option('wa_order_bshdw_blur_hover');
    delete_option('wa_order_bshdw_spread_hover');
    delete_option('wa_order_bshdw_position_hover');
    delete_option('wa_order_option_remove_btn');
    delete_option('wa_order_option_remove_btn_mobile');
    delete_option('wa_order_option_remove_price');
    delete_option('wa_order_option_remove_cart_btn');
    delete_option('wa_order_option_remove_quantity');
    delete_option('wa_order_option_exlude_single_product_cats');
    delete_option('wa_order_option_exlude_single_product_tags');
    delete_option('wa_order_single_button_margin_top');
    delete_option('wa_order_single_button_margin_right');
    delete_option('wa_order_single_button_margin_bottom');
    delete_option('wa_order_single_button_margin_left');
    delete_option('wa_order_single_button_padding_top');
    delete_option('wa_order_single_button_padding_right');
    delete_option('wa_order_single_button_padding_bottom');
    delete_option('wa_order_single_button_padding_left');
    delete_option('wa_order_display_option_shop_loop_hide_desktop');
    delete_option('wa_order_display_option_shop_loop_hide_mobile');
    delete_option('wa_order_option_exlude_shop_product_cats');
    delete_option('wa_order_exlude_shop_product_cats_archive');
    delete_option('wa_order_option_exlude_shop_product_tags');
    delete_option('wa_order_exlude_shop_product_tags_archive');
    delete_option('wa_order_display_option_cart_hide_desktop');
    delete_option('wa_order_display_option_cart_hide_mobile');
    delete_option('wa_order_display_option_checkout_hide_desktop');
    delete_option('wa_order_display_option_checkout_hide_mobile');
    delete_option('wa_order_option_convert_phone_order_details');
    delete_option('wa_order_option_custom_message_backend_order_details');

    // GDPR Options
    delete_option('wa_order_gdpr_status_enable');
    delete_option('wa_order_gdpr_message');
    delete_option('wa_order_gdpr_privacy_page');

    // Floating Button Options
    delete_option('wa_order_selected_wa_number_floating');
    delete_option('wa_order_floating_button');
    delete_option('wa_order_floating_button_position');
    delete_option('wa_order_floating_message');
    delete_option('wa_order_floating_target');
    delete_option('wa_order_floating_tooltip_enable');
    delete_option('wa_order_floating_tooltip');
    delete_option('wa_order_floating_hide_mobile');
    delete_option('wa_order_floating_hide_desktop');
    delete_option('wa_order_floating_source_url');
    delete_option('wa_order_floating_source_url_label');
    delete_option('wa_order_floating_hide_all_single_posts');
    delete_option('wa_order_floating_hide_all_single_pages');
    delete_option('wa_order_floating_hide_specific_posts');
    delete_option('wa_order_floating_hide_specific_pages');
    delete_option('wa_order_floating_hide_product_cats');
    delete_option('wa_order_floating_hide_product_tags');
    delete_option('wa_order_floating_button_margin_top');
    delete_option('wa_order_floating_button_margin_right');
    delete_option('wa_order_floating_button_margin_bottom');
    delete_option('wa_order_floating_button_margin_left');
    delete_option('wa_order_floating_button_padding_top');
    delete_option('wa_order_floating_button_padding_right');
    delete_option('wa_order_floating_button_padding_bottom');
    delete_option('wa_order_floating_button_padding_left');
    delete_option('wa_order_floating_button_icon_margin_top');
    delete_option('wa_order_floating_button_icon_margin_right');
    delete_option('wa_order_floating_button_icon_margin_bottom');
    delete_option('wa_order_floating_button_icon_margin_left');
    delete_option('wa_order_floating_button_icon_padding_top');
    delete_option('wa_order_floating_button_icon_padding_right');
    delete_option('wa_order_floating_button_icon_padding_bottom');
    delete_option('wa_order_floating_button_icon_padding_left');

    // Shortcode Options
    delete_option('wa_order_selected_wa_number_shortcode');
    delete_option('wa_order_shortcode_message');
    delete_option('wa_order_shortcode_text_button');
    delete_option('wa_order_shortcode_target');

    // Cart Options
    delete_option('wa_order_selected_wa_number_cart');
    delete_option('wa_order_option_add_button_to_cart');
    delete_option('wa_order_option_cart_custom_message');
    delete_option('wa_order_option_cart_button_text');
    delete_option('wa_order_option_cart_hide_checkout');
    delete_option('wa_order_option_cart_hide_product_url');
    delete_option('wa_order_option_cart_open_new_tab');
    delete_option('wa_order_option_cart_enable_variations');
    delete_option('wa_order_option_cart_include_tax');

    // Thank You Page Options
    delete_option('wa_order_selected_wa_number_thanks');
    delete_option('wa_order_option_thank_you_redirect_checkout');
    delete_option('wa_order_option_enable_button_thank_you');
    delete_option('wa_order_option_custom_thank_you_title');
    delete_option('wa_order_option_custom_thank_you_subtitle');
    delete_option('wa_order_option_custom_thank_you_button_text');
    delete_option('wa_order_option_custom_thank_you_custom_message');
    delete_option('wa_order_option_custom_thank_you_include_order_date');
    delete_option('wa_order_option_custom_thank_you_order_number');
    delete_option('wa_order_option_custom_thank_you_order_number_label');
    delete_option('wa_order_option_thank_you_order_summary_link');
    delete_option('wa_order_option_thank_you_order_summary_label');
    delete_option('wa_order_option_thank_you_payment_link');
    delete_option('wa_order_option_thank_you_payment_link_label');
    delete_option('wa_order_option_thank_you_view_order_link');
    delete_option('wa_order_option_thank_you_view_order_label');
    delete_option('wa_order_option_custom_thank_you_open_new_tab');
    delete_option('wa_order_option_custom_thank_you_customer_details_label');
    delete_option('wa_order_option_custom_thank_you_total_products_label');
    delete_option('wa_order_option_custom_thank_you_include_sku');
    delete_option('wa_order_option_custom_thank_you_include_tax');
    delete_option('wa_order_option_custom_thank_you_inclue_coupon');
    delete_option('wa_order_option_custom_thank_you_coupon_label');
    delete_option('wa_order_option_custom_thank_you_include_shipping');
    delete_option('wa_order_option_custom_thank_you_shipping_label');

    // Shop Page Options
    delete_option('wa_order_selected_wa_number_shop');
    delete_option('wa_order_option_enable_button_shop_loop');
    delete_option('wa_order_option_hide_atc_shop_loop');
    delete_option('wa_order_option_button_text_shop_loop');
    delete_option('wa_order_option_custom_message_shop_loop');
    delete_option('wa_order_option_shop_loop_hide_product_url');
    delete_option('wa_order_option_shop_loop_exclude_price');
    delete_option('wa_order_option_shop_loop_open_new_tab');
    delete_option('wa_order_option_shop_loop_show_regular_sale_prices');

    // Delete the uninstall option itself
    delete_option('wa_order_delete_data_on_uninstall');

    // Delete any legacy options that might exist
    delete_option('wa_order_option_phone_number'); // Old phone number option
    delete_option('wa_order_selected_wa_number'); // Legacy option
}

/**
 * Delete custom post type data and related meta
 */
function wa_order_delete_custom_post_type_data()
{
    global $wpdb;

    // Get all WhatsApp number posts
    $posts = get_posts(array(
        'post_type' => 'wa-order-numbers',
        'post_status' => 'any',
        'numberposts' => -1,
        'fields' => 'ids'
    ));

    // Delete each post and its meta
    foreach ($posts as $post_id) {
        // Delete all post meta
        $wpdb->delete($wpdb->postmeta, array('post_id' => $post_id));

        // Delete the post
        wp_delete_post($post_id, true);
    }

    // Clean up any orphaned meta for this post type
    $wpdb->query($wpdb->prepare("
        DELETE pm FROM {$wpdb->postmeta} pm
        LEFT JOIN {$wpdb->posts} p ON pm.post_id = p.ID
        WHERE p.ID IS NULL AND pm.meta_key LIKE %s
    ", 'wa_order_%'));
}

/**
 * Delete product meta data added by the plugin
 */
function wa_order_delete_product_meta_data()
{
    global $wpdb;

    // Delete all product meta keys added by the plugin
    $meta_keys = array(
        '_wa_order_phone_number_check',
        '_wa_order_phone_number',
        '_wa_order_button_text',
        '_wa_order_custom_message',
        '_hide_wa_button',
        '_hide_atc_button',
        '_force_show_atc_button'
    );

    foreach ($meta_keys as $meta_key) {
        $wpdb->delete($wpdb->postmeta, array('meta_key' => $meta_key));
    }
}

/**
 * Delete transients and cache data
 */
function wa_order_delete_transients_and_cache()
{
    global $wpdb;

    // Delete phone number cache transients
    $wpdb->query($wpdb->prepare("
        DELETE FROM {$wpdb->options}
        WHERE option_name LIKE %s
    ", '_transient_wa_order_phone_%'));

    // Delete timeout transients
    $wpdb->query($wpdb->prepare("
        DELETE FROM {$wpdb->options}
        WHERE option_name LIKE %s
    ", '_transient_timeout_wa_order_phone_%'));

    // Delete shop taxonomy cache transients
    delete_transient('wa_order_cat_ids');
    delete_transient('wa_order_tag_ids');

    // Delete admin notice transients
    delete_transient('wa_order_number_empty_notice');

    // Delete any other plugin-specific transients
    $wpdb->query($wpdb->prepare("
        DELETE FROM {$wpdb->options}
        WHERE option_name LIKE %s OR option_name LIKE %s
    ", '_transient_wa_order_%', '_transient_timeout_wa_order_%'));
}
