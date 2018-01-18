<?php

Route::middleware(['web', 'auth', 'core'])
    ->namespace('LaravelEnso\AddressesManager\app\Http\Controllers')
    ->group(function () {
        Route::prefix('addresses')->as('addresses.')->group(function () {
            Route::get('list', 'AddressesController@list')->name('list');
            Route::get('setDefault/{address}', 'AddressesController@setDefault')->name('setDefault');
            Route::post('/{type}/{id}', 'AddressesController@store')->name('store');
        });

        Route::resource('addresses', 'AddressesController',
            ['except' => ['show', 'store']]);

        Route::prefix('countries')->as('countries.')->group(function () {
            Route::get('getOptionList', 'CountriesSelectController@getOptionList')->name('getOptionList');
        });
    });
