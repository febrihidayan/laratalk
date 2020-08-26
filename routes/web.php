<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Laratalk\Http\Controllers',
    'prefix' => config('laratalk.path'),
    'middleware' => config('laratalk.middleware'),
    'as' => 'laratalk.'
] ,function() {

    Route::prefix('api')->group( function() {

        Route::get('user/{query}', 'UserController')->name('user');

        Route::resource('message', 'MessageController', [
            'only' => ['show', 'store', 'update']
        ]);

    });

    Route::get('/{view?}', 'LaratalkController')
        ->where('view', '(.*)')
        ->name('index');

});