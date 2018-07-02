function getCalendar(next_date, reset_date)
{
    var villa = $('#villa').val();
    var date = $('#date').val();

    $.ajax({
        url: ajax_url + 'calendar',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'is_renter': is_renter, 'villa': villa, 'date': date, 'next_date': next_date, 'reset_date': reset_date},
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
            append_string += '<div class="row">';
        }

        if (value.in_month === 'T')
        {
            if (value.booked === 'F')
            {
                append_string += '<div class="col-sm-2 days not-booked show-modal" data-toggle="modal"' +
                    ' data-target="#adminBookingModal" data-date="' + value.date + '">' + value.day + '</div>';
            }
            else
            {
                var booking_data = '';

                $.each(value.booking_data, function(index2, value2) {

                    if (booking_data !== '')
                    {
                        booking_data += '<br><br>';
                    }

                    booking_data += value2.date + '<br>' + value2.user;

                    if (value2.country !== '')
                    {
                        booking_data += ', ' + value2.country + '<br>' + value2.phone + ', ' + value2.email;
                    }
                });

                if (value.enable_check_in === 'T')
                {
                    append_string += '<div class="col-sm-2 days enable-check-in show-modal" data-toggle="modal"' +
                        ' data-target="#adminBookingModal" data-date="' + value.date + '">' + value.day + '</div>';
                }
                else if (value.enable_check_out === 'T')
                {
                    append_string += '<div class="col-sm-2 days enable-check-out" data-content="' + booking_data + '"' +
                        ' data-toggle="popover" data-trigger="click">' + value.day + '</div>';
                }
                else
                {
                    append_string += '<div class="col-sm-2 days booked" data-content="' + booking_data + '"' +
                     ' data-toggle="popover" data-trigger="click">' + value.day + '</div>';
                }
            }
        }
        else
        {
            append_string += '<div class="col-sm-2 days"></div>';
        }

        if (i % 7 === 0)
        {
            append_string += '</div>';
        }

        i++;
    });

    $('#calendar-content').html('').append(append_string);

    $('#date').val(date);
    $('#calendar-date').html(calendar_date);

    hideLoader();

    $('#calendar').show();
    $('[data-toggle="popover"]').popover({placement: 'top', html: 'true'});
}

function insertBooking()
{
    var villa = $('#villa').val();
    var start_date = $('.start-date').val();
    var end_date = $('.end-date').val();

    $.ajax({
        url: ajax_url + 'insertBooking',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'is_renter': is_renter, 'villa': villa, 'start_date': start_date, 'end_date': end_date},
        success: function(data) {

            enableButtons();

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    $('#adminBookingModal').modal('hide');
                    toastr.success(data.success);
                    getCalendar(0);
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
            enableButtons();
            toastr.error(error);
        }
    });
}

function rejectBooking(message)
{
    $.ajax({
        url: ajax_url + 'renter/bookings/reject',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'id': booking_id, 'message': message},
        success: function(data) {

            enableButtons();

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    location.href = ajax_url + 'renter/bookings/new';
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
            enableButtons();
            toastr.error(error);
        }
    });
}

function showLoader()
{
    $('#calendar-content').addClass('loader-opacity');
    $('.calendar-loader').css('display', 'inline-block');
}

function hideLoader()
{
    $('#calendar-content').removeClass('loader-opacity');
    $('.calendar-loader').css('display', 'none');
}

function disableButtons()
{
    $('.insert').prop('disabled', true);
    $('.cancel').prop('disabled', true);
    $('.reject').prop('disabled', true);
}

function enableButtons()
{
    $('.insert').prop('disabled', false);
    $('.cancel').prop('disabled', false);
    $('.reject').prop('disabled', false);
}

var booking_id = 0;

$(document).ready(function() {

    if (typeof is_renter !== 'undefined')
    {
        getCalendar(0, 'F');
    }

    $('.previous-arrow').click(function() {

        showLoader();

        setTimeout(function() {

            getCalendar('-', 'F');
        }, 500);
    });

    $('.next-arrow').click(function() {

        showLoader();

        setTimeout(function() {

            getCalendar('+', 'F');
        }, 500);
    });

    $('#calendar').on('click', '.show-modal', function() {

        var thisDate = $(this);
        var date = thisDate.attr('data-date');

        $('.start-date').val(date);
        $('.end-date').val(date);
    });

    $('.show-calendar').on('click', function() {

        showLoader();

        setTimeout(function() {

            getCalendar(0, 'T');
        }, 500);
    });

    $('.insert').on('click', function() {

        disableButtons();
        insertBooking();
    });

    $('.reject-booking').on('click', function() {

        booking_id = $(this).attr('data-booking-id');
    });

    $('.reject').on('click', function() {

        var message = $('.reject-message').val();

        if (message == '')
        {
            toastr.error(message_error);

            return 0;
        }

        disableButtons();
        rejectBooking(message);
    });
});