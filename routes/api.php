<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::post('upload', 'ImageController@store');
Route::get('images', 'ImageController@index');
Route::get('download', function () {
    //PDF file is stored under project/public/download/info.pdf
    $url = url('storage/upload/GeuEyHE4M78J2opzrNMaxs2fnsWF10n1ZD1ZtOPW.jpeg');
    return response($url);
});
Route::post('/resetPassword', 'ResetPasswordController@sendEmail');
Route::get('/check', 'ResetPasswordController@createToken');
Route::post('/changePassword', 'ResetPasswordController@process');
