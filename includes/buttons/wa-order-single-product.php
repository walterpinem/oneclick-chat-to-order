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
 * @category    Single Product Page
 *
 ********************************* Single Product Page ********************************* */

// Default button position single product page (kept for backward compatibility)
$wa_order_position = get_option('wa_order_single_product_button_position', 'after_atc');

/**
 * Map a "Button Position" setting to its WooCommerce hook.
 *
 * @since 1.2.0
 *
 * @param string $position Position setting value.
 * @return string Hook name.
 */
function wa_order_single_button_position_hook($position)
{
	$hooks = array(
		'after_atc'                    => 'woocommerce_after_add_to_cart_button',
		'under_atc'                    => 'woocommerce_after_add_to_cart_form',
		'after_shortdesc'              => 'woocommerce_before_add_to_cart_form',
		'after_single_product_summary' => 'woocommerce_after_single_product_summary',
		'around_share_area'            => 'woocommerce_share',
	);
	return isset($hooks[$position]) ? $hooks[$position] : 'woocommerce_after_add_to_cart_button';
}

/**
 * Where the single product button is currently attached.
 *
 * @since 1.2.0
 *
 * @return array { hook: string, priority: int }
 */
function wa_order_get_single_button_placement()
{
	if (empty($GLOBALS['wa_order_single_button_placement'])) {
		$position = get_option('wa_order_single_product_button_position', 'after_atc');
		$GLOBALS['wa_order_single_button_placement'] = array(
			'hook'     => wa_order_single_button_position_hook($position),
			'priority' => 5,
		);
	}
	return $GLOBALS['wa_order_single_button_placement'];
}

/**
 * Product IDs whose single product button has already been printed.
 *
 * @since 1.2.0
 */
$GLOBALS['wa_order_single_rendered'] = array();

/**
 * Whether the single product button should be hidden for a product:
 * the per-product "Hide WhatsApp button?" box, or the excluded categories and tags.
 *
 * Checked inside the render function, so it also applies when the button is
 * moved to another hook or priority.
 *
 * @since 1.2.0
 *
 * @param int $product_id Product ID.
 * @return bool
 */
function wa_order_single_button_is_hidden($product_id)
{
	if ('yes' === get_post_meta($product_id, '_hide_wa_button', true)) {
		return true;
	}

	$option_cats = array_filter((array) get_option('wa_order_option_exlude_single_product_cats', array()));
	if (!empty($option_cats) && has_term($option_cats, 'product_cat', $product_id)) {
		return true;
	}

	$option_tags = array_filter((array) get_option('wa_order_option_exlude_single_product_tags', array()));
	if (!empty($option_tags) && has_term($option_tags, 'product_tag', $product_id)) {
		return true;
	}

	return false;
}

/**
 * Build the plain-text price parts for a product, following the store's
 * "Display prices in the shop" tax setting.
 *
 * @since 1.2.0
 *
 * @param WC_Product $product Product.
 * @return array { price: string HTML, regular: string HTML, sale: string HTML, on_sale: bool }
 */
