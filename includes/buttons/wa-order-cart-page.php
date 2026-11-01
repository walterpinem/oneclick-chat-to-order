<?php
// Prevent direct access
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/**
 * OneClick Chat to Order Cart Page
 *
 * @package     OneClick Chat to Order
 * @author      Walter Pinem <hello@walterpinem.me>
 * @link        https://walterpinem.me/
 * @link        https://www.onlinestorekit.com/oneclick-chat-to-order/
 * @copyright   Copyright (c) 2019 - 2026, Walter Pinem | Online Store Kit
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * @category    Cart Page
 */

/**
 * Collect the cart settings, with the same filters as before 1.2.0.
 *
 * @since 1.2.0
 *
 * @return array
 */
function wa_order_get_cart_options()
{
	return array(
		'whatsapp_number' => apply_filters('wa_order_filter_whatsapp_number_cart', get_option('wa_order_selected_wa_number_cart', '')),
		'cart_button_text' => apply_filters('wa_order_filter_cart_button_text', get_option('wa_order_option_cart_button_text', 'Complete Order via WhatsApp')),
		'custom_message' => apply_filters('wa_order_filter_cart_custom_message', get_option('wa_order_option_cart_custom_message', 'Hello, I want to purchase the item(s) below:')),
		'price_label' => apply_filters('wa_order_filter_cart_price_label', get_option('wa_order_option_price_label')),
		'url_label' => apply_filters('wa_order_filter_cart_url_label', get_option('wa_order_option_url_label')),
		'thanks_label' => apply_filters('wa_order_filter_cart_thank_you_label', get_option('wa_order_option_thank_you_label', 'Thank You!')),
		'total_label' => apply_filters('wa_order_filter_cart_total_label', get_option('wa_order_option_total_amount_label', 'Total')),
		'target' => apply_filters('wa_order_filter_cart_target', get_option('wa_order_option_cart_open_new_tab')),
		'remove_product_url' => apply_filters('wa_order_filter_cart_remove_product_url', get_option('wa_order_option_cart_hide_product_url')),
		'quantity_label' => apply_filters('wa_order_filter_cart_quantity_label', get_option('wa_order_option_quantity_label', 'Quantity')),
		'include_variation' => apply_filters('wa_order_filter_cart_include_variation', get_option('wa_order_option_cart_enable_variations')),
		'include_tax' => apply_filters('wa_order_filter_cart_include_tax', get_option('wa_order_option_cart_include_tax')),
		'tax_label' => apply_filters('wa_order_filter_cart_tax_label', get_option('wa_order_option_tax_label', 'Tax')),
		'coupon_label' => apply_filters('wa_order_filter_cart_coupon_label', get_option('wa_order_option_custom_thank_you_coupon_label', 'Coupon')),
		// Since 1.2.0
		'exclude_price' => apply_filters('wa_order_filter_cart_exclude_price', get_option('wa_order_option_cart_exclude_price')),
		'include_item_data' => apply_filters('wa_order_filter_cart_include_item_data', get_option('wa_order_option_cart_include_item_data', 'yes')),
	);
}

/**
 * Extra cart item details added by other plugins (product add-ons, bookings,
 * custom fields), without the variation attributes, as plain-text lines.
 *
 * @since 1.2.0
 *
 * @param array $cart_item Cart item.
 * @return string[]
 */
function wa_order_get_cart_item_extra_lines($cart_item)
{
	$lines = array();
	$item_data = apply_filters('woocommerce_get_item_data', array(), $cart_item);
	if (!is_array($item_data)) {
		return $lines;
	}
	foreach ($item_data as $data) {
		if (!is_array($data)) {
			continue;
		}
		if (!empty($data['hidden'])) {
			continue;
		}
		$key = isset($data['key']) ? $data['key'] : (isset($data['name']) ? $data['name'] : '');
		$display = isset($data['display']) ? $data['display'] : (isset($data['value']) ? $data['value'] : '');
		$key = wa_order_plain_text($key);
		$display = wa_order_plain_text(is_scalar($display) ? (string) $display : '');
		if ('' === $key && '' === $display) {
			continue;
		}
		$lines[] = ('' !== $key) ? $key . ': ' . $display : $display;
	}
	return $lines;
}

/**
 * Build the plain-text WhatsApp message for the current cart.
 *
 * @since 1.2.0
 *
 * @param array $options Output of wa_order_get_cart_options().
 * @return string Empty when the cart is empty.
 */
