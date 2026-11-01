<?php
// Make sure we don't expose any info if called directly
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/* @wordpress-plugin
 * Plugin Name:       OneClick Chat to Order
 * Plugin URI:        https://www.onlinestorekit.com/oneclick-chat-to-order/
 * Description:       Make it easy for your customers to order via WhatsApp chat through a single button click with detailing information about a product including custom message. OneClick Chat to Order button can be displayed on a single product page and as a floating button. GDPR-ready!
 * Version:           1.2.0
 * Author:            Walter Pinem
 * Author URI:        https://walterpinem.com/
 * Developer:         Walter Pinem | Online Store Kit
 * Developer URI:     https://www.seniberpikir.com/
 * Text Domain:       oneclick-whatsapp-order
 * Domain Path:       /languages
 * License:           GPL-3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * Requires Plugins:  woocommerce
 *
 * Requires at least: 6.0
 * Requires PHP:      7.4
 *
 * WC requires at least: 8.2
 * WC tested up to: 11.2.1
 *
 * Copyright: © 2019 - 2026 Walter Pinem.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

// Plugin name
define('OCTO_NAME',         'OneClick Chat to Order');

// Plugin version (kept in sync with the header above)
define('OCTO_VERSION',      '1.2.0');

// Settings schema version, used by the migration routine.
// Only bump this when a migration step is added, never just because the plugin version changes.
define('WA_ORDER_DB_VERSION', '1.2.0');

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
 * Declare compatibility with WooCommerce features before WooCommerce initializes:
 * High-Performance Order Storage and the Cart/Checkout blocks.
 *
 * @since 1.0.5
 */
