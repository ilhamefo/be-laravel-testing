<?php

use App\Http\Controllers\UserDocumentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function(){
    return response()->json([
        "Hello" => "World"
    ]);
});

Route::get('/hello-world', function(){
    return response()->json([
        "Hello" => "World"
    ]);
});

Route::get('/email', function(){
    return view('emails.orders.test', ['name' => 'Efo']);
});

Route::get('/php-version', function(){
    return phpinfo();
});

Route::get('/pdf', [UserDocumentController::class, 'web']);
