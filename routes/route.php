<?php
/**
 * Created by PhpStorm.
 * User: professor
 * Date: 09.02.2019
 * Time: 23:11
 */

use App\Core\Route;

Route::get('/', 'MainController@index', '');
Route::get('/signin', 'MainController@signin', 'signin');
Route::get('/start', 'MainController@start', 'start');

Route::pathNotFound(function () {
    return view('404', ['code' => '404']);
});

Route::methodNotAllowed(function () {
    return view('404', ['code' => '403']);
});