add_action(
    'before_woocommerce_init',
    function () {
        if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('cart_checkout_blocks', __FILE__, true);
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
        wp_register_script('wa_order_main_front_js', OCTO_URL . 'assets/js/wa-single-button.js', array('jquery'), OCTO_VERSION, true);
        // Enqueue on product pages
        if (function_exists('is_product') && is_product()) {
            wp_enqueue_script('wa_order_main_front_js');
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
        wp_localize_script('wa_order_select2_helper', 'waOrderSelect2', array(
            'term' => __('Search and pick a term...', 'oneclick-whatsapp-order'),
            'post' => __('Search and pick a post...', 'oneclick-whatsapp-order'),
            'page' => __('Search and pick a page...', 'oneclick-whatsapp-order'),
        ));
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
        if (!class_exists('WooCommerce')) {
            printf(
                '<div class="error"><p><strong>%s</strong> %s <strong>%s</strong> %s</p></div>',
                esc_html__('OneClick Chat to Order', 'oneclick-whatsapp-order'),
                esc_html__('requires', 'oneclick-whatsapp-order'),
                esc_html__('WooCommerce plugin.', 'oneclick-whatsapp-order'),
                esc_html__('Please install and activate it.', 'oneclick-whatsapp-order')
            );
        }
    }
    add_action('admin_notices', 'OCWAORDER_check_woocommece_active');

    // Load bundled translations. Language packs from translate.wordpress.org
    // (WP_LANG_DIR) take precedence and load automatically.
    function OCWAORDER_languages_init()
    {
        load_plugin_textdomain('oneclick-whatsapp-order', false, dirname(OCTO_BASE) . '/languages');
    }
    add_action('init', 'OCWAORDER_languages_init');
}

// Add setting link plugin page
function OCWAORDER_settings_link($links_array, $plugin_file_name)
{
    if ($plugin_file_name === OCTO_BASE) {
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            esc_url(admin_url('admin.php?page=wa-order')),
            esc_html__('Settings', 'oneclick-whatsapp-order')
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
        esc_html__('Buy Me a Coffee ☕', 'oneclick-whatsapp-order')
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

/**
 * Keep only the digits of a phone number.
 *
 * WhatsApp links need the full international number without "+", spaces,
 * dashes or brackets, e.g. "+62 812-3456-7890" becomes "6281234567890".
 *
 * @since 1.2.0
 *
 * @param string $phone Raw phone number.
 * @return string Digits only.
 */
function wa_order_sanitize_phone_number($phone)
{
    if (!is_scalar($phone)) {
        return '';
    }
    return preg_replace('/\D+/', '', (string) $phone);
}

/**
 * Find a published WhatsApp number post from a stored reference.
 *
 * Settings store the number post's slug and product meta stores its title.
 * This accepts either, plus a post ID.
 *
 * @since 1.2.0
 *
 * @param int|string $reference Post ID, slug or title.
 * @return WP_Post|null
 */
function wa_order_get_number_post($reference)
{
    if (is_string($reference)) {
        $reference = trim($reference);
    }
    if ('' === $reference || null === $reference || false === $reference) {
        return null;
    }

    static $cache = array();
    $key = (string) $reference;
    if (array_key_exists($key, $cache)) {
        return $cache[$key];
    }

    $post = null;

    // Slug (what the settings dropdowns save).
    $candidate = get_page_by_path((string) $reference, OBJECT, 'wa-order-numbers');
    if ($candidate && 'publish' === $candidate->post_status) {
        $post = $candidate;
    }

    // Title (what the product "Select Number" field saves).
    if (!$post) {
        $ids = get_posts(array(
            'title'          => (string) $reference,
            'post_type'      => 'wa-order-numbers',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'no_found_rows'  => true,
        ));
        if (!empty($ids)) {
            $post = get_post($ids[0]);
        }
    }

    // Post ID (for code that passes an ID through the filters).
    if (!$post && (is_int($reference) || ctype_digit((string) $reference))) {
        $candidate = get_post((int) $reference);
        if ($candidate && 'wa-order-numbers' === $candidate->post_type && 'publish' === $candidate->post_status) {
            $post = $candidate;
        }
    }

    $cache[$key] = $post;
    return $post;
}

/**
 * Get the digits-only phone number stored on a WhatsApp number post.
 *
 * @since 1.2.0
 *
 * @param int|string|WP_Post $reference Number post, ID, slug or title.
 * @return string
 */
function wa_order_get_number_phone($reference)
{
    $post = ($reference instanceof WP_Post) ? $reference : wa_order_get_number_post($reference);
    if (!$post) {
        return '';
    }
    return wa_order_sanitize_phone_number(get_post_meta($post->ID, 'wa_order_phone_number_input', true));
}

/**
 * Resolve the phone number for one placement (cart, shop, floating, ...).
 *
 * When the placement has no number of its own and $use_fallback is true,
 * the number from the Basic tab is used instead.
 *
 * @since 1.2.0
 *
 * @param string $option_name  Option that stores the placement's number reference.
 * @param bool   $use_fallback Fall back to the Basic tab number.
 * @return string Digits-only phone number, or '' when none is configured.
 */
function wa_order_get_placement_phone($option_name, $use_fallback = true)
{
    $phone = wa_order_get_number_phone(get_option($option_name, ''));
    if ('' === $phone && $use_fallback && 'wa_order_selected_wa_number_single_product' !== $option_name) {
        $phone = wa_order_get_number_phone(get_option('wa_order_selected_wa_number_single_product', ''));
    }
    return $phone;
}

// Selected WhatsApp number that's previously defined
// Since version 1.0.5 - Rewritten in version 1.2.0 (no transient cache, digits only)
function wa_order_get_phone_number($post_id)
{
    $phone_number = '';

    // Check if a number is assigned to the product first (more specific)
    if ('yes' === get_post_meta($post_id, '_wa_order_phone_number_check', true)) {
        $phone_number = wa_order_get_number_phone(get_post_meta($post_id, '_wa_order_phone_number', true));
    }

    // Fallback to global setting if no product-specific number
    if ('' === $phone_number) {
        $phone_number = wa_order_get_number_phone(get_option('wa_order_selected_wa_number_single_product', ''));
    }

    return $phone_number;
}

/**
 * Kept for backward compatibility. Phone numbers are no longer cached in
 * transients, so there is nothing to clear.
 *
 * @since 1.0.8
 * @deprecated 1.2.0
 */
function wa_order_clear_phone_cache($meta_id = 0, $post_id = 0, $meta_key = '', $meta_value = '')
{
}

/**
 * Options access helper.
 *
 * Before 1.2.0 this class copied options into a static cache while plugins
 * were still loading, which froze them before Polylang and WPML could
 * translate them. It now reads through get_option() every time; WordPress
 * already keeps autoloaded options in memory.
 *
 * @since 1.0.8
 */
class WA_Order_Options_Manager
{
    private static $instance = null;

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
    }

    /**
     * Get an option.
     */
    public function get_option($option_name, $default = false)
    {
        return get_option($option_name, $default);
    }

    /**
     * Update an option.
     */
    public function update_option($option_name, $value)
    {
        return update_option($option_name, $value);
    }

    /**
     * Kept for backward compatibility.
     */
    public function clear_option_cache($option_name)
    {
    }

    /**
     * Kept for backward compatibility.
     */
    public function clear_all_cache()
    {
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
        error_log($log_message); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
    }
}

// Plugin activation hook - ensure compatibility
function wa_order_activation_check()
{
    // Check WordPress version
    if (version_compare(get_bloginfo('version'), '6.0', '<')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(
            esc_html__('OneClick Chat to Order requires WordPress 6.0 or higher.', 'oneclick-whatsapp-order'),
            esc_html__('Plugin Activation Error', 'oneclick-whatsapp-order'),
            array('back_link' => true)
        );
    }

    // Check PHP version
    if (version_compare(PHP_VERSION, '7.4', '<')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(
            esc_html__('OneClick Chat to Order requires PHP 7.4 or higher.', 'oneclick-whatsapp-order'),
            esc_html__('Plugin Activation Error', 'oneclick-whatsapp-order'),
            array('back_link' => true)
        );
    }

    // Check if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(
            esc_html__('OneClick Chat to Order requires WooCommerce to be installed and activated.', 'oneclick-whatsapp-order'),
            esc_html__('Plugin Activation Error', 'oneclick-whatsapp-order'),
            array('back_link' => true)
        );
    }

    // New installs start with wa.me links, which work on every device and
    // survive page caching. Existing installs keep their setting.
    if (!wa_order_has_existing_settings()) {
        add_option('wa_order_force_use_wa_me', 'yes');
    }

    // Clear all caches on activation
    wa_order_clear_all_caches();
}
register_activation_hook(__FILE__, 'wa_order_activation_check');

