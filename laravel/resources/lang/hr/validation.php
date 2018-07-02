<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'The :attribute must be accepted.',
    'active_url'           => 'The :attribute is not a valid URL.',
    'after'                => 'The :attribute must be a date after :date.',
    'after_or_equal'       => 'The :attribute must be a date after or equal to :date.',
    'alpha'                => 'The :attribute may only contain letters.',
    'alpha_dash'           => 'The :attribute may only contain letters, numbers, and dashes.',
    'alpha_num'            => 'The :attribute may only contain letters and numbers.',
    'array'                => 'The :attribute must be an array.',
    'before'               => 'The :attribute must be a date before :date.',
    'before_or_equal'      => 'The :attribute must be a date before or equal to :date.',
    'between'              => [
        'numeric' => ':attribute mora biti između :min i :max',
        'file'    => 'The :attribute must be between :min and :max kilobytes.',
        'string'  => 'The :attribute must be between :min and :max characters.',
        'array'   => 'The :attribute must have between :min and :max items.',
    ],
    'boolean'              => 'The :attribute field must be true or false.',
    'confirmed'            => 'Lozinka mora biti potvđena',
    'date'                 => 'The :attribute is not a valid date.',
    'date_format'          => 'The :attribute does not match the format :format.',
    'different'            => 'The :attribute and :other must be different.',
    'digits'               => ':attribute mora imati :digits znamenke',
    'digits_between'       => 'The :attribute must be between :min and :max digits.',
    'dimensions'           => 'The :attribute has invalid image dimensions.',
    'distinct'             => 'The :attribute field has a duplicate value.',
    'email'                => ':attribute mora biti validna email adresa',
    'exists'               => ':attribute nije validan/validna',
    'file'                 => 'The :attribute must be a file.',
    'filled'               => 'The :attribute field must have a value.',
    'image'                => 'The :attribute must be an image.',
    'in'                   => ':attribute nije validan/validna',
    'in_array'             => 'The :attribute field does not exist in :other.',
    'integer'              => ':attribute mora biti prirodni broj',
    'ip'                   => 'The :attribute must be a valid IP address.',
    'ipv4'                 => 'The :attribute must be a valid IPv4 address.',
    'ipv6'                 => 'The :attribute must be a valid IPv6 address.',
    'json'                 => 'The :attribute must be a valid JSON string.',
    'max'                  => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => ':attribute ne smije sadržavati više od :max znakova',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'                => ':attribute nema ispravan tip datoteke',
    'mimetypes'            => 'The :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => ':attribute mora biti veći od :min',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => ':attribute mora biti najmanje :min znakova',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'numeric'              => 'The :attribute must be a number.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'The :attribute format is invalid.',
    'required'             => ':attribute je obavezan podatak',
    'required_if'          => 'The :attribute field is required when :other is :value.',
    'required_unless'      => ':attribute je obavezno polje, osim ako je :other jednaka :values',
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => ':attribute i :other moraju biti identične',
    'size'                 => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'               => 'The :attribute must be a string.',
    'timezone'             => 'The :attribute must be a valid zone.',
    'unique'               => ':attribute se već koristi',
    'uploaded'             => 'The :attribute failed to upload.',
    'url'                  => 'The :attribute format is invalid.',
    'decimal'              => ':attribute mora biti decimalni broj',
    'oib'                  => ':attribute mora imati 11 znamenki',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'captcha' => [
            'required' => 'Molimo potvrdite da niste robot',
            'captcha' => 'Validacija unosa nije uspjela! Molimo pokušajte ponovno',
        ],
        'attributes.*.id' => [
            'required' => 'ID atributa je obavezan podatak',
            'integer' => 'ID atributa mora biti prirodni broj',
            'exists' => 'ID atributa nije validan'
        ],
        'prices.*.start_day' => [
            'required' => 'Početni dan je obavezan podatak',
            'price_date' => 'Početni dan mora sadržavati dan i mjesec'
        ],
        'prices.*.end_day' => [
            'required' => 'Završni dan je obavezan podatak',
            'price_date' => 'Završni dan mora sadržavati dan i mjesec'
        ],
        'prices.*.price' => [
            'required' => 'Cijena je obavezan podatak',
            'integer' => 'Cijena mora biti prirodni broj'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => ['name' => 'Naziv', 'en_name' => 'Naziv', 'category' => 'Kategorija', 'featured' => 'Istaknuta',
        'searchable' => 'Pretraživ', 'icon' => 'Ikona', 'full_name' => 'Ime i prezime', 'email' => 'E-mail', 'password' => 'Lozinka',
        'password_confirmation' => 'Potvrda lozinke', 'address' => 'Adresa', 'city' => 'Grad', 'zip_code' => 'Poštanski broj',
        'country' => 'Država', 'phone' => 'Telefon', 'oib' => 'OIB', 'invoice_full_name' => 'Ime i prezime',
        'invoice_address' => 'Adresa', 'invoice_city' => 'Grad', 'invoice_zip_code' => 'Poštanski broj', 'domestic_owner' => 'Vlasnik',
        'domestic_bank' => 'Banka', 'account_number' => 'Broj računa', 'foreign_owner' => 'Vlasnik', 'foreign_bank' => 'Banka',
        'iban' => 'IBAN', 'swift' => 'SWIFT', 'host_full_name' => 'Ime i prezime', 'host_email' => 'Email', 'host_languages' => 'Jezici',
        'url_name' => 'URL naziv', 'en_short_description' => 'Kratki opis', 'deposit' => 'Depozit',
        'pets_price' => 'Nadoplata za kućne ljubimce', 'start_month' => 'Početni mjesec', 'end_month' => 'Završni mjesec',
        'season_start_month' => 'Početni mjesec sezone', 'season_end_month' => 'Završni mjesec sezone', 'discount_type' => 'Vrsta popusta',
        'discount' => 'Popust', 'adults' => 'Odrasli', 'children' => 'Djeca', 'infants' => 'Dojenčad',
        'confirm_email' => 'Potvrđena e-mail adresa']

];
