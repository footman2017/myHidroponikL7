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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('pengaliran', 'PengaliranController');
Route::get('/getDataPPM', 'apiController@requestPPM');
Route::get('/getLastPPM', 'apiController@requestLastPPM');
Route::get('/getSerapanPPM', 'apiController@requestSerapanPPM');
Route::get('/stop/{id}','HomeController@akhiriPengaliran');