// Clear all plugin caches (and the transients older versions created)
function wa_order_clear_all_caches()
{
    global $wpdb;

    // Phone number transients created before 1.2.0, including their timeout rows.
    $wpdb->query( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
        $wpdb->prepare(
            "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
            $wpdb->esc_like('_transient_wa_order_phone_') . '%',
            $wpdb->esc_like('_transient_timeout_wa_order_phone_') . '%'
        )
    );

    // Shop exclusion transients created before 1.2.0.
    delete_transient('wa_order_cat_ids');
    delete_transient('wa_order_tag_ids');
}

/**
 * Whether this site already has plugin settings or numbers saved.
 *
 * @since 1.2.0
 *
 * @return bool
 */
function wa_order_has_existing_settings()
{
    $markers = array(
        'wa_order_db_version',
        'wa_order_selected_wa_number_single_product',
        'wa_order_option_enable_single_product',
        'wa_order_option_message',
        'wa_order_whatsapp_base_url',
        'wa_order_floating_button',
        'wa_order_option_dismiss_notice_confirmation',
    );
    foreach ($markers as $name) {
        if (false !== get_option($name, false)) {
            return true;
        }
    }

    $numbers = get_posts(array(
        'post_type'      => 'wa-order-numbers',
        'post_status'    => 'any',
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'no_found_rows'  => true,
    ));
    return !empty($numbers);
}

/**
 * Settings migration routine.
 *
 * Each step runs once, keyed by the stored schema version. The 1.1.1 checkbox
 * repair runs only for sites that never ran it; 1.1.2 re-ran it on every site
 * because the schema version was tied to the plugin version.
 *
 * @since 1.1.1
 */
