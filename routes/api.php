<?php

use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RolesPermissionController;
use Illuminate\Http\Request;
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




Route::group(['middleware' => 'auth:api'], function () {
});
######### Product ##########
Route::get('/getProducts', [ProductController::class, 'getProducts']);
Route::post('/getProductByRange', [ProductController::class, 'getProductByRange']);
######### Brands ##########
Route::get('/getBrands', [BrandController::class, 'getBrand']);
Route::post('/getBrandFilter/{brandId}', [BrandController::class, 'getBrandFilter']);
######### Roles & Permissison##########
Route::post('/addPermission', [RolesPermissionController::class, 'addPermission']);
Route::post('/updatePermission/{id}', [RolesPermissionController::class, 'updatePermission']);
Route::post('/addRole', [RolesPermissionController::class, 'addRole']);
Route::post('/updateRole/{id}', [RolesPermissionController::class, 'updateRole']);
