<?php

use App\Http\Controllers\Api\CenteralController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->middleware('connect')->group(function () {
    Route::post('/fetch_expenditures', [CenteralController::class, 'expenditureDetails']);
    Route::post('/get_amount_summary_data', [CenteralController::class, 'getAmountSummaryData']);
    Route::get('/analytics', [CenteralController::class, 'accountAnalytics']);
    Route::get('/schemes', [CenteralController::class, 'getSchemes']);
});
