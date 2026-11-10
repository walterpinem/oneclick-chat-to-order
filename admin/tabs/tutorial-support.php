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
 * @category    Support Tab
 *
 ********************************* Support Tab ********************************* */
?>
<?php
$wa_order_theme_info = '';
if (function_exists('wp_get_theme')) {
    $wa_order_theme      = wp_get_theme();
    $wa_order_theme_info = urlencode($wa_order_theme->get('Name') . ' - v' . $wa_order_theme->get('Version'));
}
// Our own properties go through oskit_url(); third-party links stay raw.
$wa_order_links = [
    [oskit_url('https://www.onlinestorekit.com/support/?wpf1567_5=' . $wa_order_theme_info . '&wpf1567_4=' . sanitize_title(OCTO_NAME)), __('Support & Feature Request', 'oneclick-whatsapp-order')],
    [oskit_url('https://www.onlinestorekit.com/docs/octo/'), __('Documentation', 'oneclick-whatsapp-order')],
    ['https://www.youtube.com/watch?v=LuURM5vZyB8&list=PLwazGJFvaLnBTOw4pNvPcsFW1ls4tn1Uj', __('Complete Tutorial', 'oneclick-whatsapp-order')],
    [oskit_url('https://walterpinem.me/'), __('walterpinem.me', 'oneclick-whatsapp-order')],
    [oskit_url('https://walterpinem.com/'), __('walterpinem.com', 'oneclick-whatsapp-order')],
    [oskit_url('https://www.onlinestorekit.com/'), __('Online Store Kit', 'oneclick-whatsapp-order')],
    [oskit_url('https://walterpinem.me/projects/tools/'), __('75+ Free Online Tools', 'oneclick-whatsapp-order')],
];
?>
<div class="octo-settings-grid">

    <?php
    wa_order_card_open(
        'sos',
        /* translators: 1: opening <strong> tag, 2: closing </strong> tag. Both are empty in a card title. */
        sprintf(__('%1$sOneClick Chat to Order%2$s is Waiting for Your Feedback', 'oneclick-whatsapp-order'), '', '')
    );
    ?>
        <p class="octo-card-description">
            <?php
            echo sprintf(
                /* translators: 1: opening <strong> tag, 2: closing </strong> tag, 3: opening <a> tag to the review page, 4: closing </a> tag */
                esc_html__('%1$sOneClick Chat to Order%2$s is my second plugin and it\'s open source. I acknowledge that there are still a lot to fix, here and there, that\'s why I really need your feedback. Let\'s get in touch and show some love by %3$sleaving a review%4$s.', 'oneclick-whatsapp-order'),
                '<strong>',
                '</strong>',
                '<a href="https://wordpress.org/support/plugin/oneclick-whatsapp-order/reviews/?rate=5#new-post" target="_blank" rel="noopener"><strong>',
                '</strong></a>'
            );
            ?>
        </p>
        <ul class="octo-check">
            <?php foreach ($wa_order_links as $wa_order_link) : ?>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt2" aria-hidden="true"></span>
                    <a href="<?php echo esc_url($wa_order_link[0]); ?>" target="_blank" rel="noopener"><?php echo esc_html($wa_order_link[1]); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
        <p class="octo-field__help"><?php esc_html_e('Author:', 'oneclick-whatsapp-order'); ?> <strong><?php esc_html_e('Walter Pinem | Online Store Kit', 'oneclick-whatsapp-order'); ?></strong></p>
    <?php wa_order_card_close(); ?>

    <?php wa_order_card_open('heart', __('Buy Me a Coffee', 'oneclick-whatsapp-order')); ?>
        <?php echo wa_order_donate_button_shortcode(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside. ?>
        <?php wa_order_credit_line(); ?>
    <?php wa_order_card_close(); ?>

</div>
