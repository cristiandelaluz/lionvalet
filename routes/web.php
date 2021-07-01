<?php

use Illuminate\Support\Facades\Route;

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
use App\ExtraService;

Route::get('/', function () {
    $services = ExtraService::all();
    return view('website', compact('services'));
});

Route::get('/votre-reservation', function () {
    $services = ExtraService::all();
    return view('reservation', compact('services'));
})->name('reservation');

Auth::routes();

Route::resource('clients', 'ClientController');
Route::resource('reservations', 'ReservationController');