function wa_order_get_product_price_parts($product)
{
	$parts = array('price' => '', 'regular' => '', 'sale' => '', 'on_sale' => false);

	if ($product->is_type('grouped')) {
		$children = array_filter(array_map('wc_get_product', $product->get_children()), 'wc_products_array_filter_visible_grouped');
		$child_prices = array();
		foreach ($children as $child) {
			if ('' !== $child->get_price()) {
				$child_prices[] = wc_get_price_to_display($child);
			}
		}
		if (!empty($child_prices)) {
			$min_price = min($child_prices);
			$max_price = max($child_prices);
			$parts['price'] = ($min_price !== $max_price) ? wc_price($min_price) . ' – ' . wc_price($max_price) : wc_price($min_price);
		}
		$parts['regular'] = $parts['price'];
		return $parts;
	}

	if ($product->is_type('variable')) {
		$min_price = $product->get_variation_price('min', true);
		$max_price = $product->get_variation_price('max', true);
		// WooCommerce returns false when no variation has a price.
		if (is_numeric($min_price)) {
			$parts['price'] = ((float) $min_price !== (float) $max_price) ? wc_price($min_price) . ' – ' . wc_price($max_price) : wc_price($min_price);
		}
		$min_regular = $product->get_variation_regular_price('min', true);
		$min_sale    = $product->get_variation_sale_price('min', true);
		$parts['regular'] = is_numeric($min_regular) ? wc_price($min_regular) : $parts['price'];
		$parts['sale']    = is_numeric($min_sale) ? wc_price($min_sale) : '';
		$parts['on_sale'] = $product->is_on_sale() && '' !== $parts['sale'] && (float) $min_sale < (float) $min_regular;
		return $parts;
	}

	if ('' !== $product->get_price()) {
		$parts['price'] = wc_price(wc_get_price_to_display($product));
	}
	if ('' !== $product->get_regular_price()) {
		$parts['regular'] = wc_price(wc_get_price_to_display($product, array('price' => $product->get_regular_price())));
	}
	if ('' !== $product->get_sale_price()) {
		$parts['sale'] = wc_price(wc_get_price_to_display($product, array('price' => $product->get_sale_price())));
	}
	$parts['on_sale'] = $product->is_on_sale() && '' !== $parts['sale'];

	return $parts;
}

/**
 * Print an inline script that wires one GDPR checkbox to its own buttons.
 *
 * Avoids "<" and "&" so the script survives wp_kses() unchanged.
 *
 * @since 1.2.0
 *
 * @param string $checkbox_id ID of the GDPR checkbox.
 * @return string Script tag.
 */
function wa_order_gdpr_group_script($checkbox_id)
{
	return '<script type="text/javascript">(function(){var c=document.getElementById(' . wp_json_encode($checkbox_id) . ');if(!c){return;}var g=c.getAttribute("data-wa-gdpr-group");var each=function(sel,fn){Array.prototype.forEach.call(document.querySelectorAll(sel),fn);};var sync=function(){each("button[data-wa-gdpr-group=\'"+g+"\']",function(b){b.disabled=!c.checked;});each("a[data-wa-gdpr-group=\'"+g+"\']",function(a){a.setAttribute("aria-disabled",c.checked?"false":"true");});};c.addEventListener("change",sync);each("a[data-wa-gdpr-group=\'"+g+"\']",function(a){a.addEventListener("click",function(e){if(!c.checked){e.preventDefault();e.stopPropagation();}},true);});sync();})();</script>';
}

/**
 * Next GDPR group number and checkbox ID. The first checkbox on a page keeps
 * the historic "gdprChkbx" ID so existing custom CSS still matches.
 *
 * @since 1.2.0
 *
 * @return array { group: int, id: string }
 */
function wa_order_next_gdpr_group()
{
	static $count = 0;
	$count++;
	return array(
		'group' => $count,
		'id'    => (1 === $count) ? 'gdprChkbx' : 'gdprChkbx-' . $count,
	);
}

