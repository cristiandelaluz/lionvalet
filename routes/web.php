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

Route::get('/reservation', function () {
    $services = ExtraService::all();
    return view('reservation', compact('services'));
})->name('reservation');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('clients', 'ClientController');
Route::post('/clients/reserve', 'ClientController@reserve')->name('reserve');