function wa_order_build_cart_message($options)
{
	if (!function_exists('WC') || !WC()->cart || WC()->cart->is_empty()) {
		return '';
	}

	$cart          = WC()->cart;
	$exclude_price = ('yes' === $options['exclude_price']);
	$incl_tax      = $cart->display_prices_including_tax();
	$price_label   = !empty($options['price_label']) ? $options['price_label'] : 'Price';
	$url_label     = !empty($options['url_label']) ? $options['url_label'] : 'URL';

	$message = (string) $options['custom_message'];

	foreach ($cart->get_cart() as $item) {
		$_product = wc_get_product($item['product_id']);
		if (!$_product) {
			continue;
		}
		$product_name = apply_filters('wa_order_filter_cart_product_name', $_product->get_name(), $_product);
		$qty = $item['quantity'];
		$price = $incl_tax ? ($item['line_subtotal'] + $item['line_subtotal_tax']) : $item['line_subtotal'];
		$format_price = apply_filters('wa_order_filter_cart_price', wa_order_plain_text(wc_price($price)), $price, $_product);
		$product_url = apply_filters('wa_order_filter_cart_product_url', get_permalink($item['product_id']), $_product);

		$message .= "\r\n\r\n" . $qty . "x - *" . wa_order_plain_text($product_name) . "*";

		if (!empty($item['variation_id']) && $_product->is_type('variable') && $options['include_variation'] === 'yes') {
			$variations = wc_get_formatted_variation($item['variation'], false);
			$variation = str_replace(array('<dl class="variation"><dt>', "</dt><dd>", "</dd><dt>", "</dd></dl>"), array('', " ", "\r\n", ""), $variations);
			$message .= "\r\n" . ucwords(wa_order_plain_text($variation));
		}

		if ('yes' === $options['include_item_data']) {
			foreach (wa_order_get_cart_item_extra_lines($item) as $line) {
				$message .= "\r\n- " . $line;
			}
		}

		// Add price information
		if (!$exclude_price) {
			$message .= "\r\n*" . $price_label . ":* " . $format_price;
		}

		// Add URL if not removed
		if ($options['remove_product_url'] !== 'yes') {
			$message .= "\r\n*" . $url_label . ":* " . $product_url;
		}
	}

	// Subtotal
	if (!$exclude_price) {
		$subtotal_label = esc_html__('Subtotal:', 'woocommerce');
		$subtotal = apply_filters('wa_order_filter_cart_subtotal', $cart->get_cart_subtotal());
		$message .= "\r\n\r\n*" . $subtotal_label . "* " . wa_order_plain_text($subtotal);
	}

	// Shipping address, for logged-in customers or guests who entered an email.
	$customer_session = (WC()->session) ? WC()->session->get('customer') : null;
	$the_customer = $cart->get_customer();
	$is_shipping_applicable = $cart->needs_shipping();
	$calculated_shipping = is_array($customer_session) && !empty($customer_session['calculated_shipping']) && !empty($customer_session['address']) && !empty($customer_session['city']) && !empty($customer_session['state']);

	if ($the_customer && (is_user_logged_in() || !empty($the_customer->get_billing_email()))) {
		if (($is_shipping_applicable || $calculated_shipping) && $cart->show_shipping()) {
			$shipping_address = wa_order_get_shipping_address($the_customer);
			if ('' !== trim($shipping_address)) {
				$ship_label = __('Shipping:', 'woocommerce');
				$message .= "\n\n*" . $ship_label . "*\r\n";
				$message .= wa_order_plain_text($shipping_address) . "\r\n";
			}
		}
	} else {
		$message .= "\r\n";
	}

	// Shipping method details
	$shipping_package = WC()->session ? WC()->session->get('shipping_for_package_0') : null;
	$chosen_methods = WC()->session ? WC()->session->get('chosen_shipping_methods') : null;
	if (!empty($chosen_methods) && !empty($shipping_package) && isset($shipping_package['rates'])) {
		$chosen_method_id = $chosen_methods[0];
		$available_rates = $shipping_package['rates'];
		if (!empty($chosen_method_id) && isset($available_rates[$chosen_method_id])) {
			$rate = $available_rates[$chosen_method_id];
			$shipping_method_name = ucwords($rate->label);
			$message .= "\r\n*" . __('Shipping Method', 'woocommerce') . ":*\r\n" . $shipping_method_name;
			if (!$exclude_price) {
				$shipping_cost = (float) $rate->cost + ($incl_tax ? array_sum((array) $rate->taxes) : 0);
				$message .= " - " . wa_order_plain_text(wc_price($shipping_cost));
			}
		}
	}

	// Coupons: use the discount WooCommerce actually applied to each code.
	$coupon_codes = $cart->get_applied_coupons();
	if (!empty($coupon_codes)) {
		$coupon_label = $options['coupon_label'];
		$voucher_label = empty($coupon_label) ? 'Voucher Code:' : $coupon_label;
		$coupon_lines = array();
		$total_discount = 0.0;

		foreach ($coupon_codes as $coupon_code) {
			$coupon = new WC_Coupon($coupon_code);
			$amount = (float) $cart->get_coupon_discount_amount($coupon->get_code(), !$incl_tax);
			$total_discount += $amount;
			$line = strtoupper($coupon->get_code());
			if (!$exclude_price) {
				$amount_text = wa_order_plain_text(wc_price($amount));
				if ($coupon->is_type('percent')) {
					$line .= ": -" . wc_format_decimal($coupon->get_amount(), '') . "% (-" . $amount_text . ")";
				} else {
					$line .= ": -" . $amount_text;
				}
			}
			$coupon_lines[] = $line;
		}

		$message .= "\r\n\r\n*" . $voucher_label . "*\r\n" . implode("\r\n", $coupon_lines) . "\r\n";

		if (!$exclude_price && $total_discount > 0) {
			$numeric_subtotal = (float) $cart->get_subtotal() + ($incl_tax ? (float) $cart->get_subtotal_tax() : 0);
			$message .= "*" . __('Discount', 'oneclick-whatsapp-order') . ":* \r\n" . wa_order_plain_text(wc_price($numeric_subtotal)) . " - " . wa_order_plain_text(wc_price($total_discount)) . " = " . wa_order_plain_text(wc_price($numeric_subtotal - $total_discount));
		}
	}

	// Tax
	if (!$exclude_price && $options['include_tax'] == 'yes') {
		$tax_label = !empty($options['tax_label']) ? $options['tax_label'] : 'Tax';
		$message .= "\r\n*" . $tax_label . ":* " . wa_order_plain_text(wc_price($cart->get_total_tax()));
	}

	// Total
	if (!$exclude_price) {
		$message .= "\r\n*" . $options['total_label'] . ":* " . wa_order_plain_text($cart->get_total());
	}
	if ('' !== (string) $options['thanks_label']) {
		$message .= "\r\n\r\n" . $options['thanks_label'];
	}

	return $message;
}

