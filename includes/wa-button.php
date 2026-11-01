<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * OneClick Chat to Order
 *
 * @package     OneClick Chat to Order
 * @author      Walter Pinem <hello@walterpinem.me>
 * @link        https://walterpinem.me/
 * @link        https://www.onlinestorekit.com/oneclick-chat-to-order/
 * @copyright   Copyright (c) 2019 - 2026, Walter Pinem | Online Store Kit
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * @category    WhatsApp Buttons Functions
 *
 ********************************* WhatsApp Buttons Functions ********************************* */

// Include important files
require_once plugin_dir_path(dirname(__FILE__)) . 'includes/buttons/wa-order-cart-page.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'includes/buttons/wa-order-display-options.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'includes/buttons/wa-order-floating-button.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'includes/buttons/wa-order-shop-archive.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'includes/buttons/wa-order-single-product.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'includes/buttons/wa-order-thank-you.php';

/**
 * Whether the current admin screen belongs to this plugin.
 *
 * @since 1.2.0
 *
 * @return bool
 */
function wa_order_is_plugin_admin_screen()
{
    $page = isset($_GET['page']) ? sanitize_key(wp_unslash($_GET['page'])) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    if ('wa-order' === $page) {
        return true;
    }
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    return $screen && isset($screen->post_type) && 'wa-order-numbers' === $screen->post_type;
}

// Display admin notice message if confirmation check is unchecked.
// Since 1.2.0: only for administrators, only on this plugin's screens (or the
// Plugins screen while no number exists yet).
function wa_order_confirm_if_number_added()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    // Get the dismiss option
    $dismiss_notice = sanitize_text_field(get_option('wa_order_option_dismiss_notice_confirmation'));
    if ($dismiss_notice === 'yes') {
        return;
    }

    global $pagenow;
    if (!wa_order_is_plugin_admin_screen()) {
        // Elsewhere, only nudge on the Plugins screen while no number exists yet.
        if ('plugins.php' !== $pagenow) {
            return;
        }
        $has_numbers = (bool) get_posts(array(
            'post_type'      => 'wa-order-numbers',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'no_found_rows'  => true,
        ));
        if ($has_numbers) {
            return;
        }
    }

    // Define the error message with translations and escaping
    $error_message = sprintf(
        /* translators: %1$s and %2$s are the placeholders for the links */
        __('%1$s Important! %2$s With %3$sOneClick Chat to Order%4$s, you can now set multiple WhatsApp numbers. Please %5$sset it here%6$s to get started. %7$sLearn more%8$s. %9$sDismiss%10$s.', 'oneclick-whatsapp-order'),
        '<strong style="color:red;">',
        '</strong>',
        '<strong>',
        '</strong>',
        '<a href="' . esc_url(admin_url('edit.php?post_type=wa-order-numbers')) . '">',
        '</a>',
        '<a href="' . esc_url(oskit_url('https://walterpinem.me/projects/oneclick-chat-to-order-mutiple-numbers-feature/')) . '" target="_blank">',
        '</a>',
        '<a href="' . esc_url(admin_url('admin.php?page=wa-order&tab=button_config')) . '">',
        '</a>'
    );

    printf(
        '<div class="notice notice-warning wa-order-notice-dismissible"><p>%s</p></div>',
        wp_kses_post($error_message)
    );
}
add_action('admin_notices', 'wa_order_confirm_if_number_added');

