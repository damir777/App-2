<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    'login_error' => 'Login-Daten sind inkorrekt',
    //'app_login_error' => 'Da bi nastavili sa radom morate biti prijavljeni',
    'user_role_error' => 'Sie sind nicht berechtigt, die angefangene Handlung vorzunehmen',
    'validation_error' => 'Formular ist inkorrekt ausgefüllt',
    'error' => 'Ein Fehler ist aufgetreten! Versuchen Sie es bitte erneut',

    'error_page_1' => 'Ein Fehler ist aufgetreten!',
    'error_page_2' => 'Entschuldigung, ein unerwarteter Fehler trat im xx-Portal ein und die gesuchte Handlung kann nicht
        vorgenommen werden.',

    'not_found_1' => 'Seite nicht gefunden!',
    'not_found_2' => 'Entschuldigung, die von Ihnen gesuchte Seite konnte nicht gefunden werden. Bitte überprüfen Sie die eingetragene
        URL-Adresse.',

    /*
    |--------------------------------------------------------------------------
    | Villa
    |--------------------------------------------------------------------------
    */

    'missing_location' => 'Tragen Sie bitte den Standort der Villa ein',
    'villa_price_start_day' => 'Der Preis muss für alle Tage im ausgewählten Zeitraum definiert werden',

    /*
    |--------------------------------------------------------------------------
    | Booking
    |--------------------------------------------------------------------------
    */

    'invalid_villa' => 'Die gewählte Villa konnte nicht gefunden werden',
    'booking_current_time_error' => 'Check-in muss grösser vom aktuellen Datum sein',
    'booking_end_time_error' => 'Check-out muss grösser vom Buchungsbeginn sein',
    'booking_time_error' => 'Die Villa ist im gewählten Termin besetzt',
    'available_months_error' => 'Die Villa kann im gewählten Termin nicht gebucht werden.',
    'max_guests_error' => 'Die Summe der Erwachsenen und Kinder muss kleiner oder gleich als :guests sein',
    'confirm_booking_error' => 'Die Buchung kann nicht bestätigt werden! Überprüfen Sie die Buchungszeit',
    'reject_booking_error' => 'Die Buchung kann nicht abgeleht werden! Überprüfen Sie die Buchungszeit',
    'multiple_bookings_error' => 'Die Buchung kann nicht bestätigt werden! In demselben Termin liegt schon eine andere Buchung vor',
    'reject_booking_message' => 'Bitte tragen Sie den Inhalt der Nachricht ein',
];
