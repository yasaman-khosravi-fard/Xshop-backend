<?php

use App\Http\Controllers\ProductController;
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
Route::get('/products' , [ProductController::class , 'index']);
Route::post('/add-product' , [ProductController::class , 'createProduct']);
Route::put('/update-product/{id}', [ProductController::class, 'updateProduct']);
Route::get('/show-product/{id}', [ProductController::class, 'showProduct']);
Route::delete('/delete-product/{id}', [ProductController::class, 'deleteProduct']);
Route::get('/get-types', [ProductController::class, 'getTypes']);
Route::get('/get-products-categorized/{type}' , [ProductController::class , 'getProductsCategorized']);


