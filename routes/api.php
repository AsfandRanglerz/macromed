<?php

use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CertificationController;
use App\Http\Controllers\Api\CompanyController;
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
Route::get('/getFeatureProduct', [ProductController::class, 'getFeaturedProduct']);
Route::get('/getProductdetails/{productId}', [ProductController::class, 'getProductdetail']);
Route::get('/getProductVaraints/{productId}', [ProductController::class, 'getProductVaraint']);
######### Brands ##########
Route::get('/getBrands', [BrandController::class, 'getBrand']);
Route::get('/getBrandFilter/{brandId}', [BrandController::class, 'getBrandFilter']);
######### Certification ##########
Route::get('/getCertifications', [CertificationController::class, 'getCertification']);
Route::get('/getCertificationFilter/{certificationId}', [CertificationController::class, 'getCertificationFilter']);
######### Category ##########
Route::get('/getCategorys', [CategoryController::class, 'getCategory']);
Route::get('/getCategoryFilter/{categoryId}', [CategoryController::class, 'getCategoryFilter']);
######### Company ##########
Route::get('/getCompany', [CompanyController::class, 'getCompany']);
Route::post('/getCompanyFilter', [CompanyController::class, 'getCompanyFilter']);
######### Roles & Permissison##########
Route::post('/addPermission', [RolesPermissionController::class, 'addPermission']);
Route::post('/updatePermission/{id}', [RolesPermissionController::class, 'updatePermission']);
Route::post('/addRole', [RolesPermissionController::class, 'addRole']);
Route::post('/updateRole/{id}', [RolesPermissionController::class, 'updateRole']);
