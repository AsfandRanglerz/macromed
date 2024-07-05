<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\AboutusController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SalesAgentController;
use App\Http\Controllers\Admin\SubAdminController;
use App\Http\Controllers\Admin\SubCategoryController;
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
        Route::get('/subadmin',  'subadminIndex')->name('subadmin.index')->middleware('permission:Sub Admins');
        Route::post('/subadmin-create',  'subadminCreate')->name('subadmin.create')->middleware('permission:Sub Admins');
        Route::get('/subadminData',  'subadminData')->name('subadmin.get')->middleware('permission:Sub Admins');
        Route::get('/subadmin/{id}',  'showSubAdmin')->name('subadmin.show')->middleware('permission:Sub Admins');
        Route::post('/subadminUpdate/{id}',  'updateAdmin')->name('subadmin.update')->middleware('permission:Sub Admins');
        Route::get('/subadmin/delete/{id}',  'deleteSubadmin')->name('subadmin.delete')->middleware('permission:Sub Admins');
        Route::get('/get-permissions/{user}',  'fetchUserPermissions')->name('get.permissions')->middleware('permission:Sub Admins');
        Route::post('/update-permissions/{user}',  'updatePermissions')->name('update.user.permissions')->middleware('permission:Sub Admins');
        Route::post('/update-user-status/{id}',  'updateBlockStatus')->name('userBlock.update')->middleware('permission:Sub Admins');
        Route::get('/subadmin-profile/{id}',  'subAdminProfile')->name('subadmin.profile')->middleware('permission:Sub Admins');
    });
    // ############## Sales Agent ############
    Route::controller(SalesAgentController::class)->group(function () {
        Route::get('/salesagent',  'salesagentIndex')->name('salesagent.index')->middleware('permission:Sales Managers');
        Route::post('/salesagent-create',  'salesagentCreate')->name('salesagent.create')->middleware('permission:Sales Managers');
        Route::get('/salesagentData',  'salesagentData')->name('salesagent.get')->middleware('permission:Sales Managers');
        Route::get('/salesagent/{id}',  'showSalesAgent')->name('salesagent.show')->middleware('permission:Sales Managers');
        Route::post('/salesagentUpdate/{id}',  'updateSalesAgent')->name('salesagent.update')->middleware('permission:Sales Managers');
        Route::get('/salesagent/delete/{id}',  'deletesalesagent')->name('salesagent.delete')->middleware('permission:Sales Managers');
        Route::post('/update-salesagent-status/{id}',  'updateAgentBlockStatus')->name('agentBlock.update')->middleware('permission:Sales Managers');
        Route::get('/salesagent-profile/{id}',  'salesagentProfile')->name('salesagent.profile')->middleware('permission:Sales Managers');
    });
    // ############## Category ############
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/category', 'categoryIndex')->name('category.index');
        Route::post('/category-create', 'categoryCreate')->name('category.create');
        Route::get('/categoryData', 'categoryData')->name('category.get');
        Route::get('/category/{id}', 'showCategory')->name('category.show');
        Route::post('/categoryUpdate/{id}', 'updateCategory')->name('category.update');
        Route::get('/category/delete/{id}', 'deleteCategory')->name('category.delete');
        Route::post('/update-category-status/{id}',  'updateCategoryStatus')->name('categoryBlock.update');
    });

    // ############## Sub Category ############
    Route::controller(SubCategoryController::class)->group(function () {
        Route::get('/subCategory',  'subCategoryIndex')->name('subCategory.index');
        Route::post('/subCategory-create',  'subCategoryCreate')->name('subCategory.create');
        Route::get('/subCategoryData',  'subCategoryData')->name('subCategory.get');
        Route::get('/subCategory/{id}',  'showSubCategory')->name('subCategory.show');
        Route::post('/subCategoryUpdate/{id}',  'updateSubCategory')->name('subCategory.update');
        Route::get('/subCategory/delete/{id}',  'deleteSubCategory')->name('subCategory.delete');
        Route::post('/update-subcategory-status/{id}',  'updateSubCategoryStatus')->name('subcategoryBlock.update');
    });
     // ############## Brands ############
     Route::controller(SubCategoryController::class)->group(function () {
        Route::get('/subCategory',  'subCategoryIndex')->name('subCategory.index');
        Route::post('/subCategory-create',  'subCategoryCreate')->name('subCategory.create');
        Route::get('/subCategoryData',  'subCategoryData')->name('subCategory.get');
        Route::get('/subCategory/{id}',  'showSubCategory')->name('subCategory.show');
        Route::post('/subCategoryUpdate/{id}',  'updateSubCategory')->name('subCategory.update');
        Route::get('/subCategory/delete/{id}',  'deleteSubCategory')->name('subCategory.delete');
        Route::post('/update-subcategory-status/{id}',  'updateSubCategoryStatus')->name('subcategoryBlock.update');
    });
});
