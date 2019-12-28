<?php

// Админка.
Route::group([
    'prefix' => "admin/meta",
    'middleware' => ['web', 'role:admin|editor'],
    'namespace' => 'PortedCheese\SeoIntegration\Http\Controllers',
    'as' => 'admin.meta.',
], function () {
    // Мета относящиеся к статичным станицам.
    Route::get('/', 'MetaController@index')
        ->name('index');
    // Создание.
    Route::post('/static', 'MetaController@storeStatic')
        ->name('store-static');
    Route::post('/{model}/{id}', 'MetaController@storeModel')
        ->name('store-model');
    Route::put('/{model}/{id}/image', 'MetaController@getImageByModel')
        ->name('image-model');
    // Редактирование.
    Route::get('/{meta}', 'MetaController@edit')
        ->name('edit');
    // Обновление.
    Route::put('/{meta}', 'MetaController@update')
        ->name('update');
    // Удаление.
    Route::delete('/{meta}', 'MetaController@destroy')
        ->name('destroy');
});