<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'WebController@index')->name('index');
Route::get('/payment/verify/{token}', 'BotController@paymentVerify')->name('payment.verify');
Route::get('/withdrawal/verify/{token}', 'BotController@withdrawalVerify')->name('withdrawal.verify');
