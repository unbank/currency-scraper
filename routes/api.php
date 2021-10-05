<?php

use Illuminate\Support\Facades\Route;


Route::prefix('api')->group(function () {
    Route::group(['middleware' => ['api']], function () {

        Route::get('exchange/rates', '\Unbank\CurrencyScraper\Http\Controllers\CurrencyController@index')
                ->name('api.exchange.rates');

    });

});


?>
