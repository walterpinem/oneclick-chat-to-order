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
 * @category    Display Tab
 *
 ********************************* Display Tab ********************************* */
?>
<form method="post" action="options.php">
    <?php settings_errors(); ?>
    <?php settings_fields('wa-order-settings-group-display-options'); ?>
    <?php do_settings_sections('wa-order-settings-group-display-options'); ?>
    <?php wp_enqueue_script('wa_order_js_select2'); ?>
    <?php wp_enqueue_script('wa_order_select2_helper'); ?>
    <?php wp_enqueue_style('wp-color-picker'); ?>
    <?php wp_enqueue_style('wa_order_selet2_style'); ?>
    <?php wp_enqueue_script('wp-color-picker-alpha'); ?>
    <?php wp_enqueue_script('wp-color-picker-init'); ?>
    <?php wp_enqueue_script('wa_order_js_admin'); ?>
    <h2 class="section_wa_order"><?php esc_html_e('Display Options', 'oneclick-wa-order'); ?></h2>
    <p>
        <?php esc_html_e('Here, you can configure some options for hiding elements to convert customers phone number into clickable WhatsApp link.', 'oneclick-wa-order'); ?>
        <br />
    </p>
    <hr>
    <!-- Button Colors - Display Options -->
    <table class="form-table">
        <tbody>
            <h3 class="section_wa_order"><?php esc_html_e('Button Colors', 'oneclick-wa-order'); ?></h3>
            <p><?php esc_html_e('Customize the WhatsApp button appearance however you like.', 'oneclick-wa-order'); ?></p>
            <!-- Button Background Color -->
            <tr class="wa_order_remove_add_btn">
                <th scope="row">
                    <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Background Color', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <?php
                    $bg = get_option('wa_order_bg_color');
                    if (empty($bg)) {
                        $bg = 'rgba(37, 211, 102, 1)';
                    }
                    ?>
                    <input type="text" class="color-picker" data-alpha-enabled="true" data-default-color="rgba(37, 211, 102, 1)" name="wa_order_bg_color" value="<?php echo esc_attr($bg); ?>" />
                </td>
            </tr>
            <!-- Button Background Hover Color -->
            <tr class="wa_order_option_remove_quantity">
                <th scope="row">
                    <label class="wa_order_option_remove_quantity" for="wa_order_option_remove_quantity"><b><?php esc_html_e('Background Hover Color', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <?php
                    $bg_hover = get_option('wa_order_bg_hover_color');
                    if (empty($bg_hover)) {
                        $bg_hover = 'rgba(37, 211, 102, 1)';
                    }
                    ?>
                    <input type="text" class="color-picker" data-alpha-enabled="true" data-default-color="rgba(37, 211, 102, 1)" name="wa_order_bg_hover_color" value="<?php echo esc_attr($bg_hover); ?>" />
                </td>
            </tr>
            <!-- Button Text Color -->
            <tr class="wa_order_remove_add_btn">
                <th scope="row">
                    <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Text Color', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <?php
                    $txt = get_option('wa_order_txt_color');
                    if (empty($txt)) {
                        $txt = 'rgba(255, 255, 255, 1)';
                    }
                    ?>
                    <input type="text" class="color-picker" data-alpha-enabled="true" data-default-color="rgba(255, 255, 255, 1)" name="wa_order_txt_color" value="<?php echo esc_attr($txt); ?>" />
                </td>
            </tr>
            <!-- Button Text Hover Color -->
            <tr class="wa_order_remove_price">
                <th scope="row">
                    <label class="wa_order_price_label" for="wa_order_remove_price"><b><?php esc_html_e('Text Hover Color', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <?php
                    $txt_hover = get_option('wa_order_txt_hover_color');
                    if (empty($txt_hover)) {
                        $txt_hover = 'rgba(255, 255, 255, 1)';
                    }
                    ?>
                    <input type="text" class="color-picker" data-alpha-enabled="true" data-default-color="rgba(255, 255, 255, 1)" name="wa_order_txt_hover_color" value="<?php echo esc_attr($txt_hover); ?>" />
                </td>
            </tr>
        </tbody>
    </table>
    <hr>
    <!-- Button Box Shadow -->
    <table class="form-table">
        <tbody>
            <h3 class="section_wa_order"><?php esc_html_e('Button Box Shadow Color', 'oneclick-wa-order'); ?></h3>
            <p><?php esc_html_e('Customize the box shadow color for the WhatsApp button.', 'oneclick-wa-order'); ?></p>
            <!-- Button Box Shadow Settings -->
            <?php
            $bshdw_hz = get_option('wa_order_bshdw_horizontal', '0');
            $bshdw_v = get_option('wa_order_bshdw_vertical', '4');
            $bshdw_b = get_option('wa_order_bshdw_blur', '7');
            $bshdw_s = get_option('wa_order_bshdw_spread', '0');
            $bshdw_color = get_option('wa_order_btn_box_shdw', 'rgba(0,0,0,0.25)');
            $bshdw_h_h = get_option('wa_order_bshdw_horizontal_hover', '0');
            $bshdw_v_h = get_option('wa_order_bshdw_vertical_hover', '4');
            $bshdw_b_h = get_option('wa_order_bshdw_blur_hover', '7');
            $bshdw_s_h = get_option('wa_order_bshdw_spread_hover', '0');
            $bshdw_color_hover = get_option('wa_order_btn_box_shdw_hover', 'rgba(0,0,0,0.25)');
            ?>
            <!-- Normal State Box Shadow -->
            <tr class="wa_order_remove_price">
                <th scope="row">
                    <label class="wa_order_price_label" for="wa_order_remove_price"><strong><?php esc_html_e('Box Shadow', 'oneclick-wa-order'); ?></strong></label>
                </th>
                <td>
                    <ul class="boxes-control">
                        <li class="box-control">
                            <input id="wa_order_bshdw_horizontal" type="number" name="wa_order_bshdw_horizontal" value="<?php echo esc_attr($bshdw_hz); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Horizontal', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                        <li class="box-control">
                            <input id="wa_order_bshdw_vertical" type="number" name="wa_order_bshdw_vertical" value="<?php echo esc_attr($bshdw_v); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Vertical', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                        <li class="box-control">
                            <input id="wa_order_bshdw_blur" type="number" name="wa_order_bshdw_blur" value="<?php echo esc_attr($bshdw_b); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Blur', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                        <li class="box-control">
                            <input id="wa_order_bshdw_spread" type="number" name="wa_order_bshdw_spread" value="<?php echo esc_attr($bshdw_s); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Spread', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                        <li class="box-color-control">
                            <input id="wa_order_btn_box_shdw" type="text" class="color-picker" data-alpha-enabled="true" name="wa_order_btn_box_shdw" value="<?php echo esc_attr($bshdw_color); ?>" />
                        </li>
                    </ul>
                </td>
            </tr>
            <!-- Hover State Box Shadow -->
            <tr class="wa_order_remove_price">
                <th scope="row">
                    <label class="wa_order_price_label" for="wa_order_remove_price"><strong><?php esc_html_e('Box Shadow Hover', 'oneclick-wa-order'); ?></strong></label>
                </th>
                <td>
                    <ul class="boxes-control">
                        <li class="box-control">
                            <input id="wa_order_bshdw_horizontal_hover" type="number" name="wa_order_bshdw_horizontal_hover" value="<?php echo esc_attr($bshdw_h_h); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Horizontal', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                        <li class="box-control">
                            <input id="wa_order_bshdw_vertical_hover" type="number" name="wa_order_bshdw_vertical_hover" value="<?php echo esc_attr($bshdw_v_h); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Vertical', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                        <li class="box-control">
                            <input id="wa_order_bshdw_blur_hover" type="number" name="wa_order_bshdw_blur_hover" value="<?php echo esc_attr($bshdw_b_h); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Blur', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                        <li class="box-control">
                            <input id="wa_order_bshdw_spread_hover" type="number" name="wa_order_bshdw_spread_hover" value="<?php echo esc_attr($bshdw_s_h); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Spread', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                        <li class="box-color-control">
                            <input id="wa_order_btn_box_shdw_hover" type="text" class="color-picker" data-alpha-enabled="true" name="wa_order_btn_box_shdw_hover" value="<?php echo esc_attr($bshdw_color_hover); ?>" />
                        </li>
                    </ul>
                </td>
            </tr>
            <!-- Box Shadow Position -->
            <tr class="wa_order_remove_price">
                <th scope="row">
                    <label class="wa_order_price_label" for="wa_order_remove_price"><b><?php esc_html_e('Position', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="radio" name="wa_order_bshdw_position" value="outline" <?php checked('outline', get_option('wa_order_bshdw_position'), true); ?>>
                    <?php esc_html_e('Outline', 'oneclick-wa-order'); ?>
                    <input type="radio" name="wa_order_bshdw_position" value="inset" <?php checked('inset', get_option('wa_order_bshdw_position'), true); ?>>
                    <?php esc_html_e('Inset', 'oneclick-wa-order'); ?>
                </td>
            </tr>
            <!-- Box Shadow Hover Position -->
            <tr class="wa_order_remove_price">
                <th scope="row">
                    <label class="wa_order_price_label" for="wa_order_remove_price"><b><?php esc_html_e('Hover Position', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="radio" name="wa_order_bshdw_position_hover" value="outline" <?php checked('outline', get_option('wa_order_bshdw_position_hover'), true); ?>>
                    <?php esc_html_e('Outline', 'oneclick-wa-order'); ?>
                    <input type="radio" name="wa_order_bshdw_position_hover" value="inset" <?php checked('inset', get_option('wa_order_bshdw_position_hover'), true); ?>>
                    <?php esc_html_e('Inset', 'oneclick-wa-order'); ?>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- END of Button Customizations - Display Options -->
    <hr>
    <!-- Single Product Page Display Options -->
    <table class="form-table">
        <tbody>
            <h3 class="section_wa_order"><?php esc_html_e('Single Product Page', 'oneclick-wa-order'); ?></h3>
            <p><?php esc_html_e('The following options will be only effective on single product page.', 'oneclick-wa-order'); ?></p>
            <!-- Hide Button on Desktop -->
            <tr class="wa_order_remove_add_btn">
                <th scope="row">
                    <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Hide Button on Desktop?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_remove_btn" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_remove_btn'), 'yes'); ?>>
                    <?php esc_html_e('This will hide WhatsApp Button on Desktop.', 'oneclick-wa-order'); ?>
                    <br>
                </td>
            </tr>
            <!-- Hide Button on Mobile -->
            <tr class="wa_order_remove_add_btn">
                <th scope="row">
                    <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Hide Button on Mobile?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_remove_btn_mobile" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_remove_btn_mobile'), 'yes'); ?>>
                    <?php esc_html_e('This will hide WhatsApp Button on Mobile.', 'oneclick-wa-order'); ?>
                    <br>
                </td>
            </tr>
            <tr class="wa_order_option_remove_quantity">
                <th scope="row">
                    <label class="wa_order_option_remove_quantity" for="wa_order_option_remove_quantity"><b><?php esc_html_e('Hide Product Quantity Option?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_remove_quantity" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_remove_quantity'), 'yes'); ?>>
                    <?php esc_html_e('This will hide product quantity option field.', 'oneclick-wa-order'); ?>
                    <br>
                </td>
            </tr>
            <tr class="wa_order_remove_price">
                <th scope="row">
                    <label class="wa_order_price_label" for="wa_order_remove_price"><b><?php esc_html_e('Hide Price in Product Page?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_remove_price" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_remove_price'), 'yes'); ?>>
                    <?php esc_html_e('This will hide price in Product page.', 'oneclick-wa-order'); ?>
                    <br>
                </td>
            </tr>
            <tr class="wa_order_remove_add_btn">
                <th scope="row">
                    <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Hide Add to Cart button?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_remove_cart_btn" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_remove_cart_btn'), 'yes'); ?>>
                    <?php esc_html_e('This will hide Add to Cart button.', 'oneclick-wa-order'); ?>
                    <br>
                </td>
            </tr>
            <tr class="wa_order_remove_add_btn">
                <th scope="row">
                    <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Hide WA Button on Products in Categories', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <select multiple="multiple" name="wa_order_option_exlude_single_product_cats[]" class="postform octo-category-filter" style="width: 50%;">
                        <?php
                        $option = get_option('wa_order_option_exlude_single_product_cats');
                        $option_array = (array) $option;
                        $args = array('taxonomy' => 'product_cat', 'orderby' => 'name');
                        $categories = get_categories($args);
                        foreach ($categories as $category) {
                            $selected = in_array($category->term_id, $option_array) ? ' selected="selected" ' : ''; ?>
                            <option value="<?php echo esc_attr($category->term_id); ?>" <?php echo esc_attr($selected); ?>>
                                <?php echo esc_html(ucwords($category->cat_name)) . ' (' . esc_html($category->category_count) . ')'; ?>
                            </option>
                        <?php } ?>
                    </select>
                    <p>
                        <?php esc_html_e('You can hide the WhatsApp button on products in the selected categories.', 'oneclick-wa-order'); ?>
                        <br />
                    </p>
                    <br>
                </td>
            </tr>
            <tr class="wa_order_remove_add_btn">
                <th scope="row">
                    <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Hide WA Button on Products in Tags', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <select multiple="multiple" name="wa_order_option_exlude_single_product_tags[]" class="postform octo-category-filter" style="width: 50%;">
                        <?php
                        $option = get_option('wa_order_option_exlude_single_product_tags');
                        $option_array = (array) $option;
                        $tags = get_terms(['taxonomy' => 'product_tag', 'orderby' => 'name']);
                        foreach ($tags as $tag) {
                            $selected = in_array($tag->term_id, $option_array) ? ' selected="selected" ' : '';
                            echo '<option value="' . esc_attr($tag->term_id) . '"' . esc_attr($selected) . '>';
                            echo esc_html(ucwords($tag->name)) . ' (' . esc_html($tag->count) . ')';
                            echo '</option>';
                        }
                        ?>
                    </select>
                    <p>
                        <?php esc_html_e('You can hide the WhatsApp button on products in the selected tags.', 'oneclick-wa-order');
                        ?>
                        <br />
                    </p>
                    <br>
                </td>
            </tr>
            <!-- Button Margin -->
            <tr class="wa_order_remove_price">
                <th scope="row">
                    <label class="wa_order_price_label" for="wa_order_remove_price">
                        <strong><?php esc_html_e('Button Margin', 'oneclick-wa-order'); ?></strong>
                    </label>
                </th>
                <td>
                    <ul class="boxes-control">
                        <li class="box-control">
                            <input id="wa_order_single_button_margin_top" type="number" name="wa_order_single_button_margin_top" value="<?php echo esc_attr(get_option('wa_order_single_button_margin_top')); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Top', 'oneclick-wa-order'); ?>
                                <br />
                            </p>
                        </li>
                        <li class="box-control">
                            <input id="wa_order_single_button_margin_right" type="number" name="wa_order_single_button_margin_right" value="<?php echo esc_attr(get_option('wa_order_single_button_margin_right')); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Right', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                        <li class="box-control">
                            <input id="wa_order_single_button_margin_bottom" type="number" name="wa_order_single_button_margin_bottom" value="<?php echo esc_attr(get_option('wa_order_single_button_margin_bottom')); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Bottom', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                        <li class="box-control">
                            <input id="wa_order_single_button_margin_left" type="number" name="wa_order_single_button_margin_left" value="<?php echo esc_attr(get_option('wa_order_single_button_margin_left')); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Left', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                    </ul>
                </td>
            </tr>
            <!-- END - Button Margin -->
            <!-- Button Padding -->
            <tr class="wa_order_remove_price">
                <th scope="row">
                    <label class="wa_order_price_label" for="wa_order_remove_price">
                        <strong><?php esc_html_e('Button Padding', 'oneclick-wa-order'); ?></strong>
                    </label>
                </th>
                <td>
                    <ul class="boxes-control">
                        <li class="box-control">
                            <input id="wa_order_single_button_padding_top" type="number" name="wa_order_single_button_padding_top" value="<?php echo esc_attr(get_option('wa_order_single_button_padding_top')); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Top', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                        <li class="box-control">
                            <input id="wa_order_single_button_padding_right" type="number" name="wa_order_single_button_padding_right" value="<?php echo esc_attr(get_option('wa_order_single_button_padding_right')); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Right', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                        <li class="box-control">
                            <input id="wa_order_single_button_padding_bottom" type="number" name="wa_order_single_button_padding_bottom" value="<?php echo esc_attr(get_option('wa_order_single_button_padding_bottom')); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Bottom', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                        <li class="box-control">
                            <input id="wa_order_single_button_padding_left" type="number" name="wa_order_single_button_padding_left" value="<?php echo esc_attr(get_option('wa_order_single_button_padding_left')); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Left', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                    </ul>
                </td>
            </tr>
            <!-- END - Button Padding -->
        </tbody>
    </table>
    <!-- END of Single Product Page Display Options -->
    <hr>
    <!-- Shop Loop Display Options -->
    <table class="form-table">
        <tbody>
            <h2 class="section_wa_order"><?php esc_html_e('Shop Loop Page', 'oneclick-wa-order'); ?></h2>
            <p><?php esc_html_e('The following options will be only effective on shop loop page.', 'oneclick-wa-order'); ?></p>
            <!-- Hide Button on Desktop -->
            <tr class="wa_order_remove_add_btn">
                <th scope="row">
                    <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Hide Button on Desktop?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_display_option_shop_loop_hide_desktop" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_display_option_shop_loop_hide_desktop'), 'yes'); ?>>
                    <?php esc_html_e('This will hide WhatsApp Button on Desktop.', 'oneclick-wa-order'); ?>
                </td>
            </tr>
            <!-- Hide Button on Mobile -->
            <tr class="wa_order_remove_add_btn">
                <th scope="row">
                    <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Hide Button on Mobile?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_display_option_shop_loop_hide_mobile" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_display_option_shop_loop_hide_mobile'), 'yes'); ?>>
                    <?php esc_html_e('This will hide WhatsApp Button on Mobile.', 'oneclick-wa-order'); ?>
                </td>
            </tr>
            <!-- Select Categories -->
            <tr class="wa_order_remove_add_btn">
                <th scope="row">
                    <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Hide WA Button Under Products in Categories', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <select multiple="multiple" name="wa_order_option_exlude_shop_product_cats[]" class="postform octo-category-filter" style="width: 50%;">
                        <?php
                        $option = get_option('wa_order_option_exlude_shop_product_cats');
                        $option_array = (array) $option;
                        $args = array(
                            'taxonomy' => 'product_cat',
                            'orderby'  => 'name'
                        );
                        $categories = get_categories($args);
                        foreach ($categories as $category) {
                            $selected = in_array($category->term_id, $option_array) ? ' selected="selected" ' : '';
                        ?>
                            <option value="<?php echo esc_attr($category->term_id); ?>" <?php echo esc_attr($selected); ?>>
                                <?php echo esc_html(ucwords($category->cat_name)) . ' (' . esc_html($category->category_count) . ')'; ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                    <p><?php esc_html_e('You can hide the WhatsApp button under products in the selected categories.', 'oneclick-wa-order'); ?></p>
                </td>
            </tr>
            <!-- Archive Pages Options -->
            <tr class="wa_order_remove_add_btn">
                <!-- For Categories -->
                <th scope="row">
                    <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Also Hide on Category Archive Page(s)?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_exlude_shop_product_cats_archive" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_exlude_shop_product_cats_archive'), 'yes'); ?>>
                    <?php esc_html_e('This will hide WhatsApp Button on the selected category archive page(s).', 'oneclick-wa-order'); ?>
                </td>
            </tr>
            <!-- Select Tags -->
            <tr class="wa_order_remove_add_btn">
                <th scope="row">
                    <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Hide WA Button Under Products in Tags', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <select multiple="multiple" name="wa_order_option_exlude_shop_product_tags[]" class="postform octo-category-filter" style="width: 50%;">
                        <?php
                        $option = get_option('wa_order_option_exlude_shop_product_tags');
                        $option_array = (array) $option;
                        $args = array(
                            'taxonomy' => 'product_tag',
                            'orderby'  => 'name'
                        );
                        $tag_query = get_terms($args);
                        foreach ($tag_query as $term) {
                            $selected = in_array($term->term_id, $option_array) ? ' selected="selected" ' : '';
                        ?>
                            <option value="<?php echo esc_attr($term->term_id); ?>" <?php echo esc_attr($selected); ?>>
                                <?php echo esc_html(ucwords($term->name)) . ' (' . esc_html($term->count) . ')'; ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                    <p><?php esc_html_e('You can hide the WhatsApp button under products in the selected tags.', 'oneclick-wa-order'); ?></p>
                </td>
            </tr>
            <!-- For Tags -->
            <tr class="wa_order_remove_add_btn">
                <th scope="row">
                    <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Also Hide on Tag Archive Page(s)?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_exlude_shop_product_tags_archive" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_exlude_shop_product_tags_archive'), 'yes'); ?>>
                    <?php esc_html_e('This will hide WhatsApp Button on the selected tag archive page(s).', 'oneclick-wa-order'); ?>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- END of Shop Loop Display Options -->
    <hr>
    <!-- Cart Display Options -->
    <table class="form-table">
        <tbody>
            <h2 class="section_wa_order"><?php esc_html_e('Cart Page', 'oneclick-wa-order'); ?></h2>
            <p><?php esc_html_e('The following options will be only effective on cart page.', 'oneclick-wa-order'); ?></p>

            <!-- Hide Button on Desktop -->
            <tr class="wa_order_remove_add_btn">
                <th scope="row">
                    <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Hide Button on Desktop?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_display_option_cart_hide_desktop" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_display_option_cart_hide_desktop'), 'yes'); ?>>
                    <?php esc_html_e('This will hide WhatsApp Button on Desktop.', 'oneclick-wa-order'); ?>
                </td>
            </tr>

            <!-- Hide Button on Mobile -->
            <tr class="wa_order_remove_add_btn">
                <th scope="row">
                    <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Hide Button on Mobile?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_display_option_cart_hide_mobile" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_display_option_cart_hide_mobile'), 'yes'); ?>>
                    <?php esc_html_e('This will hide WhatsApp Button on Mobile.', 'oneclick-wa-order'); ?>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- END of Cart Display Options -->
    <hr>
    <!-- Checkout / Thank You Page Display Options -->
    <table class="form-table">
        <tbody>
            <h2 class="section_wa_order"><?php esc_html_e('Thank You Page', 'oneclick-wa-order'); ?></h2>
            <p><?php esc_html_e('The following options will be only effective on thank you page.', 'oneclick-wa-order'); ?></p>

            <!-- Hide Button on Desktop -->
            <tr class="wa_order_remove_add_btn">
                <th scope="row">
                    <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Hide Button on Desktop?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_display_option_checkout_hide_desktop" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_display_option_checkout_hide_desktop'), 'yes'); ?>>
                    <?php esc_html_e('This will hide WhatsApp Button on Desktop.', 'oneclick-wa-order'); ?>
                </td>
            </tr>

            <!-- Hide Button on Mobile -->
            <tr class="wa_order_remove_add_btn">
                <th scope="row">
                    <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Hide Button on Mobile?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_display_option_checkout_hide_mobile" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_display_option_checkout_hide_mobile'), 'yes'); ?>>
                    <?php esc_html_e('This will hide WhatsApp Button on Mobile.', 'oneclick-wa-order'); ?>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- END of Checkout / Thank You Page Display Options -->
    <hr>
    <!-- Miscellaneous Display Options -->
    <table class="form-table">
        <tbody>
            <h2 class="section_wa_order"><?php esc_html_e('Miscellaneous', 'oneclick-wa-order'); ?></h2>
            <p><?php esc_html_e('An additional option you might need.', 'oneclick-wa-order'); ?></p>

            <!-- Convert Phone Number into WhatsApp in Order Details -->
            <tr class="wa_order_remove_add_btn">
                <th scope="row">
                    <label class="wa_order_remove_add_label" for="wa_order_convert_phone"><b><?php esc_html_e('Convert Phone Number into WhatsApp in Order Details?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_option_convert_phone_order_details" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_option_convert_phone_order_details'), 'yes'); ?>>
                    <?php esc_html_e('This will convert phone number link into WhatsApp chat link.', 'oneclick-wa-order'); ?>
                </td>
            </tr>

            <!-- Custom WhatsApp Message in Backend Order Details -->
            <tr class="wa_order_message">
                <th scope="row">
                    <label class="wa_order_message_label" for="message_wbw"><b><?php esc_html_e('Custom Message', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <textarea name="wa_order_option_custom_message_backend_order_details" class="wa_order_input_areatext" rows="5" placeholder="<?php esc_html_e('e.g. Hello, I\'d like to follow up on your order.', 'oneclick-wa-order'); ?>"><?php echo esc_textarea(get_option('wa_order_option_custom_message_backend_order_details')); ?></textarea>
                    <p class="description">
                        <?php
                        /* translators: 1. example custom message inside <code> tags */
                        echo sprintf(
                            /* translators: 1. example custom message */
                            esc_html__('Enter custom message, %1$se.g. Hello, I\'d like to follow up on your order.%2$s', 'oneclick-wa-order'),
                            '<code>', // opening <code> tag
                            '</code>' // closing <code> tag
                        );
                        ?>
                    </p>
                </td>
            </tr>

        </tbody>
    </table>
    <!-- END of Miscellaneous Display Options -->
    <hr>
    <?php submit_button(); ?>
</form>