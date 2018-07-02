function substringMatcher(cities)
{
    return function findMatches(q, cb) {

        var matches, substringRegex;

        matches = [];

        substringRegex = new RegExp(q, 'i');

        // iterate through the pool of strings and for any string that
        // contains the substring `q`, add it to the `matches` array
        $.each(cities, function(i, str) {

            if (substringRegex.test(str))
            {
                matches.push(str);
            }
        });

        cb(matches);
    };
}

var search_cities = [];

$(document).ready(function() {

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "preventDuplicates": true,
        "positionClass": "toast-bottom-right",
        "onclick": null,
        "showDuration": "400",
        "hideDuration": "1000",
        "timeOut": "8000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    $('.city-input .typeahead').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    },
    {
        source: substringMatcher(search_cities)
    });

    $('.input-group.date').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        format: "dd.mm.yyyy."
    });

    $('.lang-select, .lang-select-mobile').on('change', function() {

        var language = $(this).val();

        location.href = ajax_url + 'locale/' + language;
    });

    $('#currency-select').on('change', function() {

        var currency = $('#currency-select').val();

        location.href = ajax_url + 'currency/' + currency;
    });

    $('#advanced-search').on('click', function(e) {

        e.preventDefault();

        $('.advanced-search-holder').toggle();
    });

    $('#close-advanced-search').on('click', function(e) {

        e.preventDefault();

        $('.advanced-search-holder').hide();
    });

    $('.guests-plus').on('click', function() {

        var guests_input = $('#guests');
        var initial_guests = guests_input.val();

        if (initial_guests != '')
        {
            guests_input.val(parseInt(guests_input.val()) + 1);
        }
        else
        {
            guests_input.val(1);
        }
    });

    $('.guests-minus').on('click', function() {

        var guests_input = $('#guests');
        var initial_guests = guests_input.val();

        if (initial_guests != '')
        {
            if (initial_guests != 1)
            {
                guests_input.val(parseInt(guests_input.val()) - 1);
            }
            else
            {
                guests_input.val('');
            }
        }
    });

    $('#booking-country, .country, .booking-personal-data-country').select2();
});