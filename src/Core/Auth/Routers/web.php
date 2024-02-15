<?php
/**
 * Modestox Copyright (c) 2024.
 */

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'namespace' => 'Modestox\Ecommerce\Core\Auth\Controllers',
        'prefix'    => 'auth'
    ],
    function () {
        Route::get('/', 'IndexController@index')->name('auth.index');
    }
);

