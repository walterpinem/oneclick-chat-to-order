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
 * @category    Single Product Page
 *
 ********************************* Single Product Page ********************************* */

// Default button position single product page
$wa_order_position = wa_order_get_options_manager()->get_option('wa_order_single_product_button_position', 'after_atc');

// Start processing the WhatsApp button
// Revamped in version 1.0.5
function wa_order_add_button_plugin()
{
	// Use cached options for better performance
	$options_manager = wa_order_get_options_manager();
	$enable_wa_button = apply_filters('wa_order_filter_enable_single_product', $options_manager->get_option('wa_order_option_enable_single_product', 'yes'));
	// Check if the button should be displayed
	if ($enable_wa_button !== 'yes') {
		return;
	}

	global $product;

	// Fetch phone number
	$phone = apply_filters('wa_order_filter_phone_number', wa_order_get_phone_number($product->get_id()), $product->get_id());
	if (!$phone) {
		return; // Exit if no phone number is set
	}

	// Product details
	$product_url = apply_filters('wa_order_filter_product_url', get_permalink($product->get_id()), $product);
	$title = apply_filters('wa_order_filter_product_title', $product->get_name(), $product);

	// Handle different product types for pricing
	if ($product->is_type('grouped')) {
		// For grouped products, get price range
		$children = array_filter(array_map('wc_get_product', $product->get_children()), 'wc_products_array_filter_visible_grouped');
		if (!empty($children)) {
			$child_prices = array();
			foreach ($children as $child) {
				if ($child->get_price() !== '') {
					$child_prices[] = wc_get_price_to_display($child);
				}
			}
			if (!empty($child_prices)) {
				$min_price = min($child_prices);
				$max_price = max($child_prices);
				if ($min_price !== $max_price) {
					$price_range = wc_price($min_price) . ' – ' . wc_price($max_price);
				} else {
					$price_range = wc_price($min_price);
				}
				$price = apply_filters('wa_order_filter_product_price', $price_range, $product);
				$format_price = wp_strip_all_tags($price);
			} else {
				$price = apply_filters('wa_order_filter_product_price', '', $product);
				$format_price = '';
			}
		} else {
			$price = apply_filters('wa_order_filter_product_price', '', $product);
			$format_price = '';
		}
		// For grouped products, regular and sale prices don't apply in the traditional sense
		$format_regular_price = $format_price;
		$decoded_regular_price = html_entity_decode($format_regular_price, ENT_QUOTES, 'UTF-8');
		$format_sale_price = '';
		$decoded_sale_price = '';
	} else {
		// For other product types (simple, variable, etc.)
		$price = apply_filters('wa_order_filter_product_price', wc_price(wc_get_price_including_tax($product)), $product);
		$format_price = wp_strip_all_tags($price); // Strip HTML tags
		// Regular Price
		$regular_price = apply_filters('wa_order_filter_product_regular_price', wc_price($product->get_regular_price()), $product);
		$format_regular_price = wp_strip_all_tags($regular_price);
		$decoded_regular_price = html_entity_decode($format_regular_price, ENT_QUOTES, 'UTF-8');
		// Sale Price
		$sale_price = apply_filters('wa_order_filter_product_sale_price', wc_price($product->get_sale_price()), $product);
		$format_sale_price = wp_strip_all_tags($sale_price);
		$decoded_sale_price = html_entity_decode($format_sale_price, ENT_QUOTES, 'UTF-8');
	}

	// Decode HTML entities in the price
	$decoded_price = html_entity_decode($format_price, ENT_QUOTES, 'UTF-8');

	// Settings - Use cached options for better performance
	$button_text = apply_filters('wa_order_filter_button_text', get_post_meta($product->get_id(), '_wa_order_button_text', true) ?: $options_manager->get_option('wa_order_option_text_button', 'Buy via WhatsApp'), $product);
	$target = apply_filters('wa_order_filter_button_target', $options_manager->get_option('wa_order_option_target', '_blank'));
	$gdpr_status = apply_filters('wa_order_filter_gdpr_status', $options_manager->get_option('wa_order_gdpr_status_enable', 'no'));
	$gdpr_message = apply_filters('wa_order_filter_gdpr_message', do_shortcode(stripslashes($options_manager->get_option('wa_order_gdpr_message', ''))));

	// URL Encoding - Use cached options for better performance
	$custom_message = apply_filters('wa_order_filter_custom_message', urlencode($options_manager->get_option('wa_order_option_message', 'Hello, I want to buy:')));
	$encoded_title = apply_filters('wa_order_filter_encoded_title', urlencode($title));
	$encoded_product_url = apply_filters('wa_order_filter_encoded_product_url', urlencode($product_url));
	$encoded_price_label = apply_filters('wa_order_filter_price_label', urlencode($options_manager->get_option('wa_order_option_price_label', 'Price')));
	$encoded_url_label = apply_filters('wa_order_filter_encoded_url_label', urlencode($options_manager->get_option('wa_order_option_url_label', 'URL')));
	$encoded_thanks = apply_filters('wa_order_filter_thank_you_label', urlencode($options_manager->get_option('wa_order_option_thank_you_label', 'Thank you!')));

	if ($options_manager->get_option('wa_order_option_single_show_regular_sale_prices', 'no') === 'yes') {
		// Check if product is actually on sale and has a valid sale price
		if ($product->is_on_sale() && $product->get_sale_price() && !empty($product->get_sale_price())) {
			// Product is on sale - show both regular (strikethrough) and sale price
			$encoded_price = "~" . urlencode($decoded_regular_price) . "~ " . urlencode($decoded_sale_price);
		} else {
			// Product is not on sale or sale price is empty - show only regular price
			$encoded_price = urlencode($decoded_regular_price);
		}
	} else {
		$encoded_price = apply_filters('wa_order_filter_encoded_price', urlencode($decoded_price));
	}

	// Exclude price from the message if the option is checked
	$exclude_price = apply_filters('wa_order_filter_exclude_price', $options_manager->get_option('wa_order_exclude_price', 'no'));
	$message_content = $custom_message . "%0D%0A%0D%0A*$encoded_title*";
	if ($exclude_price !== 'yes') {
		$message_content .= "%0D%0A*$encoded_price_label:* $encoded_price";
	}
	if (apply_filters('wa_order_filter_exclude_product_url', $options_manager->get_option('wa_order_exclude_product_url')) !== 'yes') {
		$message_content .= "%0D%0A*$encoded_url_label:* $encoded_product_url";
	}
	$message_content .= "%0D%0A%0D%0A$encoded_thanks";

	// WhatsApp URL
	$button_url = apply_filters('wa_order_filter_button_url', wa_order_the_url($phone, urldecode($message_content)), $phone, $message_content); // phpcs:ignore WordPress.Security.EscapeOutput.

	// Get the 'Force Full-Width' option value
	$force_fullwidth = apply_filters('wa_order_filter_force_fullwidth', $options_manager->get_option('wa_order_single_force_fullwidth', 'no'));
	$button_fullwidth_class = ($force_fullwidth === 'yes') ? 'wa-order-fullwidth' : '';
	$button_fullwidth_style = ($force_fullwidth === 'yes') ? 'style="width:100%;display:block;"' : '';
	// Get link type setting - use direct get_option for debugging
	$link_type = apply_filters('wa_order_filter_link_type', get_option('wa_order_single_product_link_type', 'link'));

	// Get button position to determine if we should use single_add_to_cart_button class
	$button_position = apply_filters('wa_order_filter_button_position', $options_manager->get_option('wa_order_single_product_button_position', 'after_atc'));

	// Only add single_add_to_cart_button class when positioned after add to cart button
	$wc_button_class = ($button_position === 'after_atc') ? 'single_add_to_cart_button ' : '';

	// Generate button HTML based on link type
	if ($link_type === 'onclick') {
		// Use a unique ID and data attributes to completely bypass WooCommerce
		$unique_id = 'wa-btn-' . uniqid();
		$button_html = "<button type=\"button\" id=\"$unique_id\" class=\"{$wc_button_class}wa-order-button wa-order-onclick-button button alt $button_fullwidth_class\" data-wa-button=\"true\" data-wa-url=\"" . esc_attr($button_url) . "\" $button_fullwidth_style>$button_text</button>";

		// Add JavaScript to handle the click without using onclick attribute
		$button_html .= "<script>
		document.getElementById('$unique_id').addEventListener('click', function(e) {
			e.preventDefault();
			e.stopPropagation();
			e.stopImmediatePropagation();
			window.open('" . esc_js($button_url) . "', '_blank');
			return false;
		}, true); // Use capture phase to execute before other handlers
		</script>";
	} else {
		// Use standard link tag (default)
		$button_html = "<a href=\"$button_url\" class=\"wa-order-class\" role=\"button\" target=\"$target\"><button type=\"button\" class=\"wa-order-button {$wc_button_class}button alt $button_fullwidth_class\" $button_fullwidth_style>$button_text</button></a>";
	}

	// GDPR compliance
	if ($gdpr_status === 'yes') {
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

		$button_id = "sendbtn" . ($button_position === "after_atc" ? "2" : "");

		// Build GDPR button class - only add single_add_to_cart_button when positioned after add to cart
		$gdpr_wc_class = ($button_position === 'after_atc') ? 'single_add_to_cart_button ' : '';
		$button_class = $gdpr_wc_class . "gdpr_wa_button_input wa-order-button-" . ($button_position === "after_atc" ? "after-atc" : ($button_position === "under_atc" ? "under-atc" : "shortdesc"));

		// Add full-width class and inline style for GDPR button if full-width is enabled
		$button_fullwidth_class_gdpr = ($force_fullwidth === 'yes') ? 'wa-order-fullwidth' : '';
		$button_fullwidth_style_gdpr = ($force_fullwidth === 'yes') ? 'style="width:100%!important;display:block!important;"' : '';

		// Generate GDPR button HTML based on link type
		if ($link_type === 'onclick') {
			// Use onclick event for GDPR button - open in new tab
			$button_html = "
		$gdpr_script
		<label class=\"wa-button-gdpr2\" $button_fullwidth_style_gdpr>
			<button type=\"button\" class=\"gdpr_wa_button_input $button_class $button_fullwidth_class_gdpr button alt\" disabled=\"disabled\" onclick=\"if(!this.disabled) { window.open('" . esc_js($button_url) . "', '_blank'); return false; }\">
				$button_text
			</button>
		</label>
		<div class=\"wa-order-gdprchk\">
			<input type=\"checkbox\" name=\"wa_order_gdpr_status_enable\" class=\"css-checkbox wa_order_input_check\" id=\"gdprChkbx\" />
			<label for=\"gdprChkbx\" class=\"label-gdpr\">$gdpr_message</label>
		</div>
		<script type=\"text/javascript\">
			document.getElementById('gdprChkbx').addEventListener('click', function (e) {
				var buttons = document.querySelectorAll('.gdpr_wa_button_input');
				buttons.forEach(function(button) {
					button.disabled = !e.target.checked;
				});
			});
		</script>
		";
		} else {
			// Use standard link tag for GDPR button (default)
			$button_html = "
		$gdpr_script
		<label class=\"wa-button-gdpr2\" $button_fullwidth_style_gdpr>
			<a href=\"$button_url\" class=\"gdpr_wa_button\" role=\"button\" target=\"$target\">
				<button type=\"button\" class=\"gdpr_wa_button_input $button_class $button_fullwidth_class_gdpr button alt\" disabled=\"disabled\">
					$button_text
				</button>
			</a>
		</label>
		<div class=\"wa-order-gdprchk\">
			<input type=\"checkbox\" name=\"wa_order_gdpr_status_enable\" class=\"css-checkbox wa_order_input_check\" id=\"gdprChkbx\" />
			<label for=\"gdprChkbx\" class=\"label-gdpr\">$gdpr_message</label>
		</div>
		<script type=\"text/javascript\">
			document.getElementById('gdprChkbx').addEventListener('click', function (e) {
				var buttons = document.querySelectorAll('.gdpr_wa_button_input');
				buttons.forEach(function(button) {
					button.disabled = !e.target.checked;
				});
			});
		</script>
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
		'onclick' => array(),
	);

	// For GDPR functionality, we need to allow additional script and style tags
	if ($gdpr_status === 'yes') {
		$allowed_html['script'] = array(
			'type' => array(),
		);
		$allowed_html['style'] = array();
		$allowed_html['input'] = array(
			'type' => array(),
			'name' => array(),
			'class' => array(),
			'id' => array(),
			'checked' => array(),
			'disabled' => array(),
		);
		$allowed_html['label'] = array(
			'for' => array(),
			'class' => array(),
			'style' => array(),
		);
	}

	// Apply the filter and output with proper escaping
	$filtered_html = apply_filters('wa_order_filter_button_html', $button_html, $button_url, $button_text, $product);
	$final_html = wp_kses($filtered_html, $allowed_html);
	echo $final_html;
}
// Determine the position of the button and add the action accordingly
$button_position = wa_order_get_options_manager()->get_option('wa_order_single_product_button_position', 'after_atc');
$hook = 'woocommerce_after_add_to_cart_button'; // Default hook
switch ($button_position) {
	case 'under_atc':
		$hook = 'woocommerce_after_add_to_cart_form';
		break;
	case 'after_shortdesc':
		$hook = 'woocommerce_before_add_to_cart_form';
		break;
	case 'after_single_product_summary':
		$hook = 'woocommerce_after_single_product_summary';
		break;
	case 'around_share_area':
		$hook = 'woocommerce_share';
		break;
}
add_action($hook, 'wa_order_add_button_plugin', 5);

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
	if (get_post_meta($post->ID, '_hide_wa_button', true) == 'yes') {
		// Ensure the action function exists
		if (function_exists('wa_order_add_button_plugin')) {
			remove_action('woocommerce_after_add_to_cart_button', 'wa_order_add_button_plugin', 5);
			remove_action('woocommerce_after_add_to_cart_form', 'wa_order_add_button_plugin', 5);
			remove_action('woocommerce_before_add_to_cart_form', 'wa_order_add_button_plugin', 5);
			remove_action('woocommerce_after_single_product_summary', 'wa_order_add_button_plugin', 5);
			remove_action('woocommerce_share', 'wa_order_add_button_plugin', 5);
		}
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

	// Retrieve the current product ID
	global $post;
	$product_id = $post->ID;

	// Get the category and tag options - Use cached options for better performance
	$options_manager = wa_order_get_options_manager();
	$option_cats = $options_manager->get_option('wa_order_option_exlude_single_product_cats', []);
	$option_tags = $options_manager->get_option('wa_order_option_exlude_single_product_tags', []);

	// Check if the product belongs to specified categories
	if (!empty($option_cats) && has_term($option_cats, 'product_cat', $product_id)) {
		wa_order_remove_button_actions();
		return;
	}

	// Check if the product has specified tags
	if (!empty($option_tags) && has_term($option_tags, 'product_tag', $product_id)) {
		wa_order_remove_button_actions();
		return;
	}
}

function wa_order_remove_button_actions()
{
	remove_action('woocommerce_after_add_to_cart_button', 'wa_order_add_button_plugin', 5);
	remove_action('woocommerce_after_add_to_cart_form', 'wa_order_add_button_plugin', 5);
	remove_action('woocommerce_before_add_to_cart_form', 'wa_order_add_button_plugin', 5);
	remove_action('woocommerce_after_single_product_summary', 'wa_order_add_button_plugin', 5);
	remove_action('woocommerce_share', 'wa_order_add_button_plugin', 5);
}

// Hide ATC button checkbox
add_action('woocommerce_before_single_product', 'wa_order_check_and_hide_atc_button');
function wa_order_check_and_hide_atc_button()
{
	global $product;

	if (is_a($product, 'WC_Product') && get_post_meta($product->get_id(), '_hide_atc_button', true) === 'yes') {
		// add_filter('woocommerce_is_purchasable', '__return_false');

		// Directly output CSS to hide ATC button
		add_action('wp_footer', function () {
			echo '<style>
                    .single-product button[name="add-to-cart"] {
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

	// Get options for removing ATC and enabling WhatsApp - Use cached options for better performance
	$options_manager = wa_order_get_options_manager();
	$enable_wa_button = $options_manager->get_option('wa_order_option_enable_single_product', 'no');
	$hide_atc_button = $options_manager->get_option('wa_order_option_remove_cart_btn', 'no');

	// Logic to remove or show buttons based on options
	if ($hide_atc_button === 'yes') {

		if ($enable_wa_button === 'yes') {
			add_action('wp_footer', 'wa_order_hide_atc_show_wa_button_css');
		} else {
			add_filter('woocommerce_is_purchasable', '__return_false');
			add_action('wp_footer', 'wa_order_remove_atc_button_css');
		}
	} elseif ($hide_atc_button === 'no' && $enable_wa_button === 'no') {
		// Case 3: Show both Add to Cart and WhatsApp buttons
		add_filter('woocommerce_is_purchasable', '__return_true');
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

// Hide Add to Cart button, but display WhatsApp button
function wa_order_hide_atc_show_wa_button_css()
{
?>
	<style>
		.single_add_to_cart_button,
		.woocommerce-variation-add-to-cart button[type="submit"] {
			display: none !important;
		}

		.wa-order-class,
		/* Assuming this is the class for the WhatsApp button */
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

	global $post;
	if (is_product()) {
		$force_show_atc = get_post_meta($post->ID, '_force_show_atc_button', true);
		if ($force_show_atc === 'yes') {
			// Re-enable purchasability if it was disabled
			add_filter('woocommerce_is_purchasable', '__return_true');

			// Remove inline CSS that hides the ATC button
			remove_action('wp_footer', 'wa_order_remove_atc_button_css');
		}
	}
}
add_action('wp_head', 'wa_order_force_show_atc_button', 10);

// Remove price on single product page based on option
function wa_order_remove_single_product_price()
{
	$options_manager = wa_order_get_options_manager();
	$hide_price = $options_manager->get_option('wa_order_option_remove_price', 'no');
	if ($hide_price === 'yes') {
		remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
		add_filter('woocommerce_get_price_html', 'wa_order_hide_price_html', 10, 2);
		add_filter('woocommerce_variable_sale_price_html', 'wa_order_hide_price_html', 10, 2);
		add_filter('woocommerce_variable_price_html', 'wa_order_hide_price_html', 10, 2);
		add_filter('woocommerce_get_variation_price_html', 'wa_order_hide_price_html', 10, 2);
	}
}
add_action('woocommerce_before_single_product', 'wa_order_remove_single_product_price', 1);
function wa_order_hide_price_html($price, $product)
{
	if (is_single()) {
		return '';
	}
	return $price;
}

function wa_order_function_remove_elements_css()
{
	$options_manager = wa_order_get_options_manager();
	$hide_price            = $options_manager->get_option('wa_order_option_remove_price', 'no');
	$hide_button           = $options_manager->get_option('wa_order_option_remove_btn');
	$hide_button_mobile    = $options_manager->get_option('wa_order_option_remove_btn_mobile');

	// Collect CSS rules in a variable
	$custom_css = "";

	// Hide price
	if ($hide_price === 'yes') {
		$custom_css .= "
            .single-product .woocommerce-Price-amount,
            .single-product p.price {
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
            @media screen and (max-width: 768px) {
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
		echo '<style>' . esc_html($custom_css) . '</style>';
	}
}
add_action('wp_head', 'wa_order_function_remove_elements_css');
