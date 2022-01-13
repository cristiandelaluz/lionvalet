<?php

use Illuminate\Support\Facades\Route;
use App\ExtraService;

Route::get('/', function () {
    $services = ExtraService::all();
    return view('website', compact('services'));
});

Route::get('/reservations/payment/{id}', 'ReservationController@payment');
Route::post('/purchase', 'ReservationController@purchase');
Route::post('/refund', 'ReservationController@refund');

Route::get('/votre-reservation', function () {
    $services = ExtraService::all();
    return view('reservation', compact('services'));
})->name('reservation');

Auth::routes();

Route::resource('clients', 'ClientController');
Route::resource('reservations', 'ReservationController');