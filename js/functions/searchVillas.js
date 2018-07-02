function getSearchFilters(page_number)
{
    var city = $('#where').val();
    var start_date = $('#start-date').val();
    var end_date = $('#end-date').val();
    var guests = $('#guests').val();
    var attributes = [];

    $('.search-attributes').each(function() {

        var this_attribute = $(this);

        if (this_attribute.is(':checked'))
        {
            attributes.push(this_attribute.attr('data-id'));
        }
    });

    search(page_number, city, start_date, end_date, guests, attributes);
}

function search(page_number, city, start_date, end_date, guests, attributes)
{
    $.ajax({
        url: ajax_url + 'searchVillas',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'page_number': page_number, 'city': city, 'start_date': start_date, 'end_date': end_date, 'guests': guests,
            'search_attributes': attributes},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:

                    var append_string = '';

                    $('.slider-without-navigation').slick('unslick');

                    if (data.villas_array.length > 0)
                    {
                        $.each(data.villas_array, function(index, value) {

                            var slider_append_string = '';

                            append_string += '<div class="col-sm-12 fade-in">' +
                                '<div class="villa-outter">' +
                                    '<div class="villa-inner">';

                            if (value.discount)
                            {
                                append_string += '<div class="villa-discount"><p>' + value.discount_text + '</p></div>';
                            }

                            append_string += '<div class="row">' +
                                '<div class="col-6 pr-0">';

                            for (var i = 1; i < 3; i++)
                            {
                                var slider_string = '<div class="slider slider-without-navigation">';

                                $.each(value.images, function(index2, value2) {

                                    slider_string += '<div><img src="' + value.image_url + value2.image + '" class="img-fluid"></div>';
                                });

                                slider_string += '</div>';

                                if (i === 1)
                                {
                                    append_string += slider_string;
                                }
                                else
                                {
                                    slider_append_string += slider_string;
                                }
                            }

                            append_string += '</div><div class="col pl-0">' +
                                '<div class="villa-inner-content">' +
                                    '<a href="' + value.url + '" class="villa-name-link"><h4>' + value.name + '</h4></a>' +
                                    '<div class="row">' +
                                        '<div class="col-sm-12 address">' +
                                            '<p><i class="fa fa-map-pin" aria-hidden="true"></i> ' + value.city + '</p>' +
                                        '</div>' +
                                    '</div>' +
                                    '<div class="row short-description">' +
                                        '<div class="col-sm-12"><p>' + value.short_description + '</p></div>' +
                                    '</div>' +
                                    '<div class="row accm-info">' +
                                        '<div class="col-sm-6 text-center">' +
                                            '<p><img src="' + value.beds_icon + '"> ' + value.beds + '</p>' +
                                        '</div>' +
                                        '<div class="col-sm-6 text-center">' +
                                            '<p><img src="' + value.persons_icon + '"> ' + value.persons + '</p>' +
                                        '</div>' +
                                    '</div>' +
                                    '<div class="row text-center">' +
                                        '<a href="' + value.url + '" class="btn btn-primary">' + from_trans +
                                            '<span>' + value.price + '</span>' +
                                            '<i class="fa fa-long-arrow-right" aria-hidden="true"></i>' +
                                        '</a>' +
                                    '</div>' +
                                '</div></div></div></div></div></div>';

                            var current_location = {'id': value.id, 'name': value.name, 'city': value.city, 'latitude': value.latitude,
                                'longitude': value.longitude, 'slider': slider_append_string, 'url': value.url,
                                'beds': '<img src="' + value.beds_icon + '">' + value.beds,
                                'persons': '<img src="' + value.persons_icon + '">' + value.persons,
                                'price': '<a href="' + value.url + '" class="btn btn-primary">' + from_trans + '<span> ' + value.price
                                + '</span><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>'};

                            locations_array.push(current_location);
                        });
                    }
                    else
                    {
                        append_string += '<div class="col-sm-12 fade-in">' +
                            '<div class="no-villas-info"><i class="fa fa-frown-o" aria-hidden="true"></i>' + no_villas_trans +
                            '</div></div>';
                    }

                    showMap('T');

                    $('#villas-list').append(append_string);

                    $('.slider-without-navigation').slick({
                        infinite: true,
                        speed: 300,
                        fade: true,
                        cssEase: 'ease-in-out'
                    });

                    if (data.next_page !== 0)
                    {
                        $('#page-number').val(data.next_page);
                        $('.show-more-div').show();
                    }
                    else
                    {
                        $('.show-more-div').hide();
                    }

                    break;
                case 0:
                    toastr.error(data.error);
                    break;
            }
        },
        error: function() {
            toastr.error(error);
        }
    });
}

var locations_array = [];

$(document).ready(function() {

    initializeMap('F');

    getSearchFilters(1);

    $('.show-more-button').on('click', function() {

        var page_number = $('#page-number').val();

        getSearchFilters(page_number);
    });
});