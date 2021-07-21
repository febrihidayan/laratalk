<?php

use Illuminate\Support\Facades\Route;
use Laratalk\Http\Controllers\LaratalkController;
use Laratalk\Http\Controllers\Messages\StatusController;
use Laratalk\Http\Controllers\Messages\ShowController;
use Laratalk\Http\Controllers\Messages\StoreController;
use Laratalk\Http\Controllers\Users\NewChatController;
use Laratalk\Http\Controllers\Users\UserChatController;

Route::group([
    'prefix' => config('laratalk.path'),
    'middleware' => config('laratalk.middleware'),
    'as' => 'laratalk.'
] ,function() {

    Route::prefix('api')->group( function() {

        Route::get('user-chat', UserChatController::class);

        Route::get('user-new-chat', NewChatController::class);
            
        Route::get('message-show/{id}', ShowController::class);

        Route::post('message-status', StatusController::class);

        Route::post('message-store', StoreController::class);

    });

    Route::get('/{view?}', LaratalkController::class)
        ->where('view', '(.*)')
        ->name('index');

});