// Global Shortcode Function: [wa-order]
function wa_order_shortcode_button($atts, $content = null)
{
    // Consolidate get_option() calls
    $options = array(
        'target_blank'   => get_option('wa_order_shortcode_target', ''),
        'custom_message' => get_option('wa_order_shortcode_message', ''),
        'button_text'    => get_option('wa_order_shortcode_text_button', ''),
    );

    // Number from the Shortcode tab, or the Basic tab number when none is chosen.
    $phonenumb = wa_order_get_placement_phone('wa_order_selected_wa_number_shortcode');
    if ('' === $phonenumb) {
        return ''; // No number: don't print a link that opens WhatsApp's share screen.
    }

    // Define the button's target attribute
    $blank = ($options['target_blank'] === '_blank') ? ' target="_blank" rel="noopener"' : '';

    // Use the dynamic WhatsApp URL function
    $button_url = wa_order_the_url($phonenumb, $options['custom_message']);

    // Build the button HTML
    if (!empty($options['button_text'])) {
        $output = "<a id=\"sendbtn\" class=\"shortcode_wa_button\" href=\"" . esc_url($button_url) . "\"" . $blank . "><span>" . do_shortcode($content) . esc_html($options['button_text']) . "</span></a>";
    } else {
        $output = "<a id=\"sendbtn\" class=\"shortcode_wa_button_nt\" href=\"" . esc_url($button_url) . "\"" . $blank . " aria-label=\"" . esc_attr__('Chat on WhatsApp', 'oneclick-whatsapp-order') . "\"><span>" . do_shortcode($content) . "</span></a>";
    }

    return $output;
}
add_shortcode('wa-order', 'wa_order_shortcode_button');

// Dynamic Shortcode Generator
// Shortcode: [waorder phone="" button="" message="" target=""]
function wa_order_shortcode_generator_button($atts, $content = null)
{
    // Extract shortcode attributes with default values
    $atts = shortcode_atts(
        array(
            'phone'   => '',
            'button'  => '',
            'message' => '',
            'target'  => '',
        ),
        $atts,
        'waorder'
    );

    // Sanitize attributes to prevent XSS
    $phone   = wa_order_sanitize_phone_number(sanitize_text_field($atts['phone']));
    $button  = sanitize_text_field($atts['button']);
    $message = sanitize_textarea_field($atts['message']);
    $target  = sanitize_text_field($atts['target']);

    // Define the button click target (open in new tab or not)
    $blank = ($target === 'yes' || $target === '_blank') ? ' target="_blank" rel="noopener"' : '';

    // Use the dynamic WhatsApp URL function to build the correct URL
    $button_url = wa_order_the_url($phone, $message);

    // Build the output for the button
    $output = "<a id=\"sendbtn\" class=\"shortcode_wa_button\" href=\"" . esc_url($button_url) . "\"" . $blank . "><span>" . do_shortcode($content) . esc_html($button) . "</span></a>";

    return $output;
}

add_shortcode('waorder', 'wa_order_shortcode_generator_button');

// Convert phone number link into WhatsApp chat link in the order edit screen.
// Since 1.2.0 this works on both the HPOS and the legacy order screens and
// targets the billing phone link itself.
function wa_order_convert_phone_link()
{
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if (!$screen || !in_array($screen->id, array('woocommerce_page_wc-orders', 'shop_order'), true)) {
        return;
    }

    $convert_phone_no = get_option('wa_order_option_convert_phone_order_details');
    if ($convert_phone_no !== 'yes') {
        return;
    }

    $custom_message = get_option('wa_order_option_custom_message_backend_order_details');
    if (empty($custom_message)) {
        $custom_message = __('Hello, I would like to follow up on your order.', 'oneclick-whatsapp-order');
    }
    ?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var numberElement = document.querySelector('.order_data_column .address a[href^="tel:"]');
            if (!numberElement) {
                return;
            }
            var number = numberElement.textContent.replace(/[^0-9]/g, '');
            if (!number) {
                return;
            }
            var message = encodeURIComponent(<?php echo wp_json_encode($custom_message); ?>);
            numberElement.setAttribute('href', 'https://wa.me/' + number + '?text=' + message);
            numberElement.setAttribute('target', '_blank');
            numberElement.setAttribute('rel', 'noopener');
        });
    </script>
<?php
}
if (is_admin() && get_option('wa_order_option_convert_phone_order_details') === 'yes') {
    add_action('admin_footer', 'wa_order_convert_phone_link');
}