function wa_order_run_migrations()
{
    $current_db_version = get_option('wa_order_db_version', '0');

    // Skip if already migrated
    if (version_compare($current_db_version, WA_ORDER_DB_VERSION, '>=')) {
        return;
    }

    $is_fresh_install = ('0' === $current_db_version) && !wa_order_has_existing_settings();

    // 1.1.1: repair checkbox options corrupted by the missing sanitize callback.
    // Only sites that never ran it (no schema version stored yet).
    if ('0' === $current_db_version && !$is_fresh_install) {
        wa_order_migrate_repair_checkboxes();
    }

    // 1.2.0
    if (version_compare($current_db_version, '1.2.0', '<')) {
        wa_order_migrate_1_2_0($is_fresh_install);
    }

    // Mark migration complete
    update_option('wa_order_db_version', WA_ORDER_DB_VERSION);

    // One-time notice for updated sites; fresh installs don't need it.
    if (!$is_fresh_install) {
        set_transient('wa_order_migration_notice', $current_db_version, 0);
    }
}
add_action('admin_init', 'wa_order_run_migrations');

/**
 * 1.2.0 migration: clean up legacy caches and set new-install defaults.
 *
 * @since 1.2.0
 *
 * @param bool $is_fresh_install Whether the plugin has never been configured.
 */
function wa_order_migrate_1_2_0($is_fresh_install)
{
    wa_order_clear_all_caches();

    if ($is_fresh_install) {
        add_option('wa_order_force_use_wa_me', 'yes');
    }
}

/**
 * Repair checkbox options that were corrupted by the undefined sanitize_checkbox() callback
 * in 1.0.9 and 1.1.0.
 *
 * A feature is switched back on only when the store owner typed text for it
 * (button text, custom message, tooltip, GDPR text). Number dropdowns are not
 * used as evidence because they always submit a value when the tab is saved.
 * "Open in new tab" options are left alone.
 *
 * @since 1.1.1
 */
function wa_order_migrate_repair_checkboxes()
{
    $repairs_made = 0;

    // ── Single Product Button ──
    if (wa_order_has_companion_values(array(
        'wa_order_option_text_button',
        'wa_order_option_message',
    ))) {
        $repairs_made += wa_order_repair_checkbox('wa_order_option_enable_single_product');
    }

    // ── Cart Page ──
    if (wa_order_has_companion_values(array(
        'wa_order_option_cart_button_text',
        'wa_order_option_cart_custom_message',
    ))) {
        $repairs_made += wa_order_repair_checkbox('wa_order_option_add_button_to_cart');
    }

    // ── Thank You Page ──
    if (wa_order_has_companion_values(array(
        'wa_order_option_custom_thank_you_button_text',
        'wa_order_option_custom_thank_you_custom_message',
    ))) {
        $repairs_made += wa_order_repair_checkbox('wa_order_option_enable_button_thank_you');
    }

    // ── Shop Page ──
    if (wa_order_has_companion_values(array(
        'wa_order_option_button_text_shop_loop',
        'wa_order_option_custom_message_shop_loop',
    ))) {
        $repairs_made += wa_order_repair_checkbox('wa_order_option_enable_button_shop_loop');
    }

    // ── Floating Button ──
    if (wa_order_has_companion_values(array(
        'wa_order_floating_message',
        'wa_order_floating_tooltip',
    ))) {
        $repairs_made += wa_order_repair_checkbox('wa_order_floating_button');
    }

    // ── GDPR ──
    if (wa_order_has_companion_values(array(
        'wa_order_gdpr_message',
    ))) {
        $repairs_made += wa_order_repair_checkbox('wa_order_gdpr_status_enable');
    }

    return $repairs_made;
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

    update_option($option_name, $expected_val);
    return 1;
}

/**
 * Show a one-time admin notice after migration completes.
 *
 * @since 1.1.1
 */
function wa_order_migration_admin_notice()
{
    $previous_version = get_transient('wa_order_migration_notice');
    if (false === $previous_version) {
        return;
    }

    // Only show to administrators
    if (!current_user_can('manage_options')) {
        return;
    }

    $settings_url = admin_url('admin.php?page=wa-order&tab=welcome');

    // Sites that ran 1.1.2's repair a second time get a specific explanation.
    if (in_array($previous_version, array('1.1.1', '1.1.2'), true)) {
        $message = esc_html__('Version 1.1.2 may have switched some buttons back on after updating (floating, shop, cart, checkout or GDPR). Please check that each tab shows what you want.', 'oneclick-whatsapp-order');
    } else {
        $message = esc_html__('Your settings have been preserved. We recommend checking each tab once.', 'oneclick-whatsapp-order');
    }

    printf(
        '<div class="notice notice-info is-dismissible"><p><strong>%s</strong> %s <a href="%s">%s</a></p></div>',
        esc_html__('OneClick Chat to Order has been updated to 1.2.0.', 'oneclick-whatsapp-order'),
        $message, // Escaped above.
        esc_url($settings_url),
        esc_html__('Review Settings →', 'oneclick-whatsapp-order')
    );

    // Delete so it only shows once
    delete_transient('wa_order_migration_notice');
}
add_action('admin_notices', 'wa_order_migration_admin_notice');