// Start processing the WhatsApp button
// Revamped in version 1.0.5, reworked in 1.2.0
function wa_order_add_button_plugin()
{
	$enable_wa_button = apply_filters('wa_order_filter_enable_single_product', get_option('wa_order_option_enable_single_product'));
	// Check if the button should be displayed
	if ($enable_wa_button !== 'yes') {
		return;
	}

	global $product;
	$the_product = ($product instanceof WC_Product) ? $product : wc_get_product(get_the_ID());
	if (!$the_product) {
		return;
	}
	$product_id = $the_product->get_id();

	if (wa_order_single_button_is_hidden($product_id)) {
		return;
	}

	// Fetch phone number
	$phone = apply_filters('wa_order_filter_phone_number', wa_order_get_phone_number($product_id), $product_id);
	if (!$phone) {
		return; // Exit if no phone number is set
	}

	// Product details
	$product_url = apply_filters('wa_order_filter_product_url', get_permalink($product_id), $the_product);
	$title = apply_filters('wa_order_filter_product_title', $the_product->get_name(), $the_product);

	$price_parts = wa_order_get_product_price_parts($the_product);
	$price = apply_filters('wa_order_filter_product_price', $price_parts['price'], $the_product);
	$regular_price = apply_filters('wa_order_filter_product_regular_price', $price_parts['regular'], $the_product);
	$sale_price = apply_filters('wa_order_filter_product_sale_price', $price_parts['sale'], $the_product);
	$decoded_price = wa_order_plain_text($price);
	$decoded_regular_price = wa_order_plain_text($regular_price);
	$decoded_sale_price = wa_order_plain_text($sale_price);

	// Settings
	$button_text = apply_filters('wa_order_filter_button_text', get_post_meta($product_id, '_wa_order_button_text', true) ?: get_option('wa_order_option_text_button', 'Buy via WhatsApp'), $the_product);
	if ('' === trim((string) $button_text)) {
		$button_text = __('Buy via WhatsApp', 'oneclick-whatsapp-order');
	}
	$target = apply_filters('wa_order_filter_button_target', get_option('wa_order_option_target', '_blank'));
	$gdpr_status = apply_filters('wa_order_filter_gdpr_status', get_option('wa_order_gdpr_status_enable', 'no'));
	$gdpr_message = apply_filters('wa_order_filter_gdpr_message', do_shortcode(stripslashes(get_option('wa_order_gdpr_message', ''))));

	// Message text: the product's own custom message wins over the global one.
	$message_text = get_post_meta($product_id, '_wa_order_custom_message', true);
	if ('' === trim((string) $message_text)) {
		$message_text = get_option('wa_order_option_message', 'Hello, I want to buy:');
	}

	// URL Encoding (the filters below receive URL-encoded values, as before)
	$custom_message = apply_filters('wa_order_filter_custom_message', urlencode($message_text));
	$encoded_title = apply_filters('wa_order_filter_encoded_title', urlencode($title));
	$encoded_product_url = apply_filters('wa_order_filter_encoded_product_url', urlencode($product_url));
	$encoded_price_label = apply_filters('wa_order_filter_price_label', urlencode(get_option('wa_order_option_price_label', 'Price') ?: 'Price'));
	$encoded_url_label = apply_filters('wa_order_filter_encoded_url_label', urlencode(get_option('wa_order_option_url_label', 'URL') ?: 'URL'));
	$encoded_thanks = apply_filters('wa_order_filter_thank_you_label', urlencode(get_option('wa_order_option_thank_you_label', 'Thank you!')));

	if (get_option('wa_order_option_single_show_regular_sale_prices', 'no') === 'yes' && '' !== $decoded_regular_price) {
		if ($price_parts['on_sale'] && '' !== $decoded_sale_price) {
			// Product is on sale - show both regular (strikethrough) and sale price
			$encoded_price = "~" . urlencode($decoded_regular_price) . "~ " . urlencode($decoded_sale_price);
		} else {
			$encoded_price = urlencode($decoded_regular_price);
		}
	} else {
		$encoded_price = apply_filters('wa_order_filter_encoded_price', urlencode($decoded_price));
	}

	// Exclude price from the message if the option is checked, or when there is no price
	$exclude_price = apply_filters('wa_order_filter_exclude_price', get_option('wa_order_exclude_price', 'no'));
	$message_content = $custom_message . "%0D%0A%0D%0A*$encoded_title*";
	if ($exclude_price !== 'yes' && '' !== (string) $encoded_price) {
		$message_content .= "%0D%0A*$encoded_price_label:* $encoded_price";
	}
	if (apply_filters('wa_order_filter_exclude_product_url', get_option('wa_order_exclude_product_url')) !== 'yes') {
		$message_content .= "%0D%0A*$encoded_url_label:* $encoded_product_url";
	}
	if ('' !== (string) $encoded_thanks) {
		$message_content .= "%0D%0A%0D%0A$encoded_thanks";
	}

	// WhatsApp URL
	$button_url = apply_filters('wa_order_filter_button_url', wa_order_the_url($phone, urldecode($message_content)), $phone, $message_content);

	// Get the 'Force Full-Width' option value
	$force_fullwidth = apply_filters('wa_order_filter_force_fullwidth', get_option('wa_order_single_force_fullwidth', 'no'));
	$button_fullwidth_class = ($force_fullwidth === 'yes') ? 'wa-order-fullwidth' : '';
	$button_fullwidth_style = ($force_fullwidth === 'yes') ? 'style="width:100%;display:block;"' : '';
	// Get link type setting
	$link_type = apply_filters('wa_order_filter_link_type', get_option('wa_order_single_product_link_type', 'link'));

	// Get button position to determine if we should use single_add_to_cart_button class
	$button_position = apply_filters('wa_order_filter_button_position', get_option('wa_order_single_product_button_position', 'after_atc'));

	// Only add single_add_to_cart_button class when positioned after the add to cart button
	// and that button is actually there (not in the no-price / out-of-stock fallback).
	// (WooCommerce prints the add-to-cart form inside woocommerce_single_product_summary,
	// so the fallback sets its own flag instead of checking the current action.)
	$is_fallback = !empty($GLOBALS['wa_order_rendering_fallback']);
	$wc_button_class = ($button_position === 'after_atc' && !$is_fallback) ? 'single_add_to_cart_button ' : '';

	$button_text_html = esc_html($button_text);
	$target_attr = esc_attr($target);

	// Generate button HTML based on link type
	if ($link_type === 'onclick') {
		// Use a unique ID and data attributes to completely bypass WooCommerce
		$unique_id = 'wa-btn-' . uniqid();
		$button_html = "<button type=\"button\" id=\"$unique_id\" class=\"{$wc_button_class}wa-order-button wa-order-onclick-button button alt $button_fullwidth_class\" data-wa-button=\"true\" data-wa-url=\"" . esc_attr($button_url) . "\" $button_fullwidth_style>$button_text_html</button>";

		// Capture-phase click handler that runs before theme/WooCommerce handlers.
		// The URL is JSON-encoded with "&" escaped (JSON_HEX_AMP), so neither the browser nor
		// wp_kses() turns it into "&amp;" (which used to drop the message).
		$json_flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES;
		$button_html .= "<script>
		document.getElementById(" . wp_json_encode($unique_id, $json_flags) . ").addEventListener('click', function(e) {
			e.preventDefault();
			e.stopPropagation();
			e.stopImmediatePropagation();
			window.open(" . wp_json_encode($button_url, $json_flags) . ", '_blank');
			return false;
		}, true);
		</script>";
	} else {
		// Use standard link tag (default)
		$button_html = "<a href=\"" . wa_order_esc_whatsapp_url($button_url) . "\" class=\"wa-order-class\" role=\"button\" target=\"$target_attr\" rel=\"noopener\"><button type=\"button\" class=\"wa-order-button {$wc_button_class}button alt $button_fullwidth_class\" $button_fullwidth_style>$button_text_html</button></a>";
	}

	// GDPR compliance
	if ($gdpr_status === 'yes') {
		$gdpr = wa_order_next_gdpr_group();
		$gdpr_group = (int) $gdpr['group'];
		$gdpr_id = esc_attr($gdpr['id']);

		$gdpr_script = "
    <script>
        function WAOrder() {
            var phone = '" . esc_js($phone) . "',
                wa_message = '" . esc_js($custom_message) . "',
                button_url = '" . esc_url($button_url) . "',
                target = '" . esc_attr($target) . "';
        }
    </script>
    <style>
        .wa-order-button,
        .wa-order-button .wa-order-class {
            display: none !important;
        }
    </style>
    ";

		// Build GDPR button class - only add single_add_to_cart_button when positioned after add to cart
		$gdpr_wc_class = ($button_position === 'after_atc' && !$is_fallback) ? 'single_add_to_cart_button ' : '';
		$button_class = $gdpr_wc_class . "gdpr_wa_button_input wa-order-button-" . ($button_position === "after_atc" ? "after-atc" : ($button_position === "under_atc" ? "under-atc" : "shortdesc"));

		// Add full-width class and inline style for GDPR button if full-width is enabled
		$button_fullwidth_class_gdpr = ($force_fullwidth === 'yes') ? 'wa-order-fullwidth' : '';
		$button_fullwidth_style_gdpr = ($force_fullwidth === 'yes') ? 'style="width:100%!important;display:block!important;"' : '';

		$gdpr_checkbox = "
		<div class=\"wa-order-gdprchk\">
			<input type=\"checkbox\" name=\"wa_order_gdpr_status_enable\" class=\"css-checkbox wa_order_input_check\" id=\"$gdpr_id\" data-wa-gdpr-group=\"$gdpr_group\" />
			<label for=\"$gdpr_id\" class=\"label-gdpr\">$gdpr_message</label>
		</div>
		" . wa_order_gdpr_group_script($gdpr['id']);

		// Generate GDPR button HTML based on link type
		if ($link_type === 'onclick') {
			// Use onclick event for GDPR button - open in new tab
			$button_html = "
		$gdpr_script
		<label class=\"wa-button-gdpr2\" $button_fullwidth_style_gdpr>
			<button type=\"button\" class=\"gdpr_wa_button_input $button_class $button_fullwidth_class_gdpr button alt\" disabled=\"disabled\" data-wa-gdpr-group=\"$gdpr_group\" onclick=\"if(!this.disabled) { window.open('" . esc_js($button_url) . "', '_blank'); return false; }\">
				$button_text_html
			</button>
		</label>
		$gdpr_checkbox
		";
		} else {
			// Use standard link tag for GDPR button (default)
			$button_html = "
		$gdpr_script
		<label class=\"wa-button-gdpr2\" $button_fullwidth_style_gdpr>
			<a href=\"" . wa_order_esc_whatsapp_url($button_url) . "\" class=\"gdpr_wa_button\" role=\"button\" target=\"$target_attr\" rel=\"noopener\" aria-disabled=\"true\" data-wa-gdpr-group=\"$gdpr_group\">
				<button type=\"button\" class=\"gdpr_wa_button_input $button_class $button_fullwidth_class_gdpr button alt\" disabled=\"disabled\" data-wa-gdpr-group=\"$gdpr_group\">
					$button_text_html
				</button>
			</a>
		</label>
		$gdpr_checkbox
		";
		}
	}

	// Configure allowed HTML tags and attributes
	$allowed_html = wp_kses_allowed_html('post');

	// Add onclick attribute support for buttons (needed for onClick functionality)
	$allowed_html['button'] = array(
		'type' => array(),
		'class' => array(),
		'id' => array(),
		'style' => array(),
		'onclick' => array(),
		'disabled' => array(),
		'data-wa-button' => array(),
		'data-wa-url' => array(),
		'data-wa-gdpr-group' => array(),
	);

	// Allow script tags for onClick functionality
	$allowed_html['script'] = array(
		'type' => array(),
	);

	// Ensure anchor tags support all necessary attributes
	$allowed_html['a'] = array(
		'href' => array(),
		'class' => array(),
		'role' => array(),
		'target' => array(),
		'rel' => array(),
		'onclick' => array(),
		'aria-disabled' => array(),
		'data-wa-gdpr-group' => array(),
	);

	// For GDPR functionality, we need to allow additional script and style tags
	if ($gdpr_status === 'yes') {
		$allowed_html['style'] = array();
		$allowed_html['input'] = array(
			'type' => array(),
			'name' => array(),
			'class' => array(),
			'id' => array(),
			'checked' => array(),
			'disabled' => array(),
			'data-wa-gdpr-group' => array(),
		);
		$allowed_html['label'] = array(
			'for' => array(),
			'class' => array(),
			'style' => array(),
		);
	}

	$GLOBALS['wa_order_single_rendered'][$product_id] = true;

	// Apply the filter and output with proper escaping
	$filtered_html = apply_filters('wa_order_filter_button_html', $button_html, $button_url, $button_text, $the_product);
	echo wp_kses($filtered_html, $allowed_html); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_kses() is the escaping.
}

