<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    'login_error' => 'Login data are incorrect',
    //'app_login_error' => 'Da bi nastavili sa radom morate biti prijavljeni',
    'user_role_error' => 'You are not authorized to execute the initiated activity',
    'validation_error' => 'Improperly filled out form',
    'error' => 'An error has occurred! Please try again',

    'error_page_1' => 'An error has occurred!',
    'error_page_2' => 'Our apologies, xx portal experienced an unexpected error and it is not possible to perform the selected
        activity.',

    'not_found_1' => 'Page not found!',
    'not_found_2' => 'Our apologies, the page you requested was not found. Please check the entered URL address.',

    /*
    |--------------------------------------------------------------------------
    | Villa
    |--------------------------------------------------------------------------
    */

    'missing_location' => 'Please enter location of the villa',
    'villa_price_start_day' => 'Price has to be defined for all days in selected period',

    /*
    |--------------------------------------------------------------------------
    | Booking
    |--------------------------------------------------------------------------
    */

    'invalid_villa' => 'Selected villa not found',
    'booking_current_time_error' => 'Check-in date must fall after the current date',
    'booking_end_time_error' => 'Check-out date must fall after the booking start date',
    'booking_time_error' => 'Villa is already booked for the selected time period',
    'available_months_error' => 'Villa cannot be booked in the selected time period',
    'max_guests_error' => 'Total number of adults and children must be lower than or equal to :guests',
    'confirm_booking_error' => 'Booking confirmation is not possible! Please check the booking period',
    'reject_booking_error' => 'Booking rejection is not possible! Please check the booking period',
    'multiple_bookings_error' => 'Booking confirmation is not possible! Multiple bookings are made for the same time period',
    'reject_booking_message' => 'Please enter message text'
];
