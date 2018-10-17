jQuery('#enviopack_office_field').css('display', 'none');

function initMap() {


    jQuery('#enviopack-map').css('width', '100%');
    jQuery('#enviopack-map').css('height', '300px');

    gmarkers = [];
    var map = new google.maps.Map(document.getElementById('enviopack-map'), {
        zoom: 10,
        center: new google.maps.LatLng(-34.6156625, -58.5033378),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var data = {
        'action': 'get_offices'
    };

    jQuery('#enviopack-map').css('opacity', '0.5');
    jQuery('#enviopack-map').css('pointer-events', 'none');

    jQuery.ajax({
        type: 'post',
        data: data,
        url: ajax_object.ajax_url,
        success: function (data) {
            if (data.success) {
                var locations = data.data.offices;
                var center_coords = data.data.center_coords;
                if (center_coords) {
                    map.panTo(new google.maps.LatLng(center_coords[0], center_coords[1]));
                }
                for (var i = 0; i < locations.length; i++) {
                    gmarkers[locations[i][0]] =
                        createMarker(new google.maps.LatLng(locations[i]['lat'], locations[i]['lng']),
                            '<strong>Nombre:</strong> ' + locations[i]['name'] + '<br>' +
                            '<strong>Tlf:</strong> ' + locations[i]['phone'] + '<br>' +
                            '<strong>Dirección:</strong> ' + locations[i]['full_address'] + '<br>' +
                            '<strong>Tiempo de entrega:</strong> ' + locations[i]['shipping_time'] + ' Hrs<br>' +
                            '<a style="cursor: pointer" onclick="selectOffice(\'' + locations[i]['address'] + '\',\'' + locations[i]['id'] + '\',\'' + locations[i]['service'] + '\',\'' + locations[i]['price'] + '\')">Seleccionar</a> '
                        );
                }
                jQuery('#enviopack-map').css('pointer-events', 'unset');
                jQuery('#enviopack-map').css('opacity', '1');
            } else {
                jQuery('#enviopack-map').css('pointer-events', 'unset');
                jQuery('#enviopack-map').css('opacity', '1');
                console.log(data);
            }
        }
    });

    var infowindow = new google.maps.InfoWindow();
    function createMarker(latlng, html) {
        var marker = new google.maps.Marker({
            position: latlng,
            map: map
        });

        google.maps.event.addListener(marker, 'click', function () {
            infowindow.setContent(html);
            infowindow.open(map, marker);
        });
        return marker;
    }
}

function selectOffice(office_address, office_id, office_service, office_price) {

    var data = {
        'action': 'set_office',
        'office_address': office_address,
        'office_id': office_id,
        'office_service': office_service,
        'office_price': office_price
    };

    jQuery('#enviopack-map').css('opacity', '0.5');
    jQuery('#enviopack-map').css('pointer-events', 'none');

    jQuery.ajax({
        type: 'post',
        data: data,
        url: ajax_object.ajax_url,
        success: function (data) {
            if (data.success) {
                jQuery('#enviopack_office').val(data.data.office_id);
                jQuery('#enviopack_office').prop('value', data.data.office_id);
                jQuery(document.body).trigger("update_checkout");
            }
        }
    });

}