<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("governorates", "Api\MainController@governorates");
Route::get("cities", "Api\MainController@cities");
Route::get("blood-types", "Api\MainController@bloodTypes");

Route::post("register", "Api\AuthController@register");
Route::post("login", "Api\AuthController@login");
Route::post("reset-password", "Api\AuthController@resetPassword");
Route::post("new-password", "Api\AuthController@newPassword");


Route::group(['middleware'=>'auth:api'], function(){
    Route::get("categories", "Api\MainController@categories");
    Route::get("settings", "Api\MainController@settings");
    Route::get("show-notifications", "Api\MainController@showNotifications");
    Route::get("my-favourites", "Api\MainController@myFavourites");
    Route::get("posts", "Api\MainController@posts");
    Route::get("post", "Api\MainController@post");
    Route::get("notifications-count", "Api\MainController@notificationsCount");
    Route::get('donation-request','Api\MainController@donationRequest');
    Route::get("donation-requests", "Api\MainController@donationRequests");

    Route::post("donation-request/create", "Api\MainController@createDonationRequest");
    Route::post("register-token", "Api\AuthController@registerToken");
    Route::post("remove-token", "Api\AuthController@removeToken");
    Route::post("contacts", "Api\MainController@contacts");
    Route::post("profile", "Api\AuthController@profile");
    Route::post("toggle-favourites", "Api\MainController@toggleFavourites");
    Route::post("notification-settings", "Api\AuthController@notificationSettings");






});
