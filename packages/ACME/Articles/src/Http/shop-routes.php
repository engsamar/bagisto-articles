<?php

Route::group([
        'prefix'     => 'articles',
        'middleware' => ['web', 'theme', 'locale', 'currency']
    ], function () {

        Route::get('/', 'ACME\Articles\Http\Controllers\Shop\ArticlesController@index')->defaults('_config', [
            'view' => 'articles::shop.index',
        ])->name('shop.articles.index');

});