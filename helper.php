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
                $coords = array(-31.6134016, -60.7152858);
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

    public function get_zones_names_for_shipping_zone()
    {
        $zones = array();
        $zones[] = array('code' => 'AR:C', 'type' => 'state');
        $zones[] = array('code' => 'AR:B', 'type' => 'state');
        $zones[] = array('code' => 'AR:K', 'type' => 'state');
        $zones[] = array('code' => 'AR:H', 'type' => 'state');
        $zones[] = array('code' => 'AR:U', 'type' => 'state');
        $zones[] = array('code' => 'AR:X', 'type' => 'state');
        $zones[] = array('code' => 'AR:W', 'type' => 'state');
        $zones[] = array('code' => 'AR:E', 'type' => 'state');
        $zones[] = array('code' => 'AR:P', 'type' => 'state');
        $zones[] = array('code' => 'AR:Y', 'type' => 'state');
        $zones[] = array('code' => 'AR:L', 'type' => 'state');
        $zones[] = array('code' => 'AR:F', 'type' => 'state');
        $zones[] = array('code' => 'AR:M', 'type' => 'state');
        $zones[] = array('code' => 'AR:N', 'type' => 'state');
        $zones[] = array('code' => 'AR:Q', 'type' => 'state');
        $zones[] = array('code' => 'AR:R', 'type' => 'state');
        $zones[] = array('code' => 'AR:A', 'type' => 'state');
        $zones[] = array('code' => 'AR:J', 'type' => 'state');
        $zones[] = array('code' => 'AR:D', 'type' => 'state');
        $zones[] = array('code' => 'AR:Z', 'type' => 'state');
        $zones[] = array('code' => 'AR:S', 'type' => 'state');
        $zones[] = array('code' => 'AR:G', 'type' => 'state');
        $zones[] = array('code' => 'AR:V', 'type' => 'state');
        $zones[] = array('code' => 'AR:T', 'type' => 'state');
        return $zones;
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
            if ($key === 0) {
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
        $dimensions = array();
        $peso = 0;
        foreach ($products['products'] as $index => $product) {
            $peso += $product['peso'];
            $dimensions[] = array(
                'alto' => $product['alto'],
                'ancho' => $product['ancho'],
                'largo' => $product['largo']
            );
        }
        $products['shipping_info']['total_weight'] += $peso;
        $products['shipping_info']['products_details_1'] = self::get_package_estimated_size($dimensions);
        if (!$products['shipping_info']['products_details_1']) return false;
        return $products;
    }

    private function get_new_product($product_id)
    {
        $product = wc_get_product($product_id);
        if (!$product) {
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
        if (!$products) {
            $this->logger->error('Enviopack Helper -> Error obteniendo productos del carrito, productos con malas dimensiones/peso', unserialize(LOGGER_CONTEXT));
            return false;
        }
        return $products;
    }

    public function get_items_from_order($order)
    {
        $products = array(
            'products' => array(),
            'shipping_info' => array()
        );
        $items = $order->get_items();
        foreach ($items as $item) {
            $product_id = $item->get_variation_id();
            if (!$product_id)
                $product_id = $item->get_product_id();
            $new_product = $this->get_new_product($product_id);
            if (!$new_product) {
                $this->logger->error('Enviopack Helper -> Error obteniendo productos de la orden, producto con malas dimensiones - ID: ' . $product_id, unserialize(LOGGER_CONTEXT));
                return false;
            }
            for ($i = 0; $i < $item->get_quantity(); $i++) {
                array_push($products['products'], $new_product);
            }
        }
        $products = $this->set_shipping_products_info($products);
        if (!$products) {
            $this->logger->error('Enviopack Helper -> Error obteniendo productos de la orden, productos con malas dimensiones/peso', unserialize(LOGGER_CONTEXT));
            return false;
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

    public static function get_package_estimated_size($dimensiones)
    {
        $estimation_method = get_option('enviopack_packaging_mode');
        if ($estimation_method === 'sum-package') {
            return self::sum_dim_estimation($dimensiones);
        } else if ($estimation_method === 'max-package') {
            return self::max_dim_estimation($dimensiones);
        } else if ($estimation_method === 'default-package') {
            return self::default_package_estimation();
        } else {
            return 0;
        }
    }

    public static function sum_dim_estimation($dimensiones)
    {
        // Ordeno las dimensiones de los productos para facilitar las comparaciones
        foreach ($dimensiones as &$product_dimensions) {
            sort($product_dimensions);
        }
        // Ordeno los paquetes por tamaño
        array_multisort($dimensiones);

        //- Si el pedido o checkout tiene un solo producto y una sola unidad se crea el paquete con esas dimensiones.
        if (count($dimensiones) == 1) {
            $paquete = implode('x', $dimensiones[0]);
        } else {
            $all_equal_size = true;
            for ($i = 0; $i < count($dimensiones) - 1; $i++) {
                if ($dimensiones[$i] != $dimensiones[$i + 1]) {
                    $all_equal_size = false;
                }
            }

            if ($all_equal_size) {
                //- Si el pedido o checkout tiene un solo producto y 2 unidades se crea el paquete con esas dimensiones. Se calcula 2alto x ancho x largo, donde 2 es la cantidad de productos iguales. En este caso seria bueno no siempre multiplicar la cantidad por el alto sino por la dimension mas chica de las 3: alto o ancho o largo.
                $paquete = ($dimensiones[0][0] * count($dimensiones)) . 'x' . $dimensiones[0][1] . 'x' . $dimensiones[0][2];
            } else {
                //- Si el pedido o checkout tiene varios productos distintos, saco el volumen total y estimo un paquete con forma de cubo. Ej. (20x10x30) + (10x5x30) = 7000cm3 = 24x24x24
                $volumen = 0;
                foreach ($dimensiones as $producto_dimension) {
                    $volumen += $producto_dimension[0] * $producto_dimension[1] * $producto_dimension[2];
                }

                $cube_size = ceil(pow($volumen, 1 / 3));
                $paquete = $cube_size . 'x' . $cube_size . 'x' . $cube_size;
            }
        }

        return $paquete;
    }

    public static function max_dim_estimation($dimensiones)
    {
        // El paquete se arma estimando sus dimensiones en base a las dimension mas alta de cada producto (aun cuando sean muchos productos) Ej: 10x10x20 y 20x5x30. = 20x10x30
        $all_dimensions = array();
        foreach ($dimensiones as $producto_dimension) {
            foreach ($producto_dimension as $dimension_name => $value) {
                $all_dimensions[] = $value;
            }
        }
        rsort($all_dimensions);
        return $all_dimensions[0] . 'x' . $all_dimensions[1] . 'x' . $all_dimensions[2];
    }

    public static function default_package_estimation()
    {
        $ep = new Enviopack;
        $default_package = $ep->get_default_package();
        if (!empty($default_package)) {
            // Alto x ancho x largo
            return $default_package['alto'] . 'x' . $default_package['ancho'] . 'x' . $default_package['largo'];
        } else {
            return false;
        }
    }

    public static function get_address($order)
    {
        if ($order->get_shipping_address_1()) {
            $shipping_line_1 = $order->get_shipping_address_1();
            $shipping_line_2 = $order->get_shipping_address_2();
        } else {
            $shipping_line_1 = $order->get_billing_address_1();
            $shipping_line_2 = $order->get_billing_address_2();
        }

        $street_name = $street_number = $floor = $apartment = "";

        if (!empty($shipping_line_2)) {
            //there is something in the second line. Let's find out what
            $fl_apt_array = self::get_floor_and_apt($shipping_line_2);
            $floor = $fl_apt_array[0];
            $apartment = $fl_apt_array[1];
        }
    
        //Now let's work on the first line
        preg_match('/(^\d*[\D]*)(\d+)(.*)/i', $shipping_line_1, $res);
        $line1 = $res;

        if ((isset($line1[1]) && !empty($line1[1]) && $line1[1] !== " ") && !empty($line1)) {
            //everything's fine. Go ahead
            if (empty($line1[3]) || $line1[3] === " ") {
                //the user just wrote the street name and number, as he should
                $street_name = trim($line1[1]);
                $street_number = trim($line1[2]);
                unset($line1[3]);
            } else {
                //there is something extra in the first line. We'll save it in case it's important
                $street_name = trim($line1[1]);
                $street_number = trim($line1[2]);
                $shipping_line_2 = trim($line1[3]);

                if (empty($floor) && empty($apartment)) {
                    //if we don't have either the floor or the apartment, they should be in our new $shipping_line_2
                    $fl_apt_array = self::get_floor_and_apt($shipping_line_2);
                    $floor = $fl_apt_array[0];
                    $apartment = $fl_apt_array[1];

                } elseif (empty($apartment)) {
                    //we've already have the floor. We just need the apartment
                    $apartment = trim($line1[3]);
                } else {
                    //we've got the apartment, so let's just save the floor
                    $floor = trim($line1[3]);
                }
            }
        } else {
            //the user didn't write the street number. Maybe it's in the second line
            //given the fact that there is no street number in the fist line, we'll asume it's just the street name
            $street_name = $shipping_line_1;

            if (!empty($floor) && !empty($apartment)) {
                //we are in a pickle. It's a risky move, but we'll move everything one step up
                $street_number = $floor;
                $floor = $apartment;
                $apartment = "";
            } elseif (!empty($floor) && empty($apartment)) {
                //it seems the user wrote only the street number in the second line. Let's move it up
                $street_number = $floor;
                $floor = "";
            } elseif (empty($floor) && !empty($apartment)) {
                //I don't think there's a chance of this even happening, but let's write it to be safe
                $street_number = $apartment;
                $apartment = "";
            }
        }

        if (!preg_match('/^ ?\d+ ?$/', $street_number, $res)) {
            //the street number it's not an actual number. We'll move it to street
            $street_name .= " " . $street_number;
            $street_number = "";
        }

        return array('street' => $street_name, 'number' => $street_number, 'floor' => $floor, 'apartment' => $apartment);
    }

    public static function get_floor_and_apt($fl_apt)
    {
        $street_name = $street_number = $floor = $apartment = "";

        //firts we'll asume the user did things right. Something like "piso 24, depto. 5h"
        preg_match('/(piso|p|p.) ?(\w+),? ?(departamento|depto|dept|dpto|dpt|dpt.º|depto.|dept.|dpto.|dpt.|apartamento|apto|apt|apto.|apt.) ?(\w+)/i', $fl_apt, $res);
        $line2 = $res;

        if (!empty($line2)) {
            //everything was written great. Now lets grab what matters
            $floor = trim($line2[2]);
            $apartment = trim($line2[4]);
        } else {
            //maybe the user wrote something like "depto. 5, piso 24". Let's try that
            preg_match('/(departamento|depto|dept|dpto|dpt|dpt.º|depto.|dept.|dpto.|dpt.|apartamento|apto|apt|apto.|apt.) ?(\w+),? ?(piso|p|p.) ?(\w+)/i', $fl_apt, $res);
            $line2 = $res;
        }

        if (!empty($line2) && empty($apartment) && empty($floor)) {
            //apparently, that was the case. Guess some people just like to make things difficult
            $floor = trim($line2[4]);
            $apartment = trim($line2[2]);
        } else {
            //something is wrong. Let's be more specific. First we'll try with only the floor
            preg_match('/^(piso|p|p.) ?(\w+)$/i', $fl_apt, $res);
            $line2 = $res;
        }

        if (!empty($line2) && empty($floor)) {
            //now we've got it! The user just wrote the floor number. Now lets grab what matters
            $floor = trim($line2[2]);
        } else {
            //still no. Now we'll try with the apartment
            preg_match('/^(departamento|depto|dept|dpto|dpt|dpt.º|depto.|dept.|dpto.|dpt.|apartamento|apto|apt|apto.|apt.) ?(\w+)$/i', $fl_apt, $res);
            $line2 = $res;
        }

        if (!empty($line2) && empty($apartment) && empty($floor)) {
            //success! The user just wrote the apartment information. No clue why, but who am I to judge
            $apartment = trim($line2[2]);
        } else {
            //ok, weird. Now we'll try a more generic approach just in case the user missplelled something
            preg_match('/(\d+),? [a-zA-Z.,!*]* ?([a-zA-Z0-9 ]+)/i', $fl_apt, $res);
            $line2 = $res;
        }

        if (!empty($line2) && empty($floor) && empty($apartment)) {
            //finally! The user just missplelled something. It happens to the best of us
            $floor = trim($line2[1]);
            $apartment = trim($line2[2]);
        } else {
            //last try! This one is in case the user wrote the floor and apartment together ("12C")
            preg_match('/(\d+)(\D*)/i', $fl_apt, $res);
            $line2 = $res;
        }

        if (!empty($line2) && empty($floor) && empty($apartment)) {
            //ok, we've got it. I was starting to panic
            $floor = trim($line2[1]);
            $apartment = trim($line2[2]);
        } elseif (empty($floor) && empty($apartment)) {
            //I give up. I can't make sense of it. We'll save it in case it's something useful 
            $floor = $fl_apt;
        }

        return array($floor, $apartment);
    }
}