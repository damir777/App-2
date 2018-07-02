function getCalendar(next_date)
{
    var villa = $('#villa').val();
    var date = $('#date').val();

    $.ajax({
        url: ajax_url + 'calendar',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'is_renter': 'F', 'villa': villa, 'date': date, 'next_date': next_date, 'reset_date': 'F'},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    showCalendar(data.days, data.date, data.calendar_date);
                    break;
                case 2:
                    toastr.warning(data.warning);
                    break;
                case 0:
                    toastr.error(data.error);
                    break;
                default:
                    toastr.warning(data.warning);
            }
        },
        error: function() {
            toastr.error(error);
        }
    });
}

function showCalendar(days, date, calendar_date)
{
    var i = 1;

    var append_string = '';

    $.each(days, function(index, value) {

        if (i % 7 === 1)
        {
            append_string += '<div class="row m-0">';
        }

        if (value.in_month === 'T')
        {
            if (value.booked === 'F')
            {
                append_string += '<div class="month-day" data-date="' + value.date + '">' + value.day + '</div>';
            }
            else
            {
                if (value.enable_check_in === 'T')
                {
                    append_string += '<div class="month-day enable-check-in" data-date="' + value.date + '">' + value.day + '</div>';
                }
                else if (value.enable_check_out === 'T')
                {
                    append_string += '<div class="month-day enable-check-out">' + value.day + '</div>';
                }
                else
                {
                    append_string += '<div class="month-day booked">' + value.day + '</div>';
                }
            }
        }
        else
        {
            append_string += '<div class="month-day"></div>';
        }

        if (i % 7 === 0)
        {
            append_string += '</div>';
        }

        i++;
    });

    $('.days').html('').append(append_string);

    $('#date').val(date);
    $('#calendar-date').html(calendar_date);

    hideLoader();
}

function getBookingInfo()
{
    $.ajax({
        url: ajax_url + 'getBookingInfo',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'villa': villa_id, 'start_date': booking_start_date, 'end_date': booking_end_date},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:

                    $('#booking-start-date').html(booking_start_date);
                    $('#booking-end-date').html(booking_end_date);
                    $('#booking-nights').html(data.nights);
                    $('#booking-price').html(data.sum);

                    $('.booking-steps-header').show();
                    $('.move-to-authentication').show();

                    break;
                case 2:
                    toastr.warning(data.warning);
                    break;
                case 0:
                    toastr.error(data.error);
                    break;
                default:
                    toastr.warning(data.warning);
            }
        },
        error: function() {
            toastr.error(error);
        }
    });
}

function setBookingUrl(step)
{
    var location = window.location;

    location.hash = step;
}

function showBookingStep()
{
    var page_hash = window.location.hash;

    switch (page_hash)
    {
        case '#calendar':
            showBookingCalendar();
            break;
        case '#authentication':

            if (logged_in === 'T')
            {
                showBookingPayment();
            }
            else
            {
                showBookingAuthentication();
            }

            break;
        case '#payment':
            showBookingPayment();
            break;
        default:
            setBookingUrl('calendar');
    }
}

function showBookingCalendar()
{
    $('#booking-authentication').hide();
    $('#booking-payment').hide();
    $('#booking-calendar').show();

    setBookingUrl('calendar');
}

function showBookingAuthentication()
{
    $('#booking-calendar').hide();
    $('#booking-payment').hide();
    $('#booking-authentication').show();

    setBookingUrl('authentication');
}

function showBookingPayment()
{
    $('#booking-calendar').hide();
    $('#booking-authentication').hide();
    $('#booking-payment').show();

    setBookingUrl('payment');
}

function insertTempBooking()
{
    var adults = $('#adults').val();
    var children = $('#children').val();
    var infants = $('#infants').val();

    $.ajax({
        url: ajax_url + 'insertTempBooking',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'villa': villa_id, 'start_date': booking_start_date, 'end_date': booking_end_date, 'adults': adults, 'children': children,
            'infants': infants},
        success: function(data) {

            enableButton('move-to-authentication');

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:

                    $('#downpayment').html(data.formatted_downpayment);
                    $('#remaining-payment').html(data.formatted_remaining_payment);
                    $('#due-date').html(data.due_date);

                    if (logged_in === 'T')
                    {
                        showBookingPayment();
                    }
                    else
                    {
                        showBookingAuthentication();
                    }

                    break;
                case 2:
                    toastr.warning(data.warning);
                    break;
                case 0:
                    toastr.error(data.error);
                    break;
            }
        },
        error: function() {
            enableButton('move-to-authentication');
            toastr.error(error);
        }
    });
}

function insertBookingCustomer()
{
    var full_name = $('#booking-full-name').val();
    var country = $('#booking-country').val();
    var phone = $('#booking-phone').val();
    var email = $('#booking-email').val();
    var confirm_email = $('#booking-confirm-email').val();

    $.ajax({
        url: ajax_url + 'insertBookingCustomer',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'full_name': full_name, 'country': country, 'phone': phone, 'email': email, 'confirm_email': confirm_email},
        success: function(data) {

            enableButton('move-to-payment');

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    showBookingPayment();
                    break;
                case 2:
                    toastr.warning(data.warning);
                    break;
                case 0:
                    toastr.error(data.error);
                    break;
            }
        },
        error: function() {
            enableButton('move-to-payment');
            toastr.error(error);
        }
    });
}

