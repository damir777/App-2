function appendPriceElement()
{
    var price_element = '<div class="price-element animated fadeInDown">' +
        '<div class="row">' +
            '<div class="col-sm-3">' +
                '<div class="form-group">' +
                    '<label>' + start_day_trans + '</label>' +
                    '<input type="text" name="start_day" class="form-control start-day prices-datepicker">' +
                '</div>' +
            '</div>' +
            '<div class="col-sm-3">' +
                '<div class="form-group">' +
                    '<label>' + end_day_trans + '</label>' +
                    '<input type="text" name="end_day" class="form-control end-day prices-datepicker">' +
                '</div>' +
            '</div>' +
            '<div class="col-sm-2">' +
                '<div class="form-group">' +
                    '<label>' + price_trans + ' (€)</label>' +
                    '<input type="text" name="price" class="form-control price">' +
                '</div>' +
            '</div>' +
            '<div class="col-sm-3">' +
                '<div class="form-group">' +
                    '<label>&nbsp;</label>' +
                    '<button type="button" class="btn btn-danger remove-price" style="display: block">' + delete_trans + '</button>' +
                '</div>' +
            '</div>' +
        '</div>' +
        '</div>';

    $('.prices').append(price_element).show();

    $('.prices-datepicker').datepicker({
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        format: "dd.mm."
    });
}

function formatPricesArray()
{
    prices_array = [];

    $('.price-element').each(function() {

        var this_element = $(this);
        var start_day = this_element.find('.start-day').val();
        var end_day = this_element.find('.end-day').val();
        var price = this_element.find('.price').val();

        var price_object = {start_day: start_day, end_day: end_day, price: price};

        prices_array.push(price_object);
    });
}

function validateForm(renter, name, url_name, address, city, zip_code, deposit, pets_price, short_description, description, discount,
    latitude)
{
    var integer_test = /^[0-9]+$/;
    var price_date_test = /^[0-9]{2}\.[0-9]{2}\.$/;

    renter.removeAttr('style');
    url_name.removeAttr('style');
    name.removeAttr('style');
    address.removeAttr('style');
    city.removeAttr('style');
    zip_code.removeAttr('style');
    deposit.removeAttr('style');
    pets_price.removeAttr('style');
    short_description.removeAttr('style');
    description.removeAttr('style');
    discount.removeAttr('style');

    var check_validation = 1;

    if (renter.has('option').length === 0)
    {
        renter.css('border', '1px solid #FF0000');

        check_validation = 0;
    }

    if (name.val().trim() == '')
    {
        name.css('border', '1px solid #FF0000');

        check_validation = 0;
    }

    if (url_name.val().trim() == '')
    {
        url_name.css('border', '1px solid #FF0000');

        check_validation = 0;
    }

    if (address.val().trim() == '')
    {
        address.css('border', '1px solid #FF0000');

        check_validation = 0;
    }

    if (city.val().trim() == '')
    {
        city.css('border', '1px solid #FF0000');

        check_validation = 0;
    }

    if (zip_code.val().trim() == '')
    {
        zip_code.css('border', '1px solid #FF0000');

        check_validation = 0;
    }

    if (!integer_test.test(deposit.val()))
    {
        deposit.css('border', '1px solid #FF0000');

        check_validation = 0;
    }

    if (!integer_test.test(pets_price.val()))
    {
        pets_price.css('border', '1px solid #FF0000');

        check_validation = 0;
    }

    if (short_description.val().trim() == '')
    {
        short_description.css('border', '1px solid #FF0000');

        check_validation = 0;
    }

    if (description.val().trim() == '')
    {
        description.css('border', '1px solid #FF0000');

        check_validation = 0;
    }

    var discount_type = $('.discount-type').val();

    if (discount_type != 0)
    {
        if (!integer_test.test(discount.val()))
        {
            discount.css('border', '1px solid #FF0000');

            check_validation = 0;
        }
    }

    if (latitude == 0)
    {
        toastr.warning(missing_location);

        check_validation = 0;
    }

    $('.price-element').each(function() {

        var this_element = $(this);
        var start_day_input = this_element.find('.start-day');
        var end_day_input = this_element.find('.end-day');
        var price_input = this_element.find('.price');

        start_day_input.removeAttr('style');
        end_day_input.removeAttr('style');
        price_input.removeAttr('style');

        if (!price_date_test.test(start_day_input.val()))
        {
            start_day_input.css('border', '1px solid #FF0000');

            check_validation = 0;
        }

        if (!price_date_test.test(end_day_input.val()))
        {
            end_day_input.css('border', '1px solid #FF0000');

            check_validation = 0;
        }

        if (!integer_test.test(price_input.val()))
        {
            price_input.css('border', '1px solid #FF0000');

            check_validation = 0;
        }
    });

    return check_validation;
}

