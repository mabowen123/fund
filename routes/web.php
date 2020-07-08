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

Route::get('/', 'PagesController@root')->name('root');
Route::get('/login', 'LoginController@show')->name('login');
Route::post('/login', 'LoginController@login')->name('login');
Route::get('/register', 'RegisterController@show')->name('register');
Route::post('/register', 'RegisterController@register')->name('register');
Route::post('/logout', 'LoginController@logout')->name('logout');
Route::get('/fund', 'FundController@fund')->name('fund');
Route::delete('/fund/{id}', 'FundController@delete')->where(['id' => '[0-9]+'])->name('fund.delete');
Route::post('/fund/{id}', 'FundController@edit')->where(['id' => '[0-9]+'])->name('fund.edit');
Route::post('/fund', 'FundController@create')->name('fund.create');
