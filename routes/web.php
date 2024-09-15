<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Bus;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('auth.login');
});
Auth::routes();

Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home/edit/{id}', [\App\Http\Controllers\HomeController::class, 'edit_btl'])->name('edit_btl');
Route::post('/home/edit/{btls}', [\App\Http\Controllers\HomeController::class, 'post_edit_btl'])->name('post_edit_btl');
Route::get('/home/delete/{btls}', [\App\Http\Controllers\HomeController::class, 'delete'])->name('delete');

// import csv file
Route::get('/import-csv', [\App\Http\Controllers\CSVImportController::class, 'index']);
// Route::get('/batch-progress/{batchId}', 'BatchProgressController@index');
Route::get('/import-progress/{batchId}', [\App\Http\Controllers\BatchProgressController::class, 'checkImportProgress']);
Route::post('/import-csv', [\App\Http\Controllers\CSVImportController::class, 'import'])->name('import.csv');
Route::get('/tables', [\App\Http\Controllers\CSVImportController::class, 'tables'])->name('tables');
Route::get('/table/{table}', [\App\Http\Controllers\CSVImportController::class, 'showTable'])->name('table.show');
Route::put('/table/{table}', [\App\Http\Controllers\CSVImportController::class, 'updateTableItem'])->name('table.update');
// Route::get('/table/{table}/edit/{id}', [\App\Http\Controllers\CSVImportController::class, 'editTableItem'])->name('table.edit');
Route::delete('/table/{table}/delete/{id}', [\App\Http\Controllers\CSVImportController::class, 'deleteTableItem'])->name('table.delete');
Route::get('/table/{table}/search', [\App\Http\Controllers\CSVImportController::class, 'search'])->name('table.search');
// Route::get('/home', [\App\Http\Controllers\CSVImportController::class, 'showNotifications'])->name('notifications.show');
// ->middleware('auth')
Route::post('/notifications/mark-as-read/{id}', [\App\Http\Controllers\CSVImportController::class, 'markAsRead']);

// table.view

// Github And Google Login Routes

Route::get('/socialite/{driver}',[\App\Http\Controllers\SocialLoginController::class, 'toProvider'])->where('driver','github|google');
Route::get('/auth/{driver}/login',[\App\Http\Controllers\SocialLoginController::class, 'handleCallback'])->where('driver','github|google');