// Attach the button where the "Button Position" setting says.
$wa_order_single_placement = wa_order_get_single_button_placement();
add_action($wa_order_single_placement['hook'], 'wa_order_add_button_plugin', $wa_order_single_placement['priority']);

/**
 * Let themes and snippets move the button with filters:
 * wa_order_single_button_hook and wa_order_single_button_priority.
 *
 * Runs on init, after theme functions.php has loaded. If something already
 * removed the button from its default hook, that removal is respected.
 *
 * @since 1.2.0
 */
function wa_order_apply_single_button_placement_filters()
{
	$position  = get_option('wa_order_single_product_button_position', 'after_atc');
	$placement = wa_order_get_single_button_placement();

	$hook     = (string) apply_filters('wa_order_single_button_hook', $placement['hook'], $position);
	$priority = (int) apply_filters('wa_order_single_button_priority', $placement['priority'], $position);

	if ($hook === $placement['hook'] && $priority === $placement['priority']) {
		return;
	}
	if (has_action($placement['hook'], 'wa_order_add_button_plugin') !== $placement['priority']) {
		return;
	}

	remove_action($placement['hook'], 'wa_order_add_button_plugin', $placement['priority']);
	add_action($hook, 'wa_order_add_button_plugin', $priority);
	$GLOBALS['wa_order_single_button_placement'] = array('hook' => $hook, 'priority' => $priority);
}
add_action('init', 'wa_order_apply_single_button_placement_filters', 1);

