<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('books.index');
});

Route::resource('books', BookController::class)->except(['show']);
Route::resource('categories', CategoryController::class)->except(['show']);
Route::resource('users', UserController::class)->except(['show']);

Route::post('/books/{book}/toggle', [BookController::class, 'toggleStatus'])->name('books.toggle');
Route::post('/books/{book}/waiting-list', [BookController::class, 'joinWaitingList'])->name('books.waitingList');