// Single Product Shortcode Generator
// Shortcode Full: [oneclick single="true" phone="1234567890" product="current" text="Order via WhatsApp" message="Hello, I need to know more about"]
// Shortcode without message and text: [oneclick single="true" phone="1234567890" product="current"]
function wa_order_shortcode($atts)
{
    // Shortcode attributes
    $atts = shortcode_atts(array(
        'single' => 'true', // Always 'true' for single product page
        'product' => 'current', // Can be 'current' or a specific product ID
        'phone' => '', // WhatsApp number
        'text' => '', // Button text from shortcode
        'message' => '', // Custom message from shortcode
        'fullwidth' => '',
    ), $atts);
    $atts['phone']      = sanitize_text_field($atts['phone']);
    $atts['text']       = sanitize_text_field($atts['text']);
    $atts['message']    = sanitize_textarea_field($atts['message']);
    $atts['fullwidth']  = sanitize_text_field($atts['fullwidth']);

    // Resolve the product without touching the global $product, so the rest of
    // the page keeps rendering the right product.
    if (isset($atts['product']) && $atts['product'] !== 'current') {
        $product_id = absint($atts['product']);
    } else {
        $current = isset($GLOBALS['product']) ? $GLOBALS['product'] : null;
        if ($current instanceof WC_Product) {
            $product_id = $current->get_id();
        } elseif ('product' === get_post_type()) {
            $product_id = get_the_ID();
        } else {
            $product_id = 0;
        }
    }

    if (!$product_id) {
        return ''; // Not on a product and no product ID given.
    }

    $the_product = wc_get_product($product_id);

    if (!$the_product) {
        return ''; // Exit if the product is invalid
    }

    // Phone from the shortcode, or the product's number (falls back to the Basic tab number).
    $phone_attr = wa_order_sanitize_phone_number($atts['phone']);
    if ('' === $phone_attr) {
        $phone_attr = wa_order_get_phone_number($product_id);
    }
    $phone = apply_filters('wa_order_filter_oneclick_shortcode_phone', $phone_attr, $atts);
    if (!$phone) {
        return ''; // Exit if no phone number is set
    }

    // Get product details (plain text: these go into a URL, not into HTML)
    $product_url = apply_filters('wa_order_filter_oneclick_shortcode_product_url', get_permalink($product_id), $product_id);
    $title = apply_filters('wa_order_filter_oneclick_shortcode_product_title', $the_product->get_name(), $product_id);
    $price_parts = wa_order_get_product_price_parts($the_product);
    $format_price = apply_filters('wa_order_filter_oneclick_shortcode_product_price', wa_order_plain_text($price_parts['price']), $price_parts['price'], $product_id);
    $decoded_price = wa_order_plain_text($format_price);
    $decoded_regular_price = wa_order_plain_text($price_parts['regular']);
    $decoded_sale_price = wa_order_plain_text($price_parts['sale']);

    // Button text logic
    $button_text = apply_filters(
        'wa_order_filter_oneclick_shortcode_button_text',
        !empty($atts['text']) ? $atts['text'] : (
            get_post_meta($product_id, '_wa_order_button_text', true) ?:
            get_option('wa_order_option_text_button', esc_html__('Buy via WhatsApp', 'oneclick-whatsapp-order'))
        ),
        $atts
    );
    if ('' === trim((string) $button_text)) {
        $button_text = __('Buy via WhatsApp', 'oneclick-whatsapp-order');
    }
    // Message logic
    $custom_message = apply_filters('wa_order_filter_oneclick_shortcode_message', !empty($atts['message']) ? $atts['message'] : (
        get_post_meta($product_id, '_wa_order_custom_message', true) ?: get_option('wa_order_option_message', 'Hello, I want to buy:')
    ), $atts);

    // URL Encoding
    $encoded_title = urlencode($title);
    $encoded_product_url = urlencode($product_url);
    $encoded_price_label = urlencode(get_option('wa_order_option_price_label', 'Price') ?: 'Price');
    $encoded_url_label = urlencode(get_option('wa_order_option_url_label', 'URL') ?: 'URL');
    $encoded_thanks = urlencode(get_option('wa_order_option_thank_you_label', 'Thank you!'));

    if (get_option('wa_order_option_single_show_regular_sale_prices', 'no') === 'yes' && '' !== $decoded_regular_price) {
        if ($price_parts['on_sale'] && '' !== $decoded_sale_price) {
            $encoded_price = "~" . urlencode($decoded_regular_price) . "~ " . urlencode($decoded_sale_price);
        } else {
            $encoded_price = urlencode($decoded_regular_price);
        }
    } else {
        $encoded_price = urlencode($decoded_price);
    }

    // Exclude price if the option is checked or the product has no price
    $exclude_price = apply_filters('wa_order_filter_oneclick_shortcode_exclude_price', get_option('wa_order_exclude_price', 'no'), $atts);
    $message_content = apply_filters('wa_order_filter_oneclick_shortcode_message_content', urlencode($custom_message) . "%0D%0A%0D%0A*$encoded_title*", $product_id);
    if ($exclude_price !== 'yes' && '' !== $encoded_price) {
        $message_content .= "%0D%0A*$encoded_price_label:* $encoded_price";
    }
    if (get_option('wa_order_exclude_product_url') !== 'yes') {
        $message_content .= "%0D%0A*$encoded_url_label:* $encoded_product_url";
    }
    if ('' !== $encoded_thanks) {
        $message_content .= "%0D%0A%0D%0A$encoded_thanks";
    }

    // WhatsApp URL
    $button_url = apply_filters('wa_order_filter_oneclick_shortcode_button_url', wa_order_the_url($phone, urldecode($message_content)), $phone, $message_content);
    $button_href = esc_url($button_url);
    $button_text_html = esc_html($button_text);

    // Full-Width button
    $force_fullwidth = apply_filters('wa_order_filter_oneclick_shortcode_fullwidth', $atts['fullwidth'], $atts);

    // Button classes and styles based on full-width option
    $button_fullwidth_class = ($force_fullwidth === 'true') ? 'single_product_shortcode_fullwidth' : '';
    $button_fullwidth_style = ($force_fullwidth === 'true') ? 'style="width:100%;display:block;"' : '';

    // Button HTML
    $button_html = apply_filters('wa_order_filter_oneclick_shortcode_button_html', "<a href=\"$button_href\" id=\"sendbtn\" class=\"shortcode_wa_button single_product_shortcode $button_fullwidth_class\" role=\"button\" target=\"_blank\" rel=\"nofollow noopener\" $button_fullwidth_style>$button_text_html</a>", $button_url, $button_text, $atts);

    // GDPR compliance logic
    $gdpr_status = get_option('wa_order_gdpr_status_enable', 'no');
    if ($gdpr_status === 'yes') {
        $gdpr_message = wp_kses_post(do_shortcode(stripslashes(get_option('wa_order_gdpr_message'))));
        $gdpr = wa_order_next_gdpr_group();
        $gdpr_group = (int) $gdpr['group'];
        $gdpr_id = esc_attr($gdpr['id']);

        $gdpr_script = "
        <script>
            function WAOrder() {
                var phone = '" . esc_js($phone) . "',
                    wa_message = '" . esc_js($custom_message) . "',
                    button_url = '" . esc_url($button_url) . "',
                    target = '_blank';
            }
        </script>
        <style>
            .wa-order-button,
            .wa-order-button .wa-order-class {
                display: none !important;
            }
        </style>";

        $button_html = "
        $gdpr_script
        <label class=\"wa-button-gdpr\">
            <a href=\"$button_href\" class=\"gdpr_wa_button $button_fullwidth_class\" role=\"button\" target=\"_blank\" rel=\"noopener\" aria-disabled=\"true\" data-wa-gdpr-group=\"$gdpr_group\">
                <button type=\"button\" class=\"gdpr_wa_button_input $button_fullwidth_class button alt\" disabled=\"disabled\" data-wa-gdpr-group=\"$gdpr_group\" onclick=\"WAOrder();\">$button_text_html</button>
            </a>
        </label>
        <div class=\"wa-order-gdprchk\">
            <input type=\"checkbox\" name=\"wa_order_gdpr_status_enable\" class=\"css-checkbox wa_order_input_check\" id=\"$gdpr_id\" data-wa-gdpr-group=\"$gdpr_group\" />
            <label for=\"$gdpr_id\" class=\"label-gdpr\">$gdpr_message</label>
        </div>
        " . wa_order_gdpr_group_script($gdpr['id']);
    }

    return apply_filters('wa_order_filter_oneclick_shortcode_output', $button_html, $atts);
}
add_shortcode('oneclick', 'wa_order_shortcode');
