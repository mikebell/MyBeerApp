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
use App\Http\Controllers\UserData\UserDataGenerator;

Route::get('/', function () {
    return "hello world";
});

Route::get('/import/{user}', function ($user) {
    $import = new ImportController();
    $import->getData($user);
    return 'Hello World';
});

Route::get('user/{user}', function ($user) {
    $user_data = new UserDataGenerator($user);

    $years_active = $user_data->getYearsActive();

    return View::make('user', [
        'user' => $user,
        'user_total_checkins' => $user_data->totalCheckins(),
        'years_active' => $years_active,
    ]);
});

Route::get('user/{user}/{year}', function ($user, $year) {
    $user_data = new UserDataGenerator($user);
    $checkins = $user_data->getCheckinsByYear($user, $year);

    echo "<pre>";
//    print_r($checkins);
    echo "</pre>";


    return View::make('user-year', [
        'year' => $checkins->year,
        'user' => $user,
        'data' => $checkins,
    ]);
});
