<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * OneClick Chat to Order Admin Settings Page
 *
 * @package     OneClick Chat to Order
 * @author      Walter Pinem <hello@walterpinem.me>
 * @link        https://walterpinem.me/
 * @link        https://www.onlinestorekit.com/oneclick-chat-to-order/
 * @copyright   Copyright (c) 2019 - 2025, Walter Pinem | Online Store Kit
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * @category    GDPR Tab
 *
 ********************************* GDPR Tab ********************************* */

// Ensure this file is only loaded in admin context
if (!is_admin()) {
    return;
}
?>

<form method="post" action="options.php">
    <?php settings_errors(); ?>
    <?php settings_fields('wa-order-settings-group-gdpr'); ?>
    <?php do_settings_sections('wa-order-settings-group-gdpr'); ?>

    <?php wa_order_card_open('privacy', __('GDPR Notice', 'oneclick-whatsapp-order')); ?>
    <p>
        <?php echo esc_html__('If you want to add a GDPR notice before the WhatsApp button, you can enable this option. This will add a checkbox that customers must check before they can use the WhatsApp button.', 'oneclick-whatsapp-order'); ?>
    </p>

    <table class="form-table">
        <tbody>
            <tr class="wa_order_target">
                <th scope="row">
                    <label for="wa_order_gdpr_status_enable"><?php echo esc_html__('Enable GDPR Notice?', 'oneclick-whatsapp-order'); ?></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_gdpr_status_enable" id="wa_order_gdpr_status_enable" class="wa_order_input_check" value="yes" <?php checked(esc_attr(get_option('wa_order_gdpr_status_enable')), 'yes'); ?>>
                    <?php echo esc_html__('Enable GDPR Notice', 'oneclick-whatsapp-order'); ?>
                </td>
            </tr>
            <tr class="wa_order_message">
                <th scope="row">
                    <label for="wa_order_gdpr_message"><?php echo esc_html__('GDPR Message', 'oneclick-whatsapp-order'); ?></label>
                </th>
                <td>
                    <textarea name="wa_order_gdpr_message" id="wa_order_gdpr_message" class="wa_order_input_areatext" rows="5" placeholder="<?php echo esc_attr__('e.g. I agree to the [gdpr_link] and consent to my personal data being processed.', 'oneclick-whatsapp-order'); ?>"><?php echo esc_textarea(get_option('wa_order_gdpr_message')); ?></textarea>
                    <p class="description">
                        <?php echo esc_html__('Enter GDPR message. Use [gdpr_link] to add a link to your Privacy Policy page. e.g. I agree to the [gdpr_link] and consent to my personal data being processed.', 'oneclick-whatsapp-order'); ?>
                    </p>
                </td>
            </tr>
            <tr class="wa_order_target">
                <th scope="row">
                    <label for="wa_order_gdpr_privacy_page"><?php echo esc_html__('Privacy Policy Page', 'oneclick-whatsapp-order'); ?></label>
                </th>
                <td>
                    <?php
                    wp_dropdown_pages(
                        array(
                            'name'              => 'wa_order_gdpr_privacy_page',
                            'id'                => 'wa_order_gdpr_privacy_page',
                            'class'             => 'wa_order_input',
                            'show_option_none'  => esc_html__('Select a page', 'oneclick-whatsapp-order'),
                            'option_none_value' => '',
                            'selected'          => get_option('wa_order_gdpr_privacy_page'),
                        )
                    );
                    ?>
                    <p class="description">
                        <?php echo esc_html__('Select your Privacy Policy page. This will be used as the link for [gdpr_link] placeholder.', 'oneclick-whatsapp-order'); ?>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
    <?php wa_order_card_close(); ?>
    <?php wa_order_save_bar(); ?>
</form>