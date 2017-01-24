<?php

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

use App\Http\Controllers\Import\ImportController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/import/{user}', function ($user) {
    $import = new ImportController();
    $import->getData($user);
    return 'Hello World';
});

Route::get('user/{user}'), function ($user) {
    return 'Hello ' . $user;
}
