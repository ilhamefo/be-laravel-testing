<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\JobTitleController;
use App\Http\Controllers\LineOfBusinessController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OccupationController;
use App\Http\Controllers\SourceOfFundController;
use App\Http\Controllers\UserDocumentController;
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

Route::get('/', function () {
    return response()->json([
        "Hello" => "World"
    ]);
});

Route::post('/test/{id}', [UserController::class, 'testmail']);
Route::post('/import', [UserController::class, 'import']);
Route::get('/logo.jpg', [UserController::class, 'getLogo']);
Route::get('/pdf', [UserDocumentController::class, 'generatePDF']);

Route::prefix("/user")->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/landing', [UserController::class, 'getLandingFlagging']);
    Route::put('/personal-data', [UserController::class, 'updatePersonalData']);
    Route::put('/home-address', [UserController::class, 'updateHomeAddress']);
    Route::put('/employment', [UserController::class, 'updateEmployment']);
    Route::get('/search-subdistrict/{keyword}', [UserController::class, 'searchSubdistrict']);
    Route::get('/subdistrict/{id}', [UserController::class, 'searchSubdistrictByID']);

    Route::post('/upload', [UserDocumentController::class, 'create']);
    Route::post('/generate-pdf', [UserDocumentController::class, 'generatePDF']);

    Route::prefix("/cart")->group(function () {
        Route::post('/checkout', [CartController::class, 'checkout']);
        Route::post('/', [CartController::class, 'create']);
        Route::get('/', [CartController::class, 'index']);
        Route::delete('/', [CartController::class, 'delete']);
        Route::put('/', [CartController::class, 'update']);
    });
});

Route::prefix("/")->middleware(['auth:sanctum'])->group(function () {
    Route::get('/gender', [GenderController::class, 'index']);
    Route::get('/occupations', [OccupationController::class, 'index']);
    Route::get('/line-of-business', [LineOfBusinessController::class, 'index']);
    Route::get('/job-titles', [JobTitleController::class, 'index']);
    Route::get('/source-of-fund', [SourceOfFundController::class, 'index']);
    Route::get('/incomes', [IncomeController::class, 'index']);
});