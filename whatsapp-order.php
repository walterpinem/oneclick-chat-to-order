<?php
// Make sure we don't expose any info if called directly
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/* @wordpress-plugin
 * Plugin Name:       OneClick Chat to Order
 * Plugin URI:        https://www.onlinestorekit.com/oneclick-chat-to-order/
 * Description:       Make it easy for your customers to order via WhatsApp chat through a single button click with detailing information about a product including custom message. OneClick Chat to Order button can be displayed on a single product page and as a floating button. GDPR-ready!
 * Version:           1.1.1
 * Author:            Walter Pinem
 * Author URI:        https://walterpinem.com/
 * Developer:         Walter Pinem | Online Store Kit
 * Developer URI:     https://www.seniberpikir.com/
 * Text Domain:       oneclick-wa-order
 * Domain Path:       /languages
 * License:           GPL-3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * Requires Plugins:  woocommerce
 *
 * Requires at least: 6.0
 * Requires PHP:      7.4
 *
 * WC requires at least: 8.2
 * WC tested up to: 10.6.1
 *
 * Copyright: © 2019 - 2026 Walter Pinem.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

// Plugin name
define('OCTO_NAME',         'OneClick Chat to Order');

// Plugin version
define('OCTO_VERSION',      get_file_data(__FILE__, array('Version' => 'Version'), false)['Version']);

// Plugin Root File
define('OCTO_FILE',         __FILE__);

// Plugin base
define('OCTO_BASE',         plugin_basename(OCTO_FILE));

// Plugin Folder Path
define('OCTO_DIR',          plugin_dir_path(OCTO_FILE));

// Plugin Folder URL
define('OCTO_URL',          plugin_dir_url(OCTO_FILE));

// Initiate the plugin loads
add_action('plugins_loaded', 'OCWAORDER_plugin_init', 0);

// Set Global WA Base URL
// @since 1.0.5
$GLOBALS['wa_base'] = 'api';

/**
 * Adds an action to declare compatibility with High Performance Order Storage (HPOS)
 * before WooCommerce initialization.
 *
 * @since 1.0.5
 *
 * @param string   $hook_name  The name of the action to which the callback function is hooked.
 * @param callable $callback   The callback function to be executed when the action is run.
 * @param int      $priority   Optional. The order in which the callback functions are executed. Default is 10.
 * @param int      $args_count Optional. The number of arguments the callback accepts. Default is 1.
 *
 * @return void
 */
add_action(
    'before_woocommerce_init',
    function () {
        // Check if the FeaturesUtil class exists in the \Automattic\WooCommerce\Utilities namespace.
        if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
            // Declare compatibility with custom order tables using the FeaturesUtil class.
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
        }
    }
);

