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
 * @category    Floating Tab
 *
 ********************************* Floating Tab ********************************* */
?>
<form method="post" action="options.php">
    <?php settings_errors(); ?>
    <?php settings_fields('wa-order-settings-group-floating'); ?>
    <?php do_settings_sections('wa-order-settings-group-floating'); ?>
    <!-- Floating Button -->
    <h2 class="section_wa_order"><?php esc_html_e('Floating Button', 'oneclick-wa-order'); ?></h2>
    <p>
        <?php esc_html_e('Enable / disable a floating WhatsApp button on your entire pages. You can configure the floating button below.', 'oneclick-wa-order'); ?>
        <br />
    </p>
    <table class="form-table">
        <tbody>
            <tr class="wa_order_remove_add_btn">
                <th scope="row">
                    <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn"><b><?php esc_html_e('Display Floating Button?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_floating_button" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_floating_button'), 'yes'); ?>>
                    <?php esc_html_e('This will show floating WhatsApp Button', 'oneclick-wa-order'); ?>
                    <br>
                </td>
            </tr>
            <!-- Dropdown WA Number -->
            <tr>
                <th scope="row">
                    <label>
                        <?php esc_html_e('WhatsApp Number', 'oneclick-wa-order') ?>
                    </label>
                </th>
                <td>
                    <?php wa_order_phone_numbers_dropdown(
                        array(
                            'name'      => 'wa_order_selected_wa_number_floating',
                            'selected'  => (get_option('wa_order_selected_wa_number_floating')),
                        )
                    )
                    ?>
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
            <!-- END- Dropdown WA Number -->
            <tr class="wa_order_message">
                <th scope="row">
                    <label class="wa_order_message_label" for="message_wbw"><b><?php esc_html_e('Custom Message', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <textarea name="wa_order_floating_message" class="wa_order_input_areatext" rows="5" placeholder="<?php esc_html_e('e.g. Hello, I need to know more about', 'oneclick-wa-order'); ?>"><?php echo esc_textarea(get_option('wa_order_floating_message')); ?></textarea>
                    <p class="description">
                        <?php
                        /* translators: 1. example message for custom input inside <code> tags */
                        echo sprintf(
                            /* translators: 1. example message for custom input */
                            esc_html__('Enter custom message, e.g. %1$sHello, I need to know more about%2$s', 'oneclick-wa-order'),
                            '<code>', // opening <code> tag
                            '</code>' // closing <code> tag
                        );
                        ?>
                    </p>
                </td>
            </tr>
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_target_label" for="wa_order_target"><b><?php esc_html_e('Show Source Page URL?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_floating_source_url" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_floating_source_url'), 'yes'); ?>>
                    <?php esc_html_e('This will include the URL of the page where the button is clicked in the message.', 'oneclick-wa-order'); ?>
                    <br>
                </td>
            </tr>
            <tr class="wa_order_btn_text">
                <th scope="row">
                    <label class="wa_order_btn_txt_label" for="wa_order_floating_source_url_label"><b><?php esc_html_e('Source Page URL Label', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="text" name="wa_order_floating_source_url_label" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_floating_source_url_label')); ?>" placeholder="<?php esc_html_e('From URL:', 'oneclick-wa-order'); ?>">
                    <p class="description">
                        <?php
                        /* translators: 1. example label for the source page URL inside <code> tags */
                        echo sprintf(
                            /* translators: 1. example label for the source page URL */
                            esc_html__('Add a label for the source page URL. %1$se.g. From URL:%2$s', 'oneclick-wa-order'),
                            '<code>', // opening <code> tag
                            '</code>' // closing <code> tag
                        );
                        ?>
                    </p>
                </td>
            </tr>
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_target_label" for="wa_order_target"><b><?php esc_html_e('Open in New Tab?', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_floating_target" class="wa_order_input_check" value="_blank" <?php checked(get_option('wa_order_floating_target'), '_blank'); ?>>
                    <?php esc_html_e('Yes, Open in New Tab', 'oneclick-wa-order'); ?>
                    <br>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- Floating Button Display Options -->
    <table class="form-table">
        <tbody>
            <hr />
            <h2 class="section_wa_order"><?php esc_html_e('Display Options', 'oneclick-wa-order'); ?></h2>
            <p>
                <?php esc_html_e('Configure where and how you\'d like the floating button to be displayed..', 'oneclick-wa-order'); ?>
                <br />
            </p>
            <hr />
            <tr class="wa_order_remove_add_btn">
                <th scope="row">
                    <label>
                        <?php esc_html_e('Floating Button Position', 'oneclick-wa-order') ?>
                    </label>
                </th>
                <td>
                    <input type="radio" name="wa_order_floating_button_position" value="left" <?php checked('left', get_option('wa_order_floating_button_position'), true); ?>> <?php esc_html_e('Left', 'oneclick-wa-order'); ?>
                    <input type="radio" name="wa_order_floating_button_position" value="right" <?php checked('right', get_option('wa_order_floating_button_position'), true); ?>> <?php esc_html_e('Right', 'oneclick-wa-order'); ?>
                    <?php esc_html_e('Right', 'oneclick-wa-order'); ?>
                </td>
            </tr>
            <tr class="wa_order_remove_add_btn">
                <th scope="row">
                    <label class="wa_order_remove_btn_label" for="wa_order_remove_wa_order_btn">
                        <strong><?php esc_html_e('Display Tooltip?', 'oneclick-wa-order'); ?></strong>
                    </label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_floating_tooltip_enable" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_floating_tooltip_enable'), 'yes'); ?>>
                    <?php esc_html_e('This will show a custom tooltip', 'oneclick-wa-order'); ?>
                    <br>
                </td>
            </tr>
            <tr class="wa_order_btn_text">
                <th scope="row">
                    <label class="wa_order_btn_txt_label" for="floating_tooltip">
                        <strong><?php esc_html_e('Button Tooltip', 'oneclick-wa-order'); ?></strong>
                    </label>
                </th>
                <td>
                    <input type="text" name="wa_order_floating_tooltip" class="wa_order_input" value="<?php echo esc_attr(get_option('wa_order_floating_tooltip')); ?>" placeholder="<?php esc_html_e('e.g. Let\'s Chat', 'oneclick-wa-order'); ?>">
                    <p class="description">
                        <?php esc_html_e('Use this to greet your customers.', 'oneclick-wa-order'); ?>
                        <br>
                        <?php esc_html_e('The tooltip container size is very limited so make sure to make it as short as possible.', 'oneclick-wa-order'); ?>
                    </p>
                </td>
            </tr>
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_target_label" for="wa_order_target">
                        <strong><?php esc_html_e('Hide Floating Button on Mobile?', 'oneclick-wa-order'); ?></strong>
                    </label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_floating_hide_mobile" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_floating_hide_mobile'), 'yes'); ?>>
                    <?php esc_html_e('This will hide Floating Button on Mobile.', 'oneclick-wa-order'); ?>
                    <br>
                </td>
            </tr>
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_target_label" for="wa_order_target">
                        <strong><?php esc_html_e('Hide Floating Button on Desktop?', 'oneclick-wa-order'); ?></strong>
                    </label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_floating_hide_desktop" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_floating_hide_desktop'), 'yes'); ?>>
                    <?php esc_html_e('This will hide Floating Button on Desktop.', 'oneclick-wa-order'); ?>
                    <br>
                </td>
            </tr>
            <!-- Hide floating button on all posts -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_target_label" for="wa_order_target">
                        <strong><?php esc_html_e('Hide Floating Button on All Single Posts?', 'oneclick-wa-order'); ?></strong>
                    </label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_floating_hide_all_single_posts" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_floating_hide_all_single_posts'), 'yes'); ?>>
                    <?php esc_html_e('This will hide Floating Button on all single posts.', 'oneclick-wa-order'); ?>
                    <br>
                </td>
            </tr>
            <!-- END - Hide floating button on all posts -->
            <!-- Hide floating button on all pages -->
            <tr class="wa_order_target">
                <th scope="row">
                    <label class="wa_order_target_label" for="wa_order_target">
                        <strong><?php esc_html_e('Hide Floating Button on All Single Posts?', 'oneclick-wa-order'); ?></strong>
                    </label>
                </th>
                <td>
                    <input type="checkbox" name="wa_order_floating_hide_all_single_pages" class="wa_order_input_check" value="yes" <?php checked(get_option('wa_order_floating_hide_all_single_pages'), 'yes'); ?>>
                    <?php esc_html_e('This will hide Floating Button on all single pages.', 'oneclick-wa-order'); ?>
                    <br>
                </td>
            </tr>
            <!-- END - Hide floating button on all pages -->
            <!-- Multiple posts selection -->
            <tr class="wa_order_remove_add_btn">
                <th scope="row">
                    <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn">
                        <strong><?php esc_html_e('Hide Floating Button on Selected Post(s)', 'oneclick-wa-order'); ?></strong>
                    </label>
                </th>
                <td>
                    <?php wp_enqueue_script('wa_order_js_select2'); ?>
                    <?php wp_enqueue_script('wa_order_select2_helper'); ?>
                    <?php wp_enqueue_style('wa_order_selet2_style'); ?>
                    <select multiple="multiple" name="wa_order_floating_hide_specific_posts[]" class="postform octo-post-filter" style="width: 50%;">
                        <?php
                        global $post;
                        $option = get_option('wa_order_floating_hide_specific_posts');
                        $option_array = (array) $option;
                        $args = array(
                            'post_type'        => 'post',
                            'orderby'          => 'title',
                            'order'            => 'ASC',
                            'post_status'      => 'publish',
                            'posts_per_page'   => -1
                        );
                        $posts = get_posts($args);
                        foreach ($posts as $post) {
                            $selected = in_array($post->ID, $option_array) ? ' selected="selected" ' : '';
                        ?>
                            <option value="<?php echo esc_attr($post->ID); ?>" <?php echo esc_attr($selected); ?>>
                                <?php echo esc_html(ucwords($post->post_title)); ?>
                            </option>
                        <?php
                        } //endforeach
                        ?>
                    </select>
                    <p><?php esc_html_e('You can hide the floating button on the selected post(s).', 'oneclick-wa-order'); ?></p><br>
                </td>
            </tr>
            <!-- END - Multiple posts selection -->
            <!-- Multiple pages selection -->
            <tr class="wa_order_remove_add_btn">
                <th scope="row">
                    <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn">
                        <strong><?php esc_html_e('Hide Floating Button on Selected Page(s)', 'oneclick-wa-order'); ?></strong>
                    </label>
                </th>
                <td>
                    <select multiple="multiple" name="wa_order_floating_hide_specific_pages[]" class="postform octo-page-filter" style="width: 50%;">
                        <?php
                        global $post;
                        $option = get_option('wa_order_floating_hide_specific_pages');
                        $option_array = (array) $option;
                        $args = array(
                            'post_type'        => 'page',
                            'orderby'          => 'title',
                            'order'            => 'ASC',
                            'post_status'      => 'publish',
                            'posts_per_page'   => -1
                        );
                        $pages = get_posts($args);
                        foreach ($pages as $page) {
                            $selected = in_array($page->ID, $option_array) ? ' selected="selected" ' : '';
                        ?>
                            <option value="<?php echo esc_attr($page->ID); ?>" <?php echo esc_attr($selected); ?>>
                                <?php echo esc_html(ucwords($page->post_title)); ?>
                            </option>
                        <?php
                        } //endforeach
                        ?>
                    </select>
                    <p><?php esc_html_e('You can hide the floating button on the selected page(s).', 'oneclick-wa-order'); ?></p><br>
                </td>
            </tr>
            <!-- END - Multiple pages selection -->
            <tr class="wa_order_remove_add_btn">
                <th scope="row">
                    <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn">
                        <strong><?php esc_html_e('Hide Floating Button on Products in Categories', 'oneclick-wa-order'); ?></strong>
                    </label>
                </th>
                <td>
                    <select multiple="multiple" name="wa_order_floating_hide_product_cats[]" class="postform octo-category-filter" style="width: 50%;">
                        <?php
                        $option = get_option('wa_order_floating_hide_product_cats');
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
                        } //endforeach
                        ?>
                    </select>
                    <p><?php esc_html_e('You can hide the floating button on products in the selected categories.', 'oneclick-wa-order'); ?></p>
                    <br>
                </td>
            </tr>
            <tr class="wa_order_remove_add_btn">
                <th scope="row">
                    <label class="wa_order_remove_add_label" for="wa_order_remove_add_btn"><b><?php esc_html_e('Hide Floating Button on Products in Tags', 'oneclick-wa-order'); ?></b></label>
                </th>
                <td>
                    <select multiple="multiple" name="wa_order_floating_hide_product_tags[]" class="postform octo-category-filter" style="width: 50%;">
                        <?php
                        $option = get_option('wa_order_floating_hide_product_tags');
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
                        } //endforeach
                        ?>
                    </select>
                    <p>
                        <?php esc_html_e('You can hide the floating button on products in the selected tags.', 'oneclick-wa-order');
                        ?>
                        <br />
                    </p>
                    <br>
                </td>
            </tr>
            <!-- Floating Button Margin -->
            <tr class="wa_order_remove_price">
                <th scope="row">
                    <label class="wa_order_price_label" for="wa_order_floating_button_margin_top">
                        <strong><?php esc_html_e('Button Margin', 'oneclick-wa-order'); ?></strong>
                    </label>
                </th>
                <td>
                    <ul class="boxes-control">
                        <li class="box-control">
                            <input id="wa_order_floating_button_margin_top" type="number" name="wa_order_floating_button_margin_top" value="<?php echo esc_attr(get_option('wa_order_floating_button_margin_top')); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Top', 'oneclick-wa-order'); ?>
                                <br />
                            </p>
                        </li>
                        <li class="box-control">
                            <input id="wa_order_floating_button_margin_right" type="number" name="wa_order_floating_button_margin_right" value="<?php echo esc_attr(get_option('wa_order_floating_button_margin_right')); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Right', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                        <li class="box-control">
                            <input id="wa_order_floating_button_margin_bottom" type="number" name="wa_order_floating_button_margin_bottom" value="<?php echo esc_attr(get_option('wa_order_floating_button_margin_bottom')); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Bottom', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                        <li class="box-control">
                            <input id="wa_order_floating_button_margin_left" type="number" name="wa_order_floating_button_margin_left" value="<?php echo esc_attr(get_option('wa_order_floating_button_margin_left')); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Left', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                    </ul>
                </td>
            </tr>
            <!-- END - Floating Button Margin -->
            <!-- Floating Button Padding -->
            <tr class="wa_order_remove_price">
                <th scope="row">
                    <label class="wa_order_price_label" for="wa_order_floating_button_padding_top">
                        <strong><?php esc_html_e('Button Padding', 'oneclick-wa-order'); ?></strong>
                    </label>
                </th>
                <td>
                    <ul class="boxes-control">
                        <li class="box-control">
                            <input id="wa_order_floating_button_padding_top" type="number" name="wa_order_floating_button_padding_top" value="<?php echo esc_attr(get_option('wa_order_floating_button_padding_top')); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Top', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                        <li class="box-control">
                            <input id="wa_order_floating_button_padding_right" type="number" name="wa_order_floating_button_padding_right" value="<?php echo esc_attr(get_option('wa_order_floating_button_padding_right')); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Right', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                        <li class="box-control">
                            <input id="wa_order_floating_button_padding_bottom" type="number" name="wa_order_floating_button_padding_bottom" value="<?php echo esc_attr(get_option('wa_order_floating_button_padding_bottom')); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Bottom', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                        <li class="box-control">
                            <input id="wa_order_floating_button_padding_left" type="number" name="wa_order_floating_button_padding_left" value="<?php echo esc_attr(get_option('wa_order_floating_button_padding_left')); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Left', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                    </ul>
                </td>
            </tr>
            <!-- END - Floating Button Padding -->
            <!-- Floating Button Icon Margin -->
            <tr class="wa_order_remove_price">
                <th scope="row">
                    <label class="wa_order_price_label" for="wa_order_floating_button_icon_margin_top">
                        <strong><?php esc_html_e('Button Icon Margin', 'oneclick-wa-order'); ?></strong>
                    </label>
                </th>
                <td>
                    <ul class="boxes-control">
                        <li class="box-control">
                            <input id="wa_order_floating_button_icon_margin_top" type="number" name="wa_order_floating_button_icon_margin_top" value="<?php echo esc_attr(get_option('wa_order_floating_button_icon_margin_top')); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Top', 'oneclick-wa-order'); ?>
                                <br />
                            </p>
                        </li>
                        <li class="box-control">
                            <input id="wa_order_floating_button_icon_margin_right" type="number" name="wa_order_floating_button_icon_margin_right" value="<?php echo esc_attr(get_option('wa_order_floating_button_icon_margin_right')); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Right', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                        <li class="box-control">
                            <input id="wa_order_floating_button_icon_margin_bottom" type="number" name="wa_order_floating_button_icon_margin_bottom" value="<?php echo esc_attr(get_option('wa_order_floating_button_icon_margin_bottom')); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Bottom', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                        <li class="box-control">
                            <input id="wa_order_floating_button_icon_margin_left" type="number" name="wa_order_floating_button_icon_margin_left" value="<?php echo esc_attr(get_option('wa_order_floating_button_icon_margin_left')); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Left', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                    </ul>
                </td>
            </tr>
            <!-- END - Floating Button Icon Margin -->
            <!-- Floating Button Icon Padding -->
            <tr class="wa_order_remove_price">
                <th scope="row">
                    <label class="wa_order_price_label" for="wa_order_floating_button_icon_padding_top">
                        <strong><?php esc_html_e('Button Icon Padding', 'oneclick-wa-order'); ?></strong>
                    </label>
                </th>
                <td>
                    <ul class="boxes-control">
                        <li class="box-control">
                            <input id="wa_order_floating_button_icon_padding_top" type="number" name="wa_order_floating_button_icon_padding_top" value="<?php echo esc_attr(get_option('wa_order_floating_button_icon_padding_top')); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Top', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                        <li class="box-control">
                            <input id="wa_order_floating_button_icon_padding_right" type="number" name="wa_order_floating_button_icon_padding_right" value="<?php echo esc_attr(get_option('wa_order_floating_button_icon_padding_right')); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Right', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                        <li class="box-control">
                            <input id="wa_order_floating_button_icon_padding_bottom" type="number" name="wa_order_floating_button_icon_padding_bottom" value="<?php echo esc_attr(get_option('wa_order_floating_button_icon_padding_bottom')); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Bottom', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                        <li class="box-control">
                            <input id="wa_order_floating_button_icon_padding_left" type="number" name="wa_order_floating_button_icon_padding_left" value="<?php echo esc_attr(get_option('wa_order_floating_button_icon_padding_left')); ?>" placeholder="">
                            <p class="control-label"><?php esc_html_e('Left', 'oneclick-wa-order'); ?><br /></p>
                        </li>
                    </ul>
                </td>
            </tr>
            <!-- END - Floating Button Icon Padding -->
        </tbody>
    </table>
    <!-- END - Floating Button Display Options -->
    <hr>
    <?php submit_button(); ?>
</form>