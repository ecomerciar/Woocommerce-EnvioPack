<?php

namespace Ecomerciar\Enviopack;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Helper
{

    private $order, $logger;

    public function __construct($order = '')
    {
        $this->order = $order;
        $this->logger = wc_get_logger();
    }

    public function get_comments()
    {
        if (!$this->order) {
            return false;
        }
        return $this->order->get_customer_note();
    }

    public function get_customer()
    {
        if (!$this->order) {
            return false;
        }
        return array(
            'name' => ($this->order->has_shipping_address() ? $this->order->get_shipping_first_name() : $this->order->get_billing_first_name()),
            'last_name' => ($this->order->has_shipping_address() ? $this->order->get_shipping_last_name() : $this->order->get_billing_last_name()),
            'email' => $this->order->get_billing_email(),
            'phone' => $this->order->get_billing_phone()
        );
    }

    public function get_state_center($province_id = '')
    {
        if (!$province_id) {
            return false;
        }
        switch ($province_id) {
            case 'A':
                $coords = array(-24.7959127, -65.5006682);
                break;
            case 'B':
            case 'C':
            default:
                $coords = array(-34.699918, -58.5811109);
                break;
            case 'D':
                $coords = array(-33.2975802, -66.344685);
                break;
            case 'E':
                $coords = array(-32.1156458, -60.0319688);
                break;
            case 'F':
                $coords = array(-29.8396499, -68.273314);
                break;
            case 'G':
                $coords = array(-28.0532798, -64.5710443);
                break;
            case 'H':
                $coords = array(-26.1878152, -61.6924568);
                break;
            case 'J':
                $coords = array(-31.5462472, -68.5566567);
                break;
            case 'K':
                $coords = array(-27.7553095, -67.8238272);
                break;
            case 'L':
                $coords = array(-37.0395855, -66.2405196);
                break;
            case 'M':
                $coords = array(-32.88337, -68.875342);
                break;
            case 'N':
                $coords = array(-26.8225555, -55.9700858);
                break;
            case 'P':
                $coords = array(-24.657959, -61.0295816);
                break;
            case 'Q':
                $coords = array(-38.9560437, -68.1185493);
                break;
            case 'R':
                $coords = array(-40.0178043, -68.8075603);
                break;
            case 'S':
                $coords = array(-31.4129686, -62.7703876);
                break;
            case 'T':
                $coords = array(-27.0278799, -65.7376345);
                break;
            case 'U':
                $coords = array(-43.9710412, -70.0556373);
                break;
            case 'V':
                $coords = array(-54.0550412, -68.0063843);
                break;
            case 'W':
                $coords = array(-27.4878462, -58.8234578);
                break;
            case 'X':
                $coords = array(-31.4010127, -64.2492772);
                break;
            case 'Y':
                $coords = array(-23.3030358, -66.6469644);
                break;
            case 'Z':
                $coords = array(-49.4267631, -71.4255266);
                break;
        }
        return $coords;
    }

    public function get_province_name($province_id = '')
    {
        switch ($province_id) {
            case 'C':
                $zone = 'CABA';
                break;
            case 'B':
            default:
                $zone = 'Buenos Aires';
                break;
            case 'K':
                $zone = 'Catamarca';
                break;
            case 'H':
                $zone = 'Chaco';
                break;
            case 'U':
                $zone = 'Chubut';
                break;
            case 'X':
                $zone = 'Córdoba';
                break;
            case 'W':
                $zone = 'Corrientes';
                break;
            case 'E':
                $zone = 'Entre Ríos';
                break;
            case 'P':
                $zone = 'Formosa';
                break;
            case 'Y':
                $zone = 'Jujuy';
                break;
            case 'L':
                $zone = 'La Pampa';
                break;
            case 'F':
                $zone = 'La Rioja';
                break;
            case 'M':
                $zone = 'Mendoza';
                break;
            case 'N':
                $zone = 'Misiónes';
                break;
            case 'Q':
                $zone = 'Neuquén';
                break;
            case 'R':
                $zone = 'Río Negro';
                break;
            case 'A':
                $zone = 'Salta';
                break;
            case 'J':
                $zone = 'San Juan';
                break;
            case 'D':
                $zone = 'San Luis';
                break;
            case 'Z':
                $zone = 'Santa Cruz';
                break;
            case 'S':
                $zone = 'Santa Fe';
                break;
            case 'G':
                $zone = 'Santiago del Estero';
                break;
            case 'V':
                $zone = 'Tierra del Fuego';
                break;
            case 'T':
                $zone = 'Tucumán';
                break;
        }
        return $zone;
    }