// Plugin Start
function OCWAORDER_plugin_init()
{
    // Start calling main css
    function OCWAORDER_include_plugin_css()
    {
        if (!is_admin()) {
            wp_register_style('wa_order_style', OCTO_URL . 'assets/css/main-style.css', array(), OCTO_VERSION);
            wp_enqueue_style('wa_order_style');
        }
    }
    add_action('wp_enqueue_scripts', 'OCWAORDER_include_plugin_css');

    // Start calling main frontend js
    function OCWAORDER_include_plugin_main_js()
    {
        // Only enqueue if the file exists to prevent 404 errors
        $js_file = OCTO_DIR . 'assets/js/wa-single-button.js';
        if (file_exists($js_file)) {
            wp_register_script('wa_order_main_front_js', OCTO_URL . 'assets/js/wa-single-button.js', array('jquery'), OCTO_VERSION, true);
            // Enqueue on product pages
            if (is_product()) {
                wp_enqueue_script('wa_order_main_front_js');
            }
        }
    }
    add_action('wp_enqueue_scripts', 'OCWAORDER_include_plugin_main_js');

    // Start calling admin css
    function OCWAORDER_include_admin_css()
    {
        wp_enqueue_style('wa_order_style_admin',  OCTO_URL . 'assets/css/admin-style.css', array(), OCTO_VERSION);
        wp_register_style('wa_order_selet2_style',  OCTO_URL . 'assets/css/select2.min.css', array(), '4.1.0');
    }
    add_action('admin_enqueue_scripts', 'OCWAORDER_include_admin_css');

    function OCWAORDER_include_admin_js()
    {
        // Only register admin-main.js, don't enqueue it globally
        wp_register_script('wa_order_js_admin',  OCTO_URL . 'assets/js/admin-main.js', array('jquery'), OCTO_VERSION, true);
        wp_register_script('wa_order_js_select2',  OCTO_URL . 'assets/js/select2.min.js', array('jquery'), '4.1.0', true);
        wp_register_script('wa_order_select2_helper',  OCTO_URL . 'assets/js/select2-helper.js', array('wa_order_js_select2'), OCTO_VERSION, true);
        wp_register_script('wp-color-picker-alpha', plugins_url('assets/js/wp-color-picker-alpha.min.js',  __FILE__), array('wp-color-picker'), '3.0.3', true);
        wp_register_script('wp-color-picker-init', plugins_url('assets/js/wp-color-picker-init.js',  __FILE__), array('wp-color-picker-alpha'), '3.0.0', true);
    }
    add_action('admin_enqueue_scripts', 'OCWAORDER_include_admin_js');

    // Start calling main files
    require_once dirname(__FILE__) . '/admin/wa-admin-page.php';
    require_once dirname(__FILE__) . '/includes/wa-button.php';
    require_once dirname(__FILE__) . '/includes/wa-gdpr.php';
    require_once dirname(__FILE__) . '/includes/wa-metabox.php';
    require_once dirname(__FILE__) . '/includes/multiple-numbers.php';
    
    // Load security enhancements
    require_once dirname(__FILE__) . '/includes/security-enhancements.php';

    // Make sure WooCommerce is active
    function OCWAORDER_check_woocommece_active()
    {
        if (!is_plugin_active('woocommerce/woocommerce.php')) {
            printf(
                '<div class="error"><p><strong>%s</strong> %s <strong>%s</strong> %s</p></div>',
                esc_html__('OneClick Chat to Order', 'oneclick-wa-order'),
                esc_html__('requires', 'oneclick-wa-order'),
                esc_html__('WooCommerce plugin.', 'oneclick-wa-order'),
                esc_html__('Please install and activate it.', 'oneclick-wa-order')
            );
        }
    }
    add_action('admin_notices', 'OCWAORDER_check_woocommece_active');

    // Localize this plugin
    function OCWAORDER_languages_init()
    {
        $plugin_dir = basename(dirname(__FILE__));
        load_plugin_textdomain('oneclick-wa-order', false, $plugin_dir . '/languages');
    }
    add_action('plugins_loaded', 'OCWAORDER_languages_init');
}

// Add setting link plugin page
function OCWAORDER_settings_link($links_array, $plugin_file_name)
{
    if (strpos($plugin_file_name, basename(__FILE__))) {
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            esc_url(admin_url('admin.php?page=wa-order')),
            esc_html__('Settings', 'oneclick-wa-order')
        );
        array_unshift($links_array, $settings_link);
    }
    return $links_array;
}
add_filter('plugin_action_links', 'OCWAORDER_settings_link', 10, 2);

// Add Donate Link
function wa_order_donate_link_plugin($links)
{
    $donate_link = sprintf(
        '<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
        esc_url('https://www.paypal.me/WalterPinem'),
        esc_html__('Buy Me a Coffee ☕', 'oneclick-wa-order')
    );
    $links = array_merge($links, array($donate_link));
    return $links;
}
add_action('plugin_action_links_' . plugin_basename(__FILE__), 'wa_order_donate_link_plugin');

// Disable Auto Draft for WA Number CPT
add_action('admin_enqueue_scripts', 'wa_order_disable_auto_drafts');
function wa_order_disable_auto_drafts()
{
    if ('wa-order-numbers' == get_post_type())
        wp_dequeue_script('autosave');
}

