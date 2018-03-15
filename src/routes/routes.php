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
