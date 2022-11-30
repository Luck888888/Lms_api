<?php

use Illuminate\Support\Facades\Route;
use Modules\Curriculums\Http\Controllers\Api\v1\CurriculumApiController;
use Modules\Curriculums\Http\Controllers\Api\v1\CurriculumStudentsApiController;
use Modules\Curriculums\Http\Controllers\Api\v1\CurriculumStudentsContractsApiController;

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
    Route::group(['middleware' => ['auth:api']], function () {
        Route::get("curriculums", [CurriculumApiController::class, "index"])
             ->middleware("permission:curriculums_view");
        Route::get("curriculums/{curriculum_id}", [CurriculumApiController::class, "show"])
             ->middleware("permission:curriculums_view");
        Route::post("curriculums", [CurriculumApiController::class, "store"])
             ->middleware("permission:curriculums_create");
        Route::post("curriculums/{curriculum_id}", [CurriculumApiController::class, "update"])
             ->middleware("permission:curriculums_update");
        Route::delete("curriculums/{curriculum_id}", [CurriculumApiController::class, "destroy"])
             ->middleware("permission:curriculums_delete");


        Route::get("curriculums/{curriculum_id}/students", [CurriculumStudentsApiController::class, "index"])
             ->middleware("permission:curriculums_view");

        Route::post("curriculums/{curriculum_id}/students", [CurriculumStudentsApiController::class, "store"]);

        Route::delete("curriculums/{curriculum_id}/students", [CurriculumStudentsApiController::class, "destroy"]);

        Route::post("curriculums/{curriculum_id}/contracts", [CurriculumStudentsContractsApiController::class, "store"]);
        Route::get("curriculums/{curriculum_id}/contract", [CurriculumStudentsContractsApiController::class, "show"]);
    });
});
