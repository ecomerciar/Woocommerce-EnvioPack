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
        'epack_api_link',
        'Notificaciones de EnvíoPack',
        __NAMESPACE__ . '\print_epack_api_link',
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
        'branch_office',
        'Valor de envío a sucursal ($)',
        __NAMESPACE__ . '\print_branch_office',
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
        'order_status_on_processed',
        'Estado de pedido luego de procesarse en EnvíoPack',
        __NAMESPACE__ . '\print_order_status_on_processed',
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

function print_epack_api_link()
{
    echo '<p class="info-text">Ingresa a tus <a href="https://app.enviopack.com/configuraciones-api" target="_blank">Configuraciones de API</a> en EnvíoPack y coloca en "URL PARA NOTIFICACIONES" el siguiente link:
    <br><strong>' . get_site_url(null, '/wc-api/ecom-enviopack') . '</strong></p>';
}

function print_branch_office()
{
    $previous_config = get_option('enviopack_branch_office');
    echo '<input type="text" name="branch_office" value="' . ($previous_config ? $previous_config : '') . '" />';
    echo '<p class="info-text">En caso de dejar vacío, el valor predeterminado será $120</p>';
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

function print_order_status_on_processed()
{
    $statuses = wc_get_order_statuses();
    $previous_config = get_option('enviopack_status_on_processed');
    if (!$previous_config) update_option('enviopack_status_on_processed', 'wc-completed');
    echo '<select name="status_on_processed">';
    foreach ($statuses as $status_key => $status_name) {
        if ($previous_config) {
            echo '<option value="' . $status_key . '" ' . ($previous_config === $status_key ? 'selected' : '') . '>' . $status_name . '</option>';
        } else {
            echo '<option value="' . $status_key . '" ' . ($status_key === 'wc-completed' ? 'selected' : '') . '>' . $status_name . '</option>';
        }
    }
    echo '</select>';
    echo '<p class="info-text">Las ordenes de WooCommerce se pondran con este estado luego de haber sido procesadas en EnvíoPack</p>';
}

function print_extra_info()
{
    echo '<p class="info-text">Al instalar este plugin podés empezar a usar el shortcode [enviopack_tracking]. Coloca este shortcode en cualquier página que desees usar para crear un formulario de rastreo de pedidos de Envíopack</p>';
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
    if (isset($_POST['api_key'])) {
        update_option('enviopack_api_key', $_POST['api_key']);
    }

	// Save api_secret
    if (isset($_POST['api_secret'])) {
        update_option('enviopack_api_secret', $_POST['api_secret']);
    }
    
	// Save address id
    if (isset($_POST['address'])) {
        update_option('enviopack_address_id', $_POST['address']);
    }
    
    // Save branch_office
    if (isset($_POST['branch_office'])) {
        update_option('enviopack_branch_office', $_POST['branch_office']);
    }

	// Save google maps api key
    if (isset($_POST['gmap_key'])) {
        update_option('enviopack_gmap_key', $_POST['gmap_key']);
    }

	// Save shipping mode
    if (isset($_POST['shipping_mode'])) {
        update_option('enviopack_shipping_mode', $_POST['shipping_mode']);
    }

	// Save packaging mode
    if (isset($_POST['packaging_mode'])) {
        update_option('enviopack_packaging_mode', $_POST['packaging_mode']);
    }

	// Save shipping status
    if (isset($_POST['shipping_status'])) {
        update_option('enviopack_shipping_status', $_POST['shipping_status']);
    }

	// Save debug
    if (isset($_POST['debug'])) {
        update_option('enviopack_debug', $_POST['debug']);
    }

    // Save order status
    if (isset($_POST['status_on_processed'])) {
        update_option('enviopack_status_on_processed', $_POST['status_on_processed']);
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
