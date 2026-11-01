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
 * @category    Shop Archive Page
 *
 ********************************* Shop Archive Page ********************************* */

/**
 * Whether the shop-loop button is excluded for a product by the Display tab's
 * category and tag settings.
 *
 * Products in an excluded category or tag never get the button, except on
 * category or tag archive pages, where the "Also hide on ... archive pages"
 * boxes decide.
 *
 * @since 1.2.0
 *
 * @param WC_Product $product Product.
 * @return bool
 */
function wa_order_shop_button_is_excluded($product)
{
	$product_id = $product->get_id();

	$cats = array_filter((array) get_option('wa_order_option_exlude_shop_product_cats', array()));
	if (!empty($cats) && has_term($cats, 'product_cat', $product_id)) {
		if (!is_product_category() || 'yes' === get_option('wa_order_exlude_shop_product_cats_archive')) {
			return true;
		}
	}

	$tags = array_filter((array) get_option('wa_order_option_exlude_shop_product_tags', array()));
	if (!empty($tags) && has_term($tags, 'product_tag', $product_id)) {
		if (!is_product_tag() || 'yes' === get_option('wa_order_exlude_shop_product_tags_archive')) {
			return true;
		}
	}

	return false;
}

// Add WhatsApp button under each product on Shop page
function wa_order_display_button_shop_page()
{
	$enable_button = apply_filters('wa_order_filter_enable_shop_button', get_option('wa_order_option_enable_button_shop_loop'));
	if ($enable_button !== 'yes') {
		return;
	}

	global $product;
	if (!($product instanceof WC_Product)) {
		return;
	}

	if (wa_order_shop_button_is_excluded($product)) {
		return;
	}

	// Number: the product's own number first, then the Shop tab number, then the Basic tab number.
	$phonenumb = '';
	if ('yes' === get_post_meta($product->get_id(), '_wa_order_phone_number_check', true)) {
		$phonenumb = wa_order_get_number_phone(get_post_meta($product->get_id(), '_wa_order_phone_number', true));
	}
	if ('' === $phonenumb) {
		$wanumberpage = apply_filters('wa_order_filter_shop_wa_number_page', get_option('wa_order_selected_wa_number_shop'));
		$phonenumb = wa_order_get_number_phone($wanumberpage);
	}
	if ('' === $phonenumb) {
		$phonenumb = wa_order_get_number_phone(get_option('wa_order_selected_wa_number_single_product', ''));
	}
	$phonenumb = apply_filters('wa_order_filter_shop_phone_number', $phonenumb);
	if (!$phonenumb) {
		return; // No number: don't print a link that opens WhatsApp's share screen.
	}

	// Set Default Button Text
	$button_text = apply_filters('wa_order_filter_shop_button_text', get_option('wa_order_option_button_text_shop_loop', 'Buy via WhatsApp'));
	if ($button_text == '') {
		$button_text = __('Buy via WhatsApp', 'oneclick-whatsapp-order');
	}
	// Set Default Custom Message
	$custom_message = apply_filters('wa_order_filter_shop_custom_message', get_option('wa_order_option_custom_message_shop_loop', 'Hello, I want to purchase:'));
	$custom_msg = ($custom_message == '') ? __('Hello, I want to purchase:', 'oneclick-whatsapp-order') : (string) $custom_message;

	$product_url	= apply_filters('wa_order_filter_shop_product_url', $product->get_permalink(), $product);
	$product_title	= apply_filters('wa_order_filter_shop_product_title', $product->get_name(), $product);
	// Translators: %s is the product title
	$link_title		= sprintf(__('Complete order on WhatsApp to buy %s', 'oneclick-whatsapp-order'), wa_order_plain_text($product_title));

	$class			= apply_filters('wa_order_filter_shop_button_class', sprintf('button add_to_cart_button wa-shop-button product_type_%s', $product->get_type()), $product);

	// Prices follow the store's tax display setting; variable products show their range.
	$price_parts = wa_order_get_product_price_parts($product);
	if ($product->is_type('grouped')) {
		$format_price = apply_filters('wa_order_filter_shop_price', wa_order_plain_text($price_parts['price']), $price_parts['price'], $product);
	} else {
		$raw_price = ('' !== $product->get_price()) ? wc_get_price_to_display($product) : '';
		$format_price = apply_filters('wa_order_filter_shop_price', wa_order_plain_text($price_parts['price']), $raw_price, $product);
	}
	$format_regular_price = wa_order_plain_text($price_parts['regular']);
	$format_sale_price = wa_order_plain_text($price_parts['sale']);

	// Labels
	$price_label	= apply_filters('wa_order_filter_shop_price_label', get_option('wa_order_option_price_label', 'Price') ?: 'Price');
	$url_label		= apply_filters('wa_order_filter_shop_url_label', get_option('wa_order_option_url_label', 'Product URL') ?: 'Product URL');
	$thanks_label	= apply_filters('wa_order_filter_shop_thanks_label', get_option('wa_order_option_thank_you_label', 'Thank You!'));

	// URL Encoding
	$encode_custom_message = urlencode($custom_msg);
	$encode_title	= urlencode(wa_order_plain_text($product_title));
	$encode_product_url = urlencode($product_url);
	$encode_thanks	= urlencode($thanks_label);
	$encode_url_label = urlencode($url_label);
	$encode_price_label = urlencode($price_label);

	$final_message	= apply_filters('wa_order_filter_shop_final_message', "$encode_custom_message%0D%0A%0D%0A*$encode_title*", $product);

	// Exclude Price
	$excludeprice = apply_filters('wa_order_filter_shop_exclude_price', get_option('wa_order_option_shop_loop_exclude_price', 'no'));
	if ($excludeprice !== 'yes') {
		// Check if "Show Regular & Sale Prices" option is enabled
		$show_regular_sale_prices = apply_filters('wa_order_filter_shop_show_regular_sale_prices', get_option('wa_order_option_shop_loop_show_regular_sale_prices', 'no'));

		if ($show_regular_sale_prices === 'yes' && '' !== $format_regular_price) {
			if ($price_parts['on_sale'] && '' !== $format_sale_price) {
				// Product is on sale - show both regular (strikethrough) and sale price
				$encoded_price = "~" . urlencode($format_regular_price) . "~ " . urlencode($format_sale_price);
			} else {
				$encoded_price = urlencode($format_regular_price);
			}
		} else {
			// Use the current/effective price (default behavior)
			$encoded_price = urlencode(wa_order_plain_text($format_price));
		}
		if ('' !== $encoded_price) {
			$final_message .= "%0A*$encode_price_label:*%20$encoded_price";
		}
	}

	// Remove product URL
	$removeproductURL	= apply_filters('wa_order_filter_shop_remove_product_url', get_option('wa_order_option_shop_loop_hide_product_url', 'no'));
	if ($removeproductURL !== 'yes') {
		$final_message .= "%0A*$encode_url_label:*%20$encode_product_url";
	}

	if ('' !== $encode_thanks) {
		$final_message .= "%0D%0A%0D%0A$encode_thanks";
	}

	$button_url = apply_filters('wa_order_filter_shop_button_url', wa_order_the_url($phonenumb, urldecode($final_message)), $phonenumb, $final_message);
	$target = apply_filters('wa_order_filter_shop_button_target', get_option('wa_order_option_shop_loop_open_new_tab', '_blank'));
?>
	<a id="sendbtn" href="<?php echo wa_order_esc_whatsapp_url($button_url); ?>" title="<?php echo esc_attr($link_title); ?>" target="<?php echo esc_attr($target); ?>" rel="noopener" class="<?php echo esc_attr($class); ?>">
		<?php echo esc_html($button_text); ?>
	</a>
<?php
}
add_action('woocommerce_after_shop_loop_item', 'wa_order_display_button_shop_page', 20);

