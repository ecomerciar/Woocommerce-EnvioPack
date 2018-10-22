<?php

namespace Ecomerciar\Enviopack;

use WC_Shipping_Method;
use Ecomerciar\Enviopack\Enviopack;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

function enviopack_init()
{
    if (!class_exists('WC_Enviopack')) {
        class WC_Enviopack extends WC_Shipping_Method
        {

            public function __construct($instance_id = 0)
            {
                $this->id = 'enviopack';
                $this->method_title = 'Enviopack';
                $this->method_description = 'Envios con Enviopack';
                $this->title = 'Envío con Enviopack';
                $this->instance_id = absint($instance_id);
                $this->supports = array(
                    'shipping-zones',
                    'instance-settings',
                    'instance-settings-modal'
                );
                $this->logger = wc_get_logger();
                $this->init();
                add_action('woocommerce_update_options_shipping_enviopack', array($this, 'process_admin_options'));
            }

            function init()
            {
                $this->form_fields = array();
                $this->instance_form_fields = array(
                    'method_title' => array(
                        'title' => __('Nombre de envío', 'woocommerce'),
                        'default' => 'Envío',
                        'type' => 'text'
                    ),
                    'home_shipping' => array(
                        'title' => __('Envío a domicilio', 'woocommerce'),
                        'type' => 'checkbox',
                        'default' => 'yes'
                    ),
                    'office_shipping' => array(
                        'title' => __('Envío a sucursal', 'woocommerce'),
                        'type' => 'checkbox',
                        'default' => 'yes'
                    ),
                    'class' => array(
                        'title' => 'Si existe la clase',
                        'type' => 'select',
                        'default' => 'nothing',
                        'desc_tip' => true,
                        'options' => array(
                            'nothing' => 'Seleccionar'
                        )
                    ),
                    'action' => array(
                        'title' => 'Entonces',
                        'type' => 'select',
                        'default' => 'nothing',
                        'desc_tip' => true,
                        'options' => array(
                            'nothing' => 'No hacer nada',
                            'disable_method' => 'Desactivar método de envio',
                            'enable_method' => 'Activar método de envio',
                            'free_shipping' => 'Envio gratis'
                        )
                    ),
                    'free_shipping' => array(
                        'title' => __('Envío gratis', 'woocommerce'),
                        'type' => 'checkbox'
                    )
                );
				// Cargamos todas las clases disponibles de WC y las insertamos en la config de oca
                $classes = WC()->shipping->get_shipping_classes();
                foreach ($classes as $class) {
                    $this->instance_form_fields['clase']['options'][$class->name] = $class->name;
                }
            }

            public function calculate_shipping($package = array())
            {
                $products = array();
                $free_shipping = $this->get_instance_option('free_shipping');
                $office_shipping = $this->get_instance_option('office_shipping');
                $home_shipping = $this->get_instance_option('home_shipping');
                $action = $this->verify_classes($products);

                if ($action === 'disable_method' || !$action || ($home_shipping !== 'yes' && $office_shipping !== 'yes')) {
                    return false;
                }

                $cp = WC()->customer->get_shipping_postcode();
                if (empty($cp)) {
                    $cp = WC()->customer->get_billing_postcode();
                }
                $cp = filter_var($cp, FILTER_SANITIZE_NUMBER_INT);
                $province = WC()->customer->get_shipping_state();
                if (empty($province)) {
                    $province = WC()->customer->get_billing_state();
                }
                $order_subtotal = WC()->cart->get_subtotal();
                if (!empty($order_subtotal)) {
                    $order_subtotal = number_format($order_subtotal, 2, '.', '');
                }

                $ep = new Enviopack;

                $prices = $ep->get_price_to_home($province, $cp, $products['shipping_info']['total_weight'], $products['shipping_info']['products_details_1'], $order_subtotal);
                if ($prices) {
                    foreach ($prices as $price) {
                        $this->addRate(array('id' => 'D ' . $price['service'], 'label' => 'a domicilio ' . $price['service_name'] . ' (' . $price['shipping_time'] . ' Hrs)', 'price' => $price['price']));
                    }
                }

                if (WC()->session->get('enviopack_office_id') && WC()->session->get('enviopack_office_address') && WC()->session->get('enviopack_office_service') && WC()->session->get('enviopack_office_price')) {
                    $this->addRate(array('id' => 'S ' . WC()->session->get('enviopack_office_service') . ' ' . WC()->session->get('enviopack_office_id'), 'label' => 'a sucursal ' . WC()->session->get('enviopack_office_address'), 'price' => WC()->session->get('enviopack_office_price')));
                } else {
                    $this->addRate(array('id' => 'S', 'label' => 'a sucursal'));
                }
            }

            public function verify_classes(&$products)
            {
                $action = $this->get_instance_option('action');
                $class = $this->get_instance_option('class');
                $helper = new Helper;
                $products = $helper->get_items_from_cart();
                if (!$products) {
                    return false;
                }
                $condition = false;

                if (!empty($action) && $action !== 'nothing' && !empty($class) && $class !== 'nothing') {
                    // If action is disable, we search for the classes using OR logic, finding just one
                    if ($action === 'disable_method') {
                        foreach ($products as $item) {
                            $product = wc_get_product($item['id']);
                            if ($class === $product->get_shipping_class()) {
                                $condition = true;
                                $break;
                            }
                        }
                    } else {
                        $condition = true;
                        foreach ($products as $item) {
                            $product = wc_get_product($item['id']);
                            if ($class !== $product->get_shipping_class()) {
                                $condition = false;
                                $break;
                            }
                        }
                    }
                } else {
                    $condition = $action = 'nothing';
                }

                if ($condition) {
                    return $action;
                } else if ($action === 'enable_method') {
                    return 'disable_method';
                }
                return 'nothing';
            }

            public function addRate($params = array())
            {
                $rate = array(
                    'id' => 'enviopack' . (isset($params['id']) ? ' ' . $params['id'] : ''),
                    'label' => $this->get_instance_option('method_title') . (isset($params['label']) ? ' ' . $params['label'] : ''),
                    'cost' => (isset($params['price']) ? $params['price'] : 0),
                    'calc_tax' => 'per_order'
                );
                $this->add_rate($rate);
            }
        }
    }
}