<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\Api\v1\AuthAsUserController;
use Modules\Users\Http\Controllers\Api\v1\AuthenticationApiController;
use Modules\Users\Http\Controllers\Api\v1\ProfileApiController;
use Modules\Users\Http\Controllers\Api\v1\ProfilePasswordApiController;
use Modules\Users\Http\Controllers\Api\v1\ResetPasswordApiController;
use Modules\Users\Http\Controllers\Api\v1\UserApiController;
use Modules\Users\Http\Controllers\Api\v1\UsersExportController;
use Modules\Users\Http\Controllers\Api\v1\UsersProfessionController;

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


Route::group(['prefix' => 'v1'], function () {
    Route::post("login", [AuthenticationApiController::class, "get"]);
  
    Route::post("password/reset", [ResetPasswordApiController::class, "store"]);
    Route::post("password/reset/check", [ResetPasswordApiController::class, "show"]);
    Route::post("password/reset/set", [ResetPasswordApiController::class, "update"]);

    Route::group(['middleware' => ['auth:api']], function () {
        Route::post("/login_as_user", [AuthAsUserController::class, "store"]);
        Route::post("logout", [AuthenticationApiController::class, "destroy"]);
    });

    Route::group(['prefix' => 'users','middleware' => ['auth:api']], function () {

        Route::get("/export", [UsersExportController::class, "index"]);
        Route::get("/professions", [UsersProfessionController::class, "index"]);

        Route::get("/", [UserApiController::class, "index"])
             ->middleware("permission:users_view");
        Route::post("/", [UserApiController::class, "store"])
             ->middleware("permission:users_create");
        Route::get("{user_id}", [UserApiController::class, "show"])
             ->middleware("permission:users_view");
        Route::patch("{user_id}", [UserApiController::class, "update"])
             ->middleware("permission:users_update");
        Route::delete("{user_id}", [UserApiController::class, "destroy"])
             ->middleware("permission:users_delete");
    });
    Route::group(['prefix' => 'profile','middleware' => ['auth:api']], function () {
        Route::get("/", [ProfileApiController::class, "show"]);
        Route::patch("/", [ProfileApiController::class, "update"]);
        Route::patch("/password", ProfilePasswordApiController::class);
    });
});
