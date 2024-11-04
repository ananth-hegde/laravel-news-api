<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\V1\NewsArticleController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'v1'], function () {
    Route::get('/test', function () {
        return response()->json(['message' => 'Hello World!']);
    });
    Route::apiResource('news-articles', NewsArticleController::class);
});