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
    return view('auth.login');
});

Auth::routes();

//homeController
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/stop/{id}','HomeController@akhiriPengaliran');
Route::post('/image-upload', 'HomeController@update')->name('imageUpload');

//apiController
Route::get('/getDataPPM', 'apiController@requestPPM');
Route::get('/getLastPPM', 'apiController@requestLastPPM');
Route::get('/getLastSerapan', 'apiController@requestLastSerapan');
Route::get('/getSerapanPPM', 'apiController@requestSerapanPPM');
Route::get('/getSerapanPPMbyId', 'apiController@requestSerapanPPMbyId');
Route::get('/getAllSerapanPPMbyId', 'apiController@requestAllSerapanPPMbyId');
Route::get('/getAllPengaliran', 'apiController@getAllPengaliran');

//pengaliranController
Route::resource('pengaliran', 'PengaliranController');
Route::post('/sunting','PengaliranController@update')->name('Sunting');
Route::get('/deletePengaliran/{id}', 'PengaliranController@destroy');