// Selected WhatsApp number that's previously defined
// Since version 1.0.5 - Optimized in version 1.0.8
function wa_order_get_phone_number($post_id)
{
    // Use transient caching for better performance
    $cache_key = 'wa_order_phone_' . $post_id;
    $cached_phone = get_transient($cache_key);

    if (false !== $cached_phone) {
        return $cached_phone;
    }

    $phone_number = '';

    // Check if a number is assigned to the product first (more specific)
    $single_number_check = get_post_meta($post_id, '_wa_order_phone_number_check', true);
    if ($single_number_check === 'yes') {
        // WA Number from Product Metabox
        $wanumber_meta = get_post_meta($post_id, '_wa_order_phone_number', true);
        if (!empty($wanumber_meta)) {
            $args_meta = array(
                'title'       => $wanumber_meta,
                'post_type'   => 'wa-order-numbers',
                'post_status' => 'publish',
                'numberposts' => 1,
                'fields'      => 'ids' // Only get IDs for better performance
            );
            $posts_meta = get_posts($args_meta);
            if (!empty($posts_meta)) {
                $phone_number = get_post_meta($posts_meta[0], 'wa_order_phone_number_input', true);
            }
        }
    }

    // Fallback to global setting if no product-specific number
    if (empty($phone_number)) {
        $wanumberpage = get_option('wa_order_selected_wa_number_single_product');
        if (!empty($wanumberpage)) {
            $args = array(
                'name'        => $wanumberpage,
                'post_type'   => 'wa-order-numbers',
                'post_status' => 'publish',
                'numberposts' => 1,
                'fields'      => 'ids' // Only get IDs for better performance
            );
            $posts = get_posts($args);
            if (!empty($posts)) {
                $phone_number = get_post_meta($posts[0], 'wa_order_phone_number_input', true);
            }
        }
    }

    // Cache the result for 1 hour
    set_transient($cache_key, $phone_number, HOUR_IN_SECONDS);

    return $phone_number;
}

// Clear phone number cache when post meta is updated
// Since version 1.0.8
function wa_order_clear_phone_cache($meta_id, $post_id, $meta_key, $meta_value)
{
    // Clear cache when phone number related meta is updated
    if (in_array($meta_key, ['_wa_order_phone_number_check', '_wa_order_phone_number', 'wa_order_phone_number_input'])) {
        $cache_key = 'wa_order_phone_' . $post_id;
        delete_transient($cache_key);

        // Also clear cache for products that might be using this number
        if ($meta_key === 'wa_order_phone_number_input') {
            // Clear all phone number caches when a WhatsApp number is updated
            global $wpdb;
            $wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", '_transient_wa_order_phone_%'));
        }
    }
}
add_action('updated_post_meta', 'wa_order_clear_phone_cache', 10, 4);
add_action('added_post_meta', 'wa_order_clear_phone_cache', 10, 4);

// Options caching manager for better performance
// Since version 1.0.8
class WA_Order_Options_Manager
{
    private static $instance = null;
    private $cached_options = array();
    // Cache expiry removed as it's not used in current implementation

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        // Load frequently used options into cache
        $this->preload_options();
    }

    /**
     * Preload frequently used options to reduce database queries
     */
    private function preload_options()
    {
        $frequent_options = array(
            'wa_order_option_enable_single_product',
            'wa_order_option_text_button',
            'wa_order_option_message',
            'wa_order_option_target',
            'wa_order_gdpr_status_enable',
            'wa_order_exclude_price',
            'wa_order_exclude_product_url',
            'wa_order_single_product_button_position',
            'wa_order_whatsapp_base_url',
            'wa_order_whatsapp_base_url_desktop'
        );

        foreach ($frequent_options as $option_name) {
            $this->cached_options[$option_name] = get_option($option_name);
        }
    }

    /**
     * Get option with caching
     */
    public function get_option($option_name, $default = false)
    {
        if (isset($this->cached_options[$option_name])) {
            return $this->cached_options[$option_name];
        }

        $value = get_option($option_name, $default);
        $this->cached_options[$option_name] = $value;
        return $value;
    }

    /**
     * Update option and clear cache
     */
    public function update_option($option_name, $value)
    {
        $result = update_option($option_name, $value);
        if ($result) {
            $this->cached_options[$option_name] = $value;
        }
        return $result;
    }

    /**
     * Clear specific option from cache
     */
    public function clear_option_cache($option_name)
    {
        unset($this->cached_options[$option_name]);
    }

    /**
     * Clear all cached options
     */
    public function clear_all_cache()
    {
        $this->cached_options = array();
    }
}

// Helper function to get options manager instance
function wa_order_get_options_manager()
{
    return WA_Order_Options_Manager::get_instance();
}

