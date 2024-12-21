<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SecurityController;
use App\Http\Controllers\Api\WhishlistController;
use App\Http\Controllers\Api\RolesPermissionController;
use App\Http\Controllers\Api\TaraxShippingServiceController;

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
Route::get('/cache_clear', function () {
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('optimize:clear');
    Artisan::call('route:clear');
    return 'Application cache cleared!';
});

######### Auth  ##########
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/userForgetPassword', [AuthController::class, 'userForgetPassword']);
Route::post('/verifyOtp', [AuthController::class, 'verifyOtp']);
Route::post('/resendOtp', [AuthController::class, 'resendOtp']);
Route::post('/resetPassword', [AuthController::class, 'resetPassword']);


Route::get('/checkCronJob', [OrderController::class, 'checkCronJob']);
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/updatePassword/{id}', [AuthController::class, 'updatePassword']);
    Route::match(['get', 'post'], '/user/{id}', [AuthController::class, 'userProfile']);
    Route::get('/getWhishList/{userId}', [WhishlistController::class, 'getWhishList']);
    Route::get('getOrderCount/{userId}', [OrderController::class, 'getOrderCount']);
    Route::get('getOrderDetail/{userId}', [OrderController::class, 'getOrderDetail']);
    Route::get('getOrderNotification/{userId}', [OrderController::class, 'getOrderNotification']);
    Route::post('/seenBy/{id}', [OrderController::class, 'seenBy']);
});

######### Home ##########

Route::get('/getDropDownData', [HomeController::class, 'getDropDownData']);
Route::post('/getProducts', [HomeController::class, 'getFilteredProducts']);
######### Product ##########
Route::get('/getProductdetails/{productId}', [ProductController::class, 'getProductdetail']);
Route::post('/productComparison', [ProductController::class, 'productComparison']);
Route::get('/getCategoryBrand', [ProductController::class, 'getCategoryBrand']);
######### Order ##########
Route::post('/placeOrder', [OrderController::class, 'order']);
Route::get('/selesAgent', [OrderController::class, 'selesAgent']);
Route::get('/orderDiscount', [OrderController::class, 'orderDiscount']);
Route::post('/couponCode', [OrderController::class, 'couponCode']);
######### Roles & Permissison##########
Route::post('/addPermission', [RolesPermissionController::class, 'addPermission']);
Route::post('/updatePermission/{id}', [RolesPermissionController::class, 'updatePermission']);
Route::post('/addRole', [RolesPermissionController::class, 'addRole']);
Route::post('/updateRole/{id}', [RolesPermissionController::class, 'updateRole']);

######### Security Controller ##########
Route::get('about-us', [SecurityController::class, 'getAboutUs']);
Route::get('privacy-policy', [SecurityController::class, 'getPrivacyPolicy']);
Route::get('terms-condations', [SecurityController::class, 'getTermsCondation']);
Route::get('/faqs', [SecurityController::class, 'faqs']);
Route::get('/blog', [SecurityController::class, 'blogs']);
Route::get('/career-sections', [SecurityController::class, 'careerSection']);
Route::post('/sendContactMessage', [SecurityController::class, 'sendContactMessage']);
// ############### TaraxShipping #########
Route::post('/tarax/pickup-address', [TaraxShippingServiceController::class, 'addAddress']);
Route::get('/tarax/cities', [TaraxShippingServiceController::class, 'getCities']);
