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

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix'=>'division'], function ()
{
    Route::get('/', 'DivisonController@index')->name('division');
    Route::post('/lists', 'DivisonController@divisions')->name('divisions');
    Route::post('/store', 'DivisonController@store')->name('division.store');
    Route::get('/edit/{id}', 'DivisonController@edit')->name('division.edit');
    Route::post('/update', 'DivisonController@update')->name('division.update');
    Route::get('/delete/{id}', 'DivisonController@delete')->name('division.delete');
});

Route::group(['prefix'=>'district'], function ()
{
    Route::get('/', 'DistrictController@index')->name('district');
    Route::post('/lists', 'DistrictController@districts')->name('districts');
    Route::post('/store', 'DistrictController@store')->name('district.store');
    Route::get('/edit/{id}', 'DistrictController@edit')->name('district.edit');
    Route::post('/update', 'DistrictController@update')->name('district.update');
    Route::get('/delete/{id}', 'DistrictController@delete')->name('district.delete');
});

Route::group(['prefix'=>'upazila'], function ()
{
    Route::get('/', 'UpazilaController@index')->name('upazila');
    Route::post('/lists', 'UpazilaController@upazilas')->name('upazilas');
    Route::post('/store', 'UpazilaController@store')->name('upazila.store');
    Route::get('/edit/{id}', 'UpazilaController@edit')->name('upazila.edit');
    Route::post('/update', 'UpazilaController@update')->name('upazila.update');
    Route::get('/delete/{id}', 'UpazilaController@delete')->name('upazila.delete');
});

Route::group(['prefix'=>'union'], function ()
{
    Route::get('/', 'UnionController@index')->name('union');
    Route::post('/lists', 'UnionController@unions')->name('unions');
    Route::post('/store', 'UnionController@store')->name('union.store');
    Route::get('/edit/{id}', 'UnionController@edit')->name('union.edit');
    Route::post('/update', 'UnionController@update')->name('union.update');
    Route::get('/delete/{id}', 'UnionController@delete')->name('union.delete');
});

Route::group(['prefix'=>'designation'], function ()
{
    Route::get('/', 'DesignationController@index')->name('designation');
    Route::post('/lists', 'DesignationController@designations')->name('designations');
    Route::post('/store', 'DesignationController@store')->name('designation.store');
    Route::get('/edit/{id}', 'DesignationController@edit')->name('designation.edit');
    Route::post('/update', 'DesignationController@update')->name('designation.update');
    Route::get('/delete/{id}', 'DesignationController@delete')->name('designation.delete');
});

Route::group(['prefix'=>'user'], function ()
{
    Route::get('/', 'UserController@index')->name('user');
    Route::post('/lists', 'UserController@users')->name('users');
    Route::post('/store', 'UserController@store')->name('user.store');
    Route::get('/edit/{id}', 'UserController@edit')->name('user.edit');
    Route::post('/update', 'UserController@update')->name('user.update');
    Route::get('/delete/{id}', 'UserController@delete')->name('user.delete');
});