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

Route::get('/', function () {
    return view('welcome');
});

Route::group([
    'prefix' => 'v2'
], function() {

    Route::get('/booking/{entity}', [
        'as' => 'booking.list', 'uses' => 'Booking\BookingController@getBookingList'
    ]);
    Route::post('/booking_place/{entity}', [
        'as' => 'booking.create', 'uses' => 'Booking\BookingController@createBooking'
    ]);
});