/**
 * Recommend wa.me links on sites with a page cache.
 *
 * Without "Force wa.me", the WhatsApp address is picked per device on the
 * server, so a cached page can send phones to WhatsApp Web.
 *
 * @since 1.2.0
 */
function wa_order_page_cache_notice()
{
    if (!current_user_can('manage_options')) {
        return;
    }
    if (!isset($_GET['page']) || 'wa-order' !== sanitize_key(wp_unslash($_GET['page']))) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        return;
    }
    if (!(defined('WP_CACHE') && WP_CACHE) || 'yes' === get_option('wa_order_force_use_wa_me')) {
        return;
    }
    printf(
        '<div class="notice notice-warning"><p>%s <a href="%s">%s</a></p></div>',
        esc_html__('A page cache is active on this site. Turn on "Force wa.me" so visitors on phones and desktops always get a working WhatsApp link.', 'oneclick-whatsapp-order'),
        esc_url(admin_url('admin.php?page=wa-order&tab=button_config')),
        esc_html__('Open the Basic tab', 'oneclick-whatsapp-order')
    );
}
add_action('admin_notices', 'wa_order_page_cache_notice');

// A function to dynamically generate WhatsApp URL
// Rewritten in version 1.2.0: digits-only phone numbers and a final filter.
function wa_order_the_url($phone_number, $message)
{
    $phone_number = wa_order_sanitize_phone_number($phone_number);
    $message      = (string) $message;
    $force_wa_me  = get_option('wa_order_force_use_wa_me', 'no');

    // If force wa.me is enabled, use wa.me for all links
    if ($force_wa_me === 'yes') {
        $base_url = 'https://wa.me/';
    } else {
        // Use the existing logic for mobile/desktop detection
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])) : '';
        $mobile_base_url = get_option('wa_order_whatsapp_base_url', 'api'); // Default to api.whatsapp.com
        $desktop_base_url = get_option('wa_order_whatsapp_base_url_desktop', 'web'); // Default to web.whatsapp.com

        // Check if it's a mobile device
        if (wp_is_mobile() || preg_match('/iPhone|Android|iPod|iPad|webOS|BlackBerry|Windows Phone|Opera Mini|IEMobile|Mobile/', $user_agent)) {
            if ($mobile_base_url === 'protocol') {
                $base_url = 'whatsapp://send?';
            } else {
                $base_url = 'https://api.whatsapp.com/send?';
            }
        } else {
            if ($desktop_base_url === 'api') {
                $base_url = 'https://api.whatsapp.com/send?';
            } elseif ($desktop_base_url === 'protocol') {
                $base_url = 'whatsapp://send?';
            } else {
                $base_url = 'https://web.whatsapp.com/send?';
            }
        }
    }

    $encoded_message = rawurlencode($message);

    // Build the full WhatsApp URL
    if ($force_wa_me === 'yes') {
        // wa.me format: https://wa.me/phone?text=message
        $button_url = $base_url . $phone_number . '?text=' . $encoded_message;
    } else {
        // Standard format: base_url + phone=...&text=...&app_absent=0
        $button_url = $base_url . 'phone=' . $phone_number . '&text=' . $encoded_message . '&app_absent=0';
    }

    /**
     * Filter the final WhatsApp URL for every button.
     *
     * @since 1.2.0
     *
     * @param string $button_url   The WhatsApp URL.
     * @param string $phone_number Digits-only phone number.
     * @param string $message      Plain-text message (not encoded).
     */
    return apply_filters('wa_order_filter_whatsapp_url', $button_url, $phone_number, $message);
}
// Add the whatsapp protocol to the allowed protocols
function wa_order_allow_whatsapp_protocol($protocols)
{
    $protocols[] = 'whatsapp';
    return $protocols;
}
add_filter('kses_allowed_protocols', 'wa_order_allow_whatsapp_protocol');

