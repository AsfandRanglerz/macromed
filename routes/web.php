<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\AboutusController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubAdminController;
use App\Http\Controllers\Admin\TermConditionController;

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
/*
Admin routes
 * */

Route::get('/admin', [AuthController::class, 'getLoginPage']);
Route::post('admin/login', [AuthController::class, 'Login']);
Route::get('/admin-forgot-password', [AdminController::class, 'forgetPassword']);
Route::post('/admin-reset-password-link', [AdminController::class, 'adminResetPasswordLink']);
Route::get('/change_password/{id}', [AdminController::class, 'change_password']);
Route::post('/admin-reset-password', [AdminController::class, 'ResetPassword']);

Route::prefix('admin')->middleware('admin')->group(function () {
    // ############## Admin Controller ############
    Route::controller(AdminController::class)->group(function () {
        Route::get('dashboard',  'getdashboard');
        Route::get('profile',  'getProfile');
        Route::post('update-profile',  'update_profile');
        Route::get('logout',  'logout');
    });
    // ############## Resouces Controllers ############
    Route::resource('about', AboutusController::class);
    Route::resource('policy', PolicyController::class);
    Route::resource('terms', TermConditionController::class);
    Route::resource('faq', FaqController::class);
    // ############## SubAdmin ############
    Route::controller(SubAdminController::class)->group(function () {
        Route::get('/subadmin',  'subadminIndex')->name('subadmin.index');
        Route::post('/subadmin-create',  'subadminCreate')->name('subadmin.create');
        Route::get('/subadminData',  'subadminData')->name('subadmin.get');
        Route::get('/subadmin/{id}',  'showSubAdmin')->name('subadmin.show');
        Route::post('/subadminUpdate/{id}',  'updateAdmin')->name('subadmin.update');
        Route::get('/subadmin/delete/{id}',  'deleteSubadmin')->name('subadmin.delete');
        Route::get('/get-permissions/{user}',  'fetchUserPermissions')->name('get.permissions');
        Route::post('/update-permissions/{user}',  'updatePermissions')->name('update.user.permissions');
        Route::post('/update-user-status/{id}',  'updateBlockStatus')->name('userBlock.update');
        Route::get('/subadmin-profile/{id}',  'subAdminProfile')->name('subadmin.profile');
    });
    // ############## Category ############
    Route::controller(CategoryController::class)->group(function () {
        // Route::get('/category', 'categoryIndex')->name('category.index')->middleware('permission:category');
        // Route::post('/category-create', 'categoryCreate')->name('category.create')->middleware('permission:category');
        // Route::get('/categoryData', 'categoryData')->name('category.get')->middleware('permission:category');
        // Route::get('/category/{id}', 'showCategory')->name('category.show')->middleware('permission:category');
        // Route::post('/categoryUpdate/{id}', 'updateCategory')->name('category.update')->middleware('permission:category');
        // Route::get('/category/delete/{id}', 'deleteCategory')->name('category.delete')->middleware('permission:category');
    });
});
