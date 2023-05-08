<?php

use App\Http\Controllers\GenderController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\JobTitleController;
use App\Http\Controllers\LineOfBusinessController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OccupationController;
use App\Http\Controllers\SourceOfFundController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', [UserController::class, 'authenticate']);

Route::get('/', function(){
    return response()->json([
        "Hello" => "World"
    ]);
});

Route::post('/test', [TestController::class, 'store']);

Route::prefix("/user")->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/landing', [UserController::class, 'getLandingFlagging']);
    Route::put('/personal-data', [UserController::class, 'updatePersonalData']);
    Route::put('/home-address', [UserController::class, 'updateHomeAddress']);
    Route::put('/employment', [UserController::class, 'updateEmployment']);
    Route::get('/search-subdistrict/{keyword}', [UserController::class, 'searchSubdistrict']);
    Route::get('/subdistrict/{id}', [UserController::class, 'searchSubdistrictByID']);

});

Route::prefix("/")->middleware(['auth:sanctum'])->group(function () {
    Route::get('/gender', [GenderController::class, 'index']);
    Route::get('/occupations', [OccupationController::class, 'index']);
    Route::get('/line-of-business', [LineOfBusinessController::class, 'index']);
    Route::get('/job-titles', [JobTitleController::class, 'index']);
    Route::get('/source-of-fund', [SourceOfFundController::class, 'index']);
    Route::get('/incomes', [IncomeController::class, 'index']);
});
