<?php

/*
Plugin Name: Woocommerce EnvíoPack
Plugin URI: http://ecomerciar.com
Description: Suma envios a traves de EnvíoPack a tu tienda de WooCommerce
Version: 1.2
Author: Envío Pack
Requires PHP: 7
Author URI: https://www.enviopack.com
License: GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

define('LOGGER_CONTEXT', serialize(array('source' => 'enviopack')));
define('ECOM_ENVIOPACK_APIKEY', '');
define('ECOM_ENVIOPACK_SECRETKEY', '');

register_activation_hook(__FILE__, 'Ecomerciar\Enviopack\Utils\create_page');
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'Ecomerciar\Enviopack\Utils\create_settings_link');

require_once 'enviopack-method.php';
require_once 'enviopack-settings.php';
require_once 'enviopack.php';
require_once 'hooks.php';
require_once 'helper.php';
require_once 'utils.php';

add_filter('gettext', 'ep_translate_words_array', 20, 3);
add_filter('ngettext', 'ep_translate_words_array', 20, 3);
function ep_translate_words_array($translation, $text, $domain)
{
    if ($text === 'Enter your address to view shipping options.') {
        $translation = 'Ingresá tu dirección para conocer los costos de envio (Envío a Domicilio / Retiro por sucursal)';
    }
    return $translation;
}