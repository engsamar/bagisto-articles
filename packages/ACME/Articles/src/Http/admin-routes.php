<?php

Route::group([
        'prefix'        => 'admin/articles',
        'middleware'    => ['web', 'admin']
    ], function () {
        Route::get('/', 'ACME\Articles\Http\Controllers\Admin\ArticlesController@index')->defaults('_config', [
            'view' => 'articles::admin.index',
        ])->name('admin.articles.index');

        Route::get('/create', 'ACME\Articles\Http\Controllers\Admin\ArticlesController@create')->defaults('_config', [
            'view' => 'articles::admin.create',
        ])->name('admin.articles.create');

        Route::post('/create', 'ACME\Articles\Http\Controllers\Admin\ArticlesController@store')->defaults('_config', [
            'view' => 'articles::admin.store',
        ])->name('admin.articles.store');


        Route::get('/edit/{id}', 'ACME\Articles\Http\Controllers\Admin\ArticlesController@edit')->defaults('_config', [
            'view' => 'articles::admin.edit',
        ])->name('admin.articles.edit');

        Route::get('/{id}', 'ACME\Articles\Http\Controllers\Admin\ArticlesController@show')->defaults('_config', [
            'view' => 'articles::admin.show',
        ])->name('admin.articles.show');

        Route::put('/edit/{id}', 'ACME\Articles\Http\Controllers\Admin\ArticlesController@update')->defaults('_config', [
            'view' => 'articles::admin.update',
        ])->name('admin.articles.update');

        Route::delete('/{id}', 'ACME\Articles\Http\Controllers\Admin\ArticlesController@destroy')->defaults('_config', [
            'view' => 'articles::admin.delete',
        ])->name('admin.articles.delete');
    });