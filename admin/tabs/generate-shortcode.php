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
 * @category    Shortcode Tab
 *
 ********************************* Shortcode Tab ********************************* */
?>
<?php wp_enqueue_script('wa_order_js_admin'); ?>
<h2 class="section_wa_order"><?php esc_html_e('Generate Shortcode', 'oneclick-wa-order'); ?></h2>
<p>
    <?php esc_html_e('Use shortcode to display OneClick Chat to Order\'s WhatsApp button anywhere on your site. There are three options; single product, global and dynamic.', 'oneclick-wa-order'); ?>
    <br />
</p>

<hr />
<h3 class="section_wa_order"><?php esc_html_e('Single Product Shortcode Generator', 'oneclick-wa-order'); ?></h3>
<p>
    <?php esc_html_e('Create a dynamic shortcode for a single product page. Note: This shortcode will only work for single products.', 'oneclick-wa-order'); ?>
    <br />
    <?php echo esc_html__('All other options will be pulled from the Single Product Page settings under the ', 'oneclick-wa-order') . ' <a href="admin.php?page=wa-order&tab=button_config"><b>' . esc_html__('Basic', 'oneclick-wa-order') . '</b></a> tab.'; ?>
    <br />
</p>
<hr />
<!-- Single Product Shortcode Generator -->
<form>
    <table class="form-table">
        <tbody>
            <!-- Dropdown WA Number -->
            <tr>
                <th scope="row">
                    <label><?php echo esc_html_e('WhatsApp Number', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <?php wa_order_phone_numbers_dropdown_shortcode_generator(
                        array(
                            'name'      => 'wa_order_phone_numbers_dropdown_shortcode_generator',
                            'selected'  => esc_attr(get_option('wa_order_selected_wa_number_shortcode')),
                        )
                    ); ?>
                    <p class="description">
                        <?php
                        /* translators: 1. opening <strong> tag with inline red color style for "required", 2. closing </strong> tag, 3. opening <a> tag for "Numbers", 4. closing </a> tag */
                        echo wp_kses(
                            sprintf(
                                /* translators: 1. opening <strong> tag with inline red color style for "required", 2. closing </strong> tag, 3. opening <a> tag for "Numbers", 4. closing </a> tag */
                                __('WhatsApp number is %1$srequired%2$s. Please set it on the %3$sNumbers%4$s tab.', 'oneclick-wa-order'),
                                '<strong style="color:red;">', // opening <strong> tag with red color style
                                '</strong>', // closing <strong> tag
                                '<a href="edit.php?post_type=wa-order-numbers"><strong>', // opening <a> and <strong> tag
                                '</strong></a>' // closing <a> and <strong> tag
                            ),
                            array(
                                'strong' => array('style' => array()), // Allow strong with style attribute
                                'a' => array('href' => array(), 'target' => array()) // Allow a tag with href and target attributes
                            )
                        );
                        ?>
                    </p>
                </td>
            </tr>
            <!-- For Which Product? -->
            <tr class="wa_order_message">
                <th scope="row">
                    <label for="SingleWAWhichPage"><?php echo esc_html__('For Which Product?', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <select name="SingleWAWhichPage" id="SingleWAWhichPage" onChange="generateSingleWAshortcode();" class="wa_order-admin-select2 regular-text">
                        <option value="current"><?php echo esc_html__('Current Product', 'oneclick-wa-order'); ?></option>
                        <option value="product_id"><?php echo esc_html__('Product by ID', 'oneclick-wa-order'); ?></option>
                    </select>
                    <p class="description">
                        - <?php echo esc_html__('If you choose ', 'oneclick-wa-order') . ' <b>' . esc_html__('Current Product', 'oneclick-wa-order') . '</b>' . esc_html__(', the shortcode will automatically pull in the product details where you place the shortcode.', 'oneclick-wa-order'); ?>
                        <br>
                        - <?php echo esc_html__('On the other hand, if you choose ', 'oneclick-wa-order') . ' <b>' . esc_html__('Product by ID', 'oneclick-wa-order') . '</b>' . esc_html__(', just enter a  product ID and it\'ll pull in the details for that product instead.', 'oneclick-wa-order'); ?>
                    </p>
                </td>
            </tr>
            <!-- Product by ID -->
            <tr class="wa_order_message">
                <th scope="row">
                    <label for="SingleWAProductID"><?php echo esc_html__('Product ID', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <input type="number" id="SingleWAProductID" name="SingleWAProductID" onChange="generateSingleWAshortcode();" class="wa_order_input" placeholder="<?php echo esc_attr__('e.g. 23', 'oneclick-wa-order'); ?>">
                    <p class="description">
                        <?php echo esc_html__('Insert a valid ', 'oneclick-wa-order') . ' <b>' . esc_html__('Product ID', 'oneclick-wa-order') . '</b>' . esc_html__('. Ensure it\'s available and published.', 'oneclick-wa-order'); ?>
                        <br>
                    </p>
                </td>
            </tr>
            <!-- Text on Button -->
            <tr class="wa_order_btn_text">
                <th scope="row">
                    <label class="wa_order_btn_txt_label" for="SingleWAbuttonText"><b><?php echo esc_html__('Text on Button', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="text" id="SingleWAbuttonText" name="SingleWAbuttonText" onChange="generateSingleWAshortcode();" class="wa_order_input" placeholder="<?php echo esc_attr__('e.g. Order via WhatsApp', 'oneclick-wa-order'); ?>">
                    <p class="description">
                        <?php echo esc_html__('Enter button text, e.g.', 'oneclick-wa-order') . ' <code>' . esc_html__('Order via WhatsApp', 'oneclick-wa-order') . '</code>'; ?>
                        <br>
                        <?php echo esc_html__('If empty, first the shortcode will use the single product\'s button text value, then the global single product text on button.', 'oneclick-wa-order'); ?>
                    </p>
                </td>
            </tr>
            <!-- Custom Message -->
            <tr class="wa_order_message">
                <th scope="row">
                    <label class="wa_order_message_label" for="SingleWAcustomMessage"><b><?php echo esc_html__('Custom Message', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <textarea class="wa_order_input_areatext" rows="5" placeholder="<?php echo esc_attr__('e.g. Hello, I need to know more about', 'oneclick-wa-order'); ?>" id="SingleWAcustomMessage" name="SingleWAcustomMessage" onChange="generateSingleWAshortcode();"></textarea>
                    <p class="description">
                        <?php echo esc_html__('Enter custom message, e.g.', 'oneclick-wa-order') . ' <code>' . esc_html__('Hello, I need to know more about', 'oneclick-wa-order') . '</code>'; ?>
                        <br>
                        <?php echo esc_html__('If empty, first the shortcode will use the single product\'s custom message, then the global single product message.', 'oneclick-wa-order'); ?>
                    </p>
                </td>
            </tr>
            <!-- Force Fullwidth? -->
            <tr class="wa_order_message">
                <th scope="row">
                    <label for="SingleWAFullwidth"><?php echo esc_html__('Force Fullwidth?', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <select name="SingleWAFullwidth" id="SingleWAFullwidth" onChange="generateSingleWAshortcode();" class="wa_order-admin-select2 regular-text">
                        <option value="false"><?php echo esc_html__('No', 'oneclick-wa-order'); ?></option>
                        <option value="true"><?php echo esc_html__('Yes', 'oneclick-wa-order'); ?></option>
                    </select>
                </td>
            </tr>
            <!-- Copy Shortcode -->
            <tr class="wa_order_message">
                <th scope="row">
                    <label class="wa_order_message_label" for="generatedSingleWAShortcode"><b><?php echo esc_html__('Copy Shortcode', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <textarea class="wa_order_input_areatext" rows="5" id="generatedSingleWAShortcode" onclick="this.setSelectionRange(0, this.value.length)"></textarea>
                    <p class="description">
                        <?php echo esc_html__('Copy above shortcode and paste it anywhere.', 'oneclick-wa-order'); ?></p>
                </td>
            </tr>
        </tbody>
    </table>
</form>
<hr />
<!-- End - Single Product Shortcode Generator -->

<h3 class="section_wa_order"><?php esc_html_e('General Shortcode Generator', 'oneclick-wa-order'); ?></h3>
<p>
    <?php esc_html_e('Create a general purpose shortcode using the following generator.', 'oneclick-wa-order'); ?>
    <br />
</p>
<hr />
<form>
    <!-- Shortcode Generator -->
    <table class="form-table">
        <tbody>
            <!-- Dropdown WA Number -->
            <tr>
                <th scope="row">
                    <label><?php echo esc_html_e('WhatsApp Number', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <?php wa_order_phone_numbers_dropdown_shortcode_generator(
                        array(
                            'name'      => 'wa_order_phone_numbers_dropdown_shortcode_generator',
                            'selected'  => esc_attr(get_option('wa_order_selected_wa_number_shortcode')),
                        )
                    ); ?>
                    <p class="description">
                        <?php
                        /* translators: 1. opening <strong> tag with inline red color style for "required", 2. closing </strong> tag, 3. opening <a> tag for "Numbers", 4. closing </a> tag */
                        echo wp_kses(
                            sprintf(
                                /* translators: 1. opening <strong> tag with inline red color style for "required", 2. closing </strong> tag, 3. opening <a> tag for "Numbers", 4. closing </a> tag */
                                __('WhatsApp number is %1$srequired%2$s. Please set it on the %3$sNumbers%4$s tab.', 'oneclick-wa-order'),
                                '<strong style="color:red;">', // opening <strong> tag with red color style
                                '</strong>', // closing <strong> tag
                                '<a href="edit.php?post_type=wa-order-numbers"><strong>', // opening <a> and <strong> tag
                                '</strong></a>' // closing <a> and <strong> tag
                            ),
                            array(
                                'strong' => array('style' => array()), // Allow strong with style attribute
                                'a' => array('href' => array(), 'target' => array()) // Allow a tag with href and target attributes
                            )
                        );
                        ?>
                    </p>
                </td>
            </tr>
            <!-- Text on Button -->
            <tr class="wa_order_btn_text">
                <th scope="row">
                    <label class="wa_order_btn_txt_label" for="WAbuttonText"><b><?php echo esc_html__('Text on Button', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="text" id="WAbuttonText" name="WAbuttonText" onChange="generateWAshortcode();" class="wa_order_input" placeholder="<?php echo esc_attr__('e.g. Order via WhatsApp', 'oneclick-wa-order'); ?>">
                </td>
            </tr>
            <!-- Custom Message -->
            <tr class="wa_order_message">
                <th scope="row">
                    <label class="wa_order_message_label" for="message_wbw"><b><?php echo esc_html__('Custom Message', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <textarea class="wa_order_input_areatext" rows="5" placeholder="<?php echo esc_attr__('e.g. Hello, I need to know more about', 'oneclick-wa-order'); ?>" id="WAcustomMessage" name="WAcustomMessage" onChange="generateWAshortcode();"></textarea>
                    <p class="description">
                        <?php echo esc_html__('Enter custom message, e.g.', 'oneclick-wa-order') . ' <code>' . esc_html__('Hello, I need to know more about', 'oneclick-wa-order') . '</code>'; ?>
                    </p>
                </td>
            </tr>
            <!-- Open in New Tab? -->
            <tr class="wa_order_message">
                <th scope="row">
                    <label for="WAnewTab"><?php echo esc_html__('Open in New Tab?', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <select name="WAnewTab" id="WAnewTab" onChange="generateWAshortcode();">
                        <option value="no"><?php echo esc_html__('No', 'oneclick-wa-order'); ?></option>
                        <option value="yes"><?php echo esc_html__('Yes', 'oneclick-wa-order'); ?></option>
                    </select>
                </td>
            </tr>
            <!-- Copy Shortcode -->
            <tr class="wa_order_message">
                <th scope="row">
                    <label class="wa_order_message_label" for="message_wbw"><b><?php echo esc_html__('Copy Shortcode', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <textarea class="wa_order_input_areatext" rows="5" id="generatedShortcode" onclick="this.setSelectionRange(0, this.value.length)"></textarea>
                    <p class="description">
                        <?php echo esc_html__('Copy above shortcode and paste it anywhere.', 'oneclick-wa-order'); ?></p>
                </td>
            </tr>
        </tbody>
    </table>
</form>
<hr />
<!-- End - Shortcode Generator -->
<!-- Start Global Shortcode -->
<form method="post" action="options.php">
    <?php settings_errors(); ?>
    <?php settings_fields('wa-order-settings-group-shortcode'); ?>
    <?php do_settings_sections('wa-order-settings-group-shortcode'); ?>
    <h3 class="section_wa_order"><?php echo esc_html__('Global Shortcode', 'oneclick-wa-order'); ?></h3>
    <p>
        <?php echo esc_html__('You need to click the', 'oneclick-wa-order') . ' <b>' . esc_html__('Save Changes', 'oneclick-wa-order') . '</b> ' . esc_html__('button below in order to use the', 'oneclick-wa-order') . ' <code>[wa-order]</code> ' . esc_html__('shortcode.', 'oneclick-wa-order'); ?>
    </p>
    <table class="form-table">
        <tbody>
            <!-- Dropdown WA Number -->
            <tr>
                <th scope="row">
                    <label><?php echo esc_html__('WhatsApp Number', 'oneclick-wa-order'); ?></label>
                </th>
                <td>
                    <?php wa_order_phone_numbers_dropdown(
                        array(
                            'name'      => 'wa_order_selected_wa_number_shortcode',
                            'selected'  => esc_attr(get_option('wa_order_selected_wa_number_shortcode')),
                        )
                    ); ?>
                    <p class="description">
                        <?php
                        /* translators: 1. opening <strong> tag with inline red color style for "required", 2. closing </strong> tag, 3. opening <a> tag for "Numbers", 4. closing </a> tag */
                        echo wp_kses(
                            sprintf(
                                /* translators: 1. opening <strong> tag with inline red color style for "required", 2. closing </strong> tag, 3. opening <a> tag for "Numbers", 4. closing </a> tag */
                                __('WhatsApp number is %1$srequired%2$s. Please set it on the %3$sNumbers%4$s tab.', 'oneclick-wa-order'),
                                '<strong style="color:red;">', // opening <strong> tag with red color style
                                '</strong>', // closing <strong> tag
                                '<a href="edit.php?post_type=wa-order-numbers"><strong>', // opening <a> and <strong> tag
                                '</strong></a>' // closing <a> and <strong> tag
                            ),
                            array(
                                'strong' => array('style' => array()), // Allow strong with style attribute
                                'a' => array('href' => array(), 'target' => array()) // Allow a tag with href and target attributes
                            )
                        );
                        ?>
                    </p>
                </td>
            </tr>
            <!-- Text on Button -->
            <tr class="wa_order_btn_text">
                <th scope="row">
                    <label class="wa_order_btn_txt_label" for="text_button"><b><?php echo esc_html__('Text on Button', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="text" name="wa_order_shortcode_text_button" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_shortcode_text_button')); ?>" placeholder="<?php echo esc_attr__('e.g. Order via WhatsApp', 'oneclick-wa-order'); ?>">
                </td>
            </tr>
            <!-- Custom Message -->
            <tr class="wa_order_message">
                <th scope="row">
                    <label class="wa_order_message_label" for="message_wbw"><b><?php echo esc_html__('Custom Message', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <textarea name="wa_order_shortcode_message" class="wa_order_input_areatext" rows="5" placeholder="<?php echo esc_attr__('e.g. Hello, I need to know more about', 'oneclick-wa-order'); ?>"><?php echo esc_textarea(get_option('wa_order_shortcode_message')); ?></textarea>
                    <p class="description">
                        <?php echo esc_html__('Enter custom message, e.g.', 'oneclick-wa-order') . ' <code>' . esc_html__('Hello, I need to know more about', 'oneclick-wa-order') . '</code>'; ?>
                    </p>
                </td>
            </tr>
            <!-- Copy Shortcode -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_copy_label" for="wa_order_copy"><b><?php echo esc_html__('Copy Shortcode', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input style="letter-spacing: 1px;" class="wa_order_shortcode_input" onClick="this.setSelectionRange(0, this.value.length)" value="[wa-order]" readonly />
                </td>
            </tr>
            <!-- Open in New Tab? -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_target_label" for="wa_order_target"><b><?php echo esc_html__('Open in New Tab?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_shortcode_target" class="wa_order_input_check" value="_blank" <?php checked(get_option('wa_order_shortcode_target'), '_blank'); ?>>
                    <?php echo esc_html__('Yes, Open in New Tab', 'oneclick-wa-order'); ?>
                </td>
            </tr>
        </tbody>
    </table>
    <hr>
    <?php submit_button(); ?>
</form>
<!-- End - Shortcode Tab Setting Page -->