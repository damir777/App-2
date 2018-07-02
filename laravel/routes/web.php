<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\User;
use App\Notifications\confirmAccount;

Route::get('damir', function() {

    $user = User::find(1);

    $user->notify(new confirmAccount('dsajdklsajdklsa', 'http://pero-zdero.com'));

    return 'OK';
});

/*
|--------------------------------------------------------------------------
| Authentication routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'auth'], function() {

    Route::group(['middleware' => 'login'], function () {

        //ajax route
        Route::post('login/user', ['uses' => 'AuthController@loginUser']);

        //ajax route
        Route::post('register/user', ['uses' => 'AuthController@registerUser']);

        Route::get('confirm/{token}', ['uses' => 'AuthController@confirmAccount']);

        Route::get('social/redirect/{provider}', ['uses' => 'SocialLoginController@redirectToProvider']);

        Route::get('social/{provider}', ['uses' => 'SocialLoginController@providerCallback']);
    });

    Route::get('logout/user', ['as' => 'LogoutUser', 'uses' => 'AuthController@logoutUser']);
});

/*
|--------------------------------------------------------------------------
| Site routes
|--------------------------------------------------------------------------
*/

Route::get('/', ['as' => 'HomePage', 'uses' => 'SiteController@getHomePage']);

Route::get('villa/{url_name}', ['as' => 'GetVillaDetails', 'uses' => 'SiteController@getVillaDetails']);

Route::get('booking/{url_name}', ['as' => 'GetBookingPage', 'uses' => 'SiteController@getBookingPage']);

Route::get('locale/{code}', ['as' => 'ChangeLocale', 'uses' => 'SiteController@changeLocale']);
Route::get('currency/{code}', ['as' => 'ChangeCurrency', 'uses' => 'SiteController@changeCurrency']);

Route::get('about', ['as' => 'AboutUs', 'uses' => 'SiteController@aboutUs']);
Route::get('terms-and-conditions', ['as' => 'TermsAndConditions', 'uses' => 'SiteController@termsAndConditions']);
Route::get('newsletter', ['uses' => 'SiteController@newsletter']);

//ajax route
Route::post('searchVillas', ['uses' => 'SiteController@searchVillas']);

//ajax route
Route::post('calendar', ['uses' => 'BookingController@getCalendar']);

Route::get('404', ['as' => 'NotFoundPage', 'uses' => 'SiteController@showNotFoundPage']);

/*
|--------------------------------------------------------------------------
| Booking routes
|--------------------------------------------------------------------------
*/

//ajax route
Route::post('getBookingInfo', ['uses' => 'BookingController@getBookingInfo']);

//ajax route
Route::post('insertTempBooking', ['uses' => 'BookingController@insertTempBooking']);

//ajax route
Route::post('insertBookingCustomer', ['uses' => 'BookingController@insertBookingCustomer']);

//ajax route
Route::post('userConfirmBooking', ['uses' => 'BookingController@userConfirmBooking']);

//ajax route
Route::get('checkBookingPersonalData', ['uses' => 'UserController@checkBookingPersonalData']);

//ajax route
Route::post('updateBookingPersonalData', ['uses' => 'UserController@updateBookingPersonalData']);

Route::get('payment', ['as' => 'PaymentPage', 'uses' => 'BookingController@showPaymentPage']);

Route::get('WSPayResponse', ['as' => 'WSPayResponse', 'uses' => 'BookingController@handleWSPayResponse']);

