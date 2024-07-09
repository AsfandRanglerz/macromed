<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\AboutusController;
use App\Http\Controllers\Admin\BrandsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CertificationController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\ModalsController;
use App\Http\Controllers\Admin\ProductController;
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
    Route::controller(BrandsController::class)->group(function () {
        Route::get('/brands',  'brandsIndex')->name('brands.index');
        Route::post('/brands-create',  'brandsCreate')->name('brands.create');
        Route::get('/brandsData',  'brandsData')->name('brands.get');
        Route::get('/brands/{id}',  'showBrands')->name('brands.show');
        Route::post('/brandsUpdate/{id}',  'updateBrands')->name('brands.update');
        Route::get('/brands/delete/{id}',  'deleteBrands')->name('brands.delete');
        Route::post('/update-brands-status/{id}',  'updateBrandsStatus')->name('brandsBlock.update');
    });
    // ############## Comapny ############
    Route::controller(CompanyController::class)->group(function () {
        Route::get('/company',  'companyIndex')->name('company.index');
        Route::post('/company-create',  'companyCreate')->name('company.create');
        Route::get('/companyData',  'companyData')->name('company.get');
        Route::get('/company/{id}',  'showCompany')->name('company.show');
        Route::post('/companyUpdate/{id}',  'updateCompany')->name('company.update');
        Route::get('/company/delete/{id}',  'deleteCompany')->name('company.delete');
        Route::post('/update-company-status/{id}',  'updateCompanyStatus')->name('companyBlock.update');
    });

    // ############## Model ############
    Route::controller(ModalsController::class)->group(function () {
        Route::get('/models',  'modelsIndex')->name('models.index');
        Route::post('/models-create',  'modelsCreate')->name('models.create');
        Route::get('/modelsData',  'modelsData')->name('models.get');
        Route::get('/models/{id}',  'showModels')->name('models.show');
        Route::post('/modelsUpdate/{id}',  'updateModels')->name('models.update');
        Route::get('/models/delete/{id}',  'deleteModels')->name('models.delete');
        Route::post('/update-models-status/{id}',  'updateModelsStatus')->name('modelsBlock.update');
    });

    // ############## Certifications ############
    Route::controller(CertificationController::class)->group(function () {
        Route::get('/certification',  'certificationIndex')->name('certification.index');
        Route::post('/certification-create',  'certificationCreate')->name('certification.create');
        Route::get('/certificationData',  'certificationData')->name('certification.get');
        Route::get('/certification/{id}',  'showCertification')->name('certification.show');
        Route::post('/certificationUpdate/{id}',  'updateCertification')->name('certification.update');
        Route::get('/certification/delete/{id}',  'deleteCertification')->name('certification.delete');
        Route::post('/update-certification-status/{id}',  'updateCertificationStatus')->name('certificationBlock.update');
    });

    // ############## Product ############
    Route::controller(ProductController::class)->group(function () {
        Route::get('/productData',  'productData')->name('products.get');
        Route::get('/product',  'productIndex')->name('product.index');
        Route::get('/product-create',  'productCreateIndex')->name('product.create');
        Route::post('/product-store', 'productStore')->name('product.store');
        Route::get('/category-subCategories', 'getSubCategories')->name('category.subCategories');
        Route::get('/product-variants/{id}', 'productVariantIndex')->name('product_variant.index');
        Route::get('/products/{product}/variants', 'productVariantStore')->name('product-variant.store');
        Route::post('/update-products-status/{id}',  'updateProductStatus')->name('productsBlock.update');
    });
});
