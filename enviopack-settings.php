<?php

namespace Ecomerciar\Enviopack\Settings;

use Ecomerciar\Enviopack\Enviopack;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

function init_settings()
{
    register_setting('ecom_enviopack', 'ecom_enviopack_options');

    add_settings_section(
        'ecom_enviopack',
        'Configuración',
        '',
        'enviopack_settings'
    );

    add_settings_field(
        'epack_config',
        'Configurar cuenta de EnvíoPack',
        __NAMESPACE__ . '\print_epack_config',
        'enviopack_settings',
        'ecom_enviopack'
    );

    add_settings_field(
        'api_key',
        'Api Key',
        __NAMESPACE__ . '\print_api_key',
        'enviopack_settings',
        'ecom_enviopack'
    );

    add_settings_field(
        'api_secret',
        'Api Secret',
        __NAMESPACE__ . '\print_api_secret',
        'enviopack_settings',
        'ecom_enviopack'
    );

    add_settings_field(
        'environment',
        'Entorno',
        __NAMESPACE__ . '\print_environment',
        'enviopack_settings',
        'ecom_enviopack'
    );

    add_settings_field(
        'address',
        'Dirección de envío',
        __NAMESPACE__ . '\print_address',
        'enviopack_settings',
        'ecom_enviopack'
    );

    add_settings_field(
        'courier',
        'Correos activos',
        __NAMESPACE__ . '\print_courier',
        'enviopack_settings',
        'ecom_enviopack'
    );

    /* add_settings_field(
        'default_shipping',
        'Modalidad de envío',
        __NAMESPACE__ . '\print_shipping_mode',
        'enviopack_settings',
        'ecom_enviopack'
    ); */

    add_settings_field(
        'map',
        'Google maps API key',
        __NAMESPACE__ . '\print_google',
        'enviopack_settings',
        'ecom_enviopack'
    );

    add_settings_field(
        'packaging_mode',
        'Cálculo de medidas',
        __NAMESPACE__ . '\print_packaging_mode',
        'enviopack_settings',
        'ecom_enviopack'
    );

    add_settings_field(
        'default_shipping_status',
        'Estado de pedido',
        __NAMESPACE__ . '\print_default_shipping_status',
        'enviopack_settings',
        'ecom_enviopack'
    );

    add_settings_field(
        'extra_info',
        'Información adicional',
        __NAMESPACE__ . '\print_extra_info',
        'enviopack_settings',
        'ecom_enviopack'
    );
}

function add_assets_files($hook)
{
    if ($hook !== 'settings_page_enviopack_settings') {
        return;
    }
    wp_enqueue_style('admin.css', plugin_dir_url(__FILE__) . 'css/admin.css', array(), 1.0);
}

function print_epack_config()
{
    echo 'Para configurar tus preferencias de envío ingresá a tu cuenta de EnvíoPack haciendo <a href="https://app.enviopack.com/correos-y-tarifas">click aqui</a>';
}


function print_api_key()
{
    $previous_config = get_option('enviopack_api_key');
    echo '<input type="text" name="api_key" value="' . ($previous_config ? $previous_config : '') . '" />';
}

function print_api_secret()
{
    $previous_config = get_option('enviopack_api_secret');
    echo '<input type="text" name="api_secret" value="' . ($previous_config ? $previous_config : '') . '" />';
}

function print_environment()
{
    $previous_config = get_option('enviopack_environment');
    echo '<select name="environment">';
    echo '<option value="test" ' . ($previous_config === 'test' ? 'selected' : '') . '>Prueba</option>';
    echo '<option value="prod" ' . ($previous_config === 'prod' ? 'selected' : '') . '>Producción</option>';
    echo '</select>';
}