// Error logging function for debugging
// Since version 1.0.8
function wa_order_log_error($message, $context = array())
{
    if (defined('WP_DEBUG') && WP_DEBUG) {
        $log_message = '[OneClick Chat to Order] ' . $message;
        if (!empty($context)) {
            $log_message .= ' Context: ' . wp_json_encode($context);
        }
        error_log($log_message);
    }
}

// Plugin activation hook - ensure compatibility
function wa_order_activation_check()
{
    // Check WordPress version
    if (version_compare(get_bloginfo('version'), '5.3', '<')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(
            esc_html__('OneClick Chat to Order requires WordPress 5.3 or higher.', 'oneclick-wa-order'),
            esc_html__('Plugin Activation Error', 'oneclick-wa-order'),
            array('back_link' => true)
        );
    }

    // Check PHP version
    if (version_compare(PHP_VERSION, '7.4', '<')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(
            esc_html__('OneClick Chat to Order requires PHP 7.4 or higher.', 'oneclick-wa-order'),
            esc_html__('Plugin Activation Error', 'oneclick-wa-order'),
            array('back_link' => true)
        );
    }

    // Check if WooCommerce is active
    if (!is_plugin_active('woocommerce/woocommerce.php')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(
            esc_html__('OneClick Chat to Order requires WooCommerce to be installed and activated.', 'oneclick-wa-order'),
            esc_html__('Plugin Activation Error', 'oneclick-wa-order'),
            array('back_link' => true)
        );
    }

    // Clear all caches on activation
    wa_order_clear_all_caches();
}
register_activation_hook(__FILE__, 'wa_order_activation_check');

// Clear all plugin caches
function wa_order_clear_all_caches()
{
    global $wpdb;

    // Clear phone number caches
    $wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", '_transient_wa_order_phone_%'));

    // Clear options manager cache
    $options_manager = wa_order_get_options_manager();
    $options_manager->clear_all_cache();

    // wa_order_log_error('All plugin caches cleared'); // Debug only
}

/**
 * Database migration system for safe upgrades across 40k+ installs.
 * Runs once per version bump, tracked via wa_order_db_version.
 *
 * @since 1.1.1
 */
function wa_order_run_migrations()
{
    $current_db_version = get_option('wa_order_db_version', '0');
    $target_db_version  = '1.1.1';

    // Skip if already migrated
    if (version_compare($current_db_version, $target_db_version, '>=')) {
        return;
    }

    // Migration 1.1.1: Repair checkbox options corrupted by missing sanitize_checkbox()
    wa_order_migrate_repair_checkboxes();

    // Mark migration complete
    update_option('wa_order_db_version', $target_db_version);

    // Show one-time admin notice
    set_transient('wa_order_migration_notice', true, 0);

    // wa_order_log_error('Migration completed to DB version ' . $target_db_version); // Debug only
}
add_action('admin_init', 'wa_order_run_migrations');

/**
 * Repair checkbox options that were corrupted by the undefined sanitize_checkbox() callback.
 *
 * Strategy: For each settings group, we check "companion" non-checkbox options
 * (button text, custom messages, phone numbers) that would only have values if
 * the user actively configured that tab. If companions have values, we know
 * the user intended to enable that feature, so we safely restore the checkbox.
 *
 * Rules:
 * - If the checkbox already has value 'yes' → skip (not corrupted)
 * - If companion options are populated → restore to 'yes'
 * - If no companions have values → leave as-is (user never configured this)
 *
 * @since 1.1.1
 */
