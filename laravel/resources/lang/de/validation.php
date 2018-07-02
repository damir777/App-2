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
        'numeric' => ':attribute muss zwischen :min und :max sein',
        'file'    => 'The :attribute must be between :min and :max kilobytes.',
        'string'  => 'The :attribute must be between :min and :max characters.',
        'array'   => 'The :attribute must have between :min and :max items.',
    ],
    'boolean'              => 'The :attribute field must be true or false.',
    'confirmed'            => 'Das Passwort muss bestätigt sein',
    'date'                 => 'The :attribute is not a valid date.',
    'date_format'          => 'The :attribute does not match the format :format.',
    'different'            => 'The :attribute and :other must be different.',
    'digits'               => ':attribute müssen :digits Ziffern haben',
    'digits_between'       => 'The :attribute must be between :min and :max digits.',
    'dimensions'           => 'The :attribute has invalid image dimensions.',
    'distinct'             => 'The :attribute field has a duplicate value.',
    'email'                => ':attribute muss eine gültige E-Mail-Adresse sein',
    'exists'               => ':attribute ist nicht gültig',
    'file'                 => 'The :attribute must be a file.',
    'filled'               => 'The :attribute field must have a value.',
    'image'                => 'The :attribute must be an image.',
    'in'                   => ':attribute ist nicht gültig',
    'in_array'             => 'The :attribute field does not exist in :other.',
    'integer'              => ':attribute muss eine ganze Zahl sein',
    'ip'                   => 'The :attribute must be a valid IP address.',
    'ipv4'                 => 'The :attribute must be a valid IPv4 address.',
    'ipv6'                 => 'The :attribute must be a valid IPv6 address.',
    'json'                 => 'The :attribute must be a valid JSON string.',
    'max'                  => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => ':attribute darf nicht mehr als :max Zeichen enthalten',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'                => ':attribute besitzt keinen gültigen Datothekentyp',
    'mimetypes'            => 'The :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => ':attribute muss grösser als :min sein',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => ':attribute muss mindestens :min Zeichen haben',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'numeric'              => 'The :attribute must be a number.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'The :attribute format is invalid.',
    'required'             => ':attribute ist obligatorische Information',
    'required_if'          => 'The :attribute field is required when :other is :value.',
    'required_unless'      => ':attribute ist obligatorisches Feld, außer :other ist gleich wie :values',
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => 'The :attribute and :other must match.',
    'size'                 => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'               => 'The :attribute must be a string.',
    'timezone'             => 'The :attribute must be a valid zone.',
    'unique'               => ':attribute wird bereits benutzt',
    'uploaded'             => 'The :attribute failed to upload.',
    'url'                  => 'The :attribute format is invalid.',
    'decimal'              => ':attribute muss eine Dezimalzahl sein',
    'oib'                  => ':attribute muss 11 Ziffern enthalten',

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
            'required' => 'Bitte bestätigen Sie, dass Sie kein Roboter sind',
            'captcha' => 'Validierung des Eintrags ungültig! Versuchen Sie es bitte erneut',
        ],
        'attributes.*.id' => [
            'required' => 'Die Attribut-ID ist eine Pflichtangabe',
            'integer' => 'Die Attribut-ID muss eine ganze Zahl sein',
            'exists' => 'Die Attribut-ID ist nicht gültig'
        ],
        'prices.*.start_day' => [
            'required' => 'Das Anfangsdatum ist eine Pflichtangabe',
            'price_date' => 'Das Anfangsdatum muss den Tag und Monat enthalten'
        ],
        'prices.*.end_day' => [
            'required' => 'Das Abschlussdatum ist eine Pflichtangabe',
            'price_date' => 'Das Abschlussdatum muss den Tag und Monat enthalten'
        ],
        'prices.*.price' => [
            'required' => 'Der Preis ist eine Pflichtangabe',
            'integer' => 'Der Preis muss eine ganze Zahl sein'
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

    'attributes' => ['name' => 'Name', 'en_name' => 'Name', 'category' => 'Kategorie', 'featured' => 'vorgestellt',
        'searchable' => 'durchsuchbar', 'icon' => 'Icon', 'full_name' => 'Vorname und Name', 'email' => 'E-Mail', 'password' => 'Passwort',
        'password_confirmation' => 'Passwortbestätigung', 'address' => 'Adresse', 'city' => 'Stadt', 'zip_code' => 'Postleitzahl',
        'country' => 'Land', 'phone' => 'Telefon', 'oib' => 'PIN', 'invoice_full_name' => 'Vorname und Name',
        'invoice_address' => 'Adresse', 'invoice_city' => 'Stadt', 'invoice_zip_code' => 'Postleitzahl', 'domestic_owner' => 'Eigentümer',
        'domestic_bank' => 'Bank', 'account_number' => 'Kontonummer', 'foreign_owner' => 'Eigentümer', 'foreign_bank' => 'Bank',
        'iban' => 'IBAN', 'swift' => 'SWIFT', 'host_full_name' => 'Vorname und Name', 'host_email' => 'E-Mail',
        'host_languages' => 'Sprachen', 'url_name' => 'URL-Name', 'en_short_description' => 'Kurze Beschreibung', 'deposit' => 'Depot',
        'pets_price' => 'Zuschlag für Haustiere', 'start_month' => 'Anfangsmonat', 'end_month' => 'Abschlussmonat',
        'season_start_month' => 'Anfangsmonat der Saison', 'season_end_month' => 'Abschlussmonat der Saison',
        'discount_type' => 'Nachlassart', 'discount' => 'Nachlass', 'adults' => 'Erwachsene', 'children' => 'Kinder',
        'infants' => 'Säuglinge', 'confirm_email' => 'Bestätigte E-Mail-Adresse']

];