/**
 * Turn formatted WooCommerce HTML (prices, labels) into plain text for a message.
 *
 * @since 1.2.0
 *
 * @param string $html HTML such as the output of wc_price().
 * @return string
 */
function wa_order_plain_text($html)
{
    $text = html_entity_decode(wp_strip_all_tags((string) $html), ENT_QUOTES, 'UTF-8');
    // Normalize non-breaking spaces some currencies use.
    $text = str_replace("\xC2\xA0", ' ', $text);
    return trim($text);
}

/**
 * The URL of the page being viewed, built from the home URL so sites in a
 * subdirectory don't get the path twice.
 *
 * @since 1.2.0
 *
 * @return string
 */
function wa_order_current_url()
{
    $request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '/'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
    $home_path   = (string) wp_parse_url(home_url('/'), PHP_URL_PATH);

    if ('' !== $home_path && '/' !== $home_path && 0 === strpos($request_uri, $home_path)) {
        $request_uri = substr($request_uri, strlen($home_path));
    }

    return esc_url_raw(home_url('/' . ltrim($request_uri, '/')));
}

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
    if (function_exists('is_product') && is_product()) {
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
 * OSKIT URL parameters for links to the author's sites.
 *
 * Since 1.2.0 the links carry only the plugin name, plugin version and the
 * admin screen they were clicked from; no PHP version, WordPress version or
 * locale is sent.
 *
 * @param string $base_url The URL to track.
 * @return string          Fully escaped URL.
 */
if (! function_exists('oskit_url')) {
    function oskit_url($base_url)
    {
        $base_url = esc_url_raw($base_url);

        $plugin_name = '';
        if (defined('OCTO_NAME')) {
            $plugin_name = sanitize_title(OCTO_NAME);
        }

        $plugin_version = '';
        if (defined('OCTO_VERSION')) {
            $plugin_version = OCTO_VERSION;
        }

        $screen = 'frontend';
        if (is_admin()) {
            $page = isset($_GET['page']) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                ? sanitize_key(wp_unslash($_GET['page'])) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                : '';
            $tab  = isset($_GET['tab']) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                ? sanitize_key(wp_unslash($_GET['tab'])) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                : '';
            if ($page) {
                $screen = $page . ($tab ? '-' . $tab : '');
            }
        }

        $params = array(
            'plugin_name'    => $plugin_name,
            'plugin_version' => $plugin_version,
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
    $screen    = function_exists('get_current_screen') ? get_current_screen() : null;
    $screen_id = ($screen && isset($screen->id)) ? $screen->id : '';
    $page      = isset($_GET['page']) ? sanitize_key(wp_unslash($_GET['page'])) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

    // Check if we're on one of our target pages
    if ('wa-order' === $page || 'edit-wa-order-numbers' === $screen_id) {
        $footer_text = sprintf(
            /* translators: 1: plugin name, 2: 5-star review link */
            esc_html__('Enjoyed %1$s? Please leave a %2$s rating. I really appreciate your support!', 'oneclick-whatsapp-order'),
            '<strong>' . esc_html(OCTO_NAME) . '</strong>',
            '<a href="' . esc_url('https://wordpress.org/support/plugin/oneclick-whatsapp-order/reviews/?rate=5#new-post') . '" target="_blank" rel="noopener"><span class="screen-reader-text">' . esc_html__('5 stars', 'oneclick-whatsapp-order') . '</span>★★★★★</a>'
        );

        // Wrap in the desired HTML structure
        $footer_text = '<span class="oskit-footer-thankyou">' . $footer_text . '</span>';
    }

    return $footer_text;
}
add_filter('admin_footer_text', 'OCWAORDER_admin_footer_text');

// Plugin deactivation hook - cleanup caches only, settings are kept.
function wa_order_deactivation_cleanup()
{
    wa_order_clear_all_caches();
}
register_deactivation_hook(__FILE__, 'wa_order_deactivation_cleanup');