function insertVilla(renter, name, url_name, address, city, zip_code, deposit, pets_price, discount, latitude)
{
    var villa_object = {'renter': renter, 'name': name, 'url_name': url_name, 'address': address, 'city': city,
        'zip_code': zip_code, 'deposit': deposit, 'pets_price': pets_price, 'discount': discount};

    var short_description;
    var description;

    $.each(lang_array, function(index, value) {

        short_description = $('.' + value + '-short-description').val();
        description = $('.' + value + '-description').val();

        villa_object[value + '_short_description'] = short_description;
        villa_object[value + '_description'] = description;
    });

    villa_object.cash_payment = $('.cash-payment').val();
    villa_object.featured = $('.featured').val();
    villa_object.start_month = $('.start-month').val();
    villa_object.end_month = $('.end-month').val();
    villa_object.season_start_month = $('.season-start-month').val();
    villa_object.season_end_month = $('.season-end-month').val();
    villa_object.discount_type = $('.discount-type').val();
    villa_object.latitude = latitude;
    villa_object.longitude = $('.longitude').val();
    villa_object.active = $('.active-status').val();

    var attributes_list = [];

    $('.attribute-checkboxes').each(function() {

        var this_attribute = $(this);
        var checkbox_class = this_attribute.find('.attribute-checkbox');
        var attribute_value = this_attribute.find('.attribute-value').val();

        if (checkbox_class.is(':checked'))
        {
            var attribute_object = {id: checkbox_class.attr('data-attribute-id'), value: attribute_value, va_id: null};

            attributes_list.push(attribute_object);
        }
    });

    villa_object.attributes = attributes_list;
    villa_object.attribute_translations = attribute_translations;
    villa_object.prices = prices_array;

    $.ajax({
        url: ajax_url + 'villas/insert',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: villa_object,
        success: function(data) {

            enableButtons(false);

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    location.href = ajax_url + 'villas/list';
                    break;
                case 0:
                    toastr.error(data.error);
                    break;
                default:
                    toastr.warning(data.warning);
            }
        },
        error: function() {
            enableButtons(false);
            toastr.error(error);
        }
    });
}

function updateVilla(renter, name, url_name, address, city, zip_code, deposit, pets_price, discount, latitude)
{
    var villa_object = {'id': villa_id, 'renter': renter, 'name': name, 'url_name': url_name, 'address': address,
        'city': city, 'zip_code': zip_code, 'deposit': deposit, 'pets_price': pets_price, 'discount': discount};

    var short_description;
    var description;

    $.each(lang_array, function(index, value) {

        short_description = $('.' + value + '-short-description').val();
        description = $('.' + value + '-description').val();

        villa_object[value + '_short_description'] = short_description;
        villa_object[value + '_description'] = description;
    });

    villa_object.cash_payment = $('.cash-payment').val();
    villa_object.featured = $('.featured').val();
    villa_object.start_month = $('.start-month').val();
    villa_object.end_month = $('.end-month').val();
    villa_object.season_start_month = $('.season-start-month').val();
    villa_object.season_end_month = $('.season-end-month').val();
    villa_object.discount_type = $('.discount-type').val();
    villa_object.latitude = latitude;
    villa_object.longitude = $('.longitude').val();
    villa_object.active = $('.active-status').val();

    var attributes_list = [];

    $('.attribute-checkboxes').each(function() {

        var this_attribute = $(this);
        var checkbox_class = this_attribute.find('.attribute-checkbox');
        var attribute_value = this_attribute.find('.attribute-value').val();

        if (checkbox_class.is(':checked'))
        {
            var attribute_object = {id: checkbox_class.attr('data-attribute-id'), value: attribute_value,
                va_id: checkbox_class.attr('data-va-id')};

            attributes_list.push(attribute_object);
        }
    });

    villa_object.attributes = attributes_list;
    villa_object.attribute_translations = attribute_translations;
    villa_object.prices = prices_array;

    $.ajax({
        url: ajax_url + 'villas/update',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: villa_object,
        success: function(data) {

            enableButtons(true);

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    location.href = ajax_url + 'villas/list';
                    break;
                case 0:
                    toastr.error(data.error);
                    break;
                default:
                    toastr.warning(data.warning);
            }
        },
        error: function() {
            enableButtons(true);
            toastr.error(error);
        }
    });
}

function disableButtons(villa_id)
{
    if (villa_id)
    {
        $('.update').prop('disabled', true);
    }
    else
    {
        $('.insert').prop('disabled', true);
    }

    $('.cancel').prop('disabled', true);
}

function enableButtons(villa_id)
{
    if (villa_id)
    {
        $('.update').prop('disabled', false);
    }
    else
    {
        $('.insert').prop('disabled', false);
    }

    $('.cancel').prop('disabled', false);
}