function wa_order_migrate_repair_checkboxes()
{
    $repairs_made = 0;

    // ── Single Product Button ──
    // If button text or custom message is set, user intended to enable the button
    if (wa_order_has_companion_values(array(
        'wa_order_option_text_button',
        'wa_order_option_message',
        'wa_order_selected_wa_number_single_product'
    ))) {
        $repairs_made += wa_order_repair_checkbox('wa_order_option_enable_single_product');
        $repairs_made += wa_order_repair_checkbox('wa_order_option_target', '_blank');
    }

    // ── Cart Page ──
    if (wa_order_has_companion_values(array(
        'wa_order_option_cart_button_text',
        'wa_order_option_cart_custom_message',
        'wa_order_selected_wa_number_cart'
    ))) {
        $repairs_made += wa_order_repair_checkbox('wa_order_option_add_button_to_cart');
        $repairs_made += wa_order_repair_checkbox('wa_order_option_cart_open_new_tab', '_blank');
    }

    // ── Thank You Page ──
    if (wa_order_has_companion_values(array(
        'wa_order_option_custom_thank_you_button_text',
        'wa_order_option_custom_thank_you_custom_message',
        'wa_order_selected_wa_number_thanks'
    ))) {
        $repairs_made += wa_order_repair_checkbox('wa_order_option_enable_button_thank_you');
        $repairs_made += wa_order_repair_checkbox('wa_order_option_custom_thank_you_open_new_tab', '_blank');
    }

    // ── Shop Page ──
    if (wa_order_has_companion_values(array(
        'wa_order_option_button_text_shop_loop',
        'wa_order_option_custom_message_shop_loop',
        'wa_order_selected_wa_number_shop'
    ))) {
        $repairs_made += wa_order_repair_checkbox('wa_order_option_enable_button_shop_loop');
        $repairs_made += wa_order_repair_checkbox('wa_order_option_shop_loop_open_new_tab', '_blank');
    }

    // ── Floating Button ──
    if (wa_order_has_companion_values(array(
        'wa_order_floating_message',
        'wa_order_selected_wa_number_floating',
        'wa_order_floating_tooltip'
    ))) {
        $repairs_made += wa_order_repair_checkbox('wa_order_floating_button');
        $repairs_made += wa_order_repair_checkbox('wa_order_floating_target', '_blank');
        $repairs_made += wa_order_repair_checkbox('wa_order_floating_tooltip_enable');
    }

    // ── GDPR ──
    if (wa_order_has_companion_values(array(
        'wa_order_gdpr_message',
        'wa_order_gdpr_privacy_page'
    ))) {
        $repairs_made += wa_order_repair_checkbox('wa_order_gdpr_status_enable');
    }

    // ── Shortcode ──
    if (wa_order_has_companion_values(array(
        'wa_order_shortcode_message',
        'wa_order_shortcode_text_button',
        'wa_order_selected_wa_number_shortcode'
    ))) {
        $repairs_made += wa_order_repair_checkbox('wa_order_shortcode_target', '_blank');
    }

    // ── Display Options: These are "hide" checkboxes — only repair if the
    //    corresponding feature is enabled (proven above). We DON'T blindly
    //    restore "hide" toggles because a false value means "show" which is
    //    the safe/expected default. ──

    // wa_order_log_error('Checkbox migration complete. Repairs made: ' . $repairs_made); // Debug only
}

/**
 * Check if any of the given companion option names have a non-empty value.
 *
 * @param array $option_names List of option names to check.
 * @return bool True if at least one companion has a value.
 */
function wa_order_has_companion_values($option_names)
{
    foreach ($option_names as $name) {
        $value = get_option($name);
        if (!empty($value)) {
            return true;
        }
    }
    return false;
}

/**
 * Repair a single checkbox option if it appears corrupted.
 *
 * @param string $option_name  The option to repair.
 * @param string $expected_val The value the checkbox should have when checked. Default 'yes'.
 * @return int 1 if repaired, 0 if skipped.
 */
function wa_order_repair_checkbox($option_name, $expected_val = 'yes')
{
    $current = get_option($option_name);

    // Already has the correct value — not corrupted
    if ($current === $expected_val) {
        return 0;
    }

    // Value exists in DB but is corrupted (false, empty string, etc.)
    // Restore it to the expected value
    update_option($option_name, $expected_val);
    // wa_order_log_error('Repaired checkbox option: ' . $option_name . ' → ' . $expected_val); // Debug only
    return 1;
}

/**
 * Show a one-time admin notice after migration completes.
 *
 * @since 1.1.1
 */
function wa_order_migration_admin_notice()
{
    if (!get_transient('wa_order_migration_notice')) {
        return;
    }

    // Only show to administrators
    if (!current_user_can('manage_options')) {
        return;
    }

    $settings_url = admin_url('admin.php?page=wa-order&tab=welcome');
    printf(
        '<div class="notice notice-info is-dismissible"><p><strong>%s</strong> %s <a href="%s">%s</a></p></div>',
        esc_html__('OneClick Chat to Order has been updated!', 'oneclick-wa-order'),
        esc_html__('Your settings have been automatically preserved. We recommend verifying your configuration on each tab.', 'oneclick-wa-order'),
        esc_url($settings_url),
        esc_html__('Review Settings →', 'oneclick-wa-order')
    );

    // Delete so it only shows once
    delete_transient('wa_order_migration_notice');
}
add_action('admin_notices', 'wa_order_migration_admin_notice');