Route::group(['middleware' => 'authentication'], function() {

    /*
    |--------------------------------------------------------------------------
    | SuperAdmin routes
    |--------------------------------------------------------------------------
    */

    Route::group(['middleware' => 'superAdmin'], function() {

        Route::group(['prefix' => 'categories'], function() {

            Route::get('list', ['as' => 'GetCategories', 'uses' => 'SuperAdminController@getCategories']);

            Route::get('add', ['as' => 'AddCategory', 'uses' => 'SuperAdminController@addCategory']);

            Route::post('insert', ['as' => 'InsertCategory', 'uses' => 'SuperAdminController@insertCategory']);

            Route::get('edit/{id}', ['as' => 'EditCategory', 'uses' => 'SuperAdminController@editCategory']);

            Route::post('update', ['as' => 'UpdateCategory', 'uses' => 'SuperAdminController@updateCategory']);

            Route::get('delete/{id}', ['as' => 'DeleteCategory', 'uses' => 'SuperAdminController@deleteCategory']);
        });

        Route::group(['prefix' => 'attributes'], function() {

            Route::get('list', ['as' => 'GetAttributes', 'uses' => 'SuperAdminController@getAttributes']);

            Route::get('add', ['as' => 'AddAttribute', 'uses' => 'SuperAdminController@addAttribute']);

            Route::post('insert', ['as' => 'InsertAttribute', 'uses' => 'SuperAdminController@insertAttribute']);

            Route::get('edit/{id}', ['as' => 'EditAttribute', 'uses' => 'SuperAdminController@editAttribute']);

            Route::post('update', ['as' => 'UpdateAttribute', 'uses' => 'SuperAdminController@updateAttribute']);

            Route::get('delete/{id}', ['as' => 'DeleteAttribute', 'uses' => 'SuperAdminController@deleteAttribute']);
        });

        Route::group(['prefix' => 'renters'], function() {

            Route::get('list', ['as' => 'GetRenters', 'uses' => 'SuperAdminController@getRenters']);

            Route::get('add', ['as' => 'AddRenter', 'uses' => 'SuperAdminController@addRenter']);

            Route::post('insert', ['as' => 'InsertRenter', 'uses' => 'SuperAdminController@insertRenter']);

            Route::get('edit/{id}', ['as' => 'EditRenter', 'uses' => 'SuperAdminController@editRenter']);

            Route::post('update', ['as' => 'UpdateRenter', 'uses' => 'SuperAdminController@updateRenter']);

            Route::get('delete/{id}', ['as' => 'DeleteRenter', 'uses' => 'SuperAdminController@deleteRenter']);
        });

        Route::group(['prefix' => 'users'], function() {

            Route::get('list', ['as' => 'GetUsers', 'uses' => 'SuperAdminController@getUsers']);
        });

        Route::group(['prefix' => 'villas'], function() {

            Route::get('list', ['as' => 'GetVillas', 'uses' => 'SuperAdminController@getVillas']);

            Route::get('add', ['as' => 'AddVilla', 'uses' => 'SuperAdminController@addVilla']);

            //ajax route
            Route::post('insert', ['uses' => 'SuperAdminController@insertVilla']);

            Route::get('edit/{id}', ['as' => 'EditVilla', 'uses' => 'SuperAdminController@editVilla']);

            //ajax route
            Route::post('update', ['uses' => 'SuperAdminController@updateVilla']);

            Route::get('delete/{id}', ['as' => 'DeleteVilla', 'uses' => 'SuperAdminController@deleteVilla']);

            //ajax route
            Route::post('uploadImage', ['uses' => 'ImageController@uploadImage']);

            //ajax route
            Route::post('deleteImage', ['uses' => 'ImageController@deleteImage']);
        });

        Route::group(['prefix' => 'bookings'], function() {

            Route::get('calendar', ['as' => 'SuperAdminGetCalendar', 'uses' => 'SuperAdminController@getCalendar']);

            Route::get('list', ['as' => 'SuperAdminGetBookings', 'uses' => 'SuperAdminController@getBookings']);
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Renter routes
    |--------------------------------------------------------------------------
    */

    Route::group(['prefix' => 'renter', 'middleware' => 'renter'], function() {

        Route::group(['prefix' => 'villas'], function() {

            Route::get('list', ['as' => 'GetRenterVillas', 'uses' => 'RenterController@getVillas']);

            Route::get('preview/{id}', ['as' => 'PreviewVilla', 'uses' => 'RenterController@previewVilla']);
        });

        Route::group(['prefix' => 'bookings'], function() {

            Route::get('calendar', ['as' => 'GetRenterCalendar', 'uses' => 'RenterController@getCalendar']);

            Route::get('new', ['as' => 'GetRenterNewBookings', 'uses' => 'RenterController@getNewBookings']);

            Route::get('list', ['as' => 'GetRenterBookings', 'uses' => 'RenterController@getBookings']);

            Route::get('confirm/{id}', ['as' => 'ConfirmBooking', 'uses' => 'BookingController@confirmBooking']);

            //ajax route
            Route::post('reject', ['uses' => 'BookingController@rejectBooking']);
        });
    });

    /*
    |--------------------------------------------------------------------------
    | User routes
    |--------------------------------------------------------------------------
    */

    Route::group(['prefix' => 'user', 'middleware' => 'user'], function() {

        Route::get('bookings/list', ['as' => 'GetUserBookings', 'uses' => 'UserController@getBookings']);

        Route::get('profile', ['as' => 'GetUserProfile', 'uses' => 'UserController@getUserProfile']);

        Route::post('updateProfile', ['as' => 'UpdateUserProfile', 'uses' => 'UserController@updateUserProfile']);
    });

    /*
    |--------------------------------------------------------------------------
    | Admin booking routes
    |--------------------------------------------------------------------------
    */

    Route::group(['middleware' => 'adminBooking'], function() {

        //ajax route
        Route::post('insertBooking', ['uses' => 'BookingController@insertBooking']);
    });
});

Auth::routes();