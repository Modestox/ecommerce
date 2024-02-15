<?php
/**
 * Modestox Copyright (c) 2024.
 */

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'namespace' => 'Modestox\Ecommerce\Core\Auth\Controllers',
        'prefix'    => 'test'
    ],
    function () {
        Route::get('/', 'IndexController@index')->name('auth.index');
    }
);



//Route::get('/test', function () {
//    return view('Auth::index');
//});