// A function to dynamically generate WhatsApp URL
// Optimized in version 1.0.8 for better performance
function wa_order_the_url($phone_number, $message)
{
    // Get user settings for WhatsApp base URLs using cached options
    $options_manager = wa_order_get_options_manager();
    $force_wa_me = $options_manager->get_option('wa_order_force_use_wa_me', 'no');

    // If force wa.me is enabled, use wa.me for all links
    if ($force_wa_me === 'yes') {
        $base_url = 'https://wa.me/';
    } else {
        // Use the existing logic for mobile/desktop detection
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])) : '';
        $mobile_base_url = $options_manager->get_option('wa_order_whatsapp_base_url', 'api'); // Default to api.whatsapp.com
        $desktop_base_url = $options_manager->get_option('wa_order_whatsapp_base_url_desktop', 'web'); // Default to web.whatsapp.com

        // Check if it's a mobile device
        if (wp_is_mobile() || preg_match('/iPhone|Android|iPod|iPad|webOS|BlackBerry|Windows Phone|Opera Mini|IEMobile|Mobile/', $user_agent)) {
            // Mobile device detected
            if ($mobile_base_url === 'protocol') {
                $base_url = 'whatsapp://send?'; // Use whatsapp:// protocol
            } else {
                $base_url = 'https://api.whatsapp.com/send?'; // Use api.whatsapp.com
            }
        } else {
            // Desktop or web browser detected
            if ($desktop_base_url === 'api') {
                $base_url = 'https://api.whatsapp.com/send?'; // Use api.whatsapp.com
            } elseif ($desktop_base_url === 'protocol') {
                $base_url = 'whatsapp://send?'; // Use whatsapp://send
            } else {
                $base_url = 'https://web.whatsapp.com/send?'; // Use web.whatsapp.com
            }
        }
    }

    // Encode the phone number and message
    $encoded_phone = urlencode($phone_number);
    $encoded_message = rawurlencode($message);

    // Build the full WhatsApp URL
    if ($force_wa_me === 'yes') {
        // wa.me format: https://wa.me/phone?text=message
        $button_url = $base_url . $encoded_phone . '?text=' . $encoded_message;
    } else {
        // Standard format: base_url + phone=...&text=...&app_absent=0
        $button_url = $base_url . 'phone=' . $encoded_phone . '&text=' . $encoded_message . '&app_absent=0';
    }

    return $button_url;
}
// Add the whatsapp protocol to the allowed protocols
function wa_order_allow_whatsapp_protocol($protocols)
{
    $protocols[] = 'whatsapp';
    return $protocols;
}
add_filter('kses_allowed_protocols', 'wa_order_allow_whatsapp_protocol');

// Customer Shipping Details Function to Simplify the Logic
function wa_order_get_shipping_address($customer)
{
    // Get full state name if available
    $country_code = $customer->get_shipping_country();
    $state_code = $customer->get_shipping_state();
    $states = WC()->countries->get_states($country_code);
    $state_name = isset($states[$state_code]) ? $states[$state_code] : '';

    // Get full country name if available
    $countries = WC()->countries->get_countries();
    $country_name = isset($countries[$country_code]) ? $countries[$country_code] : '';

    // Build the full address, filtering out empty values
    $address_parts = array_filter(array(
        trim($customer->get_shipping_first_name() . ' ' . $customer->get_shipping_last_name()), // Combine first and last name
        $customer->get_shipping_address(),
        $customer->get_shipping_address_2(),
        $customer->get_shipping_city(),
        $state_name,  // Add state only if it's valid
        $country_name,  // Add country only if it's valid
        $customer->get_shipping_postcode()
    ));

    return implode("\r\n", $address_parts);
}

function wa_order_enqueue_scripts()
{
    if (is_product()) {
        wp_enqueue_script('wa-order-single-product', plugin_dir_url(__FILE__) . 'assets/js/single-product.js', array('jquery'), OCTO_VERSION, true);
    }
}
add_action('wp_enqueue_scripts', 'wa_order_enqueue_scripts');

