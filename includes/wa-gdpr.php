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
 * @category    GDPR Page Selection Class
 *
 ********************************* GDPR Page Selection Class ********************************* */

class WA_GDPR
{

	// Begin creating
	function __construct()
	{
		if (get_option('wa_order_gdpr_status_enable') != 'yes')
			return;

		add_shortcode('gdpr_link', array($this, 'get_gdpr_link'));
		add_action('wa_order_action_plugin', array($this, 'display_gdpr'));
	}

	// Construct the display
	function display_gdpr()
	{
?>
		<div class="wa_order-gdpr">
			<div>
				<label for="wa_order-gdpr-checkbox">
					<input type="checkbox" id="wa_order-gdpr-checkbox"> <?php echo do_shortcode(stripslashes(do_shortcode(get_option('wa_order_gdpr_privacy_page')))) ?>
				</label>
			</div>
		</div>
<?php
	}

	// Generate GDPR Link
	function get_gdpr_link()
	{
		// Get the page ID from the option (wp_dropdown_pages saves page ID, not slug)
		$page_id = get_option('wa_order_gdpr_privacy_page');

		// Return empty if no page is selected
		if (empty($page_id)) {
			return '';
		}

		// Get page title and permalink using the page ID
		$page_title = get_the_title($page_id);
		$page_permalink = get_permalink($page_id);

		// Return empty if page doesn't exist
		if (empty($page_title) || empty($page_permalink)) {
			return '';
		}

		return "<a href='" . esc_url($page_permalink) . "' target='_blank'><strong>" . esc_html($page_title) . "</strong></a>";
	}

	// Get option if enabled
	private function _get_option($data)
	{

		$option = get_option('wa_order_gdpr_status_enable');

		return $option[$data];
	}
} // WA_GDPR

new WA_GDPR;

// GDPR Page Selection
if (! function_exists('wa_order_options_dropdown')) {
	function wa_order_options_dropdown($args)
	{
		// Fetch all published pages using get_pages()
		$pages = get_pages(array(
			'post_type'   => 'page',
			'post_status' => 'publish'
		));
		$name = ($args['name']) ? 'name="' . esc_attr($args['name']) . '" ' : '';
		$multiple = isset($args['multiple']) ? 'multiple' : '';
		echo '<select ' . esc_attr($name) . ' class="wa_order-admin-select2 regular-text" ' . esc_attr($multiple) . '>';
		foreach ($pages as $page) {
			$selected = '';
			if ($args['selected']) {
				if ($multiple) {
					$selected = in_array($page->post_name, (array)$args['selected']) ? 'selected="selected"' : '';
				} else {
					$selected = ($page->post_name == $args['selected']) ? 'selected="selected"' : '';
				}
			}
			echo '<option value="' . esc_attr($page->post_name) . '" ' . esc_attr($selected) . '>' . esc_html($page->post_title) . '</option>';
		}
		echo '</select>';
	}
}