    public function get_street()
    {
        if (!$this->order) {
            return false;
        }
        if ($this->order->has_shipping_address()) {
            $address = $this->order->get_shipping_address_1();
        } else {
            $address = $this->order->get_billing_address_1();
        }
        
        $address_array = explode(" ", $address);
        $address = '';
        foreach ($address_array as $key => $value_of_array) {
          if($key === 0) {
              $address .= $value_of_array;
          } else {
              if (is_numeric($value_of_array)) {
                  break;
              }
              $address .= ' ' . $value_of_array;
          }
        }
        return $address;
    }

    public function get_province_id()
    {
        if (!$this->order) {
            return false;
        }
        if ($this->order->has_shipping_address()) {
            $province = $this->order->get_shipping_state();
        } else {
            $province = $this->order->get_billing_state();
        }
        return $province;
    }

    public function get_postal_code()
    {
        if (!$this->order) {
            return false;
        }
        if ($this->order->has_shipping_address()) {
            return $this->order->get_shipping_postcode();
        }
        return $this->order->get_billing_postcode();
    }

    private function set_shipping_products_info($products)
    {
        $products['shipping_info']['total_weight'] = 0;
        $products['shipping_info']['products_details_1'] = '';
        foreach ($products['products'] as $index => $product) {
            $products['shipping_info']['total_weight'] += $product['peso'];
            $products['shipping_info']['products_details_1'] .= ($index === 0 ? '' : ',') . $product['alto'] . 'x' . $product['ancho'] . 'x' . $product['largo'];
        }
        return $products;
    }

    private function get_new_product($product_id)
    {
        $product = wc_get_product($product_id);
        if( ! $product ) {
          return false;
        }
        if (empty($product->get_height()) || empty($product->get_length()) || empty($product->get_width()) || !$product->has_weight()) {
            return false;
        }
        $new_product = array(
            'alto' => ($product->get_height() ? wc_get_dimension($product->get_height(), 'cm') : '0'),
            'ancho' => ($product->get_width() ? wc_get_dimension($product->get_width(), 'cm') : '0'),
            'largo' => ($product->get_length() ? wc_get_dimension($product->get_length(), 'cm') : '0'),
            'peso' => ($product->has_weight() ? wc_get_weight($product->get_weight(), 'kg') : '0'),
            'id' => $product_id
        );
        return $new_product;
    }

    public function get_items_from_cart()
    {
        $products = array(
            'products' => array(),
            'shipping_info' => array()
        );
        $items = WC()->cart->get_cart();
        foreach ($items as $item) {
            $product_id = $item['data']->get_id();
            $new_product = $this->get_new_product($product_id);
            if (!$new_product) {
                $this->logger->error('Enviopack Helper -> Error obteniendo productos del carrito, producto con malas dimensiones - ID: ' . $product_id, unserialize(LOGGER_CONTEXT));
                return false;
            }
            for ($i = 0; $i < $item['quantity']; $i++) {
                array_push($products['products'], $new_product);
            }
        }
        $products = $this->set_shipping_products_info($products);
        return $products;
    }

    public function get_items_from_order()
    {
        $products = array();
        $items = $this->order->get_items();
        foreach ($items as $item) {
            $product_id = $item->get_variation_id();
            if (!$product_id) {
                $product_id = $item->get_product_id();
            }
            $new_product = $this->get_new_product($product_id);
            if (!$new_product) {
                $this->logger->error('Enviopack Helper -> Error obteniendo productos de la orden, producto con malas dimensiones - ID: ' . $product_id, unserialize(LOGGER_CONTEXT));
                return false;
            }
            unset($new_product['id']);
            for ($i = 0; $i < $item->get_quantity(); $i++) {
                array_push($products, $new_product);
            }
        }
        return $products;
    }

    public function get_street_number()
    {
        if (!$this->order) {
            return false;
        }
        if ($this->order->has_shipping_address()) {
            $address = $this->order->get_shipping_address_1();
        } else {
            $address = $this->order->get_billing_address_1();
        }

        $number = '';
        $address_array = array_reverse(explode(" ", $address));
        foreach ($address_array as $value_of_array) {
          if (is_numeric($value_of_array)) {
              $number = $value_of_array;
              break;
          }
        }

        if (!$number) {
            if ($this->order->has_shipping_address()) {
                $address = $this->order->get_shipping_address_2();
            } else {
                $address = $this->order->get_billing_address_2();
            }
            if (is_numeric($address)) {
                $number = $address;
            }
        }

        return $number;
    }
}