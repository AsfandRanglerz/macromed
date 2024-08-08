<?php

use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CertificationController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RolesPermissionController;
use App\Models\Product;
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
// ################### Home Controllers ###################
Route::get('/getDropDownData',[HomeController::class,'getDropDownData']);
Route::post('/getProducts',[HomeController::class,'getFilteredProducts']);
Route::get('/getFeatureProduct', [HomeController::class, 'getFeaturedProduct']);
######### Product ##########
// Route::get('/getProducts', [ProductController::class, 'getProducts']);
// Route::get('/getFilteredProducts', [ProductController::class, 'getFilteredProducts']);
// Route::post('/getProductByRange', [ProductController::class, 'getProductByRange']);
Route::get('/getProductdetails/{productId}', [ProductController::class, 'getProductdetail']);
Route::get('/getProductVaraints/{productId}', [ProductController::class, 'getProductVaraint']);
Route::get('/getProductTab/{productId}', [ProductController::class, 'getProductTab']);
Route::get('/getRelatedProduct/{productId}', [ProductController::class, 'getRelatedProduct']);
######### Roles & Permissison##########
Route::post('/addPermission', [RolesPermissionController::class, 'addPermission']);
Route::post('/updatePermission/{id}', [RolesPermissionController::class, 'updatePermission']);
Route::post('/addRole', [RolesPermissionController::class, 'addRole']);
Route::post('/updateRole/{id}', [RolesPermissionController::class, 'updateRole']);