/**
 * Show the button on products WooCommerce can't add to the cart.
 *
 * WooCommerce skips the add-to-cart form, and the hooks the default button
 * positions use, when a product has no price or is out of stock. This prints
 * the button after the summary's add-to-cart area instead.
 *
 * Controlled by "Show on products without a price" (on by default) and
 * "Show on out-of-stock products" (off by default) on the Basic tab.
 *
 * @since 1.2.0
 */
function wa_order_add_button_plugin_fallback()
{
	global $product;
	$the_product = ($product instanceof WC_Product) ? $product : wc_get_product(get_the_ID());
	if (!$the_product || !empty($GLOBALS['wa_order_single_rendered'][$the_product->get_id()])) {
		return;
	}

	// Only for the positions that live inside the add-to-cart form, and only
	// while the button is still attached there (someone may have removed it).
	$placement = wa_order_get_single_button_placement();
	$form_hooks = array('woocommerce_after_add_to_cart_button', 'woocommerce_after_add_to_cart_form', 'woocommerce_before_add_to_cart_form');
	if (!in_array($placement['hook'], $form_hooks, true) || false === has_action($placement['hook'], 'wa_order_add_button_plugin')) {
		return;
	}

	$no_price     = !$the_product->is_purchasable() && '' === $the_product->get_price();
	$out_of_stock = !$the_product->is_in_stock();

	$show = false;
	if ($no_price && 'yes' === get_option('wa_order_option_show_without_price', 'yes')) {
		$show = true;
	}
	if ($out_of_stock && 'yes' === get_option('wa_order_option_show_out_of_stock', 'no')) {
		$show = true;
	}

	/**
	 * Filter whether to print the button on a product WooCommerce can't add to the cart.
	 *
	 * @since 1.2.0
	 *
	 * @param bool       $show    Whether to show it.
	 * @param WC_Product $product Product.
	 */
	if (apply_filters('wa_order_filter_show_on_unpurchasable', $show, $the_product)) {
		$GLOBALS['wa_order_rendering_fallback'] = true;
		ob_start();
		wa_order_add_button_plugin();
		$button = trim(ob_get_clean());
		$GLOBALS['wa_order_rendering_fallback'] = false;
		// No empty wrapper when the button is off or has no number.
		if ('' !== $button) {
			echo '<div class="wa-order-unpurchasable">' . $button . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by wa_order_add_button_plugin().
		}
	}
}
add_action('woocommerce_single_product_summary', 'wa_order_add_button_plugin_fallback', 31);

