<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * OneClick Chat to Order Thank You Page
 *
 * @package     OneClick Chat to Order
 * @author      Walter Pinem <hello@walterpinem.me>
 * @link        https://walterpinem.me/
 * @link        https://www.onlinestorekit.com/oneclick-chat-to-order/
 * @copyright   Copyright (c) 2019 - 2026, Walter Pinem | Online Store Kit
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * @category    Checkout Page
 */

/**
 * Turn a formatted WooCommerce address (with <br/> separators) into plain text lines.
 *
 * @since 1.2.0
 *
 * @param string $address Formatted address HTML.
 * @return string
 */
function wa_order_plain_address($address)
{
    return wa_order_plain_text(preg_replace('#<br\s*/?>#i', "\r\n", (string) $address));
}

// Checkbox value
$override_thankyou_page = get_option('wa_order_option_enable_button_thank_you');
function wa_order_thank_you_override($title, $id)
{
    global $wp;
    // Consolidate get_option() calls with proper default values
    $options = array(
        'wanumberpage'               => get_option('wa_order_selected_wa_number_thanks', ''),
        'custom_title'               => !empty(get_option('wa_order_option_custom_thank_you_title')) ? get_option('wa_order_option_custom_thank_you_title') : __('Thanks and You\'re Awesome', 'oneclick-whatsapp-order'),
        'custom_subtitle'            => !empty(get_option('wa_order_option_custom_thank_you_subtitle')) ? get_option('wa_order_option_custom_thank_you_subtitle') : __('For faster response, send your order details by clicking below button.', 'oneclick-whatsapp-order'),
        'button_text'                => !empty(get_option('wa_order_option_custom_thank_you_button_text')) ? get_option('wa_order_option_custom_thank_you_button_text') : __('Send Order Details', 'oneclick-whatsapp-order'),
        'custom_message'             => !empty(get_option('wa_order_option_custom_thank_you_custom_message')) ? get_option('wa_order_option_custom_thank_you_custom_message') : __("Hello, here's my order details:", 'oneclick-whatsapp-order'),
        'thanks_label'               => get_option('wa_order_option_thank_you_label', ''),
        'include_order_number'       => get_option('wa_order_option_custom_thank_you_order_number', 'no'),
        'order_number_label'         => !empty(get_option('wa_order_option_custom_thank_you_order_number_label')) ? get_option('wa_order_option_custom_thank_you_order_number_label') : 'Order Number',
        'include_payment_link'       => get_option('wa_order_option_thank_you_payment_link', 'no'),
        'payment_link_label'         => !empty(get_option('wa_order_option_thank_you_payment_link_label')) ? get_option('wa_order_option_thank_you_payment_link_label') : 'Payment Link',
        'include_order_summary_link' => get_option('wa_order_option_thank_you_order_summary_link', 'no'),
        'order_summary_label'        => !empty(get_option('wa_order_option_thank_you_order_summary_label')) ? get_option('wa_order_option_thank_you_order_summary_label') : 'Check Order Summary',
        'include_view_order_link'    => get_option('wa_order_option_thank_you_view_order_link', 'no'),
        'view_order_label'           => !empty(get_option('wa_order_option_thank_you_view_order_label')) ? get_option('wa_order_option_thank_you_view_order_label') : 'View Order',
        'tax_label'                  => get_option('wa_order_option_tax_label', 'Tax'),
        'customer_details_label'     => !empty(get_option('wa_order_option_custom_thank_you_customer_details_label')) ? get_option('wa_order_option_custom_thank_you_customer_details_label') : 'Customer Details',
        'total_products_label'       => !empty(get_option('wa_order_option_custom_thank_you_total_products_label')) ? get_option('wa_order_option_custom_thank_you_total_products_label') : 'Total Products',
        'total_label'                => get_option('wa_order_option_total_amount_label', 'Total'),
        'total_discount_label'       => get_option('wa_order_option_total_discount_label'),
        'payment_label'              => get_option('wa_order_option_payment_method_label'),
        'include_sku'                => get_option('wa_order_option_custom_thank_you_include_sku', 'no'),
        'include_tax'                => get_option('wa_order_option_custom_thank_you_include_tax', 'no'),
        'include_coupon'             => get_option('wa_order_option_custom_thank_you_inclue_coupon', 'no'),
        'coupon_label'               => !empty(get_option('wa_order_option_custom_thank_you_coupon_label')) ? get_option('wa_order_option_custom_thank_you_coupon_label') : 'Voucher Code',
        'order_date'                 => get_option('wa_order_option_custom_thank_you_include_order_date', 'no'),
        'open_new_tab'               => get_option('wa_order_option_custom_thank_you_open_new_tab', '_blank'),
        'include_shipping'           => get_option('wa_order_option_custom_thank_you_include_shipping', 'yes'),
        'shipping_label'             => !empty(get_option('wa_order_option_custom_thank_you_shipping_label')) ? get_option('wa_order_option_custom_thank_you_shipping_label') : 'Shipping',
    );

    // Number from the Checkout tab, or the Basic tab number when none is chosen.
    $phonenumb = wa_order_get_number_phone($options['wanumberpage']);
    if ('' === $phonenumb) {
        $phonenumb = wa_order_get_number_phone(get_option('wa_order_selected_wa_number_single_product', ''));
    }
    $phonenumb = apply_filters('wa_order_filter_thank_you_page_phone_number', $phonenumb);
    if (!$phonenumb) {
        return $title; // Keep WooCommerce's own text rather than print a dead button.
    }

    // Mapping the options
    $custom_title           = apply_filters('wa_order_filter_thank_you_page_custom_title', $options['custom_title']);
    $custom_subtitle        = apply_filters('wa_order_filter_thank_you_page_subtitle', $options['custom_subtitle']);
    $button_text            = apply_filters('wa_order_filter_thank_you_page_button_text', $options['button_text']);
    $custom_message         = apply_filters('wa_order_filter_thank_you_page_custom_message', $options['custom_message']);
    $thanks_label           = $options['thanks_label'];
    $include_order_number   = $options['include_order_number'];
    $order_number_label     = apply_filters('wa_order_filter_thank_you_page_order_number_label', $options['order_number_label']);

    $customer_details_label = $options['customer_details_label'];
    $total_products_label   = $options['total_products_label'];
    $total_discount_label   = $options['total_discount_label'];
    $tax_label              = $options['tax_label'];
    $include_payment_link   = $options['include_payment_link'];
    $payment_link_label     = $options['payment_link_label'];
    $include_view_order     = $options['include_view_order_link'];
    $view_order_label       = $options['view_order_label'];
    $include_order_summary  = $options['include_order_summary_link'];
    $order_summary_label    = $options['order_summary_label'];

    // The order: WooCommerce passes it to this filter; fall back to the endpoint query var.
    $order = ($id instanceof WC_Order) ? $id : null;
    $order_id = $order ? $order->get_id() : (isset($wp->query_vars['order-received']) ? absint($wp->query_vars['order-received']) : 0);
    if (!$order_id) {
        return $title;
    }

    // Validate order access (if security enhancements are loaded)
    if (function_exists('wa_order_validate_order_access') && !wa_order_validate_order_access($order_id)) {
        return $title;
    }

    if (!$order) {
        $order = wc_get_order($order_id);
    }
    if (!$order) {
        return $title;
    }

    // Prices in the message follow the store's cart/checkout tax display setting.
    $display_incl_tax = ('incl' === get_option('woocommerce_tax_display_cart'));

    // Prepare the message
    $customer_id        = $order->get_user_id();
    $first_name         = $order->get_billing_first_name();
    $message            = $custom_message . "\n\n";
    /* translators: 1: thank you title, 2: customer's first name */
    $thetitle           = $first_name ? sprintf(__('%1$s, %2$s!', 'oneclick-whatsapp-order'), $custom_title, $first_name) : $custom_title;
    $subtitle           = $custom_subtitle;
    $button             = $button_text;
    $customer_email     = $order->get_billing_email();
    $customer_phone     = $order->get_billing_phone();

    $formatted_billing  = wa_order_plain_address($order->get_formatted_billing_address()) . "\r\n" . $customer_phone . "\r\n" . $customer_email;
    $formatted_shipping = wa_order_plain_address($order->get_formatted_shipping_address());

    $total_label        = $options['total_label'] ?: 'Total';
    $payment_label      = $options['payment_label'];
    $format_subtotal_price = wa_order_plain_text($order->get_subtotal_to_display());
    $total_format_subtotal_price = "*" . __('Subtotal:', 'woocommerce') . "*" . "\r\n" . $format_subtotal_price;
    $payment_method     = $order->get_payment_method_title();
    $payment            = "\r\n" . "*" . ($payment_label ?: __('Payment method:', 'woocommerce')) . ($payment_label ? ":" : "") . "*" . "\r\n" . $payment_method . "\r\n";
    $date_created       = $order->get_date_created();
    $date               = $date_created ? wc_format_datetime($date_created, get_option('date_format') . ' - ' . get_option('time_format')) : '';
    $order_number       = $order->get_order_number();
    $on_label           = ('' === (string) $order_number_label) ? 'Order Number:' : $order_number_label;

    // If Order Number inclusion is checked
    if ($include_order_number === 'yes') {
        $message .= "*" . $on_label . "*: " . "#" . $order_number . "\n\n";
    }
    if ($total_products_label) {
        $message .= "*" . $total_products_label . "*: " . $order->get_item_count() . "\n-----------\n";
    }
    foreach ($order->get_items() as $item_id => $item) {
        $quantity       = $item->get_quantity();
        $product_name   = wa_order_plain_text($item->get_name());
        $message        .= $quantity . "x - *" . $product_name . "*" . "\n";

        $formatted_meta_data = $item->get_formatted_meta_data('_', true);
        foreach ($formatted_meta_data as $meta) {
            $message    .= "     - ```" . wa_order_plain_text($meta->display_key) . ":``` ```" . wa_order_plain_text($meta->display_value) . "```" . "\r\n";
        }

        // Include SKU if enabled and available
        if ($options['include_sku'] === 'yes') {
            $productsku = $item->get_product();
            if ($productsku) {
                $sku = $productsku->get_sku();
                if (!empty($sku)) {
                    $message .= "     - ```" . __('SKU', 'woocommerce') . ": " . $sku . "```" . "\n";
                }
            }
        }
    }
    $message .= "\n" . $total_format_subtotal_price . "\n" . $payment;

    // Customer Details
    if (!empty($customer_details_label)) {
        $message .= "\n*" . $customer_details_label . "*\n" . $formatted_billing . "\n";
    }

    // Shipping section (read through the order object, so it works with HPOS)
    if ($options['include_shipping'] === 'yes') {
        $shipping_method = $order->get_shipping_method();
        $shipping_total = (float) $order->get_shipping_total() + ($display_incl_tax ? (float) $order->get_shipping_tax() : 0);
        $plain_shipping_cost = wa_order_plain_text(wc_price($shipping_total, array('currency' => $order->get_currency())));
        $ship_label = apply_filters('wa_order_filter_thank_you_page_shipping_label', $options['shipping_label']);
        $plain_shipping_cost = apply_filters('wa_order_filter_thank_you_page_shipping_cost', $plain_shipping_cost);

        if (!empty($shipping_method)) {
            $message .= "\r\n*" . $ship_label . ":*\r\n";
            $message .= $shipping_method . ' - ' . $plain_shipping_cost . "\r\n";

            if ($order->has_shipping_address() && '' !== $formatted_shipping) {
                $message .= "\n-----------\n";
                $message .= $formatted_shipping;
            }
        }
    }

    // Coupons: read from the order's own coupon lines, so deleted or renamed
    // coupons still show and the amounts match what WooCommerce applied.
    $coupon_items = $order->get_items('coupon');
    if (!empty($coupon_items) && $order->get_total_discount() > 0 && $options['include_coupon'] === 'yes') {
        $voucher_label = ('' === (string) $options['coupon_label']) ? 'Voucher Code:' : $options['coupon_label'];
        $order_args = array('currency' => $order->get_currency());

        foreach ($coupon_items as $coupon_item) {
            $code = $coupon_item->get_code();
            $amount = (float) $coupon_item->get_discount() + ($display_incl_tax ? (float) $coupon_item->get_discount_tax() : 0);
            $amount_text = wa_order_plain_text(wc_price($amount, $order_args));

            $percent = '';
            $coupon_id = wc_get_coupon_id_by_code($code);
            if ($coupon_id) {
                $coupon = new WC_Coupon($coupon_id);
                if ($coupon->is_type('percent')) {
                    $percent = wc_format_decimal($coupon->get_amount(), false, true) . '% ';
                }
            }

            $coupon_code = "\r\n" . "*" . $voucher_label . "*" . "\r\n" . strtoupper($code) . ": -" . ('' !== $percent ? $percent . "(-" . $amount_text . ")" : $amount_text);
            $coupon_code = apply_filters('wa_order_filter_thank_you_page_coupon_code', $coupon_code);
            $message .= "\r\n" . $coupon_code . "\r\n";
        }

        $discount_total = (float) $order->get_discount_total() + ($display_incl_tax ? (float) $order->get_discount_tax() : 0);
        $total_discount = apply_filters('wa_order_filter_thank_you_page_total_discount', wa_order_plain_text(wc_price($discount_total, $order_args)));
        $discount_label = $total_discount_label ? $total_discount_label : __('Total Discount', 'woocommerce');
        $discount_label = apply_filters('wa_order_filter_thank_you_page_discount_label', $discount_label);
        $message .= "*" . $discount_label . ":* " . $total_discount . "\r\n";

        $subtotal_tax = 0.0;
        if ($display_incl_tax) {
            foreach ($order->get_items() as $line_item) {
                $subtotal_tax += (float) $line_item->get_subtotal_tax();
            }
        }
        $numeric_subtotal = (float) $order->get_subtotal() + $subtotal_tax;
        $message .= "*" . __('Discount', 'woocommerce') . ":* " . "\r\n" . wa_order_plain_text(wc_price($numeric_subtotal, $order_args)) . " - " . $total_discount . " = " . wa_order_plain_text(wc_price($numeric_subtotal - $discount_total, $order_args));
    }

    $note = $order->get_customer_note();
    if ($note) {
        $purchase_note = "*" . __('Note:', 'woocommerce') . "*" . "\r\n" . $note . "\n\n";
    } else {
        $purchase_note = "";
    }
    $total_tax = $order->get_total_tax();
    if ($options['include_tax'] === 'yes' && $total_tax > 0) {
        $formatted_total_tax = apply_filters('wa_order_filter_thank_you_page_tax', wa_order_plain_text(wc_price($total_tax, array('currency' => $order->get_currency()))));
        $tax_label           = apply_filters('wa_order_filter_thank_you_page_tax_label', $tax_label);
        $message            .= "\n*" . $tax_label . ":* " . $formatted_total_tax;
    }
    $total_price        = apply_filters('wa_order_filter_thank_you_page_total_price', wa_order_plain_text(wc_price($order->get_total(), array('currency' => $order->get_currency()))));
    $label_total        = apply_filters('wa_order_filter_thank_you_page_total_label', "*" . $total_label . ":*");
    $message            .= "\r\n" . $label_total . "\r\n" . $total_price;

    // Order Summary Link
    $order_summary_link  = apply_filters('wa_order_filter_thank_you_page_order_summary_link', $order->get_checkout_order_received_url());
    $order_summary_label = apply_filters('wa_order_filter_thank_you_page_order_summary_label', $order_summary_label);
    if ($include_order_summary === 'yes') {
        $message .= "\n\n*" . $order_summary_label . ":* \r\n" . $order_summary_link;
    }

    // Payment Link
    $payment_link       = apply_filters('wa_order_filter_thank_you_page_payment_link', $order->get_checkout_payment_url());
    $payment_link_label = apply_filters('wa_order_filter_thank_you_page_payment_link_label', $payment_link_label);
    if ($include_payment_link === 'yes') {
        $message .= "\n\n*" . $payment_link_label . ":* \r\n" . $payment_link;
    }

    // View Order Link
    $order_view_url     = apply_filters('wa_order_filter_thank_you_page_view_order_url', $order->get_view_order_url());
    $view_order_label   = apply_filters('wa_order_filter_thank_you_page_view_order_label', $view_order_label);
    if ($include_view_order === 'yes' && ($customer_id || !empty($order->get_billing_email()))) {
        $message .= "\n\n*" . $view_order_label . ":* \r\n" . $order_view_url;
    }

    // Order Date, Purchase Note & Thank You Message
    if ($options['order_date'] !== 'yes' || '' === $date) {
        $message .= "\n\n" . $purchase_note . $thanks_label;
    } else {
        $message .= "\n\n" . $purchase_note . $thanks_label . "\n\n(" . $date . ")";
    }
    $message            = apply_filters('wa_order_filter_thank_you_page_final_message', $message);
    // WhatsApp Button
    $button_url         = wa_order_the_url($phonenumb, $message);
    $target             = $options['open_new_tab'];
    // Final Output
    $final_output = '<div class="thankyoucustom_wrapper">
    <h2 class="thankyoutitle">' . esc_html($thetitle) . '</h2>
    <p class="subtitle">' . esc_html($subtitle) . '</p>
    <a id="sendbtn" href="' . wa_order_esc_whatsapp_url($button_url) . '" target="' . esc_attr($target) . '" rel="noopener" class="wa-order-thankyou">
        ' . esc_html($button) . '
    </a>
    </div>';
    return wp_kses_post($final_output);
}
if ($override_thankyou_page === 'yes') {
    add_filter('woocommerce_thankyou_order_received_text', 'wa_order_thank_you_override', 10, 2);
}

// Remove WooCommerce's now-empty "order received" paragraph on the order received page.
// Since 1.2.0: plain JavaScript (no jQuery dependency), printed only on that page,
// and it never removes an element that still holds the WhatsApp button or text.
function wa_order_remove_default_thankyou_class()
{
    if (get_option('wa_order_option_enable_button_thank_you') !== 'yes') {
        return;
    }
    if (!function_exists('is_order_received_page') || !is_order_received_page()) {
        return;
    }
?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            Array.prototype.forEach.call(document.querySelectorAll('.woocommerce-thankyou-order-received'), function(el) {
                if (el.querySelector('.thankyoucustom_wrapper') || el.textContent.trim() !== '') {
                    return;
                }
                el.parentNode.removeChild(el);
            });
        });
    </script>
<?php
}
// Remove element based on class
add_action('wp_footer', 'wa_order_remove_default_thankyou_class');
