<?php

/*
Plugin Name: Woocommerce EnvíoPack
Plugin URI: http://ecomerciar.com
Description: Suma envios a traves de EnvíoPack a tu tienda de WooCommerce
Version: 1.0
Author: Ecomerciar
Requires PHP: 5.6
Author URI: http://ecomerciar.com
License: GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 */

define('LOGGER_CONTEXT', serialize(array('source' => 'enviopack')));

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

register_activation_hook(__FILE__, 'Ecomerciar\Enviopack\Utils\create_page');
register_deactivation_hook(__FILE__, 'Ecomerciar\Enviopack\Utils\destroy_page');
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'Ecomerciar\Enviopack\Utils\create_settings_link');

require_once 'enviopack-method.php';
require_once 'enviopack-settings.php';
require_once 'enviopack.php';
require_once 'hooks.php';
require_once 'helper.php';
require_once 'utils.php';