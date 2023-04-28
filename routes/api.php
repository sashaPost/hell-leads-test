<?php

use App\Http\Controllers\LeadController;
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

// Route::post('/create-lead', [LeadController::class, 'createLeadSecond']);
Route::post('/create_lead', [LeadController::class, 'createLeadFirst']);

Route::post('/get-lead-status', [LeadController::class, 'getLeadStatus']);

Route::middleware('verify.token')->prefix('v2')->group(function () {
    Route::post('/create_lead', [LeadController::class, 'createLeadUpd']);
});