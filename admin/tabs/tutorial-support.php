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
<!-- Begin creating Support tab admin page -->
<div class="wrap">
    <div class="feature-section one-col wrap about-wrap">
        <div class="about-text">
            <h4><?php
                /* translators: 1. <strong> tag for "OneClick Chat to Order" */
                echo sprintf(
                    /* translators: 1. opening <strong> tag */
                    esc_html__('%1$sOneClick Chat to Order%2$s is Waiting for Your Feedback', 'oneclick-whatsapp-order'),
                    '<strong>', // opening <strong> tag
                    '</strong>' // closing <strong> tag
                );
                ?></h4>
        </div>
        <div class="indo-about-description">
            <?php
            /* translators: 1. <strong> tag for "OneClick Chat to Order", 2. <a> tag with href to the review page, 3. closing </a> tag */
            echo sprintf(
                /* translators: 1. <strong> tag for "OneClick Chat to Order" */
                /* translators: 2. opening <a> tag for "leaving a review" */
                /* translators: 3. closing <a> tag */
                esc_html__('%1$sOneClick Chat to Order%2$s is my second plugin and it\'s open source. I acknowledge that there are still a lot to fix, here and there, that\'s why I really need your feedback. Let\'s get in touch and show some love by %3$sleaving a review%4$s.', 'oneclick-whatsapp-order'),
                '<strong>', // opening <strong> tag
                '</strong>', // closing <strong> tag
                '<a href="https://wordpress.org/support/plugin/oneclick-whatsapp-order/reviews/?rate=5#new-post" target="_blank"><strong>', // opening <a> and <strong> tag
                '</strong></a>' // closing <a> and <strong> tag
            );
            ?>
        </div>
        <table class="tg" style="table-layout: fixed; width: 100%;">
            <colgroup>
                <col style="width: 80px">
                <col style="width: 500px">
            </colgroup>
            <tr>
                <th class="tg-kiyi">
                    <?php esc_html_e('Author:', 'oneclick-whatsapp-order'); ?></th>
                <th class="tg-fymr">
                    <?php esc_html_e('Walter Pinem | Online Store Kit', 'oneclick-whatsapp-order'); ?></th>
            </tr>
            <tr>
                <td class="tg-kiyi">
                    <?php esc_html_e('Website:', 'oneclick-whatsapp-order'); ?></td>
                <td class="tg-fymr"><a href="<?php echo esc_url(oskit_url('https://walterpinem.me/')); ?>" title="<?php esc_attr_e('Visit walterpinem.me', 'oneclick-whatsapp-order'); ?>" target="_blank">
                        <?php esc_html_e('walterpinem.me', 'oneclick-whatsapp-order'); ?></a></td>
            </tr>
            <tr>
                <td class="tg-kiyi">
                <td class="tg-fymr"><a href="<?php echo esc_url(oskit_url('https://walterpinem.com/')); ?>" title="<?php esc_attr_e('Visit walterpinem.com', 'oneclick-whatsapp-order'); ?>" target="_blank">
                        <?php esc_html_e('walterpinem.com', 'oneclick-whatsapp-order'); ?></a></td>
            </tr>
            <tr>
                <td class="tg-kiyi">
                <td class="tg-fymr"><a href="<?php echo esc_url(oskit_url('https://www.onlinestorekit.com/')); ?>" title="<?php esc_attr_e('Online Store Kit', 'oneclick-whatsapp-order'); ?>" target="_blank">
                        <?php esc_html_e('Online Store Kit', 'oneclick-whatsapp-order'); ?></a></td>
            </tr>
            <tr>
                <td class="tg-kiyi">
                <td class="tg-fymr"><a href="<?php echo esc_url(oskit_url('https://walterpinem.me/projects/tools/')); ?>" title="<?php esc_attr_e('75+ Free Online Tools', 'oneclick-whatsapp-order'); ?>" target="_blank">
                        <?php esc_html_e('75+ Free Online Tools', 'oneclick-whatsapp-order'); ?></a></td>
            </tr>
            <tr>
                <td class="tg-kiyi">
                    <?php esc_html_e('Docs:', 'oneclick-whatsapp-order'); ?></td>
                <td class="tg-fymr"><a href="<?php echo esc_url(oskit_url('https://www.onlinestorekit.com/docs/octo/')); ?>" title="<?php esc_attr_e('Read full documentation.', 'oneclick-whatsapp-order'); ?>" target="_blank">
                        <?php esc_html_e('Documentation', 'oneclick-whatsapp-order'); ?></a></td>
            </tr>
            <tr>
                <td class="tg-kiyi"><?php esc_html_e('More:', 'oneclick-whatsapp-order'); ?></td>
                <td class="tg-fymr"><a href="https://www.youtube.com/watch?v=LuURM5vZyB8&list=PLwazGJFvaLnBTOw4pNvPcsFW1ls4tn1Uj" title="<?php esc_attr_e('Complete Youtube Tutorial', 'oneclick-whatsapp-order'); ?>" target="_blank">
                        <?php esc_html_e('Complete Tutorial', 'oneclick-whatsapp-order'); ?></a></td>
            </tr>
            <tr>
                <td class="tg-kiyi" rowspan="3"></td>
                <td class="tg-fymr"><a href="<?php
                                                if (function_exists('wp_get_theme')) {
                                                    $current_theme = wp_get_theme();
                                                    $theme_name = $current_theme->get('Name');
                                                    $theme_ver = $current_theme->get('Version');
                                                    $theme_info = urlencode($theme_name . ' - v' . $theme_ver);
                                                }
                                                echo esc_url(oskit_url('https://www.onlinestorekit.com/support/?wpf1567_5=' . $theme_info . '&wpf1567_4=' . sanitize_title(OCTO_NAME)));
                                                ?>" title="<?php esc_attr_e('Support & Feature Request', 'oneclick-whatsapp-order'); ?>" target="_blank">
                        <?php esc_html_e('Support & Feature Request', 'oneclick-whatsapp-order'); ?></a></td>
            </tr>
        </table>
        <br>
        <hr>
        <?php echo wa_order_donate_button_shortcode(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside. ?>
        <center>
            <p><?php
                echo wp_kses_post(
                    sprintf(
                        "Made with ❤ and ☕ in Central Jakarta, Indonesia by <a href=\"%s\" target=\"_blank\"><strong>Walter Pinem</strong></a>",
                        esc_url(oskit_url('https://walterpinem.com/'))
                    )
                );
                ?></p>
        </center>
    </div>
</div>