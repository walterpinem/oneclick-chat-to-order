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
 * @category    Add-ons Tab
 *
 ********************************* Add-ons Tab ********************************* */
?>
<?php
// The add-ons, as the house plugin grid. A dashicon plate replaced each
// add-on's raster icon in 1.3.0. Same meaning, same icon: the three
// marketplace connectors share one.
$wa_order_addons = [
    [
        'file'  => 'oneclick-wcfm-connector/oneclick-wcfm-connector.php',
        'icon'  => 'groups',
        'name'  => __('OneClick WCFM Connector', 'oneclick-whatsapp-order'),
        'desc'  => __('Easily connect OneClick WCFM Connector with WCFM - Frontend Manager for WooCommerce to enable your vendors to show and use WhatsApp on their own stores.', 'oneclick-whatsapp-order'),
        'buy'   => 'https://www.onlinestorekit.com/kit/oneclick-wcfm-connector/',
        'about' => 'https://www.onlinestorekit.com/oneclick-wcfm-connector/',
    ],
    [
        'file'  => 'oneclick-dokan-connector/oneclick-dokan-connector.php',
        'icon'  => 'groups',
        'name'  => __('OneClick Dokan Connector', 'oneclick-whatsapp-order'),
        'desc'  => __('Extend the functionality of Dokan Multivendor Marketplace by combining it with OneClick Chat to Order. Let vendors show the WhatsApp button(s) on their own products.', 'oneclick-whatsapp-order'),
        'buy'   => 'https://www.onlinestorekit.com/kit/oneclick-dokan-connector/',
        'about' => 'https://www.onlinestorekit.com/oneclick-dokan-connector/',
    ],
    [
        'file'  => 'oneclick-wcvendors-connector/oneclick-wcvendors-connector.php',
        'icon'  => 'groups',
        'name'  => __('OneClick WCVendors Connector', 'oneclick-whatsapp-order'),
        'desc'  => __('Integrate OneClick Chat to Order with WC Vendors Multivendor Marketplace plugin to allow vendors to receive orders and purchases directly through WhatsApp.', 'oneclick-whatsapp-order'),
        'buy'   => 'https://www.onlinestorekit.com/kit/oneclick-wcvendors-connector/',
        'about' => 'https://www.onlinestorekit.com/oneclick-wcvendors-connector/',
    ],
    [
        'file'   => 'oneclick-variations-grabber/oneclick-variations-grabber.php',
        'icon'   => 'forms',
        'accent' => true,
        'name'   => __('OneClick Variations Grabber', 'oneclick-whatsapp-order'),
        'desc'   => __('Let your customers send the selected product variations, quantities, and other important details from WooCommerce product pages directly to you via WhatsApp.', 'oneclick-whatsapp-order'),
        'buy'    => 'https://www.onlinestorekit.com/kit/oneclick-variations-grabber/',
        'about'  => 'https://www.onlinestorekit.com/oneclick-variations-grabber/',
    ],
    [
        'file'  => 'external-marketplace-buttons/external-marketplace-buttons.php',
        'icon'  => 'external',
        'name'  => __('External Marketplace Buttons', 'oneclick-whatsapp-order'),
        'desc'  => __('Easily add external marketplace buttons on your product pages. Extremely useful if you also sell somewhere else.', 'oneclick-whatsapp-order'),
        'buy'   => 'https://www.onlinestorekit.com/kit/external-marketplace-buttons/',
        'about' => 'https://www.onlinestorekit.com/external-marketplace-buttons/',
    ],
];
if (oskit_is_indonesian_store()) {
    $wa_order_addons[] = [
        'file'  => 'indonesian-banks-premium/indonesian-banks-premium.php',
        'icon'  => 'bank',
        'name'  => __('Indonesian Banks Premium', 'oneclick-whatsapp-order'),
        'desc'  => __('Indonesian Banks plugin consists of a collection of Indonesian banks for offline payment methods on WooCommerce-enabled online store.', 'oneclick-whatsapp-order'),
        'buy'   => 'https://www.dequixote.com/product/indonesian-banks-premium/',
        'about' => 'https://www.dequixote.com/indonesian-banks-premium/',
    ];
}
?>
<h2 class="screen-reader-text"><?php esc_html_e('OneClick Chat to Order Add-ons List', 'oneclick-whatsapp-order'); ?></h2>
<div class="octo-plugin-grid">
    <?php foreach ($wa_order_addons as $wa_order_addon) : ?>
        <div class="octo-plugin">
            <div class="octo-plugin__head">
                <span class="octo-plugin__icon octo-plugin__icon--glyph<?php echo empty($wa_order_addon['accent']) ? '' : ' octo-plugin__icon--accent'; ?> dashicons dashicons-<?php echo esc_attr($wa_order_addon['icon']); ?>" aria-hidden="true"></span>
                <div class="octo-plugin__title">
                    <h3><?php echo esc_html($wa_order_addon['name']); ?></h3>
                </div>
            </div>
            <p class="octo-plugin__description"><?php echo esc_html($wa_order_addon['desc']); ?></p>
            <div class="octo-plugin__actions">
                <?php if (is_plugin_active($wa_order_addon['file'])) : ?>
                    <span class="octo-plugin__running"><span class="dashicons dashicons-yes-alt" aria-hidden="true"></span><?php esc_html_e('Active', 'oneclick-whatsapp-order'); ?></span>
                <?php else : ?>
                    <a class="octo-button-primary" href="<?php echo esc_url(oskit_url($wa_order_addon['buy'])); ?>" target="_blank" rel="noopener"><?php esc_html_e('Install Now', 'oneclick-whatsapp-order'); ?></a>
                <?php endif; ?>
                <a class="octo-button-secondary" href="<?php echo esc_url(oskit_url($wa_order_addon['about'])); ?>" target="_blank" rel="noopener"><?php esc_html_e('More Details', 'oneclick-whatsapp-order'); ?></a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