// Single product custom metabox
// Hide button checkbox
function wa_order_execute_metabox_value()
{
	// Check if WooCommerce is active
	if (!function_exists('is_product')) {
		return;
	}

	// Check if it's a product page
	if (!is_product()) {
		return;
	}

	// Get the current post object
	$post = get_post();

	// Check if the WhatsApp button should be hidden
	if ($post && get_post_meta($post->ID, '_hide_wa_button', true) == 'yes') {
		wa_order_remove_button_actions();
	}
}
add_action('wp_head', 'wa_order_execute_metabox_value');

// Hide WA button based on categories & tags
add_action('wp_head', 'wa_order_hide_single_taxonomies');
function wa_order_hide_single_taxonomies()
{
	// Check if WooCommerce is active
	if (!function_exists('is_product')) {
		return;
	}

	// Only proceed if on a product page
	if (!is_product()) {
		return;
	}

	$product_id = get_queried_object_id();
	if ($product_id && wa_order_single_button_is_hidden($product_id)) {
		wa_order_remove_button_actions();
	}
}

/**
 * Detach the single product button from every hook it can be on.
 * Kept for custom code that calls it; visibility is also checked when rendering.
 */
function wa_order_remove_button_actions()
{
	$placement = wa_order_get_single_button_placement();
	remove_action($placement['hook'], 'wa_order_add_button_plugin', $placement['priority']);
	remove_action('woocommerce_after_add_to_cart_button', 'wa_order_add_button_plugin', 5);
	remove_action('woocommerce_after_add_to_cart_form', 'wa_order_add_button_plugin', 5);
	remove_action('woocommerce_before_add_to_cart_form', 'wa_order_add_button_plugin', 5);
	remove_action('woocommerce_after_single_product_summary', 'wa_order_add_button_plugin', 5);
	remove_action('woocommerce_share', 'wa_order_add_button_plugin', 5);
	remove_action('woocommerce_single_product_summary', 'wa_order_add_button_plugin_fallback', 31);
}

