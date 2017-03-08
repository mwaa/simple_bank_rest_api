<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('balance', 'BankController@balance');
Route::middleware('deposit-limit')->post('/deposit', 'BankController@deposit');
Route::middleware('withdraw-limit')->post('/withdraw', 'BankController@withdraw');