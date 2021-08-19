<?php

use FebriHidayan\Laratalk\Config;
use FebriHidayan\Laratalk\Http\Controllers\Groups\ChangeAdminController;
use FebriHidayan\Laratalk\Http\Controllers\Groups\CreateController;
use FebriHidayan\Laratalk\Http\Controllers\Groups\ParticipantController;
use FebriHidayan\Laratalk\Http\Controllers\Groups\RemoveParticipantController;
use FebriHidayan\Laratalk\Http\Controllers\Groups\StoreParticipantController;
use FebriHidayan\Laratalk\Http\Controllers\LaratalkController;
use FebriHidayan\Laratalk\Http\Controllers\Messages\DestroyController;
use FebriHidayan\Laratalk\Http\Controllers\Messages\PullMessageController;
use FebriHidayan\Laratalk\Http\Controllers\Messages\StatusController;
use FebriHidayan\Laratalk\Http\Controllers\Messages\ShowController;
use FebriHidayan\Laratalk\Http\Controllers\Messages\StoreController;
use FebriHidayan\Laratalk\Http\Controllers\UploadAvatarController;
use FebriHidayan\Laratalk\Http\Controllers\Users\ChangeDarkmodeController;
use FebriHidayan\Laratalk\Http\Controllers\Users\ChangeLanguageController;
use FebriHidayan\Laratalk\Http\Controllers\Users\NewChatController;
use FebriHidayan\Laratalk\Http\Controllers\Users\UserChatController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('laratalk.path'),
    'middleware' => config('laratalk.middleware'),
    'as' => 'laratalk.'
] ,function() {

    Route::prefix('api')->group( function() {

        Route::get('user-chat', UserChatController::class);

        Route::post('user-language', ChangeLanguageController::class);

        Route::post('user-darkmode', ChangeDarkmodeController::class);

        Route::get('user-new-chat', NewChatController::class);

        Route::post('message-destroy', DestroyController::class);

        Route::post('message-pull', PullMessageController::class);
            
        Route::get('message-show/{id}', ShowController::class);

        Route::post('message-status', StatusController::class);

        Route::post('message-store', StoreController::class);

        Route::post('upload-avatar', UploadAvatarController::class);

        /**
         * If group is enabled then this routing should be used
         */
        if (Config::groupEnabled()) {

            Route::post('group-admin', ChangeAdminController::class);

            Route::post('group-create', CreateController::class);

            Route::post('group-participant', StoreParticipantController::class);

            Route::delete('group-participant', RemoveParticipantController::class);

            Route::get('user-group', ParticipantController::class);

        }

    });

    Route::get('/{view?}', LaratalkController::class)
        ->where('view', '(.*)')
        ->name('index');

});