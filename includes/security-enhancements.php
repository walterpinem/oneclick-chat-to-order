<?php
/**
 * OneClick Chat to Order - Security Enhancement
 * 
 * IDOR vulnerability fix - Order access validation
 * 
 * @package     OneClick Chat to Order
 * @author      Walter Pinem <hello@walterpinem.me>
 * @link        https://walterpinem.me/
 * @copyright   Copyright (c) 2019 - 2025, Walter Pinem | Online Store Kit
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * @version     1.0.9
 * @category    Security
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Order Access Validation
 * 
 * Validates if current user has permission to access order data
 * Prevents IDOR (Insecure Direct Object Reference) vulnerability
 * 
 * @param int $order_id Order ID to validate
 * @return bool Whether access is allowed
 */
function wa_order_validate_order_access($order_id) {
    if (!$order_id) {
        return false;
    }
    
    $order = wc_get_order($order_id);
    if (!$order) {
        return false;
    }
    
    $current_user_id = get_current_user_id();
    $order_user_id = $order->get_user_id();
    
    // Allow access if user owns the order
    if ($current_user_id && $current_user_id === $order_user_id) {
        return true;
    }
    
    // Allow access for administrators
    if (current_user_can('manage_woocommerce')) {
        return true;
    }
    
    // Check order key for guest orders (WooCommerce standard)
    $order_key = isset($_GET['key']) ? sanitize_text_field(wp_unslash($_GET['key'])) : '';
    if ($order_key && hash_equals($order->get_order_key(), $order_key)) {
        return true;
    }
    
    return false;
}