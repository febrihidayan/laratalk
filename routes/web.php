<?php

use Illuminate\Support\Facades\Route;
use Laratalk\Http\Controllers\Groups\CreateController;
use Laratalk\Http\Controllers\LaratalkController;
use Laratalk\Http\Controllers\Messages\DestroyController;
use Laratalk\Http\Controllers\Messages\PullMessageController;
use Laratalk\Http\Controllers\Messages\StatusController;
use Laratalk\Http\Controllers\Messages\ShowController;
use Laratalk\Http\Controllers\Messages\StoreController;
use Laratalk\Http\Controllers\Users\ChangeDarkmodeController;
use Laratalk\Http\Controllers\Users\ChangeLanguageController;
use Laratalk\Http\Controllers\Users\NewChatController;
use Laratalk\Http\Controllers\Users\UserChatController;

Route::group([
    'prefix' => config('laratalk.path'),
    'middleware' => config('laratalk.middleware'),
    'as' => 'laratalk.'
] ,function() {

    Route::prefix('api')->group( function() {

        Route::post('group-create', CreateController::class);

        Route::get('user-chat', UserChatController::class);

        Route::post('user-language', ChangeLanguageController::class);

        Route::post('user-darkmode', ChangeDarkmodeController::class);

        Route::get('user-new-chat', NewChatController::class);

        Route::post('message-destroy', DestroyController::class);

        Route::post('message-pull', PullMessageController::class);
            
        Route::get('message-show/{id}', ShowController::class);

        Route::post('message-status', StatusController::class);

        Route::post('message-store', StoreController::class);

    });

    Route::get('/{view?}', LaratalkController::class)
        ->where('view', '(.*)')
        ->name('index');

});