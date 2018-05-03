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
    return redirect()->route('products.index');
});

Route::middleware('auth')->resource('products', 'ProductController');

Route::get('/export/products', 'HomeController@export')->name('products.export');
Route::post('/delete/products', 'HomeController@deleteSelected')->name('products.delete.selected');

Auth::routes();
