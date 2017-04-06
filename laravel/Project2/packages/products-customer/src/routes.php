<?php

Route::group(['namespace' => 'Galpa\ProductsCustomer\Controllers', 'prefix' => 'admin', 'middleware' => 'admin'], function () {
    Route::get('products/validate/', 'ProductsCustomerController@validateItems')->name('admin.products.validate');
    Route::post('products/validate/', 'ProductsCustomerController@validateItems')->name('admin.products.validate');

    Route::post('products/search/', 'ProductsCustomerController@search')->name('admin.products.search');
});