function print_packaging_mode()
{
    $previous_config = get_option('enviopack_packaging_mode');
    echo '<select name="packaging_mode">';
    echo '<option value="sum-package" ' . ($previous_config === 'sum-package' ? 'selected' : '') . '>Calcular sumando las dimensiones de los productos</option>';
    echo '<option value="max-package" ' . ($previous_config === 'max-package' ? 'selected' : '') . '>Calcular tomando la dimension mas alta de cada producto</option>';
    echo '<option value="default-package" ' . ($previous_config === 'default-package' ? 'selected' : '') . '>Paquete default</option>';
    echo '</select>';
    echo '<p class="info-text"><strong>Calcular sumando las dimensiones de los productos:</strong> Se suman los volúmenes totales de cada paquete y se calculará una caja en forma de cubo con el volumen total.
		<br> <strong>Calcular tomando la dimension mas alta de cada producto:</strong> Se estimará un solo paquete tomando los lados más grandes de todos los productos.
		<br> <strong>Paquete default:</strong> Se estimará un solo paquete que previamente se haya <a href="https://app.enviopack.com/configuracion/mis-paquetes" target="_blank">cargado en la plataforma de EnvioPack</a> elegido por usted. </p>';
}

function print_shipping_mode()
{
    if (get_option('enviopack_api_key') && get_option('enviopack_api_secret')) {
        $ep = new Enviopack;
        $couriers = $ep->get_couriers();
        $previous_config = get_option('enviopack_shipping_mode');
        echo '<select name="shipping_mode">';
        echo '<option value="manual" ' . (!$previous_config || $previous_config === 'manual' ? 'selected' : '') . '>Enviar manualmente</option>';
        foreach ($couriers as $courier) {
            echo '<option value="' . $courier['id'] . '" ' . ($previous_config === $courier['id'] ? 'selected' : '') . '>Enviar automaticamente - ' . $courier['name'] . '</option>';
        }
        echo '</select>';
        echo '<p class="info-text"><strong>Manual:</strong> Tendrás que confirmar el pedido desde el panel de ordenes de WooCommerce.
		<br> <strong>Automático:</strong> Todos los pedidos a domicilio marcados como "completado" se enviarán automaticamente con el correo seleccionado. Para los envíos a sucursal se enviarán con el envío seleccionado preseleccionado por la sucursal
		<br> <strong>NOTA:</strong> Si vas a usar el modo automático, asegurate de que el correo seleccionado está activo para los distintos tipos de modalidades de envío (Express, Normal, etc), de lo contrario tu pedido no será enviado. </p>';
    }
}

function print_default_shipping_status()
{
    $statuses = wc_get_order_statuses();
    $previous_config = get_option('enviopack_shipping_status');
    if (!$previous_config) update_option('enviopack_shipping_status', 'wc-completed');
    echo '<select name="shipping_status">';
    foreach ($statuses as $status_key => $status_name) {
        if ($previous_config) {
            echo '<option value="' . $status_key . '" ' . ($previous_config === $status_key ? 'selected' : '') . '>' . $status_name . '</option>';
        } else {
            echo '<option value="' . $status_key . '" ' . ($status_key === 'wc-completed' ? 'selected' : '') . '>' . $status_name . '</option>';
        }
    }
    echo '</select>';
    echo '<p class="info-text">Los pedidos con este estado serán enviados automáticamente a EnvioPack</p>';
}

function print_courier()
{
    if (get_option('enviopack_api_key') && get_option('enviopack_api_secret')) {
        $ep = new Enviopack;
        $couriers = $ep->get_couriers();
        if (empty($couriers)) {
            return false;
        }
        update_option('enviopack_couriers', serialize($couriers));
        echo '<p>';
        foreach ($couriers as $index => $courier) {
            if ($index === count($couriers) - 1) {
                echo $courier['name'] . '.';
            } else {
                echo $courier['name'] . ', ';
            }
        }
        echo '</p>';
        echo '<p class="info-text">Para modificar los correos activos lo podés hacer desde tu <a href="https://app.enviopack.com/correos-y-tarifas" target="_blank">configuración de correos y tarifas</p>';
    }
}

