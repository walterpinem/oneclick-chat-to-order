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
 * @copyright   Copyright (c) 2019 - 2025, Walter Pinem | Online Store Kit
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * @category    Cart Page
 *
 ********************************* Cart Page ********************************* */

// Additional safety check for WordPress functions
if (!function_exists('add_action') || !function_exists('get_option')) {
	// Try to load WordPress if not already loaded
	if (!defined('WP_CONTENT_DIR')) {
		// Find WordPress root directory
		$wp_root = dirname(dirname(dirname(dirname(__FILE__))));
		if (file_exists($wp_root . '/wp-load.php')) {
			require_once($wp_root . '/wp-load.php');
		}
	}

	// Final check - if still not available, exit gracefully
	if (!function_exists('add_action')) {
		// Use basic PHP error instead of wp_die since WordPress might not be loaded
		if (function_exists('wp_die')) {
			wp_die(
				'<h1>Error</h1><p>WordPress functions are not available. Please ensure WordPress is properly loaded.</p>',
				'WordPress Loading Error',
				array('response' => 500)
			);
		} else {
			// Fallback for when wp_die is not available
			header('HTTP/1.1 500 Internal Server Error');
			echo '<h1>Error</h1><p>WordPress functions are not available. Please ensure WordPress is properly loaded.</p>';
			exit;
		}
	}
}

/**
 * OneClick Chat to Order Cart Page
 *
 * @package     OneClick Chat to Order
 * @author      Walter Pinem <hello@walterpinem.me>
 * @link        https://walterpinem.me/
 * @link        https://www.onlinestorekit.com/oneclick-chat-to-order/
 * @copyright   Copyright (c) 2019 - 2025, Walter Pinem | Online Store Kit
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * @category    Cart Page
 */

