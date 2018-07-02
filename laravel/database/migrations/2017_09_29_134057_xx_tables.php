<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class xxTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function(Blueprint $table) {
            $table->increments('id');
            $table->string('code', 3)->unique();
            $table->softDeletes();
        });

        Schema::create('statuses', function(Blueprint $table) {
            $table->increments('id');
            $table->string('code', 20);
            $table->softDeletes();
        });

        Schema::create('payment_types', function(Blueprint $table) {
            $table->increments('id');
            $table->string('code', 20);
            $table->char('downpayment', 1);
            $table->char('remaining_payment', 1);
            $table->softDeletes();
        });

        Schema::create('countries', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->char('code', 2)->unique();
        });

        Schema::create('attribute_categories', function(Blueprint $table) {
            $table->increments('id');
            $table->string('en_name');
            $table->string('hr_name')->nullable();
            $table->string('de_name')->nullable();
            $table->string('it_name')->nullable();
            $table->string('fr_name')->nullable();
            $table->string('ru_name')->nullable();
            $table->string('dk_name')->nullable();
            $table->string('no_name')->nullable();
            $table->string('sv_name')->nullable();
            $table->softDeletes();
        });

        Schema::create('attributes', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->unsigned();
            $table->char('featured', 1)->default('F');
            $table->char('searchable', 1)->default('F');
            $table->char('is_input', 1)->default('F');
            $table->string('icon', 20)->nullable();
            $table->string('en_name');
            $table->string('hr_name')->nullable();
            $table->string('de_name')->nullable();
            $table->string('it_name')->nullable();
            $table->string('fr_name')->nullable();
            $table->string('ru_name')->nullable();
            $table->string('dk_name')->nullable();
            $table->string('no_name')->nullable();
            $table->string('sv_name')->nullable();
            $table->softDeletes();

            $table->foreign('category_id')->references('id')->on('attribute_categories');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_name');
            $table->string('email', 50)->unique();
            $table->string('password');
            $table->string('address', 100)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->char('country', 2)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('oib', 11)->nullable();
            $table->string('facebook_id', 120)->nullable();
            $table->string('google_id', 200)->nullable();
            $table->char('active', 1)->default('F');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_name');
            $table->char('country', 2);
            $table->string('phone', 50);
            $table->string('email', 50);

            $table->foreign('country')->references('code')->on('countries');
        });

        Schema::create('invoice_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('renter_id')->unsigned();
            $table->string('full_name');
            $table->string('address', 100);
            $table->string('city', 50);
            $table->string('zip_code', 20);

            $table->foreign('renter_id')->references('id')->on('users');
        });

        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('renter_id')->unsigned();
            $table->string('domestic_owner', 70);
            $table->string('domestic_bank', 100);
            $table->string('account_number', 100);
            $table->string('foreign_owner', 70);
            $table->string('foreign_bank', 100);
            $table->string('iban', 50);
            $table->string('swift', 50);

            $table->foreign('renter_id')->references('id')->on('users');
        });

        Schema::create('hosts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('renter_id')->unsigned();
            $table->string('full_name');
            $table->string('email', 50);
            $table->string('languages', 70);

            $table->foreign('renter_id')->references('id')->on('users');
        });

        Schema::create('villas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('renter_id')->unsigned();
            $table->string('name', 70);
            $table->string('url_name', 70);
            $table->string('address', 50);
            $table->string('city', 50);
            $table->string('zip_code', 20)->nullable();
            $table->integer('deposit');
            $table->integer('pets_price');
            $table->string('en_short_description', 100);
            $table->string('hr_short_description', 100)->nullable();
            $table->string('de_short_description', 100)->nullable();
            $table->string('it_short_description', 100)->nullable();
            $table->string('fr_short_description', 100)->nullable();
            $table->string('ru_short_description', 100)->nullable();
            $table->string('dk_short_description', 100)->nullable();
            $table->string('no_short_description', 100)->nullable();
            $table->string('sv_short_description', 100)->nullable();
            $table->text('en_description');
            $table->text('hr_description')->nullable();
            $table->text('de_description')->nullable();
            $table->text('it_description')->nullable();
            $table->text('fr_description')->nullable();
            $table->text('ru_description')->nullable();
            $table->text('dk_description')->nullable();
            $table->text('no_description')->nullable();
            $table->text('sv_description')->nullable();
            $table->char('cash_payment', 1);
            $table->char('featured', 1);
            $table->tinyInteger('start_month');
            $table->tinyInteger('end_month');
            $table->tinyInteger('season_start_month');
            $table->tinyInteger('season_end_month');
            $table->tinyInteger('discount_type');
            $table->tinyInteger('discount')->nullable();
            $table->string('latitude', 20);
            $table->string('longitude', 20);
            $table->char('active', 1);
            $table->softDeletes();

            $table->foreign('renter_id')->references('id')->on('users');

            $table->index('name');
        });

        Schema::create('villa_prices', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('villa_id')->unsigned();
            $table->date('start_day');
            $table->date('end_day');
            $table->smallInteger('price')->unsigned();

            $table->foreign('villa_id')->references('id')->on('villas');
        });

        Schema::create('villa_attributes', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('villa_id')->unsigned();
            $table->integer('attribute_id')->unsigned();
            $table->string('value', 100)->nullable();
            $table->string('en_value', 150)->nullable();
            $table->string('hr_value', 150)->nullable();
            $table->string('de_value', 150)->nullable();
            $table->string('it_value', 150)->nullable();
            $table->string('fr_value', 150)->nullable();
            $table->string('ru_value', 150)->nullable();
            $table->string('dk_value', 150)->nullable();
            $table->string('no_value', 150)->nullable();
            $table->string('sv_value', 150)->nullable();

            $table->foreign('villa_id')->references('id')->on('villas');
            $table->foreign('attribute_id')->references('id')->on('attributes');
        });

        Schema::create('villa_featured_images', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('villa_id')->unsigned();
            $table->string('image');
            $table->string('gallery_token', 40)->nullable();
        });

        Schema::create('villa_images', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('villa_id')->unsigned();
            $table->string('image');
            $table->string('gallery_token', 40)->nullable();
        });

        Schema::create('temp_bookings', function(Blueprint $table) {
            $table->increments('id');
            $table->string('booking_token', 40);
            $table->integer('villa_id')->unsigned();
            $table->integer('customer_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->tinyInteger('adults');
            $table->tinyInteger('children');
            $table->tinyInteger('infants');
            $table->integer('downpayment_id')->unsigned();
            $table->integer('remaining_payment_id')->unsigned();
            $table->timestamps();

            $table->foreign('villa_id')->references('id')->on('villas');
            $table->foreign('downpayment_id')->references('id')->on('payment_types');
            $table->foreign('remaining_payment_id')->references('id')->on('payment_types');
        });

        Schema::create('bookings', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('villa_id')->unsigned();
            $table->integer('user_id');
            $table->integer('customer_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->tinyInteger('adults');
            $table->tinyInteger('children');
            $table->tinyInteger('infants');
            $table->integer('downpayment_id')->unsigned();
            $table->integer('remaining_payment_id')->unsigned();
            $table->integer('status_id')->unsigned()->default(1);
            $table->timestamps();

            $table->foreign('villa_id')->references('id')->on('villas');
            $table->foreign('downpayment_id')->references('id')->on('payment_types');
            $table->foreign('remaining_payment_id')->references('id')->on('payment_types');
            $table->foreign('status_id')->references('id')->on('statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