// Hide ATC button checkbox
add_action('woocommerce_before_single_product', 'wa_order_check_and_hide_atc_button');
function wa_order_check_and_hide_atc_button()
{
	global $product;

	if (is_a($product, 'WC_Product') && get_post_meta($product->get_id(), '_hide_atc_button', true) === 'yes') {
		// Hide the Add to Cart button on simple and variable products, never the WhatsApp button.
		add_action('wp_footer', function () {
			echo '<style>
                    .single-product button[name="add-to-cart"]:not(.wa-order-button):not(.gdpr_wa_button_input),
                    .single-product .single_add_to_cart_button:not(.wa-order-button):not(.gdpr_wa_button_input) {
                        display: none !important;
                    }
                  </style>';
		});
	}
}

function wa_order_remove_atc_button()
{
	// Ensure WooCommerce is active
	if (!class_exists('WooCommerce')) {
		return;
	}

	$enable_wa_button = get_option('wa_order_option_enable_single_product', 'no');
	$hide_atc_button = get_option('wa_order_option_remove_cart_btn', 'no');

	// Logic to remove or show buttons based on options.
	// 1.2.0: the branch that forced every product purchasable is gone; it
	// overrode WooCommerce and catalog-mode or membership plugins.
	if ($hide_atc_button === 'yes') {
		if ($enable_wa_button === 'yes') {
			add_action('wp_footer', 'wa_order_hide_atc_show_wa_button_css');
		} else {
			add_filter('woocommerce_is_purchasable', '__return_false');
			add_action('wp_footer', 'wa_order_remove_atc_button_css');
		}
	}
}
add_action('wp', 'wa_order_remove_atc_button');
// Completely hide Add to Cart and WhatsApp buttons
function wa_order_remove_atc_button_css()
{
?>
	<style>
		.single_add_to_cart_button,
		.woocommerce-variation-add-to-cart button[type="submit"],
		.single-product button[name="add-to-cart"],
		.wa-order-class,
		.wa-order-button {
			display: none !important;
		}
	</style>
<?php
}

