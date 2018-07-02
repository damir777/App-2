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
        'numeric' => 'The :attribute must be between :min and :max',
        'file'    => 'The :attribute must be between :min and :max kilobytes.',
        'string'  => 'The :attribute must be between :min and :max characters.',
        'array'   => 'The :attribute must have between :min and :max items.',
    ],
    'boolean'              => 'The :attribute field must be true or false.',
    'confirmed'            => 'Password must be confirmed',
    'date'                 => 'The :attribute is not a valid date.',
    'date_format'          => 'The :attribute does not match the format :format.',
    'different'            => 'The :attribute and :other must be different.',
    'digits'               => 'The :attribute must be :digits digits',
    'digits_between'       => 'The :attribute must be between :min and :max digits.',
    'dimensions'           => 'The :attribute has invalid image dimensions.',
    'distinct'             => 'The :attribute field has a duplicate value.',
    'email'                => 'The :attribute must be a valid email address',
    'exists'               => 'The :attribute is invalid',
    'file'                 => 'The :attribute must be a file.',
    'filled'               => 'The :attribute field must have a value.',
    'image'                => 'The :attribute must be an image.',
    'in'                   => 'The :attribute is invalid',
    'in_array'             => 'The :attribute field does not exist in :other.',
    'integer'              => 'The :attribute must be an integer',
    'ip'                   => 'The :attribute must be a valid IP address.',
    'ipv4'                 => 'The :attribute must be a valid IPv4 address.',
    'ipv6'                 => 'The :attribute must be a valid IPv6 address.',
    'json'                 => 'The :attribute must be a valid JSON string.',
    'max'                  => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => 'The :attribute may not be greater than :max characters',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'                => 'The :attribute has invalid file type',
    'mimetypes'            => 'The :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'The :attribute must be at least :min',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => 'The :attribute must be at least :min characters',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'numeric'              => 'The :attribute must be a number.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'The :attribute format is invalid.',
    'required'             => 'The :attribute is required data',
    'required_if'          => 'The :attribute field is required when :other is :value.',
    'required_unless'      => 'The :attribute is required data, unless :other is equal :values',
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
    'unique'               => 'The :attribute is already being used',
    'uploaded'             => 'The :attribute failed to upload.',
    'url'                  => 'The :attribute format is invalid.',
    'decimal'              => 'The :attribute must be a decimal number',
    'oib'                  => 'The :attribute must have 11 digits',

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
            'required' => 'Please confirm that you are not a robot',
            'captcha' => 'Entry validation failed! Please try again'
        ],
        'attributes.*.id' => [
            'required' => 'Attribute ID is required data',
            'integer' => 'Attribute ID must be an integer',
            'exists' => 'Attribute ID is invalid'
        ],
        'prices.*.start_day' => [
            'required' => 'Start date is required data',
            'price_date' => 'End date must contain a day and a month'
        ],
        'prices.*.end_day' => [
            'required' => 'End date is required data',
            'price_date' => 'End date must contain a day and a month'
        ],
        'prices.*.price' => [
            'required' => 'Price is required data',
            'integer' => 'Price must be an integer'
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

    'attributes' => ['name' => 'Name', 'en_name' => 'Name', 'category' => 'Category', 'featured' => 'Featured',
        'searchable' => 'Searchable', 'icon' => 'Icon', 'full_name' => 'Full name', 'email' => 'Email', 'password' => 'Password',
        'password_confirmation' => 'Password confirmation', 'address' => 'Address', 'city' => 'City', 'zip_code' => 'Postal code',
        'country' => 'Country', 'phone' => 'Phone', 'oib' => 'PIN', 'invoice_full_name' => 'Full name', 'invoice_address' => 'Address',
        'invoice_city' => 'City', 'invoice_zip_code' => 'Postal code', 'domestic_owner' => 'Owner', 'domestic_bank' => 'Bank',
        'account_number' => 'Account number', 'foreign_owner' => 'Owner', 'foreign_bank' => 'Bank', 'iban' => 'IBAN',
        'swift' => 'SWIFT', 'host_full_name' => 'Full name', 'host_email' => 'Email', 'host_languages' => 'Languages',
        'url_name' => 'URL name', 'en_short_description' => 'Short description', 'deposit' => 'Deposit',
        'pets_price' => 'Surcharge for pets', 'start_month' => 'Start month', 'end_month' => 'End month',
        'season_start_month' => 'Season start month', 'season_end_month' => 'Season final month', 'discount_type' => 'Discount type',
        'discount' => 'Discount', 'adults' => 'Adults', 'children' => 'Children', 'infants' => 'Infants',
        'confirm_email' => 'E-mail address confirmed']

];
