<?php

Route::group(['prefix' => 'api/nilai', 'middleware' => ['web']], function() {
    $controllers = (object) [
        'index'     => 'Bantenprov\Nilai\Http\Controllers\NilaiController@index',
        'create'    => 'Bantenprov\Nilai\Http\Controllers\NilaiController@create',
        'show'      => 'Bantenprov\Nilai\Http\Controllers\NilaiController@show',
        'store'     => 'Bantenprov\Nilai\Http\Controllers\NilaiController@store',
        'edit'      => 'Bantenprov\Nilai\Http\Controllers\NilaiController@edit',
        'update'    => 'Bantenprov\Nilai\Http\Controllers\NilaiController@update',
        'destroy'   => 'Bantenprov\Nilai\Http\Controllers\NilaiController@destroy',
    ];

    Route::get('/',             $controllers->index)->name('nilai.index');
    Route::get('/create',       $controllers->create)->name('nilai.create');
    Route::get('/{id}',         $controllers->show)->name('nilai.show');
    Route::post('/',            $controllers->store)->name('nilai.store');
    Route::get('/{id}/edit',    $controllers->edit)->name('nilai.edit');
    Route::put('/{id}',         $controllers->update)->name('nilai.update');
    Route::delete('/{id}',      $controllers->destroy)->name('nilai.destroy');
});

Route::group(['prefix' => 'api/akademik', 'middleware' => ['web']], function() {
    $controllers = (object) [
        'index'     => 'Bantenprov\Nilai\Http\Controllers\AkademikController@index',
        'create'    => 'Bantenprov\Nilai\Http\Controllers\AkademikController@create',
        'show'      => 'Bantenprov\Nilai\Http\Controllers\AkademikController@show',
        'store'     => 'Bantenprov\Nilai\Http\Controllers\AkademikController@store',
        'edit'      => 'Bantenprov\Nilai\Http\Controllers\AkademikController@edit',
        'update'    => 'Bantenprov\Nilai\Http\Controllers\AkademikController@update',
        'destroy'   => 'Bantenprov\Nilai\Http\Controllers\AkademikController@destroy',

    ];

    Route::get('/',             $controllers->index)->name('akademik.index');
    Route::get('/create',       $controllers->create)->name('akademik.create');
    Route::get('/{id}',         $controllers->show)->name('akademik.show');
    Route::post('/',            $controllers->store)->name('akademik.store');
    Route::get('/{id}/edit',    $controllers->edit)->name('akademik.edit');
    Route::put('/{id}',         $controllers->update)->name('akademik.update');
    Route::delete('/{id}',      $controllers->destroy)->name('akademik.destroy');
    
});
