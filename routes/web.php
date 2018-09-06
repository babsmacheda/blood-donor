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

Route::get('/', [
    "as" => "index",
    "uses" => "WebController@index"
]);

Route::post("save-data", "webController@saveData");

Route::get("get-donors/{lat?}/{lng?}", "webController@getDonors");