// Start the function to show WhatsApp button on Cart page
// Reworked in 1.2.0: prices follow the tax display setting, coupons use the
// discounts WooCommerce applied, and no HTML reaches the message.
function wa_order_add_button_to_cart_page()
{
	$options = wa_order_get_cart_options();

	$phonenumb = wa_order_get_number_phone($options['whatsapp_number']);
	// Fall back to the Basic tab number when the cart button is switched on but has no number.
	if ('' === $phonenumb && 'yes' === get_option('wa_order_option_add_button_to_cart')) {
		$phonenumb = wa_order_get_number_phone(get_option('wa_order_selected_wa_number_single_product', ''));
	}
	$phonenumb = apply_filters('wa_order_filter_cart_phone_number', $phonenumb);
	if (!$phonenumb) {
		return;
	}

	$message = wa_order_build_cart_message($options);
	if ('' === $message) {
		return;
	}

	$button_url = apply_filters('wa_order_filter_cart_button_url', wa_order_the_url($phonenumb, $message), $phonenumb, urlencode($message));
	$cart_button_text = apply_filters('wa_order_filter_cart_button_text_final', $options['cart_button_text']);
	if ('' === trim((string) $cart_button_text)) {
		$cart_button_text = __('Complete Order via WhatsApp', 'oneclick-whatsapp-order');
	}
	$target = apply_filters('wa_order_filter_cart_button_target', $options['target']);
?>
	<div class="wc-proceed-to-checkout">
		<a id="sendbtn" href="<?php echo esc_url($button_url); ?>" target="<?php echo esc_attr($target); ?>" rel="noopener" class="wa-order-checkout checkout-button button wa-cart-button">
			<?php echo esc_html($cart_button_text); ?>
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

// ── Classic Cart Support ──
// Hook for the classic WooCommerce cart shortcode ([woocommerce_cart]).
// These hooks only fire when the Cart page uses the legacy shortcode widget.
if (get_option('wa_order_option_add_button_to_cart', 'yes') === 'yes') {
	add_action('woocommerce_after_cart_totals', 'wa_order_add_button_to_cart_page', 1);
}

// Hide the Proceed to Checkout button (Classic Cart)
function wa_order_remove_proceed_to_checkout_button()
{
	$hide_checkout_button = apply_filters('wa_order_filter_cart_hide_checkout_button', get_option('wa_order_option_cart_hide_checkout'));
	if ($hide_checkout_button === 'yes') {
		remove_action('woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20);
	}
}
add_action('woocommerce_before_cart', 'wa_order_remove_proceed_to_checkout_button', 1);

// ── WooCommerce Block Cart Support ──
// The Block Cart is rendered by React and updates through the Store API without
// reloading, so the button is injected with JavaScript, kept in place when React
// re-renders, and refreshed from the server whenever the cart changes.
// @since 1.1.1, reworked in 1.2.0

/**
 * Whether the Cart page is built with the Cart block.
 *
 * @since 1.2.0
 *
 * @return bool
 */
function wa_order_is_block_cart_page()
{
	if (!function_exists('is_cart') || !is_cart()) {
		return false;
	}
	$cart_page_id = function_exists('wc_get_page_id') ? wc_get_page_id('cart') : 0;
	$post_id = get_queried_object_id() ? get_queried_object_id() : $cart_page_id;
	return has_block('woocommerce/cart', $post_id) || ($cart_page_id > 0 && has_block('woocommerce/cart', $cart_page_id));
}

/**
 * wc-ajax endpoint returning the current cart button HTML.
 * WooCommerce loads the visitor's session and cart for wc-ajax requests.
 *
 * @since 1.2.0
 */
function wa_order_ajax_cart_button()
{
	if (!function_exists('WC') || !WC()->cart) {
		wp_send_json_error();
	}
	WC()->cart->calculate_totals();
	ob_start();
	wa_order_add_button_to_cart_page();
	wp_send_json_success(array('html' => ob_get_clean()));
}
add_action('wc_ajax_wa_order_cart_button', 'wa_order_ajax_cart_button');

/**
 * Inject and maintain the WhatsApp button on the Block Cart.
 *
 * @since 1.1.1
 */
function wa_order_cart_block_footer_script()
{
	if (!wa_order_is_block_cart_page()) {
		return;
	}

	// Capture the WhatsApp button HTML
	ob_start();
	wa_order_add_button_to_cart_page();
	$wa_button_html = ob_get_clean();

	if (empty(trim($wa_button_html))) {
		return;
	}

	$encoded_html = wp_json_encode($wa_button_html);
	$endpoint = class_exists('WC_AJAX') ? WC_AJAX::get_endpoint('wa_order_cart_button') : '';
	if (!$encoded_html) {
		return;
	}
?>
	<script>
	(function() {
		var waButtonHTML = <?php echo $encoded_html; // phpcs:ignore WordPress.Security.EscapeOutput ?>;
		var endpoint = <?php echo wp_json_encode($endpoint); ?>;
		var waButtonId = 'wa-order-block-cart-button';
		var refreshTimer = null;
		var pending = false;
		var waiting = [];
		var lastKey = null;

		function injectWAButton() {
			if (!waButtonHTML) {
				var stale = document.getElementById(waButtonId);
				if (stale) { stale.parentNode.removeChild(stale); }
				return true;
			}
			if (document.getElementById(waButtonId)) {
				return true;
			}
			var checkoutBtn = document.querySelector('.wc-block-cart__submit-button') || document.querySelector('.wp-block-woocommerce-proceed-to-checkout-block');
			if (!checkoutBtn) {
				return false;
			}
			var container = checkoutBtn.closest('.wc-block-cart__submit-container') || checkoutBtn.parentElement;
			if (!container || !container.parentElement) {
				return false;
			}
			var wrapper = document.createElement('div');
			wrapper.id = waButtonId;
			wrapper.innerHTML = waButtonHTML;
			container.parentElement.insertBefore(wrapper, container.nextSibling);
			return true;
		}

		// Keep the button in place: the Cart block re-renders its sidebar when
		// the cart changes and when it switches to its mobile layout.
		function watch() {
			var root = document.querySelector('.wp-block-woocommerce-cart') || document.body;
			var scheduled = false;
			new MutationObserver(function() {
				if (scheduled) { return; }
				scheduled = true;
				window.requestAnimationFrame(function() {
					scheduled = false;
					injectWAButton();
				});
			}).observe(root, { childList: true, subtree: true });
			injectWAButton();
		}

		function finishRefresh() {
			pending = false;
			var link = document.querySelector('#' + waButtonId + ' a.wa-cart-button');
			var callbacks = waiting;
			waiting = [];
			callbacks.forEach(function(cb) { cb(link); });
		}

		function refresh() {
			if (!endpoint || !window.fetch) { pending = false; return; }
			window.fetch(endpoint, { credentials: 'same-origin' })
				.then(function(response) { return response.json(); })
				.then(function(result) {
					if (result && result.success && result.data) {
						waButtonHTML = result.data.html || '';
						var wrapper = document.getElementById(waButtonId);
						if (wrapper && waButtonHTML) {
							wrapper.innerHTML = waButtonHTML;
						} else {
							injectWAButton();
						}
					}
					finishRefresh();
				})
				.catch(finishRefresh);
		}

		function cartKey() {
			try {
				var cart = window.wp.data.select('wc/store/cart').getCartData();
				if (!cart || !cart.items) { return null; }
				return JSON.stringify([
					cart.items.map(function(item) { return [item.key, item.quantity]; }),
					cart.totals ? cart.totals.total_price : null,
					(cart.coupons || []).map(function(c) { return c.code; }),
					(cart.shippingRates || []).map(function(pkg) {
						return (pkg.shipping_rates || []).filter(function(r) { return r.selected; }).map(function(r) { return r.rate_id; });
					}),
					cart.shippingAddress || null
				]);
			} catch (e) {
				return null;
			}
		}

		function listenForCartChanges() {
			if (!window.wp || !window.wp.data || !window.wp.data.subscribe) { return; }
			lastKey = cartKey();
			window.wp.data.subscribe(function() {
				var key = cartKey();
				if (key && key !== lastKey) {
					lastKey = key;
					pending = true;
					clearTimeout(refreshTimer);
					refreshTimer = setTimeout(refresh, 300);
				}
			});
		}

		// If the customer clicks while a refresh is still running, wait for the
		// fresh link instead of sending the old cart.
		document.addEventListener('click', function(e) {
			var link = e.target.closest ? e.target.closest('#' + waButtonId + ' a.wa-cart-button') : null;
			if (!link || !pending) { return; }
			e.preventDefault();
			var newTab = link.getAttribute('target') === '_blank';
			var win = newTab ? window.open('', '_blank') : null;
			waiting.push(function(freshLink) {
				var href = freshLink ? freshLink.getAttribute('href') : link.getAttribute('href');
				if (win) { win.location.href = href; } else { window.location.href = href; }
			});
		}, true);

		function start() {
			watch();
			listenForCartChanges();
		}

		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', start);
		} else {
			start();
		}
	})();
	</script>
	<?php
}

if (get_option('wa_order_option_add_button_to_cart', 'yes') === 'yes') {
	add_action('wp_footer', 'wa_order_cart_block_footer_script');
}

/**
 * Hide the Proceed to Checkout button on the WooCommerce Block Cart.
 *
 * Outputs CSS via wp_head that hides the Block Cart's checkout button
 * (`.wc-block-cart__submit-button`) when the "Hide Proceed to Checkout button?"
 * setting is enabled. This complements the Classic Cart's remove_action() approach.
 *
 * @since 1.1.1
 */
function wa_order_cart_block_hide_checkout_button()
{
	// Only output on the Cart page
	if (!function_exists('is_cart') || !is_cart()) {
		return;
	}

	$hide_checkout_button = apply_filters(
		'wa_order_filter_cart_hide_checkout_button',
		get_option('wa_order_option_cart_hide_checkout')
	);

	if ($hide_checkout_button === 'yes') {
?>
		<style>
			/* Hide Block Cart Proceed to Checkout button — OneClick Chat to Order */
			.wc-block-cart__submit-button:not(.wa-cart-button),
			.wp-block-woocommerce-proceed-to-checkout-block > .wc-block-components-checkout-place-order-button,
			.wp-block-woocommerce-proceed-to-checkout-block > a.wc-block-cart__submit-button {
				display: none !important;
			}
		</style>
		<?php
	}
}
add_action('wp_head', 'wa_order_cart_block_hide_checkout_button');
