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

 
Route::get('/', 'AuthController@showFormLogin')->name('login');
Route::get('login', 'AuthController@showFormLogin')->name('login');
Route::post('login', 'AuthController@login');
Route::get('register', 'AuthController@showFormRegister')->name('register');
Route::post('register', 'AuthController@register');
Route::get('/verif/{id}', 'AuthController@verif');
Route::get('/admin', 'AuthController@show_by_admin');
Route::get('/delete_user/{id}', 'AuthController@delete');
Route::get('/edit_user/{id}', 'AuthController@edit_user');
Route::post('/edit_user_process', 'AuthController@edit_user_process');

Route::get('list_app', 'AppregisController@list_app');
Route::get('/edit_app/{id}', 'AppregisController@edit_app');
Route::post('/edit_app_process', 'AppregisController@edit_app_process');
Route::get('/delete_app/{id}', 'AppregisController@delete');
Route::get('/add_app', 'appregisController@add_app');
Route::post('/add_app_process', 'appregisController@add_app_process');

Route::group(['middleware' => 'auth'], function () {
 
    Route::get('home', 'HomeController@index')->name('home');
    Route::get('logout', 'AuthController@logout')->name('logout');
 
});
