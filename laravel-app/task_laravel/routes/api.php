<?php

use App\Http\Controllers\api\ArticleController;
use App\Http\Controllers\Api\UserApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post("/register", [UserApiController::class,"register"]);
Route::post("/login", [UserApiController::class,"login"]);


Route::group(["prefix"=>"/user","middleware"=> ["auth:api"]], function () {
    Route::get("/profile", [UserApiController::class,"profile"]);
    Route::get("/refresh-token", [UserApiController::class,"refreshToken"]);
    Route::get("/log-out", [UserApiController::class,"logout"]);
});
Route::group(["prefix"=> "/admin/articles","middleware"=> ["auth:api"]], function (){
    Route::get("/", [ArticleController::class,"index"]);
    Route::post("/show", [ArticleController::class,"show"]);
    Route::post("/store", [ArticleController::class,"store"]);
    Route::put("/update", [ArticleController::class,"update"]);
    Route::delete("/destroy", [ArticleController::class,"destroy"]);
});