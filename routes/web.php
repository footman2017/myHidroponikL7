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

// Route::get('/', function () {
//     return view('auth.login');
// });
Route::get('/', 'HomeController@index')->name('home');
Auth::routes();

//homeController
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/stop/{id}','HomeController@akhiriPengaliran');
Route::post('/image-upload', 'HomeController@update')->name('imageUpload');
Route::get('/getLastPPM', 'HomeController@requestLastPPM');
Route::get('/getLastSerapan', 'HomeController@requestLastSerapan');

//pengaliranController
Route::resource('pengaliran', 'PengaliranController');
Route::get('/show/{id}', 'PengaliranController@show');
Route::post('/sunting','PengaliranController@update')->name('Sunting');
Route::get('/deletePengaliran/{id}', 'PengaliranController@destroy');
Route::get('/getAllPengaliran', 'PengaliranController@getAllPengaliran');

//DatasensorController
Route::get('/add/{ppm1}/{ppm2}','DatasensorController@insert');
Route::get('/getDeskripsiPengaliran','DatasensorController@getDeskripsi');
Route::get('/getMinPPM','DatasensorController@getMinPPM');
