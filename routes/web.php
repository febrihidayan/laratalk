<?php

use Illuminate\Support\Facades\Route;
use Laratalk\Http\Controllers\LaratalkController;
use Laratalk\Http\Controllers\MessageController;
use Laratalk\Http\Controllers\UserController;

Route::group([
    'prefix' => config('laratalk.path'),
    'middleware' => config('laratalk.middleware'),
    'as' => 'laratalk.'
] ,function() {

    Route::prefix('api')->group( function() {

        Route::get('user/{query}', UserController::class)
            ->name('user');

        Route::resource('message', MessageController::class)
            ->only(['show', 'store', 'update']);

    });

    Route::get('/{view?}', LaratalkController::class)
        ->where('view', '(.*)')
        ->name('index');

});