/**
 * Add the shop button under the "Add to Cart" button of block-based product
 * grids (Product Collection, the block Product Catalog template, related
 * products in block themes), which don't fire woocommerce_after_shop_loop_item.
 *
 * @since 1.2.0
 *
 * @param string        $block_content Rendered block.
 * @param array         $parsed_block  Parsed block.
 * @param WP_Block|null $block         Block instance with context.
 * @return string
 */
function wa_order_render_block_product_button($block_content, $parsed_block, $block = null)
{
	if (is_admin() || (defined('REST_REQUEST') && REST_REQUEST)) {
		return $block_content;
	}

	$post_id = ($block instanceof WP_Block && isset($block->context['postId'])) ? (int) $block->context['postId'] : get_the_ID();
	$block_product = $post_id ? wc_get_product($post_id) : null;
	if (!$block_product) {
		return $block_content;
	}

	// Render with this product as the current one, then restore whatever was there.
	$previous_product = isset($GLOBALS['product']) ? $GLOBALS['product'] : null;
	$GLOBALS['product'] = $block_product;
	ob_start();
	wa_order_display_button_shop_page();
	$button = trim(ob_get_clean());
	$GLOBALS['product'] = $previous_product;

	if ('' === $button) {
		return $block_content;
	}
	return $block_content . '<div class="wa-order-block-shop-button">' . $button . '</div>';
}
add_filter('render_block_woocommerce/product-button', 'wa_order_render_block_product_button', 10, 3);

// Option to remove Add to Cart on Shop page product loop
if (get_option('wa_order_option_hide_atc_shop_loop') === 'yes') {
	remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
}
// Alternative way to force hide the Add to Cart button on shop loop page
add_action('wp_head', 'wa_order_alternative_way_hide_shop_loop');
function wa_order_alternative_way_hide_shop_loop()
{
	if (get_option('wa_order_option_hide_atc_shop_loop') === 'yes') {
?>
		<style>
			.add_to_cart_button:not(.wa-shop-button),
			.ajax_add_to_cart:not(.wa-shop-button) {
				display: none !important;
			}

			.wa-shop-button {
				display: inline-block !important;
			}
		</style>
<?php
	}
}

/**
 * Before 1.2.0 this printed one <style> tag per excluded product on every page,
 * based on product lists cached for an hour. Exclusions are now checked when
 * each button renders (wa_order_shop_button_is_excluded()), so this does nothing.
 *
 * @deprecated 1.2.0
 */
function wa_order_hide_shop_taxonomies()
{
}
