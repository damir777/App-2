<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\Language;
use App\Status;
use App\PaymentType;
use App\User;
use App\AttributeCategory;
use App\Attribute;
use App\InvoiceAddress;
use App\BankAccount;
use App\Host;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        |--------------------------------------------------------------------------
        | User roles
        |--------------------------------------------------------------------------
        */

        $roles = [['name' => 'SuperAdmin', 'display_name' => 'Super Admin'], ['name' => 'Renter',
            'display_name' => 'Renter'], ['name' => 'User', 'display_name' => 'User']];

        Role::insert($roles);

        /*
        |--------------------------------------------------------------------------
        | Languages
        |--------------------------------------------------------------------------
        */

        $languages = [['code' => 'hr'], ['code' => 'en'], ['code' => 'de'], ['code' => 'fr'], ['code' => 'it'],
            ['code' => 'ru'], ['code' => 'dk'], ['code' => 'no'], ['code' => 'sv']];

        Language::insert($languages);

        /*
        |--------------------------------------------------------------------------
        | Statuses
        |--------------------------------------------------------------------------
        */

        $statuses = [['code' => 'new'], ['code' => 'payment_waiting'], ['code' => 'confirmed']];

        Status::insert($statuses);

        /*
        |--------------------------------------------------------------------------
        | Payment types
        |--------------------------------------------------------------------------
        */

        $payment_types = [['code' => 'bank_transfer', 'downpayment' => 'T', 'remaining_payment' => 'T'],
            ['code' => 'credit_card', 'downpayment' => 'T', 'remaining_payment' => 'T'],
            ['code' => 'cash_on_arrival', 'downpayment' => 'F', 'remaining_payment' => 'T']];

        PaymentType::insert($payment_types);

        /*
        |--------------------------------------------------------------------------
        | Attribute categories
        |--------------------------------------------------------------------------
        */

        $categories = [
            ['en_name' => 'General', 'hr_name' => 'Općenito', 'de_name' => 'General', 'it_name' => 'General',
                'fr_name' => 'General', 'ru_name' => 'General', 'dk_name' => 'General', 'no_name' => 'General',
                'sv_name' => 'General']];

        AttributeCategory::insert($categories);

        /*
        |--------------------------------------------------------------------------
        | Attributes
        |--------------------------------------------------------------------------
        */

        $attributes = [
            ['category_id' => 1, 'featured' => 'T', 'icon' => 'beds.svg', 'en_name' => 'Beds', 'hr_name' => 'Broj kreveta',
                'de_name' => 'Beds', 'it_name' => 'Beds', 'fr_name' => 'Beds', 'ru_name' => 'Beds', 'dk_name' => 'Beds',
                'no_name' => 'Beds', 'sv_name' => 'Beds'],
            ['category_id' => 1, 'featured' => 'T', 'icon' => 'persons.svg', 'en_name' => 'Persons', 'hr_name' => 'Broj osoba',
                'de_name' => 'Persons', 'it_name' => 'Persons', 'fr_name' => 'Persons', 'ru_name' => 'Persons',
                'dk_name' => 'Persons', 'no_name' => 'Persons', 'sv_name' => 'Persons']];

        Attribute::insert($attributes);
    }
}