// Function to check whether it's Indonesian store
if (!function_exists('oskit_is_indonesian_store')) {
    function oskit_is_indonesian_store()
    {
        // Check if WooCommerce is active first
        if (class_exists('WooCommerce')) {
            // Get the base location array
            $base_location = wc_get_base_location();

            // Check if $base_location is an array and has the 'country' key
            if (is_array($base_location) && isset($base_location['country'])) {
                $shop_base_country = $base_location['country'];

                // Compare with the correct country code for Indonesia
                if ($shop_base_country === 'ID') { // Use 'ID' for Indonesia
                    return true; // Return true if the condition is met
                }
            }
        }
        return false; // Return false otherwise
    }
}

/**
 * OSKIT Dynamic URL parameters.
 *
 * @param string $base_url The URL to track.
 * @return string          Fully escaped URL.
 */
if (! function_exists('oskit_url')) {
    function oskit_url($base_url)
    {
        $base_url = esc_url_raw($base_url);
        $php_version = phpversion();
        $wp_version  = get_bloginfo('version');

        $plugin_name = '';
        if (defined('OCTO_NAME')) {
            $plugin_name = sanitize_title(OCTO_NAME);
        }

        $plugin_version = '';
        if (defined('OCTO_VERSION')) {
            $plugin_version = OCTO_VERSION;
        }

        if (function_exists('get_user_locale')) {
            $user_language = get_user_locale();
        } elseif (function_exists('determine_locale')) {
            $user_language = determine_locale();
        } else {
            $user_language = get_locale();
        }

        $screen = 'frontend';
        if (is_admin()) {
            $page = isset($_GET['page'])
                ? sanitize_key(wp_unslash($_GET['page']))
                : '';
            $tab  = isset($_GET['tab'])
                ? sanitize_key(wp_unslash($_GET['tab']))
                : '';
            if ($page) {
                $screen = $page . ($tab ? '-' . $tab : '');
            }
        }

        $params = array(
            'plugin_name'    => $plugin_name,
            'plugin_version' => $plugin_version,
            'php_version'    => $php_version,
            'wp_version'     => $wp_version,
            'user_language'  => $user_language,
            'screen'         => $screen,
        );

        /**
         * Allow other code to tweak the URL params.
         *
         * @param array  $params   Params before URL build.
         * @param string $base_url Original URL.
         */
        $params = apply_filters('oskit_url_params', $params, $base_url);
        $tracked = add_query_arg($params, $base_url);
        return esc_url($tracked);
    }
}

/**
 * Modify admin footer text for specific plugin pages
 *
 * @param string $footer_text Original footer text
 * @return string Modified footer text
 */
function OCWAORDER_admin_footer_text($footer_text)
{
    $screen = get_current_screen();

    // Define the pages to modify the footer
    $target_pages = array(
        'edit-wa-order-numbers'
    );
    $allowed_pages = [
        'wa-order'
    ];
    // Check if we're on one of our target pages
    if (isset($_GET['page']) && in_array($_GET['page'], $allowed_pages) || in_array($screen->id, $target_pages)) {
        $footer_text = sprintf(
            /* translators: 1: plugin name, 2: 5-star review link */
            esc_html__('Enjoyed %1$s? Please leave a %2$s rating. I really appreciate your support!', 'oneclick-wa-order'),
            '<strong>' . esc_html(OCTO_NAME) . '</strong>',
            '<a href="' . esc_url('https://wordpress.org/support/plugin/oneclick-whatsapp-order/reviews/?rate=5#new-post') . '" target="_blank" rel="noopener"><span class="screen-reader-text">' . esc_html__('5 stars', 'oneclick-wa-order') . '</span>★★★★★</a>'
        );

        // Wrap in the desired HTML structure
        $footer_text = '<span class="oskit-footer-thankyou">' . $footer_text . '</span>';
    }

    return $footer_text;
}
add_filter('admin_footer_text', 'OCWAORDER_admin_footer_text');

// Plugin deactivation hook - cleanup
function wa_order_deactivation_cleanup()
{
    // Clear all plugin caches
    wa_order_clear_all_caches();

    // Log deactivation
    // wa_order_log_error('Plugin deactivated and caches cleared'); // Debug only
}
register_deactivation_hook(__FILE__, 'wa_order_deactivation_cleanup');