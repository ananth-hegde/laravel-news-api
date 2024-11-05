<?php

use App\Http\Controllers\api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\V1\NewsArticleController;
use App\Http\Controllers\api\V1\UserPreferenceController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [UserController::class, 'createUser']);
Route::post('/login', [UserController::class, 'loginUser']);
Route::post('/logout', [UserController::class, 'logoutUser'])->middleware('auth:sanctum');
Route::post('/password/reset', [UserController::class, 'resetPassword']);

Route::group([
    'prefix' => 'v1',
    'middleware' => ['auth:sanctum']
], function () {
    Route::apiResource('news-articles', NewsArticleController::class);
    Route::get('/preferences', [UserPreferenceController::class, 'show']);
    Route::put('/preferences', [UserPreferenceController::class, 'update']);
    Route::get('/personalizedFeed', [UserPreferenceController::class, 'personalizedFeed']);
});