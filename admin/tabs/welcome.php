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
<!-- Begin creating plugin admin page -->
<div class="wrap">
    <div class="feature-section one-col wrap about-wrap">
        <div class="indo-title-text">
            <h2><?php echo wp_kses_post('Thank you for using <br><strong>OneClick Chat to Order</strong>', 'oneclick-wa-order'); ?></h2>
            <img src="<?php echo esc_url(OCTO_URL . 'assets/images/oneclick-chat-to-order.png'); ?>" alt="<?php esc_attr_e('OneClick Chat to Order banner', 'oneclick-wa-order'); ?>" />
        </div>
        <div class="feature-section one-col about-text">
            <h3><?php esc_html_e("Make It Easy for Customers to Reach You!", 'oneclick-wa-order'); ?></h3>
        </div>
        <div class="feature-section one-col indo-about-description">
            <p>
                <?php esc_html_e('OneClick Chat to Order seamlessly integrates your WooCommerce-based online store with WhatsApp, providing your customers with an efficient and straightforward method to connect with you and finalize their transactions via WhatsApp.', 'oneclick-wa-order'); ?>
            </p>
            <p>
                <a href="<?php echo esc_url(oskit_url('https://www.onlinestorekit.com/oneclick-chat-to-order/')); ?>" class="button button-primary" target="_blank" rel="noopener"><?php esc_html_e('Learn More', 'oneclick-wa-order'); ?></a>
                <a href="<?php echo esc_url(oskit_url('https://www.onlinestorekit.com/docs/octo/')); ?>" class="button button-secondary" target="_blank" rel="noopener"><?php esc_html_e('Read Documentation', 'oneclick-wa-order'); ?></a>
            </p>
        </div>
        <div class="clear"></div>
        <hr>
        <div class="feature-section one-col about-text">
            <h4><?php echo wp_kses_post(__('<strong style="color:red;">NEW!</strong> OneClick Variations Grabber', 'oneclick-wa-order')); ?></h4>
        </div>
        <div class="feature-section one-col indo-about-description">
            <p>
                <?php esc_html_e('A powerful plugin add-on that enhances customer interactions by allowing them to easily send selected product variations, quantities, and additional details directly through WhatsApp, making the order process smoother and more efficient.', 'oneclick-wa-order'); ?>
            </p>
            <p>
                <a href="<?php echo esc_url(oskit_url('https://www.onlinestorekit.com/oneclick-variations-grabber/')); ?>" class="button button-primary" target="_blank" rel="noopener"><?php esc_html_e('Read Details', 'oneclick-wa-order'); ?></a>
                <a href="https://www.youtube.com/watch?v=R0th4wQhb6c&list=PLwazGJFvaLnBfmpEu2PUcoxDtws55LI_D" class="button button-secondary" target="_blank" rel="noopener"><?php esc_html_e('Watch Video', 'oneclick-wa-order'); ?></a>
                <a href="<?php echo admin_url('admin.php?page=wa-order&tab=addons'); ?>" class="button button-primary" target="_blank" rel="noopener"><?php esc_html_e('More Add-ons', 'oneclick-wa-order'); ?></a>

            </p>
        </div>
        <div class="clear"></div>
        <hr />
        <div class="feature-section one-col">
            <h3 style="text-align: center;"><?php esc_html_e('Watch the Complete Overview and Tutorial', 'oneclick-wa-order'); ?></h3>
            <div class="headline-feature feature-video">
                <div class='embed-container'>
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/videoseries?si=P4KW9wnME3q2Mqvj&amp;list=PLwazGJFvaLnBTOw4pNvPcsFW1ls4tn1Uj" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <hr />
        <div class="feature-section one-col">
            <div class="indo-get-started">
                <h3><?php esc_html_e('Let\'s Get Started', 'oneclick-wa-order'); ?></h3>
                <ul>
                    <li><strong><?php esc_html_e('Step #1:', 'oneclick-wa-order'); ?></strong> <?php esc_html_e('Start adding your WhatsApp number on WhatsApp Numbers post type. You can add unlimited numbers! Learn more or dismiss notice.', 'oneclick-wa-order'); ?></li>
                    <li><strong><?php esc_html_e('Step #2:', 'oneclick-wa-order'); ?></strong> <?php esc_html_e('Show a fancy Floating Button with customized message and tooltip which you can customize easily on Floating Button setting panel.', 'oneclick-wa-order'); ?></li>
                    <li><strong><?php esc_html_e('Step #3:', 'oneclick-wa-order'); ?></strong> <?php esc_html_e('Configure some options to display or hide buttons, including the WhatsApp button on Display Options setting panel.', 'oneclick-wa-order'); ?></li>
                    <li><strong><?php esc_html_e('Step #4:', 'oneclick-wa-order'); ?></strong> <?php esc_html_e('Make your online store GDPR-ready by showing GDPR Notice right under the WhatsApp Order button on GDPR Notice setting panel.', 'oneclick-wa-order'); ?></li>
                    <li><strong><?php esc_html_e('Step #5:', 'oneclick-wa-order'); ?></strong> <?php esc_html_e('Display WhatsApp button anywhere you like with a single shortcode. You can generate it with a customized message and a nice text on button on Generate Shortcode setting panel.', 'oneclick-wa-order'); ?></li>
                    <li><strong><?php esc_html_e('Step #6:', 'oneclick-wa-order'); ?></strong> <?php esc_html_e('Have an inquiry? Find out how to reach me out on Support panel.', 'oneclick-wa-order'); ?></li>
                </ul>
            </div>
        </div>
        <hr>
        <div class="feature-section two-col">
            <div class="col">
                <img src="<?php echo esc_url(plugin_dir_url(dirname(dirname(__FILE__))) . 'assets/images/simple-chat-button.png'); ?>" alt="<?php esc_attr_e('Simple Chat Button', 'oneclick-wa-order'); ?>" />
                <h3><?php esc_html_e('Simple Chat to Order Button', 'oneclick-wa-order'); ?></h3>
                <p><?php esc_html_e('Replace the default Add to Cart button or simply show both. Once the Chat to Order button is clicked, the message along with the product details are sent to you through WhatsApp.', 'oneclick-wa-order'); ?></p>
            </div>
            <div class="col">
                <img src="<?php echo esc_url(plugin_dir_url(dirname(dirname(__FILE__))) . 'assets/images/fancy-floating-button.png'); ?>" alt="<?php esc_attr_e('Fancy Floating Button', 'oneclick-wa-order'); ?>" />
                <h3><?php esc_html_e('Fancy Floating Button', 'oneclick-wa-order'); ?></h3>
                <p><?php esc_html_e('Make it easy for any customers/visitors to reach you out through a click of a floating WhatsApp button, displayed on the left of right with tons of customization options.', 'oneclick-wa-order'); ?></p>
            </div>
        </div>
        <div class="feature-section two-col">
            <div class="col">
                <img src="<?php echo esc_url(plugin_dir_url(dirname(dirname(__FILE__))) . 'assets/images/display-this-or-hide-that.png'); ?>" alt="<?php esc_attr_e('Display or Hide Elements', 'oneclick-wa-order'); ?>" />
                <h3><?php esc_html_e('Display This or Hide That', 'oneclick-wa-order'); ?></h3>
                <p><?php esc_html_e('Wanna hide some buttons or elements you don\'t like? You have the command to rule them all. Just visit the panel and all of the options are there to configure.', 'oneclick-wa-order'); ?></p>
            </div>
            <div class="col">
                <img src="<?php echo esc_url(plugin_dir_url(dirname(dirname(__FILE__))) . 'assets/images/gdpr-ready.png'); ?>" alt="<?php esc_attr_e('GDPR Ready', 'oneclick-wa-order'); ?>" />
                <h3><?php esc_html_e('Make It GDPR-Ready', 'oneclick-wa-order'); ?></h3>
                <p><?php esc_html_e('The regulations are real and it\'s time to make your site ready for them. Make your site GDPR-ready with some simple configurations, really easy!', 'oneclick-wa-order'); ?></p>
            </div>
        </div>
        <div class="feature-section two-col">
            <div class="col">
                <img src="<?php echo esc_url(plugin_dir_url(dirname(dirname(__FILE__))) . 'assets/images/shortcode.png'); ?>" alt="<?php esc_attr_e('Shortcode Generator', 'oneclick-wa-order'); ?>" />
                <h3><?php esc_html_e('Shortcode Generator', 'oneclick-wa-order'); ?></h3>
                <p><?php esc_html_e('Are the previous options still not enough for you? You can extend the flexibility to display a WhatsApp button using a shortcode, which you can generate easily.', 'oneclick-wa-order'); ?></p>
            </div>
            <div class="col">
                <img src="<?php echo esc_url(plugin_dir_url(dirname(dirname(__FILE__))) . 'assets/images/documentation.png'); ?>" alt="<?php esc_attr_e('Comprehensive Documentation', 'oneclick-wa-order'); ?>" />
                <h3><?php esc_html_e('Comprehensive Documentation', 'oneclick-wa-order'); ?></h3>
                <p><?php esc_html_e('You will not be left alone. My complete documentation or tutorial will always help and support all your needs to get started. Watch tutorial videos.', 'oneclick-wa-order'); ?></p>
            </div>
        </div>
        <br>
        <hr>
        <?php echo do_shortcode('[donate]'); ?>
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
<br>