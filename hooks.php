<?php

if (!defined('ABSPATH')) {
	exit;// Exit if accessed directly.
}

// --- Settings
add_action('admin_init', 'Ecomerciar\Enviopack\Settings\init_settings');
add_action('admin_menu', 'Ecomerciar\Enviopack\Settings\create_menu_option');
add_action('admin_enqueue_scripts', 'Ecomerciar\Enviopack\Settings\add_assets_files');

// --- Method
add_action('woocommerce_shipping_init', 'Ecomerciar\Enviopack\enviopack_init');
add_filter('woocommerce_shipping_methods', 'Ecomerciar\Enviopack\Utils\add_method');

// --- Checkout
add_action('woocommerce_review_order_before_submit', 'Ecomerciar\Enviopack\Utils\add_maps');
add_action('woocommerce_after_checkout_billing_form', 'Ecomerciar\Enviopack\Utils\create_office_field');
add_action('woocommerce_checkout_process', 'Ecomerciar\Enviopack\Utils\check_office_field');
add_action('woocommerce_checkout_update_order_meta', 'Ecomerciar\Enviopack\Utils\update_order_meta');
add_action('wp_ajax_get_offices', 'Ecomerciar\Enviopack\Utils\get_offices');
add_action('wp_ajax_nopriv_get_offices', 'Ecomerciar\Enviopack\Utils\get_offices');
add_action('wp_ajax_set_office', 'Ecomerciar\Enviopack\Utils\set_office');
add_action('wp_ajax_nopriv_set_office', 'Ecomerciar\Enviopack\Utils\set_office');
add_filter('woocommerce_cart_shipping_method_full_label', 'Ecomerciar\Enviopack\Utils\enviopack_add_free_shipping_label', 10, 2);
add_filter('woocommerce_checkout_update_order_review', 'Ecomerciar\Enviopack\Utils\clear_cache');

// --- Orders
// add_action('woocommerce_new_order', 'Ecomerciar\Enviopack\Utils\create_shipment');
add_action('woocommerce_order_status_changed', 'Ecomerciar\Enviopack\Utils\process_order_status', 10, 3);
//add_action('woocommerce_order_status_changed', 'Ecomerciar\Enviopack\Utils\confirm_shipment');
add_action('add_meta_boxes', 'Ecomerciar\Enviopack\Utils\add_box');
add_action('woocommerce_process_shop_order_meta', 'Ecomerciar\Enviopack\Utils\save_box');
add_filter('woocommerce_admin_order_actions', 'Ecomerciar\Enviopack\Utils\add_action_button', 10, 2);
add_action('admin_enqueue_scripts', 'Ecomerciar\Enviopack\Utils\add_button_css_file');


// --- Other
add_shortcode('enviopack_tracking', 'Ecomerciar\Enviopack\Utils\create_shortcode');

// --- Webhook
add_action('woocommerce_api_ecom-enviopack', 'Ecomerciar\Enviopack\Utils\handle_webhook');