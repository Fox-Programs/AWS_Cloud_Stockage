<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\FileController;

Route::get('/', function () {
    return redirect()->route('drive.index');
});

Route::prefix('drive')->name('drive.')->group(function () {
    Route::get('/', [FileController::class, 'index'])->name('index');
    Route::post('/store', [FileController::class, 'store'])->name('store');
    Route::get('/download/{path}', [FileController::class, 'download'])->name('download')->where('path', '.*');
    Route::delete('/destroy/{path}', [FileController::class, 'destroy'])->name('destroy')->where('path', '.*');
});
