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
 * @category    Welcome Tab
 *
 ********************************* Welcome Tab ********************************* */
?>
<div class="octo-settings-grid octo-settings-grid--2col">

    <?php wa_order_card_open('controls-play', __('Make It Easy for Customers to Reach You!', 'oneclick-whatsapp-order')); ?>
        <p><?php esc_html_e('OneClick Chat to Order seamlessly integrates your WooCommerce-based online store with WhatsApp, providing your customers with an efficient and straightforward method to connect with you and finalize their transactions via WhatsApp.', 'oneclick-whatsapp-order'); ?></p>
        <div class="octo-card-actions">
            <a href="<?php echo esc_url(oskit_url('https://www.onlinestorekit.com/oneclick-chat-to-order/')); ?>" class="octo-button-primary" target="_blank" rel="noopener"><?php esc_html_e('Learn More', 'oneclick-whatsapp-order'); ?></a>
            <a href="<?php echo esc_url(oskit_url('https://www.onlinestorekit.com/docs/octo/')); ?>" class="octo-button-secondary" target="_blank" rel="noopener"><?php esc_html_e('Read Documentation', 'oneclick-whatsapp-order'); ?></a>
        </div>
    <?php wa_order_card_close(); ?>

    <?php wa_order_card_open('forms', __('OneClick Variations Grabber', 'oneclick-whatsapp-order')); ?>
        <p><?php esc_html_e('A powerful plugin add-on that enhances customer interactions by allowing them to easily send selected product variations, quantities, and additional details directly through WhatsApp, making the order process smoother and more efficient.', 'oneclick-whatsapp-order'); ?></p>
        <div class="octo-card-actions">
            <a href="<?php echo esc_url(oskit_url('https://www.onlinestorekit.com/oneclick-variations-grabber/')); ?>" class="octo-button-primary" target="_blank" rel="noopener"><?php esc_html_e('Read Details', 'oneclick-whatsapp-order'); ?></a>
            <a href="https://www.youtube.com/watch?v=R0th4wQhb6c&amp;list=PLwazGJFvaLnBfmpEu2PUcoxDtws55LI_D" class="octo-button-secondary" target="_blank" rel="noopener"><?php esc_html_e('Watch Video', 'oneclick-whatsapp-order'); ?></a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=wa-order&tab=addons')); ?>" class="octo-button-secondary"><?php esc_html_e('More Add-ons', 'oneclick-whatsapp-order'); ?></a>
        </div>
    <?php wa_order_card_close(); ?>

    <?php wa_order_card_open('list-view', __('Let\'s Get Started', 'oneclick-whatsapp-order')); ?>
        <ol class="octo-steps">
            <li><?php esc_html_e('Start adding your WhatsApp number on WhatsApp Numbers post type. You can add unlimited numbers! Learn more or dismiss notice.', 'oneclick-whatsapp-order'); ?></li>
            <li><?php esc_html_e('Show a fancy Floating Button with customized message and tooltip which you can customize easily on Floating Button setting panel.', 'oneclick-whatsapp-order'); ?></li>
            <li><?php esc_html_e('Configure some options to display or hide buttons, including the WhatsApp button on Display Options setting panel.', 'oneclick-whatsapp-order'); ?></li>
            <li><?php esc_html_e('Make your online store GDPR-ready by showing GDPR Notice right under the WhatsApp Order button on GDPR Notice setting panel.', 'oneclick-whatsapp-order'); ?></li>
            <li><?php esc_html_e('Display WhatsApp button anywhere you like with a single shortcode. You can generate it with a customized message and a nice text on button on Generate Shortcode setting panel.', 'oneclick-whatsapp-order'); ?></li>
            <li><?php esc_html_e('Have an inquiry? Find out how to reach me out on Support panel.', 'oneclick-whatsapp-order'); ?></li>
        </ol>
    <?php wa_order_card_close(); ?>

    <?php wa_order_card_open('video-alt3', __('Watch the Complete Overview and Tutorial', 'oneclick-whatsapp-order')); ?>
        <div class="octo-embed">
            <iframe src="https://www.youtube.com/embed/videoseries?list=PLwazGJFvaLnBTOw4pNvPcsFW1ls4tn1Uj" title="<?php esc_attr_e('Watch the Complete Overview and Tutorial', 'oneclick-whatsapp-order'); ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>
    <?php wa_order_card_close(); ?>


</div>

<?php
// What the plugin does: one card per feature, each with the dashicon of the
// tab that configures it. These replaced the 600x180 text images in 1.3.0.
$wa_order_features = [
    ['cart', __('Simple Chat to Order Button', 'oneclick-whatsapp-order'), __('Replace the default Add to Cart button or simply show both. Once the Chat to Order button is clicked, the message along with the product details are sent to you through WhatsApp.', 'oneclick-whatsapp-order')],
    ['format-chat', __('Fancy Floating Button', 'oneclick-whatsapp-order'), __('Make it easy for any customers/visitors to reach you out through a click of a floating WhatsApp button, displayed on the left of right with tons of customization options.', 'oneclick-whatsapp-order')],
    ['visibility', __('Display This or Hide That', 'oneclick-whatsapp-order'), __('Wanna hide some buttons or elements you don\'t like? You have the command to rule them all. Just visit the panel and all of the options are there to configure.', 'oneclick-whatsapp-order')],
    ['privacy', __('Make It GDPR-Ready', 'oneclick-whatsapp-order'), __('The regulations are real and it\'s time to make your site ready for them. Make your site GDPR-ready with some simple configurations, really easy!', 'oneclick-whatsapp-order')],
    ['shortcode', __('Shortcode Generator', 'oneclick-whatsapp-order'), __('Are the previous options still not enough for you? You can extend the flexibility to display a WhatsApp button using a shortcode, which you can generate easily.', 'oneclick-whatsapp-order')],
    ['book', __('Comprehensive Documentation', 'oneclick-whatsapp-order'), __('You will not be left alone. My complete documentation or tutorial will always help and support all your needs to get started. Watch tutorial videos.', 'oneclick-whatsapp-order')],
];
?>
<div class="octo-plugin-grid octo-plugin-grid--3col">
    <?php foreach ($wa_order_features as $wa_order_feature) : ?>
        <div class="octo-plugin">
            <div class="octo-plugin__head">
                <span class="octo-plugin__icon octo-plugin__icon--glyph dashicons dashicons-<?php echo esc_attr($wa_order_feature[0]); ?>" aria-hidden="true"></span>
                <div class="octo-plugin__title">
                    <h3><?php echo esc_html($wa_order_feature[1]); ?></h3>
                </div>
            </div>
            <p class="octo-plugin__description"><?php echo esc_html($wa_order_feature[2]); ?></p>
        </div>
    <?php endforeach; ?>
</div>

<?php echo wa_order_donate_button_shortcode(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside. ?>
<?php wa_order_credit_line(); ?>
