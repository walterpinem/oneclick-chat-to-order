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
<div class="oneclick-spacer"></div>
<div class="oneclick-addons plugin-install-tab-featured">
    <form id="plugin-filter">
        <div class="wp-list-table widefat plugin-install">
            <h2 class="screen-reader-text"><?php echo esc_html__('OneClick Chat to Order Add-ons List', 'oneclick-wa-order'); ?></h2>
            <div id="the-list">
                <!-- OneClick WCFM Connector -->
                <div class="plugin-card plugin-card-oneclick-wcfm-connector">
                    <div class="plugin-card-top">
                        <div class="name column-name">
                            <h3>
                                <?php echo esc_html__('OneClick WCFM Connector', 'oneclick-wa-order'); ?> <img src="<?php echo esc_url(OCTO_URL . 'assets/images/icon-ocwcfm.png'); ?>" class="plugin-icon" alt="OneClick WCFM Connector">
                            </h3>
                        </div>
                        <div class="action-links">
                            <ul class="plugin-action-buttons">
                                <li>
                                    <?php if (is_plugin_active('oneclick-wcfm-connector/oneclick-wcfm-connector.php')) { ?>
                                        <button type="button" class="button button-disabled" disabled="disabled"><?php echo esc_html__('Active', 'oneclick-wa-order'); ?></button>
                                    <?php } else { ?>
                                        <a class="install-now button" href="<?php echo esc_url(oskit_url('https://www.onlinestorekit.com/kit/oneclick-wcfm-connector/')); ?>" aria-label="<?php echo esc_html__('Install OneClick WCFM Connector now', 'oneclick-wa-order'); ?>" role="button" title="<?php echo esc_html__('Install OneClick WCFM Connector now', 'oneclick-wa-order'); ?>" target="_blank"><?php echo esc_html__('Install Now', 'oneclick-wa-order'); ?></a>
                                    <?php } ?>
                                </li>
                                <li><a href="<?php echo esc_url(oskit_url('https://www.onlinestorekit.com/oneclick-wcfm-connector/')); ?>" class="thickbox open-plugin-details-modal" aria-label="<?php echo esc_html__('More information about OneClick WCFM Connector', 'oneclick-wa-order'); ?>" title="<?php echo esc_html__('More information about OneClick WCFM Connector', 'oneclick-wa-order'); ?>" target="_blank"><?php echo esc_html__('More Details', 'oneclick-wa-order'); ?></a></li>
                            </ul>
                        </div>
                        <div class="desc column-description">
                            <p><?php echo esc_html__('Easily connect OneClick WCFM Connector with WCFM - Frontend Manager for WooCommerce to enable your vendors to show and use WhatsApp on their own stores.', 'oneclick-wa-order'); ?></p>
                        </div>
                    </div>
                </div>
                <!-- OneClick Dokan Connector -->
                <div class="plugin-card plugin-card-oneclick-dokan-connector">
                    <div class="plugin-card-top">
                        <div class="name column-name">
                            <h3>
                                <?php echo esc_html__('OneClick Dokan Connector', 'oneclick-wa-order'); ?> <img src="<?php echo esc_url(OCTO_URL . 'assets/images/icon-ocdc.png'); ?>" class="plugin-icon" alt="OneClick Dokan Connector">
                            </h3>
                        </div>
                        <div class="action-links">
                            <ul class="plugin-action-buttons">
                                <li>
                                    <?php if (is_plugin_active('oneclick-dokan-connector/oneclick-dokan-connector.php')) { ?>
                                        <button type="button" class="button button-disabled" disabled="disabled"><?php echo esc_html__('Active', 'oneclick-wa-order'); ?></button>
                                    <?php } else { ?>
                                        <a class="install-now button" href="<?php echo esc_url(oskit_url('https://www.onlinestorekit.com/kit/oneclick-dokan-connector/')); ?>" aria-label="<?php echo esc_html__('Install OneClick Dokan Connector now', 'oneclick-wa-order'); ?>" role="button" title="<?php echo esc_html__('Install OneClick Dokan Connector now', 'oneclick-wa-order'); ?>" target="_blank"><?php echo esc_html__('Install Now', 'oneclick-wa-order'); ?></a>
                                    <?php } ?>
                                </li>
                                <li><a href="<?php echo esc_url(oskit_url('https://www.onlinestorekit.com/oneclick-dokan-connector/')); ?>" class="thickbox open-plugin-details-modal" aria-label="<?php echo esc_html__('More information about OneClick Dokan Connector', 'oneclick-wa-order'); ?>" title="<?php echo esc_html__('More information about OneClick Dokan Connector', 'oneclick-wa-order'); ?>" target="_blank"><?php echo esc_html__('More Details', 'oneclick-wa-order'); ?></a></li>
                            </ul>
                        </div>
                        <div class="desc column-description">
                            <p><?php echo esc_html__('Extend the functionality of Dokan Multivendor Marketplace by combining it with OneClick Chat to Order. Let vendors show the WhatsApp button(s) on their own products.', 'oneclick-wa-order'); ?></p>
                        </div>
                    </div>
                </div>
                <!-- OneClick WCVendors Connector -->
                <div class="plugin-card plugin-card-oneclick-wcvendors-connector">
                    <div class="plugin-card-top">
                        <div class="name column-name">
                            <h3>
                                <?php echo esc_html__('OneClick WCVendors Connector', 'oneclick-wa-order'); ?> <img src="<?php echo esc_url(OCTO_URL . 'assets/images/icon-ocwcv.png'); ?>" class="plugin-icon" alt="OneClick WCVendors Connector">
                            </h3>
                        </div>
                        <div class="action-links">
                            <ul class="plugin-action-buttons">
                                <li>
                                    <?php if (is_plugin_active('oneclick-wcvendors-connector/oneclick-wcvendors-connector.php')) { ?>
                                        <button type="button" class="button button-disabled" disabled="disabled"><?php echo esc_html__('Active', 'oneclick-wa-order'); ?></button>
                                    <?php } else { ?>
                                        <a class="install-now button" href="<?php echo esc_url(oskit_url('https://www.onlinestorekit.com/kit/oneclick-wcvendors-connector/')); ?>" aria-label="<?php echo esc_html__('Install OneClick WCVendors Connector now', 'oneclick-wa-order'); ?>" role="button" title="<?php echo esc_html__('Install OneClick WCVendors Connector now', 'oneclick-wa-order'); ?>" target="_blank"><?php echo esc_html__('Install Now', 'oneclick-wa-order'); ?></a>
                                    <?php } ?>
                                </li>
                                <li><a href="<?php echo esc_url(oskit_url('https://www.onlinestorekit.com/oneclick-wcvendors-connector/')); ?>" class="thickbox open-plugin-details-modal" aria-label="<?php echo esc_html__('More information about OneClick WCVendors Connector', 'oneclick-wa-order'); ?>" title="<?php echo esc_html__('More information about OneClick WCVendors Connector', 'oneclick-wa-order'); ?>" target="_blank"><?php echo esc_html__('More Details', 'oneclick-wa-order'); ?></a></li>
                            </ul>
                        </div>
                        <div class="desc column-description">
                            <p><?php echo esc_html__('Integrate OneClick Chat to Order with WC Vendors Multivendor Marketplace plugin to allow vendors to receive orders and purchases directly through WhatsApp.', 'oneclick-wa-order'); ?></p>
                        </div>
                    </div>
                </div>
                <!-- OneClick Variations Grabber -->
                <div class="plugin-card plugin-card-oneclick-variations-grabber">
                    <div class="plugin-card-top">
                        <div class="name column-name">
                            <h3>
                                <?php echo esc_html__('OneClick Variations Grabber', 'oneclick-wa-order'); ?> <img src="<?php echo esc_url(OCTO_URL . 'assets/images/icon-ocvg.png'); ?>" class="plugin-icon" alt="OneClick Variations Grabber">
                            </h3>
                        </div>
                        <div class="action-links">
                            <ul class="plugin-action-buttons">
                                <li>
                                    <?php if (is_plugin_active('oneclick-variations-grabber/oneclick-variations-grabber.php')) { ?>
                                        <button type="button" class="button button-disabled" disabled="disabled"><?php echo esc_html__('Active', 'oneclick-wa-order'); ?></button>
                                    <?php } else { ?>
                                        <a class="install-now button" href="<?php echo esc_url(oskit_url('https://www.onlinestorekit.com/kit/oneclick-variations-grabber/')); ?>" aria-label="<?php echo esc_html__('Install OneClick Variations Grabber now', 'oneclick-wa-order'); ?>" role="button" title="<?php echo esc_html__('Install OneClick Variations Grabber now', 'oneclick-wa-order'); ?>" target="_blank"><?php echo esc_html__('Install Now', 'oneclick-wa-order'); ?></a>
                                    <?php } ?>
                                </li>
                                <li><a href="<?php echo esc_url(oskit_url('https://www.onlinestorekit.com/oneclick-variations-grabber/')); ?>" class="thickbox open-plugin-details-modal" aria-label="<?php echo esc_html__('More information about OneClick Variations Grabber', 'oneclick-wa-order'); ?>" title="<?php echo esc_html__('More information about OneClick Variations Grabber', 'oneclick-wa-order'); ?>" target="_blank"><?php echo esc_html__('More Details', 'oneclick-wa-order'); ?></a></li>
                            </ul>
                        </div>
                        <div class="desc column-description">
                            <p><?php echo esc_html__('Let your customers send the selected product variations, quantities, and other important details from WooCommerce product pages directly to you via WhatsApp.', 'oneclick-wa-order'); ?></p>
                        </div>
                    </div>
                </div>
                <!-- External Marketplace Buttons -->
                <div class="plugin-card plugin-card-external-marketplace-buttons">
                    <div class="plugin-card-top">
                        <div class="name column-name">
                            <h3>
                                <?php echo esc_html__('External Marketplace Buttons', 'oneclick-wa-order'); ?> <img src="<?php echo esc_url(OCTO_URL . 'assets/images/icon-emb.png'); ?>" class="plugin-icon" alt="External Marketplace Buttons">
                            </h3>
                        </div>
                        <div class="action-links">
                            <ul class="plugin-action-buttons">
                                <li>
                                    <?php if (is_plugin_active('external-marketplace-buttons/external-marketplace-buttons.php')) { ?>
                                        <button type="button" class="button button-disabled" disabled="disabled"><?php echo esc_html__('Active', 'oneclick-wa-order'); ?></button>
                                    <?php } else { ?>
                                        <a class="install-now button" href="<?php echo esc_url(oskit_url('https://www.onlinestorekit.com/kit/external-marketplace-buttons/')); ?>" aria-label="<?php echo esc_html__('Install External Marketplace Buttons now', 'oneclick-wa-order'); ?>" role="button" title="<?php echo esc_html__('Install External Marketplace Buttons now', 'oneclick-wa-order'); ?>" target="_blank"><?php echo esc_html__('Install Now', 'oneclick-wa-order'); ?></a>
                                    <?php } ?>
                                </li>
                                <li><a href="<?php echo esc_url(oskit_url('https://www.onlinestorekit.com/external-marketplace-buttons/')); ?>" class="thickbox open-plugin-details-modal" aria-label="<?php echo esc_html__('More information about External Marketplace Buttons', 'oneclick-wa-order'); ?>" title="<?php echo esc_html__('More information about External Marketplace Buttons', 'oneclick-wa-order'); ?>" target="_blank"><?php echo esc_html__('More Details', 'oneclick-wa-order'); ?></a></li>
                            </ul>
                        </div>
                        <div class="desc column-description">
                            <p><?php echo esc_html__('Easily add external marketplace buttons on your product pages. Extremely useful if you also sell somewhere else.', 'oneclick-wa-order'); ?></p>
                        </div>
                    </div>
                </div>
                <?php
                // Call the function to determine if it's an Indonesian store
                $include_ibp = oskit_is_indonesian_store();

                // Use the result of the function call in the conditional statement
                if ($include_ibp) :
                ?>
                    <!-- Indonesian Banks Premium -->
                    <div class="plugin-card plugin-card-indonesian-banks-premium">
                        <div class="plugin-card-top">
                            <div class="name column-name">
                                <h3>
                                    <?php echo esc_html__('Indonesian Banks Premium', 'oneclick-wa-order'); ?> <img src="<?php echo esc_url(OCTO_URL . 'assets/images/icon-ibp.png'); ?>" class="plugin-icon" alt="Indonesian Banks Premium">
                                </h3>
                            </div>
                            <div class="action-links">
                                <ul class="plugin-action-buttons">
                                    <li>
                                        <?php if (is_plugin_active('indonesian-banks-premium/indonesian-banks-premium.php')) { ?>
                                            <button type="button" class="button button-disabled" disabled="disabled"><?php echo esc_html__('Active', 'oneclick-wa-order'); ?></button>
                                        <?php } else { ?>
                                            <a class="install-now button" href="<?php echo esc_url(oskit_url('https://www.dequixote.com/product/indonesian-banks-premium/')); ?>" aria-label="<?php echo esc_html__('Install Indonesian Banks Premium now', 'oneclick-wa-order'); ?>" role="button" title="<?php echo esc_html__('Install Indonesian Banks Premium now', 'oneclick-wa-order'); ?>" target="_blank"><?php echo esc_html__('Install Now', 'oneclick-wa-order'); ?></a>
                                        <?php } ?>
                                    </li>
                                    <li><a href="<?php echo esc_url(oskit_url('https://www.dequixote.com/indonesian-banks-premium/')); ?>" class="thickbox open-plugin-details-modal" aria-label="<?php echo esc_html__('More information about Indonesian Banks Premium', 'oneclick-wa-order'); ?>" title="<?php echo esc_html__('More information about Indonesian Banks Premium', 'oneclick-wa-order'); ?>" target="_blank"><?php echo esc_html__('More Details', 'oneclick-wa-order'); ?></a></li>
                                </ul>
                            </div>
                            <div class="desc column-description">
                                <p><?php echo esc_html__('Indonesian Banks plugin consists of a collection of Indonesian banks for offline payment methods on WooCommerce-enabled online store.', 'oneclick-wa-order'); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>