// Start the function to show WhatsApp button on Cart page
function wa_order_add_button_to_cart_page()
{
	// Cart functionality handled by WC()->cart calls below
	// Load the setting values
	$options = array(
		'whatsapp_number'     => apply_filters('wa_order_filter_whatsapp_number_cart', get_option('wa_order_selected_wa_number_cart', '')),
		'cart_button_text'    => apply_filters('wa_order_filter_cart_button_text', get_option('wa_order_option_cart_button_text', 'Complete Order via WhatsApp')),
		'custom_message'      => apply_filters('wa_order_filter_cart_custom_message', get_option('wa_order_option_cart_custom_message', 'Hello, I want to purchase the item(s) below:')),
		'price_label'         => apply_filters('wa_order_filter_cart_price_label', get_option('wa_order_option_price_label')),
		'url_label'           => apply_filters('wa_order_filter_cart_url_label', get_option('wa_order_option_url_label')),
		'thanks_label'        => apply_filters('wa_order_filter_cart_thank_you_label', get_option('wa_order_option_thank_you_label', 'Thank You!')),
		'total_label'         => apply_filters('wa_order_filter_cart_total_label', get_option('wa_order_option_total_amount_label', 'Total')),
		'target'              => apply_filters('wa_order_filter_cart_target', get_option('wa_order_option_cart_open_new_tab')),
		'remove_product_url'  => apply_filters('wa_order_filter_cart_remove_product_url', get_option('wa_order_option_cart_hide_product_url')),
		'quantity_label'      => apply_filters('wa_order_filter_cart_quantity_label', get_option('wa_order_option_quantity_label', 'Quantity')),
		'include_variation'   => apply_filters('wa_order_filter_cart_include_variation', get_option('wa_order_option_cart_enable_variations')),
		'include_tax'         => apply_filters('wa_order_filter_cart_include_tax', get_option('wa_order_option_cart_include_tax')),
		'tax_label'           => apply_filters('wa_order_filter_cart_tax_label', get_option('wa_order_option_tax_label', 'Tax')),
		'coupon_label'        => apply_filters('wa_order_filter_cart_coupon_label', get_option('wa_order_option_custom_thank_you_coupon_label', 'Coupon')),
	);

	$whatsapp_number	= $options['whatsapp_number'];
	$postid				= get_page_by_path($whatsapp_number, OBJECT, 'wa-order-numbers');
	if (!$postid) {
		return;
	}
	$phonenumb = apply_filters('wa_order_filter_cart_phone_number', get_post_meta($postid->ID, 'wa_order_phone_number_input', true));
	if (!$phonenumb) {
		return;
	}
	$items				= WC()->cart->get_cart();
	$cart_button_text	= $options['cart_button_text'];
	$custom_message		= $options['custom_message'];
	$message			= urlencode($custom_message);
	// Currency and quantity label handled inline where needed
	foreach ($items as $item) {
		$_product		= wc_get_product($item['product_id']);
		$product_name	= apply_filters('wa_order_filter_cart_product_name', $_product->get_name(), $_product);
		$qty			= $item['quantity'];
		$price			= $item['line_subtotal'];
		$format_price	= apply_filters('wa_order_filter_cart_price', html_entity_decode(wp_strip_all_tags(wc_price($price))), $price, $_product);
		$product_url	= apply_filters('wa_order_filter_cart_product_url', get_post_permalink($item['product_id']), $_product);
		$total_amount	= wc_price(WC()->cart->get_cart_total());
		$price_label	= !empty($options['price_label']) ? $options['price_label'] : 'Price';
		$url_label		= !empty($options['url_label']) ? $options['url_label'] : 'URL';
		$thanks_label	= $options['thanks_label'];
		$total_label	= $options['total_label'];
		$target			= $options['target'];
		$removeproductURL = $options['remove_product_url'];
		$message		.= urlencode("\r\n\r\n" . $qty . "x - *" . $product_name . "*");
		$include_variation = $options['include_variation'];
		if ($item['variation_id'] > 0 && $_product->is_type('variable') && $include_variation === 'yes') {
			$variations = wc_get_formatted_variation($item['variation'], false);
			$variation = str_replace(array('<dl class="variation"><dt>', "</dt><dd>", "</dd><dt>", "</dd></dl>"), array('', " ", "\r\n", ""), $variations);
			$message .= urlencode("\r\n" . ucwords($variation) . "");
		} else {
			$message .= "";
		}
		// Add price information
		$message .= urlencode("\r\n*" . $price_label . ":* " . $format_price);

		// Add URL if not removed
		if ($removeproductURL !== 'yes') {
			$message .= urlencode("\r\n*" . $url_label . ":* " . $product_url);
		}
	}
	// Subtotal
	$subtotal_label = esc_html__('Subtotal:', 'woocommerce');
	$subtotal		= apply_filters('wa_order_filter_cart_subtotal', WC()->cart->get_cart_subtotal());
	$message		.= urlencode("\r\n\r\n*" . $subtotal_label . "* " . html_entity_decode(wp_strip_all_tags($subtotal)) . "");

	// Check if the cart contains non-virtual, non-downloadable products and shipping is calculated
	$customer = WC()->session->get('customer');
	$the_customer = WC()->cart->get_customer();
	$is_shipping_applicable = false;

	foreach (WC()->cart->get_cart() as $cart_item) {
		if (!$cart_item['data']->is_virtual() && !$cart_item['data']->is_downloadable()) {
			$is_shipping_applicable = true;
			break;
		}
	}

	// Check whether a customer is logged in and has an existing account
	if (is_user_logged_in() || !empty($the_customer->get_billing_email())) {
		// Shipping details
		if ($is_shipping_applicable || $customer['calculated_shipping'] && !empty($customer['address']) && !empty($customer['city']) && !empty($customer['state'])) {

			if (WC()->cart->show_shipping()) {
				$shipping_address	= wa_order_get_shipping_address(WC()->cart->get_customer());
				$address			= html_entity_decode($shipping_address);
				$ship_label			= __('Shipping:', 'woocommerce');
				$message			.= urlencode("\n\n*" . $ship_label . "*\r\n");
				$message			.= urlencode("" . $address . "\r\n");
			}
		}
	} else {
		$message			.= urlencode("\r\n");
	}
	// Shipping method details
	$shipping_package = WC()->session->get('shipping_for_package_0'); // Check if shipping rates exist
	if (!empty(WC()->session->get('chosen_shipping_methods')) && !empty($shipping_package)) {
		$chosen_method_id = WC()->session->get('chosen_shipping_methods')[0];
		$available_rates = $shipping_package['rates'];
		// Proceed only if there is a chosen method and it's available in rates
		if (!empty($chosen_method_id) && isset($available_rates[$chosen_method_id])) {
			$rate = $available_rates[$chosen_method_id];
			$shipping_method_name = ucwords($rate->label);
			$shipping_cost = wc_price($rate->cost + array_sum($rate->taxes));
			// Format and append to message
			$message .= urlencode("\r\n*" . __('Shipping Method', 'woocommerce') . ":*\r\n" . $shipping_method_name . " - " . html_entity_decode(wp_strip_all_tags($shipping_cost)));
		}
	}

	$coupons = WC()->cart->get_applied_coupons();
	foreach ($coupons as $coupon_code) {
		$coupon = new WC_Coupon($coupon_code);

		if (WC()->cart->has_discount($coupon->get_code())) {
			$coupon_label = $options['coupon_label'];
			$voucher_label = empty($coupon_label) ? "Voucher Code:" : $coupon_label;

			// For Fixed Cart or Fixed Product Discounts
			if ($coupon->is_type('fixed_cart') || $coupon->is_type('fixed_product')) {
				// Get individual discount amount
				$indv_discount = wc_price($coupon->get_amount());
				$discount_format = html_entity_decode(wp_strip_all_tags($indv_discount));

				// Prepare coupon message
				$coupon_message = "*" . $voucher_label . "*\r\n" . strtoupper($coupon->get_code()) . ": -" . $discount_format;
				$message .= urlencode("\r\n\r\n" . $coupon_message . "\r\n");

				// Calculate subtotal after discount
				$numeric_subtotal = WC()->cart->subtotal_ex_tax;
				$numeric_discount = floatval($coupon->get_amount());
				$subtotal_minus_discount = $numeric_subtotal - $numeric_discount;

				// Format subtotal after discount
				$subtotal_minus_discount_formatted = wc_price($subtotal_minus_discount);
				$subtotal_message = html_entity_decode(wp_strip_all_tags($subtotal_minus_discount_formatted));
				$message .= urlencode("*" . __('Discount', 'oneclick-wa-order') . ":* \r\n" . html_entity_decode(wp_strip_all_tags(wc_price($numeric_subtotal))) . " - " . $discount_format . " = " . $subtotal_message);

				// For Percentage Discounts
			} elseif ($coupon->is_type('percent')) {
				$discount_percent = $coupon->get_amount(); // Get percentage
				$numeric_subtotal = WC()->cart->subtotal_ex_tax;

				// Calculate discount amount
				$discount_amount = ($discount_percent / 100) * $numeric_subtotal;
				$discount_format = wc_price($discount_amount);

				// Calculate subtotal after applying discount
				$subtotal_minus_discount = $numeric_subtotal - $discount_amount;
				$subtotal_minus_discount_formatted = wc_price($subtotal_minus_discount);

				// Prepare coupon message
				$coupon_message = "*" . $voucher_label . "*\r\n" . strtoupper($coupon->get_code()) . ": -" . $discount_percent . "% (-" . $discount_format . ")";
				$message .= urlencode("\r\n\r\n" . $coupon_message . "\r\n");

				// Format and add the subtotal message
				$message .= urlencode("*" . __('Discount', 'oneclick-wa-order') . ":* \r\n" . html_entity_decode(wp_strip_all_tags(wc_price($numeric_subtotal))) . " - " . $discount_format . " = " . $subtotal_minus_discount_formatted);
			}
		}
	}

	// Tax
	if ($options['include_tax'] == 'yes') {
		$tax = WC()->cart->get_total_tax();
		$tax_label = !empty($options['tax_label']) ? $options['tax_label'] : 'Tax';
		$formatted_tax = wc_price($tax);
		$message .= urlencode("\r\n*" . $tax_label . ":* " . html_entity_decode(wp_strip_all_tags($formatted_tax)));
	}

	// Total
	$total_amount = wp_kses_data(WC()->cart->get_total());
	$message .= urlencode("\r\n*" . $total_label . ":* " . html_entity_decode($total_amount));
	$message .= urlencode("\r\n\r\n" . $thanks_label);
	$button_url			 = apply_filters('wa_order_filter_cart_button_url', wa_order_the_url($phonenumb, urldecode($message)), $phonenumb, $message); // phpcs:ignore WordPress.Security.EscapeOutput.
	$cart_button_text	 = apply_filters('wa_order_filter_cart_button_text_final', $options['cart_button_text']);
	$target				 = apply_filters('wa_order_filter_cart_button_target', $options['target']);
?>
	<div class="wc-proceed-to-checkout">
		<a id="sendbtn" href="<?php echo $button_url;  // phpcs:ignore WordPress.Security.
								?>" target="<?php echo esc_attr($target); ?>" class="wa-order-checkout checkout-button button wa-cart-button">
			<?php echo esc_html($cart_button_text);  ?>
		</a>
	</div>

	<!-- Ensure cart button is always visible and fullwidth like original -->
	<style>
		.wa-cart-button {
			display: block !important;
			width: 100% !important;
			text-align: center !important;
		}

		/* Override any global hide rules that might affect cart button */
		.wc-proceed-to-checkout .wa-cart-button {
			display: block !important;
			width: 100% !important;
		}

		/* Ensure the container allows fullwidth */
		.wc-proceed-to-checkout {
			width: 100% !important;
		}
	</style>
<?php
}
// Add cart button - keep original single button approach
if (get_option('wa_order_option_add_button_to_cart', 'yes') === 'yes') {
	add_action('woocommerce_after_cart_totals', 'wa_order_add_button_to_cart_page', 1);
}

// Hide the Proceed to Checkout button
function wa_order_remove_proceed_to_checkout_button()
{
	$hide_checkout_button = apply_filters('wa_order_filter_cart_hide_checkout_button', get_option('wa_order_option_cart_hide_checkout'));
	if ($hide_checkout_button === 'yes') {
		remove_action('woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20);
	}
}
add_action('woocommerce_before_cart', 'wa_order_remove_proceed_to_checkout_button', 1);
