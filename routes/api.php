<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['api'])
    ->prefix('v1')
    ->namespace('Api\V1')
    ->name('api.v1.')
    ->group(function () {
        Route::apiResource('boards', 'BoardController');
        
        Route::put('boards/{board}/columns/reorder', 'ColumnController@reorder')
            ->name('boards.columns.reorder');
        Route::apiResource('boards.columns', 'ColumnController');

        Route::put('boards/{board}/columns/{column}/cards/reorder', 'CardController@reorder')
            ->name('boards.columns.cards.reorder');
        Route::apiResource('boards.cards', 'CardController');
    });