function print_address()
{
    $previous_config = get_option('enviopack_address_id');
    if (get_option('enviopack_api_key') && get_option('enviopack_api_secret')) {
        $ep = new Enviopack;
        $addresses = $ep->get_shipping_addresses();
        echo '<select name="address">';
        foreach ($addresses as $address) {
            if ($previous_config) {
                if ($previous_config === $address['id']) {
                    echo '<option value="' . $address['id'] . ' selected">' . $address['address'] . '</option>';
                } else {
                    echo '<option value="' . $address['id'] . '">' . $address['address'] . '</option>';
                }
            } else {
                if ($address['default']) {
                    echo '<option value="' . $address['id'] . ' selected">' . $address['address'] . '</option>';
                    update_option('enviopack_address_id', $address['id']);
                } else {
                    echo '<option value="' . $address['id'] . '">' . $address['address'] . '</option>';
                }
            }
        }
        echo '</select>';
    }
    echo '<p class="info-text">Podés configurar tus direcciones de envío ingresando a <a href="https://app.enviopack.com/configuracion/mis-direcciones" target="_blank">Configuración / Mis Direcciones</a></p>';
}

function print_google()
{
    $previous_config = get_option('enviopack_gmap_key');
    echo '<input type="text" name="gmap_key" value="' . ($previous_config ? $previous_config : '') . '" />';
    echo '<p class="info-text">API Key usada para mostrar mapa de sucursales en el checkout, para mas información <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">ingresa acá</a></p>';
}

function print_extra_info()
{
    echo '<p class="info-text">Al instalar este plugin automaticamente se crea la página de Rastreo, la cual contiene el shortcode [rastreo_enviopack]. Coloca este shortcode en cualquier página que desees usar para rastrear los pedidos de Envíopack o usa la página que creamos por ti.</p>';
}

function create_menu_option()
{
    add_options_page(
        'Configuración de EnvíoPack',
        'Configuración de EnvíoPack',
        'manage_options',
        'enviopack_settings',
        __NAMESPACE__ . '\settings_page_content'
    );
}

function settings_page_content()
{

    if (!current_user_can('manage_options')) {
        return;
    }

	// Save api_key
    if (isset($_POST['api_key']) && !empty($_POST['api_key'])) {
        update_option('enviopack_api_key', $_POST['api_key']);
    }

	// Save api_secret
    if (isset($_POST['api_secret']) && !empty($_POST['api_secret'])) {
        update_option('enviopack_api_secret', $_POST['api_secret']);
    }

	// Save environment
    if (isset($_POST['environment']) && !empty($_POST['environment'])) {
        update_option('enviopack_environment', $_POST['environment']);
    }

	// Save address id
    if (isset($_POST['address']) && !empty($_POST['address'])) {
        update_option('enviopack_address_id', $_POST['address']);
    }

	// Save google maps api key
    if (isset($_POST['gmap_key']) && !empty($_POST['gmap_key'])) {
        update_option('enviopack_gmap_key', $_POST['gmap_key']);
    }

	// Save shipping mode
    if (isset($_POST['shipping_mode']) && !empty($_POST['shipping_mode'])) {
        update_option('enviopack_shipping_mode', $_POST['shipping_mode']);
    }

	// Save packaging mode
    if (isset($_POST['packaging_mode']) && !empty($_POST['packaging_mode'])) {
        update_option('enviopack_packaging_mode', $_POST['packaging_mode']);
    }

	// Save shipping status
    if (isset($_POST['shipping_status']) && !empty($_POST['shipping_status'])) {
        update_option('enviopack_shipping_status', $_POST['shipping_status']);
    }

	// Save debug
    if (isset($_POST['debug']) && !empty($_POST['debug'])) {
        update_option('enviopack_debug', $_POST['debug']);
    }

    ?>
	<div class="wrap">
		<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
		<form action="options-general.php?page=enviopack_settings" method="post">
			<?php
    settings_fields('enviopack_settings');
    do_settings_sections('enviopack_settings');
    submit_button('Guardar');
    ?>
		</form>
	</div>
	<?php

}