function addAttributeTranslation(id)
{
    var en_translation_input = $('.en-translation');

    if (en_translation_input.val() == '')
    {
        en_translation_input.css('border', '1px solid #FF0000');

        toastr.warning(validation_error);

        return 0;
    }
    else
    {
        en_translation_input.removeAttr('style');
    }

    var translation_object = {};

    $.each(lang_array, function(index, value) {

        translation_object[value] = $('.' + value + '-translation').val();
    });

    attribute_translations[id] = translation_object; console.log(attribute_translations);

    $('#attributeTranslationsModal').modal('hide');

    clearTranslations();

    toastr.success(translation_insert);
}

function removeAttributeTranslation(id)
{
    delete attribute_translations[id];

    $('#attributeTranslationsModal').modal('hide');

    clearTranslations();

    toastr.success(translation_delete);
}

function clearTranslations()
{
    $.each(lang_array, function(index, value) {

        $('.' + value + '-translation').val('');
    });
}

var lang_array = ['en', 'hr', 'de', 'it', 'fr', 'ru', 'dk', 'no', 'sv'];
var prices_array = [];
var attribute_translations = [];

$(document).ready(function() {

    $('.form-language').on('change', function() {

        var language = $('.form-language').val();

        $('#en_div').hide();
        $('#hr_div').hide();
        $('#de_div').hide();
        $('#it_div').hide();
        $('#fr_div').hide();
        $('#ru_div').hide();
        $('#dk_div').hide();
        $('#no_div').hide();
        $('#sv_div').hide();

        $('#' + language + '_div').show();
    });

    $('.cancel').on('click', function() {

        location.href = ajax_url + 'villas/list';
    });

    $('.insert').on('click', function() {

        disableButtons(false);
        formatPricesArray();

        var renter_input = $('.renter');
        var name_input = $('.name');
        var url_name_input = $('.url-name');
        var address_input = $('.address');
        var city_input = $('.city');
        var zip_code_input = $('.zip-code');
        var deposit_input = $('.deposit');
        var pets_price_input = $('.pets-price');
        var short_description_input = $('.en-short-description');
        var description_input = $('.en-description');
        var discount_input = $('.discount');
        var latitude = $('.latitude').val();

        var validation = validateForm(renter_input, name_input, url_name_input, address_input, city_input, zip_code_input,
            deposit_input, pets_price_input, short_description_input, description_input, discount_input, latitude);

        if (!validation)
        {
            enableButtons(false);
            toastr.error(validation_error);

            return 0;
        }

        insertVilla(renter_input.val(), name_input.val(), url_name_input.val(), address_input.val(), city_input.val(),
            zip_code_input.val(), deposit_input.val(), pets_price_input.val(), discount_input.val(), latitude, prices_array);
    });

    $('.update').on('click', function() {

        disableButtons(true);
        formatPricesArray();

        var renter_input = $('.renter');
        var name_input = $('.name');
        var url_name_input = $('.url-name');
        var address_input = $('.address');
        var city_input = $('.city');
        var zip_code_input = $('.zip-code');
        var deposit_input = $('.deposit');
        var pets_price_input = $('.pets-price');
        var short_description_input = $('.en-short-description');
        var description_input = $('.en-description');
        var discount_input = $('.discount');
        var latitude = $('.latitude').val();

        var validation = validateForm(renter_input, name_input, url_name_input, address_input, city_input, zip_code_input,
            deposit_input, pets_price_input, short_description_input, description_input, discount_input, latitude);

        if (!validation)
        {
            enableButtons(true);
            toastr.error(validation_error);

            return 0;
        }

        updateVilla(renter_input.val(), name_input.val(), url_name_input.val(), address_input.val(), city_input.val(),
            zip_code_input.val(), deposit_input.val(), pets_price_input.val(), discount_input.val(), latitude, prices_array);
    });

    $('.attribute-translation').on('click', function() {

        clearTranslations();

        var attribute_id = $(this).attr('data-attribute-id');

        $('#attribute-id').val(attribute_id);

        if (typeof attribute_translations[attribute_id] !== 'undefined')
        {
            $.each(attribute_translations[attribute_id], function(index, value) {

                $('.' + index + '-translation').val(value);
            });
        }

        $('#attributeTranslationsModal').modal('show');
    });

    $('.add-translation').on('click', function() {

        addAttributeTranslation($('#attribute-id').val());
    });

    $('.remove-translation').on('click', function() {

        removeAttributeTranslation($('#attribute-id').val());
    });

    $('.add-price').on('click', function() {

        appendPriceElement();
    });

    $('.prices').on('click', '.remove-price', function() {

        $(this).parents('.price-element').remove();
    });
});