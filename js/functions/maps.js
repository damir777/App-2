function getLocation()
{
    var latitude = $('.latitude').val();
    var longitude = $('.longitude').val();

    if (latitude != 0 && longitude != 0)
    {
        initializeMap('T', latitude, longitude);
    }
    else
    {
        initializeMap('F');
    }
}

function initializeMap(location, latitude, longitude)
{
    var styles = [
        {"featureType": "administrative", "elementType": "labels.text.fill", "stylers": [{"color": "#444444"}]},
        {"featureType": "landscape", "elementType": "all", "stylers": [{"color": "#f2f2f2"}]},
        {"featureType": "poi", "elementType": "all", "stylers": [{"visibility": "off"}]},
        {"featureType": "road", "elementType": "all", "stylers": [{"saturation": -100}, {"lightness": 45}]},
        {"featureType": "road.highway", "elementType": "all", "stylers": [{"visibility": "simplified"}]},
        {"featureType": "road.arterial", "elementType": "labels.icon", "stylers": [{"visibility": "off"}]},
        {"featureType": "transit", "elementType": "all", "stylers": [{"visibility": "off"}]},
        {"featureType": "water", "elementType": "all", "stylers": [{"color": "#46bcec"}, {"visibility": "on"}]}];

    var zoom = 8;
    var center = new google.maps.LatLng(45.15, 14.10);

    if (location === 'T')
    {
        center = new google.maps.LatLng(latitude, longitude);

        $("#latitude").val(latitude);
        $("#longitude").val(longitude);
    }
    else
    {
        if (is_search === 'T')
        {
            zoom = 9;
        }
    }

    var mapOptions = {
        zoom: zoom,
        center: center,
        styles: styles,
        mapTypeControl: false
    };

    map = new google.maps.Map(document.getElementById('villa-map'), mapOptions);

    if (is_search === 'F')
    {
        showMap(location, latitude, longitude)
    }
}

function showMap(location, latitude, longitude)
{
    var marker_image = new google.maps.MarkerImage(ajax_url + 'img/marker.svg');

    if (is_search === 'F')
    {
        if (location === 'T')
        {
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(latitude, longitude),
                map: map
            });

            active_markers.push(marker);
        }

        google.maps.event.addListener(map, 'click', function (e) {

            placeMarker(e.latLng);

            $('.latitude').val(e.latLng.lat());
            $('.longitude').val(e.latLng.lng());
        });
    }
    else
    {
        if (info_window)
        {
            info_window.close();
        }

        clearMarkers();

        info_window = new google.maps.InfoWindow();

        $.each(locations_array, function(index, value) {

            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(value.latitude, value.longitude),
                map: map,
                icon: marker_image
            });

            active_markers.push(marker);

            google.maps.event.addListener(marker, 'click', (function(e) {
                return function() {

                    $('.slider-without-navigation').slick('unslick');

                    info_window.setContent(value.slider + '<h4><a href="' + value.url + '">' + value.name + '</a></h4>' +
                        '<h6>' + value.city + '</h6><div class="info-window-icons"><span>' + value.beds + '</span><span>' +
                        value.persons + '</span>' + value.price + '</div>');
                    info_window.open(map, e);

                    $('.slider-without-navigation').slick({
                        infinite: true,
                        speed: 300,
                        fade: true,
                        cssEase: 'ease-in-out'
                    });
                }
            })(marker, value.id));
        });
    }
}

function placeMarker(position)
{
    clearMarkers();

    marker = new google.maps.Marker({
        position: position,
        map: map
    });

    map.panTo(position);

    active_markers.push(marker);
}

function clearMarkers()
{
    for (j = 0; j < active_markers.length; j++)
    {
        active_markers[j].setMap(null);
    }
}

var active_markers = [];
var info_window;
var map;

$(document).ready(function() {

    if (is_search === 'F')
    {
        getLocation();
    }
});