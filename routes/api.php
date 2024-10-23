<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RolesPermissionController;
use App\Http\Controllers\Api\WhishlistController;
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


######### Auth  ##########
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/userForgetPassword', [AuthController::class, 'userForgetPassword']);
Route::post('/verifyOtp', [AuthController::class, 'verifyOtp']);
Route::post('/resendOtp', [AuthController::class, 'resendOtp']);
Route::post('/resetPassword', [AuthController::class, 'resetPassword']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/updatePassword/{id}', [AuthController::class, 'updatePassword']);
    Route::match(['get', 'post'], '/user/{id}', [AuthController::class, 'userProfile']);
    Route::get('/getWhishList/{userId}', [WhishlistController::class, 'getWhishList']);
    Route::get('getOrderCount/{userId}', [OrderController::class, 'getOrderCount']);
    Route::get('getOrderDetail/{userId}', [OrderController::class, 'getOrderDetail']);
});
######### Home ##########
Route::get('/getDropDownData', [HomeController::class, 'getDropDownData']);
Route::post('/getProducts', [HomeController::class, 'getFilteredProducts'])->middleware('throttle:100,1');
######### Product ##########
Route::get('/getProductdetails/{productId}', [ProductController::class, 'getProductdetail']);
Route::post('/productComparison', [ProductController::class, 'productComparison']);
######### Order ##########
Route::post('/placeOrder', [OrderController::class, 'order']);
Route::get('/selesAgent', [OrderController::class, 'selesAgent']);
######### Roles & Permissison##########
Route::post('/addPermission', [RolesPermissionController::class, 'addPermission']);
Route::post('/updatePermission/{id}', [RolesPermissionController::class, 'updatePermission']);
Route::post('/addRole', [RolesPermissionController::class, 'addRole']);
Route::post('/updateRole/{id}', [RolesPermissionController::class, 'updateRole']);
