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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('admin')->group(function () {

    Route::get('/home', 'Admin\HomeController@index');

    Route::get('/angkutan/hapus/{id}', 'Admin\AngkutanController@destroy');
    Route::resource('/angkutan', 'Admin\AngkutanController');
    Route::resource('/lokasi', 'Admin\LokasiController');

});

route::get('test', 'HomeController@test');