function confirmBooking()
{
    var downpayment = parseInt($('input[name=downpayment]:checked').val());
    var remaining_payment = parseInt($('input[name=remaining_payment]:checked').val());

    $.ajax({
        url: ajax_url + 'userConfirmBooking',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'downpayment': downpayment, 'remaining_payment': remaining_payment},
        success: function(data) {

            enableButton('proceed-payment');
            enableButton('confirm-booking');

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    location.href = data.url;
                    break;
                case 0:
                    toastr.error(data.error);
                    break;
                default:
                    toastr.warning(data.warning);
            }
        },
        error: function() {
            enableButton('proceed-payment');
            enableButton('confirm-booking');
            toastr.error(error);
        }
    });
}

function checkBookingPersonalData()
{
    $.ajax({
        url: ajax_url + 'checkBookingPersonalData',
        type: 'get',
        dataType: 'json',
        success: function(data) {

            enableButton('proceed-payment');
            enableButton('confirm-booking');

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:

                    if (data.country == null || data.phone == null)
                    {

                        $('.booking-personal-data-country').val(data.country).trigger('change');
                        $('.user-phone').val(data.phone);

                        $('#bookingPersonalDataModal').modal('show');

                        toastr.info(data.info);
                    }
                    else
                    {
                        disableButton('proceed-payment');
                        disableButton('confirm-booking');

                        confirmBooking();
                    }

                    break;
                case 0:
                    toastr.error(data.error);
                    break;
                default:
                    toastr.warning(data.warning);
            }
        },
        error: function() {
            enableButton('proceed-payment');
            enableButton('confirm-booking');
            toastr.error(error);
        }
    });
}

function updateBookingPersonalData()
{
    var country = $('.user-country').val();
    var phone = $('.user-phone').val();

    $.ajax({
        url: ajax_url + 'updateBookingPersonalData',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'country': country, 'phone': phone},
        success: function(data) {

            enableButton('update-booking-personal-data');

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:

                    $('#bookingPersonalDataModal').modal('hide');

                    disableButton('proceed-payment');
                    disableButton('confirm-booking');
                    confirmBooking();

                    break;
                case 0:
                    toastr.error(data.error);
                    break;
                default:
                    toastr.warning(data.warning);
            }
        },
        error: function() {
            enableButton('update-booking-personal-data');
            toastr.error(error);
        }
    });
}

function managePaymentNextButton()
{
    if (downpayment_selected === 'T' && remaining_payment_selected === 'T')
    {
        var downpayment = parseInt($('input[name=downpayment]:checked').val());

        if (downpayment === 1)
        {
            enableButton('confirm-booking');
            $('.proceed-payment').hide();
            $('.confirm-booking').show();
        }
        else
        {
            enableButton('proceed-payment');
            $('.confirm-booking').hide();
            $('.proceed-payment').show();
        }
    }
}

function showLoader()
{
    $('.days').addClass('loader-opacity');
    $('.calendar-loader').css('display', 'inline-block');
}

function hideLoader()
{
    $('.days').removeClass('loader-opacity');
    $('.calendar-loader').css('display', 'none');
}

function disableButton(button_class)
{
    $('.' + button_class).prop('disabled', true);
}

function enableButton(button_class)
{
    $('.' + button_class).prop('disabled', false);
}

var downpayment_selected = 'F';
var remaining_payment_selected = 'F';

$(document).ready(function() {

    if (is_booking_page === 'T')
    {
        if (selected_start_date)
        {
            setBookingDates();
        }

        showBookingStep();
    }
    else
    {
        getCalendar(0);
    }

    $('#calendar-prev').click(function() {

        showLoader();

        setTimeout(function() {

            getCalendar('-');
        }, 500);
    });

    $('#calendar-next').click(function() {

        showLoader();

        setTimeout(function() {

            getCalendar('+');
        }, 500);
    });

    $('.move-to-authentication').on('click', function() {

        disableButton('move-to-authentication');
        insertTempBooking();
    });

    $('.move-to-payment').on('click', function() {

        disableButton('move-to-payment');
        insertBookingCustomer();
    });

    $('.proceed-payment').on('click', function() {

        disableButton('proceed-payment');

        if (logged_in === 'T')
        {
            checkBookingPersonalData();
        }
        else
        {
            confirmBooking();
        }
    });

    $('.confirm-booking').on('click', function() {

        disableButton('confirm-booking');

        if (logged_in === 'T')
        {
            checkBookingPersonalData();
        }
        else
        {
            confirmBooking();
        }
    });

    $('.back-to-calendar').on('click', function() {

        showBookingCalendar();
    });

    $('.back-to-authentication').on('click', function() {

        if (logged_in === 'T')
        {
            showBookingCalendar();
        }
        else
        {
            showBookingAuthentication();
        }
    });

    $('.update-booking-personal-data').on('click', function() {

        disableButton('update-booking-personal-data');
        updateBookingPersonalData();
    });

    $('input[type=radio][name=downpayment]').change(function() {

        downpayment_selected = 'T';

        managePaymentNextButton();
    });

    $('input[type=radio][name=remaining_payment]').change(function() {

        remaining_payment_selected = 'T';

        managePaymentNextButton();
    });
});