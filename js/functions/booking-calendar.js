function setBookingDates()
{
    var booking_date;

    if (is_booking_page === 'T')
    {
        booking_date = $('#booking-calendar-input').val();
    }
    else
    {
        booking_date = $('#when').val();
    }

    var start_date_input = $('#start-date');
    var end_date_input = $('#end-date');

    var to_string_position = booking_date.indexOf('-');

    if (to_string_position === -1)
    {
        if (is_booking_page === 'T')
        {
            booking_start_date = null;
            booking_end_date = null;
        }
        else
        {
            start_date_input.val('');
            end_date_input.val('');
        }
    }
    else
    {
        if (is_booking_page === 'T')
        {
            booking_start_date = booking_date.slice(0, 11);
            booking_end_date = booking_date.slice(14, 25);

            getBookingInfo();
        }
        else
        {
            start_date_input.val(booking_date.slice(0, 11));
            end_date_input.val(booking_date.slice(14, 25));
        }
    }
}

function clearBookingDates()
{
    if (is_booking_page === 'T')
    {
        bookingCalendar.clear();
    }
    else
    {
        searchCalendar.clear();

        $('#start-date').val('');
        $('#end-date').val('');
    }
}

var searchCalendar;
var bookingCalendar;
var i18n;

$(document).ready(function() {

    $('.clear-selection').on('click', function() {

        clearBookingDates();
    });

    $('#booking-calendar').on('click', '.clear-booking-selection', function() {

        clearBookingDates();
    });

    if (is_booking_page === 'T')
    {
        var input = document.getElementById('booking-calendar-input');
        bookingCalendar = new HotelDatepicker(input,
            {

                format: 'DD.MM.YYYY.',
                startOfWeek: 'monday',
                showTopbar: false,
                moveBothMonths: true,
                selectForward: true,
                seasonMonths: season_months,
                minNights: 5,
                showClearTopbar: true,
                autoClose: false,
                enableCheckout: true,
                endDate: calendar_end_date,
                disabledMonths: disabled_months,
                disabledDates: no_check_in_dates,
                invalidDates: disabled_dates,
                noCheckInDates: no_check_in_dates,
                noCheckOutDates: no_check_out_dates,
                i18n: i18n,
                onDayClick: function() {

                    setBookingDates();
                }
            });

        if (selected_start_date)
        {
            bookingCalendar.setRange(selected_start_date, selected_end_date);
        }

        bookingCalendar.open();
    }
    else
    {
        var when = document.getElementById('when');
        searchCalendar = new HotelDatepicker(when,
            {
                format: 'DD.MM.YYYY.',
                startOfWeek: 'monday',
                showTopbar: false,
                moveBothMonths: true,
                selectForward: true,
                minNights: 5,
                enableDocumentClick: true,
                i18n: i18n,
                onDayClick: function() {

                    setBookingDates();
                }
            });
    }
});