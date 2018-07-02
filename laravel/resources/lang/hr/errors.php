<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    'login_error' => 'Podaci za prijavu nisu točni',
    //'app_login_error' => 'Da bi nastavili sa radom morate biti prijavljeni',
    'user_role_error' => 'Nemate prava izvršiti započetu radnju',
    'validation_error' => 'Forma je nepravilno popunjena',
    'error' => 'Dogodila se greška! Molimo pokušajte ponovno',

    'error_page_1' => 'Dogodila se greška!',
    'error_page_2' => 'Ispričavamo se, dogodila se neočekivana greška na xx portalu te nije moguće izvršiti traženu radnju.',

    'not_found_1' => 'Stranica nije pronađena!',
    'not_found_2' => 'Ispričavamo se, stranica koju ste tražili nije pronađena. Molimo provjerite upisanu URL adresu.',

    /*
    |--------------------------------------------------------------------------
    | Villa
    |--------------------------------------------------------------------------
    */

    'missing_location' => 'Molimo unesite lokaciju vile',
    'villa_price_start_day' => 'Cijena mora biti definirana za sve dane u odabranom periodu',

    /*
    |--------------------------------------------------------------------------
    | Booking
    |--------------------------------------------------------------------------
    */

    'invalid_villa' => 'Odabrana vila nije pronađena',
    'booking_current_time_error' => 'Check-in mora biti veći od trenutnog datuma',
    'booking_end_time_error' => 'Check-out mora biti veći od datuma početka rezervacije',
    'booking_time_error' => 'Vila je zauzeta u odabranom terminu',
    'available_months_error' => 'Vilu nije moguće rezervirati u odabranom terminu',
    'max_guests_error' => 'Zbroj odraslih i djece mora biti manji ili jednak :guests',
    'confirm_booking_error' => 'Potvrda rezervacije nije moguća! Provjerite vrijeme rezervacije',
    'reject_booking_error' => 'Odbijanje rezervacije nije moguće! Provjerite vrijeme rezervacije',
    'multiple_bookings_error' => 'Potvrda rezervacije nije moguća! U istom terminu postoji druga rezervacija',
    'reject_booking_message' => 'Molimo unesite tekst poruke'
];
