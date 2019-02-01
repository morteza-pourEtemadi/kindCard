<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'WebController@index')->name('index');
Route::get('/payment/verify/{token}', 'BotController@paymentVerify')->name('payment.verify');
Route::get('/withdrawal/verify/{token}', 'BotController@withdrawalVerify')->name('withdrawal.verify');

Route::prefix('admin')->group(function () {
    Route::get('login', 'BotController@loginPage')->name('admin.login');
    Route::post('login', 'BotController@loginPage')->name('admin.login');

    Route::middleware('admin.area')->group(function () {
        Route::get('/cancelWithdrawals', 'BotController@cancelUnCompleteWithdrawals')->name('withdrawal.cancel');
    });

    Route::middleware('cron.area')->group(function () {
        Route::get('/cancelWithdrawals', 'BotController@cancelUnCompleteWithdrawals')->name('withdrawal.cancel');
    });
});