// Hide Add to Cart button, but display WhatsApp button (including the GDPR variant)
function wa_order_hide_atc_show_wa_button_css()
{
?>
	<style>
		.single_add_to_cart_button:not(.wa-order-button):not(.gdpr_wa_button_input),
		.woocommerce-variation-add-to-cart button[type="submit"]:not(.wa-order-button):not(.gdpr_wa_button_input) {
			display: none !important;
		}

		.wa-order-class,
		.wa-order-button {
			display: block !important;
		}
	</style>
<?php
}

// Force show Add to Cart button product metabox
function wa_order_force_show_atc_button()
{
	// Ensure WooCommerce is active
	if (!class_exists('WooCommerce')) {
		return;
	}

	if (is_product()) {
		$force_show_atc = get_post_meta(get_queried_object_id(), '_force_show_atc_button', true);
		if ($force_show_atc === 'yes') {
			// Re-enable purchasability only if this plugin disabled it.
			remove_filter('woocommerce_is_purchasable', '__return_false');

			// Remove inline CSS that hides the ATC button, whether or not the WhatsApp button is on.
			remove_action('wp_footer', 'wa_order_remove_atc_button_css');
			remove_action('wp_footer', 'wa_order_hide_atc_show_wa_button_css');
		}
	}
}
add_action('wp_head', 'wa_order_force_show_atc_button', 10);

// Remove price on single product page based on option
function wa_order_remove_single_product_price()
{
	$hide_price = get_option('wa_order_option_remove_price', 'no');
	if ($hide_price === 'yes') {
		remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
		add_filter('woocommerce_get_price_html', 'wa_order_hide_price_html', 10, 2);
		add_filter('woocommerce_variable_sale_price_html', 'wa_order_hide_price_html', 10, 2);
		add_filter('woocommerce_variable_price_html', 'wa_order_hide_price_html', 10, 2);
		add_filter('woocommerce_get_variation_price_html', 'wa_order_hide_price_html', 10, 2);
	}
}
add_action('woocommerce_before_single_product', 'wa_order_remove_single_product_price', 1);

// Hide prices only for the product whose page is being viewed (and its variations),
// not for related products, upsells or product blocks in posts.
function wa_order_hide_price_html($price, $product)
{
	if (!function_exists('is_product') || !is_product() || !($product instanceof WC_Product)) {
		return $price;
	}
	$product_id = $product->get_parent_id() ? $product->get_parent_id() : $product->get_id();
	if ($product_id === get_queried_object_id()) {
		return '';
	}
	return $price;
}

function wa_order_function_remove_elements_css()
{
	$hide_price            = get_option('wa_order_option_remove_price', 'no');
	$hide_button           = get_option('wa_order_option_remove_btn');
	$hide_button_mobile    = get_option('wa_order_option_remove_btn_mobile');

	// Collect CSS rules in a variable
	$custom_css = "";

	// Hide price
	if ($hide_price === 'yes') {
		$custom_css .= "
            .single-product .product.type-product .summary .woocommerce-Price-amount,
            .single-product .product.type-product .summary p.price {
                display: none !important;
            }
        ";
	}

	// Hide button for desktop
	if ($hide_button === 'yes') {
		$custom_css .= "
            @media screen and (min-width: 768px) {
                .wa-order-button,
                .gdpr_wa_button_input,
                .wa-order-gdprchk,
                button.gdpr_wa_button_input:disabled,
                button.gdpr_wa_button_input {
                    display: none !important;
                }
            }
        ";
	}

	// Hide button for mobile
	if ($hide_button_mobile === 'yes') {
		$custom_css .= "
            @media screen and (max-width: 767px) {
                .wa-order-button,
                .gdpr_wa_button_input,
                .wa-order-gdprchk,
                button.gdpr_wa_button_input:disabled,
                button.gdpr_wa_button_input {
                    display: none !important;
                }
            }
        ";
	}

	// Output the CSS if it's not empty
	if (!empty($custom_css)) {
		echo '<style>' . wp_strip_all_tags($custom_css) . '</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static CSS.
	}
}
add_action('wp_head', 'wa_order_function_remove_elements_css');
