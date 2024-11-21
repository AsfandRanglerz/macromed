<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\BrandsController;
use App\Http\Controllers\Admin\ModalsController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\AboutusController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SubAdminController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\PivateNoteController;
use App\Http\Controllers\Admin\SalesAgentController;
use App\Http\Controllers\Admin\NumberOfUseController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\MainMaterialController;
use App\Http\Controllers\Admin\CertificationController;
use App\Http\Controllers\Admin\SterilizationController;
use App\Http\Controllers\Admin\TermConditionController;
use App\Http\Controllers\Admin\WithDrawLimitController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\WithDrawRequestController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\BrandDiscountController;
use App\Http\Controllers\Admin\CategoryDiscountController;
use App\Http\Controllers\Admin\DiscountCodeController;
use App\Http\Controllers\Admin\ProductDiscountController;

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

Route::get('/cache_clear', function () {
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('optimize:clear');
    Artisan::call('route:clear');
    return 'Application cache cleared!';
});

Route::get('/', function () {
    return redirect('/admin-login');
});


Route::get('/admin-login', [AuthController::class, 'getLoginPage'])->name('login');
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
    Route::resource('about', AboutusController::class)->middleware('permission:About Us');
    Route::resource('policy', PolicyController::class)->middleware('permission:Privacy Policy');
    Route::resource('terms', TermConditionController::class)->middleware('permission:Terms & Conditions');
    // ############## Silder ############
    Route::controller(SliderController::class)->group(function () {
        Route::get('/silder-image',  'showSilderImage')->name('silder.image')->middleware('permission:Sliders');
        Route::post('/silders/upload-images',  'uploadSilderImages')->name('silders.upload-images')->middleware('permission:Sliders');
        Route::put('/silders/update-cover-status/{imageId}', 'updateSilderStatus')->name('silders.update-cover-status')->middleware('permission:Sliders');
        Route::get('/silder/delete/{id}', 'deleteSilderImage')->name('silderImage.delete')->middleware('permission:Sliders');
    });
    // ############## SubAdmin ############
    Route::controller(SubAdminController::class)->group(function () {
        Route::get('/subadmin',  'subadminIndex')->name('subadmin.index')->middleware('permission:Sales Agent');
        Route::post('/subadmin-create',  'subadminCreate')->name('subadmin.create')->middleware('permission:Sales Agent');
        Route::get('/subadminData',  'subadminData')->name('subadmin.get')->middleware('permission:Sales Agent');
        Route::get('/subadmin/{id}',  'showSubAdmin')->name('subadmin.show')->middleware('permission:Sales Agent');
        Route::post('/subadminUpdate/{id}',  'updateAdmin')->name('subadmin.update')->middleware('permission:Sales Agent');
        Route::get('/subadmin/delete/{id}',  'deleteSubadmin')->name('subadmin.delete')->middleware('permission:Sales Agent');
        Route::get('/get-permissions/{user}',  'fetchUserPermissions')->name('get.permissions')->middleware('permission:Sales Agent');
        Route::post('/update-permissions/{user}',  'updatePermissions')->name('update.user.permissions')->middleware('permission:Sales Agent');
        Route::post('/update-user-status/{id}',  'updateBlockStatus')->name('userBlock.update')->middleware('permission:Sales Agent');
        Route::get('/subadmin-profile/{id}',  'subAdminProfile')->name('subadmin.profile')->middleware('permission:Sales Agent');
    });
    // ############## Sales Agent ############
    Route::controller(SalesAgentController::class)->group(function () {
        Route::get('/salesagent',  'salesagentIndex')->name('salesagent.index')->middleware('permission:Sales Agent');
        Route::post('/salesagent-create',  'salesagentCreate')->name('salesagent.create')->middleware('permission:Sales Agent');
        Route::get('/salesagentData',  'salesagentData')->name('salesagent.get')->middleware('permission:Sales Agent');
        Route::get('/salesagent/{id}',  'showSalesAgent')->name('salesagent.show')->middleware('permission:Sales Agent');
        Route::post('/salesagentUpdate/{id}',  'updateSalesAgent')->name('salesagent.update')->middleware('permission:Sales Agent');
        Route::get('/salesagent/delete/{id}',  'deletesalesagent')->name('salesagent.delete')->middleware('permission:Sales Agent');
        Route::post('/update-salesagent-status/{id}',  'updateAgentBlockStatus')->name('agentBlock.update')->middleware('permission:Sales Agent');
        Route::get('/salesagent-profile/{id}',  'salesagentProfile')->name('salesagent.profile')->middleware('permission:Sales Agent');
        Route::get('/fetch-states', 'fetchStates')->name('fetchStates');
        Route::get('/fetch-cities', 'fetchCities')->name('fetchCities');
        // ######### Payment History###########
        Route::get('/salesagent/history/{id}',  'getPaymentHistory')->name('userPaymentHistory.index');
    });

    // ############## Customer ############
    Route::controller(CustomerController::class)->group(function () {
        Route::get('/customer',  'customerIndex')->name('customer.index')->middleware('permission:Customer');
        Route::post('/customer-create',  'customerCreate')->name('customer.create')->middleware('permission:Customer');
        Route::get('/customerData',  'customerData')->name('customer.get')->middleware('permission:Customer');
        Route::get('/customer/{id}',  'showCustomer')->name('customer.show')->middleware('permission:Customer');
        Route::post('/customerUpdate/{id}',  'updateCustomer')->name('customer.update')->middleware('permission:Customer');
        Route::get('/customer/delete/{id}',  'deleteCustomer')->name('customer.delete')->middleware('permission:Customer');
        Route::post('/update-customer-status/{id}',  'updateCustomerBlockStatus')->name('customerBlock.update')->middleware('permission:Customer');
        Route::get('/customer-profile/{id}',  'customerProfile')->name('customer.profile')->middleware('permission:Customer');
        Route::get('/fetchCustomer-states', 'fetchCutomerStates')->name('fetchCustomerStates')->middleware('permission:Customer');
        Route::get('/fetchCustomer-cities', 'fetchCustomerCities')->name('fetchCustomerCities')->middleware('permission:Customer');
    });
    // ############## Category ############
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/category', 'categoryIndex')->name('category.index')->middleware('permission:Category');
        Route::post('/category/autosave', 'autosave')->name('category.autosave');
        Route::post('/category-create/{id?}', 'createEntity')->name('category.create')->middleware('permission:Category');
        Route::get('/categoryData', 'categoryData')->name('category.get')->middleware('permission:Category');
        Route::get('/category/{id}', 'showCategory')->name('category.show')->middleware('permission:Category');
        Route::get('/category/delete/{id}', 'deleteEntity')->name('category.delete')->middleware('permission:Category');
        Route::post('/update-category-status/{id}',  'updateStatus')->name('categoryBlock.update')->middleware('permission:Category');
    });
    // ############## Category Discounts ############
    Route::controller(CategoryDiscountController::class)->group(function () {
        Route::get('/category/discounts/{id}', 'discountsIndex')->name('discounts.index')->middleware('permission:Category');
        Route::get('/category/discounts-data/{id}', 'getDiscounts')->name('discounts.get')->middleware('permission:Category');
        Route::post('/category/discounts-create/{id}', 'discountsCreate')->name('discounts.create')->middleware('permission:Category');
        Route::get('/category/discounts-show/{id}', 'discountsShow')->name('discounts.show')->middleware('permission:Category');
        Route::post('/category/discounts-update/{id}', 'discountsUpdate')->name('discounts.update')->middleware('permission:Category');
        Route::get('/category/discounts-delete/{id}', 'discountsDelete')->name('discounts.delete')->middleware('permission:Category');
        Route::post('/update-discount-status/{id}',  'updateDiscountStatus')->name('discountsBlock.update')->middleware('permission:Category');
    });

    // ############## Sub Category ############
    Route::controller(SubCategoryController::class)->group(function () {
        Route::get('/subCategory',  'subCategoryIndex')->name('subCategory.index')->middleware('permission:Sub Category');
        Route::post('/subCategory/autosave', 'autosave')->name('subCategory.autosave');
        Route::post('/subCategory-create/{id?}',  'createEntity')->name('subCategory.create')->middleware('permission:Sub Category');
        Route::get('/subCategoryData',  'subCategoryData')->name('subCategory.get')->middleware('permission:Sub Category');
        Route::get('/subCategory/{id}',  'showSubCategory')->name('subCategory.show')->middleware('permission:Sub Category');
        Route::get('/subCategory/delete/{id}',  'deleteEntity')->name('subCategory.delete')->middleware('permission:Sub Category');
        Route::post('/update-subcategory-status/{id}',  'updateStatus')->name('subcategoryBlock.update')->middleware('permission:Sub Category');
    });
    // ############## Brands ############
    Route::controller(BrandsController::class)->group(function () {
        Route::get('/brands',  'brandsIndex')->name('brands.index')->middleware('permission:Brands');
        Route::post('/subCategory/autosave', 'autosave')->name('brands.autosave');
        Route::post('/brands-create',  'createEntity')->name('brands.create')->middleware('permission:Brands');
        Route::get('/brandsData',  'brandsData')->name('brands.get')->middleware('permission:Brands');
        Route::get('/brands/{id}',  'showBrands')->name('brands.show')->middleware('permission:Brands');
        Route::post('/brandsUpdate/{id}',  'updateBrands')->name('brands.update')->middleware('permission:Brands');
        Route::get('/brands/delete/{id}',  'deleteEntity')->name('brands.delete')->middleware('permission:Brands');
        Route::post('/update-brands-status/{id}',  'updateStatus')->name('brandsBlock.update')->middleware('permission:Brands');
    });
    // ############## Brands Discounts ############
    Route::controller(BrandDiscountController::class)->group(function () {
        Route::get('/brands/discounts/{id}', 'discountsIndex')->name('brandDiscounts.index')->middleware('permission:Category');
        Route::get('/brands/discounts-data/{id}', 'getDiscounts')->name('brandDiscounts.get')->middleware('permission:Category');
        Route::post('/brands/discounts-create/{id}', 'discountsCreate')->name('brandDiscounts.create')->middleware('permission:Category');
        Route::get('/brands/discounts-show/{id}', 'discountsShow')->name('brandDiscounts.show')->middleware('permission:Category');
        Route::post('/brands/discounts-update/{id}', 'discountsUpdate')->name('brandDiscounts.update')->middleware('permission:Category');
        Route::get('/brands/discounts-delete/{id}', 'discountsDelete')->name('brandDiscounts.delete')->middleware('permission:Category');
        Route::post('/update-branddiscount-status/{id}',  'updateDiscountStatus')->name('brandDiscountsBlock.update')->middleware('permission:Category');
    });
    // ############## Comapny ############
    Route::controller(CompanyController::class)->group(function () {
        Route::get('/company',  'companyIndex')->name('company.index')->middleware('permission:Company');
        Route::post('/company-create',  'companyCreate')->name('company.create')->middleware('permission:Company');
        Route::get('/companyData',  'companyData')->name('company.get')->middleware('permission:Company');
        Route::get('/company/{id}',  'showCompany')->name('company.show')->middleware('permission:Company');
        Route::post('/companyUpdate/{id}',  'updateCompany')->name('company.update')->middleware('permission:Company');
        Route::get('/company/delete/{id}',  'deleteCompany')->name('company.delete')->middleware('permission:Company');
        Route::post('/update-company-status/{id}',  'updateCompanyStatus')->name('companyBlock.update')->middleware('permission:Company');
        Route::get('/fetchCompany-states', 'fetchCompanyStates')->name('fetchCompanyStates')->middleware('permission:Company');
        Route::get('/fetchCompany-cities', 'fetchCompanyCities')->name('fetchCompanyCities')->middleware('permission:Company');
    });

    // ############## Model ############
    Route::controller(ModalsController::class)->group(function () {
        Route::get('/models',  'modelsIndex')->name('models.index')->middleware('permission:Models');
        Route::post('/models-create',  'modelsCreate')->name('models.create')->middleware('permission:Models');
        Route::get('/modelsData',  'modelsData')->name('models.get')->middleware('permission:Models');
        Route::get('/models/{id}',  'showModels')->name('models.show')->middleware('permission:Models');
        Route::post('/modelsUpdate/{id}',  'updateModels')->name('models.update')->middleware('permission:Models');
        Route::get('/models/delete/{id}',  'deleteModels')->name('models.delete')->middleware('permission:Models');
        Route::post('/update-models-status/{id}',  'updateModelsStatus')->name('modelsBlock.update')->middleware('permission:Models');
    });

    // ############## Certifications ############
    Route::controller(CertificationController::class)->group(function () {
        Route::get('/certification',  'certificationIndex')->name('certification.index')->middleware('permission:Certification');
        Route::post('/certification-create',  'certificationCreate')->name('certification.create')->middleware('permission:Certification');
        Route::get('/certificationData',  'certificationData')->name('certification.get')->middleware('permission:Certification');
        Route::get('/certification/{id}',  'showCertification')->name('certification.show')->middleware('permission:Certification');
        Route::post('/certificationUpdate/{id}',  'updateCertification')->name('certification.update')->middleware('permission:Certification');
        Route::get('/certification/delete/{id}',  'deleteCertification')->name('certification.delete')->middleware('permission:Certification');
        Route::post('/update-certification-status/{id}',  'updateCertificationStatus')->name('certificationBlock.update')->middleware('permission:Certification');
    });

    // ############## Units ############
    Route::controller(UnitController::class)->group(function () {
        Route::get('/units',  'unitsIndex')->name('units.index')->middleware('permission:Units');
        Route::post('/units-create',  'unitsCreate')->name('units.create')->middleware('permission:Units');
        Route::get('/unitsData',  'unitsData')->name('units.get')->middleware('permission:Units');
        Route::get('/units/{id}',  'showunits')->name('units.show')->middleware('permission:Units');
        Route::post('/unitsUpdate/{id}',  'updateUnits')->name('units.update')->middleware('permission:Units');
        Route::get('/units/delete/{id}',  'deleteUnits')->name('units.delete')->middleware('permission:Units');
        Route::post('/update-units-status/{id}',  'updateUnitsStatus')->name('unitsBlock.update')->middleware('permission:Units');
    });

    // ############## Units ############
    Route::controller(SterilizationController::class)->group(function () {
        Route::get('/sterilization',  'sterilizationIndex')->name('sterilization.index')->middleware('permission:Sterilization');
        Route::post('/sterilization-create',  'sterilizationCreate')->name('sterilization.create')->middleware('permission:Sterilization');
        Route::get('/sterilizationData',  'sterilizationData')->name('sterilization.get')->middleware('permission:Sterilization');
        Route::get('/sterilization/{id}',  'showSterilization')->name('sterilization.show')->middleware('permission:Sterilization');
        Route::post('/sterilizationUpdate/{id}',  'updateSterilization')->name('sterilization.update')->middleware('permission:Sterilization');
        Route::get('/sterilization/delete/{id}',  'deleteSterilization')->name('sterilization.delete')->middleware('permission:Sterilization');
        Route::post('/update-sterilization-status/{id}',  'updateSterilizationStatus')->name('sterilizationBlock.update')->middleware('permission:Sterilization');
    });

    // ############## Number of Use ############
    Route::controller(NumberOfUseController::class)->group(function () {
        Route::get('/number-of-use',  'numberOfUseIndex')->name('numberOfUse.index')->middleware('permission:Number Of Use');
        Route::post('/numberOfUse-create',  'numberOfUseCreate')->name('numberOfUse.create')->middleware('permission:Number Of Use');
        Route::get('/numberOfUseData',  'numberOfUseData')->name('numberOfUse.get')->middleware('permission:Number Of Use');
        Route::get('/numberOfUse/{id}',  'showNumberOfUse')->name('numberOfUse.show')->middleware('permission:Number Of Use');
        Route::post('/numberOfUseUpdate/{id}',  'updateNumberOfUse')->name('numberOfUse.update')->middleware('permission:Number Of Use');
        Route::get('/numberOfUse/delete/{id}',  'deleteNumberOfUse')->name('numberOfUse.delete')->middleware('permission:Number Of Use');
        Route::post('/update-numberOfUse-status/{id}',  'updateNumberOfUseStatus')->name('numberOfUseBlock.update')->middleware('permission:Number Of Use');
    });

    // ############## Suppliers ############
    Route::controller(SupplierController::class)->group(function () {
        Route::get('/supplier',  'supplierIndex')->name('supplier.index')->middleware('permission:Supplier');
        Route::post('/supplier-create',  'supplierCreate')->name('supplier.create')->middleware('permission:Supplier');
        Route::get('/supplierData',  'supplierData')->name('supplier.get')->middleware('permission:Supplier');
        Route::get('/supplier/{id}',  'showSupplier')->name('supplier.show')->middleware('permission:Supplier');
        Route::post('/supplierUpdate/{id}',  'updateSupplier')->name('supplier.update')->middleware('permission:Supplier');
        Route::get('/supplier/delete/{id}',  'deleteSupplier')->name('supplier.delete')->middleware('permission:Supplier');
        Route::post('/update-supplier-status/{id}',  'updateSupplierStatus')->name('supplierBlock.update')->middleware('permission:Supplier');
        Route::get('/fetchSupplier-states', 'fetchSupplierStates')->name('fetchSupplierStates')->middleware('permission:Supplier');
        Route::get('/fetchSupplier-cities', 'fetchSupplierCities')->name('fetchSupplierCities')->middleware('permission:Supplier');
    });

    // ############## Main Material ############
    Route::controller(MainMaterialController::class)->group(function () {
        Route::get('/mainMaterial',  'mainMaterialIndex')->name('mainMaterial.index')->middleware('permission:Main Material');
        Route::post('/mainMaterial-create',  'mainMaterialCreate')->name('mainMaterial.create')->middleware('permission:Main Material');
        Route::get('/mainMaterialData',  'mainMaterialData')->name('mainMaterial.get')->middleware('permission:Main Material');
        Route::get('/mainMaterial/{id}',  'showMainMaterial')->name('mainMaterial.show')->middleware('permission:Main Material');
        Route::post('/mainMaterialUpdate/{id}',  'updateMainMaterial')->name('mainMaterial.update')->middleware('permission:Main Material');
        Route::get('/mainMaterial/delete/{id}',  'deleteMainMaterial')->name('mainMaterial.delete')->middleware('permission:Main Material');
        Route::post('/update-mainMaterial-status/{id}',  'updateMainMaterialStatus')->name('mainMaterialBlock.update')->middleware('permission:Main Material');
    });

    // ############## Product ############
    Route::controller(ProductController::class)->group(function () {
        Route::get('/productData',  'productData')->name('product.get')->middleware('permission:Products');
        Route::get('/product',  'productIndex')->name('product.index')->middleware('permission:Products');
        Route::get('/product-create',  'productCreateIndex')->name('product.create')->middleware('permission:Products');
        Route::post('/product-store', 'productStore')->name('product.store')->middleware('permission:Products');
        Route::get('/product-edit/{id}', 'productEdit')->name('product.show')->middleware('permission:Products');
        Route::post('/product-update/{id}', 'productUpdate')->name('product.update')->middleware('permission:Products');
        Route::get('/category-subCategories', 'getSubCategories')->name('category.subCategories')->middleware('permission:Products');
        Route::post('/update-products-status/{id}',  'updateProductStatus')->name('productsBlock.update')->middleware('permission:Products');
        Route::post('/update-productfeature-status/{id}',  'updateProductFeatureStatus')->name('productsFeature.update')->middleware('permission:Products');
        Route::get('/product/delete/{id}',  'deleteProduct')->name('product.delete')->middleware('permission:Products');
        Route::get('/supplier-name',  'getSuppliers')->name('getSuppliers')->middleware('permission:Products');
        // Products Images
        Route::get('/product/Image/{id}',  'show')->name('product.image')->middleware('permission:Products');
        Route::post('/products/{id}/upload-images',  'uploadImages')->name('products.upload-images')->middleware('permission:Products');
        Route::put('/products/{productId}/images/{imageId}/update-cover-status',  'updateCoverStatus')->name('products.images.update-cover-status')->middleware('permission:Products');
        Route::delete('/image/delete/{id}',  'deleteImage')->name('image.delete')->middleware('permission:Products');
    });

    // ############## Product Varaint ############
    Route::controller(ProductVariantController::class)->group(function () {
        Route::get('/product-variants-index/{id}', 'productVariantViewIndex')->name('product_variant_index.index')->middleware('permission:Products');
        Route::get('/product-variant/{id}', 'getProductVariants')->name('product.variants')->middleware('permission:Products');
        Route::get('/product-variants/{id}', 'productVariantIndex')->name('product_variant.index')->middleware('permission:Products');
        Route::post('/products/{product}/variants', 'productVariantStore')->name('product-variant.store')->middleware('permission:Products');
        Route::post('/variantUpdate/{id}',  'updateVariant')->name('variants.update')->middleware('permission:Products');
        Route::get('/variants/{id}',  'showVariants')->name('variants.show')->middleware('permission:Products');
        Route::get('/variants/delete/{id}',  'deleteProductVariant')->name('variant.delete')->middleware('permission:Products');
        Route::post('/update-variants-status/{id}',  'updateVariantsStatus')->name('variantsBlock.update')->middleware('permission:Products');
    });
    // ############## Products Discounts ############
    Route::controller(ProductDiscountController::class)->group(function () {
        Route::get('/product/discounts/{id}', 'discountsIndex')->name('productDiscounts.index')->middleware('permission:Category');
        Route::get('/product/discounts-data/{id}', 'getDiscounts')->name('productDiscounts.get')->middleware('permission:Category');
        Route::post('/product/discounts-create/{id}', 'discountsProductCreate')->name('productDiscounts.create')->middleware('permission:Category');
        Route::get('/product/discounts-show/{id}', 'discountsShow')->name('productDiscounts.show')->middleware('permission:Category');
        Route::post('/product/discounts-update/{id}', 'discountsUpdate')->name('productDiscounts.update')->middleware('permission:Category');
        Route::get('/product/discounts-delete/{id}', 'discountsDelete')->name('productDiscounts.delete')->middleware('permission:Category');
        Route::post('/update-productdiscount-status/{id}',  'updateDiscountStatus')->name('productDiscountsBlock.update')->middleware('permission:Category');
    });
    // ############## Currency ############
    Route::controller(CurrencyController::class)->group(function () {
        Route::get('/currency',  'currencyIndex')->name('currency.index')->middleware('permission:Currency');
        Route::post('/currency-create',  'currencyCreate')->name('currency.create')->middleware('permission:Currency');
        Route::get('/currencyData',  'currencyData')->name('currency.get')->middleware('permission:Currency');
        Route::get('/currency/{id}',  'showCurrency')->name('currency.show')->middleware('permission:Currency');
        Route::post('/currencyUpdate/{id}',  'updateCurrency')->name('currency.update')->middleware('permission:Currency');
        Route::get('/currency/delete/{id}',  'deleteCurrency')->name('currency.delete')->middleware('permission:Currency');
    });

    // ############## Private Notes ############
    Route::controller(PivateNoteController::class)->group(function () {
        Route::get('/privateNotes',  'privateNotesIndex')->name('privateNotes.index')->middleware('permission:Private Notes');
        Route::post('/privateNotes-create',  'privateNotesCreate')->name('privateNotes.create')->middleware('permission:Private Notes');
        Route::get('/privateNotesData',  'privateNotesData')->name('privateNotes.get')->middleware('permission:Private Notes');
        Route::get('/privateNotes/{id}',  'showPrivateNotes')->name('privateNotes.show')->middleware('permission:Private Notes');
        Route::post('/privateNotesUpdate/{id}',  'updatePrivateNotes')->name('privateNotes.update')->middleware('permission:Private Notes');
        Route::get('/privateNotes/delete/{id}',  'deletePrivateNotes')->name('privateNotes.delete')->middleware('permission:Private Notes');
    });

    // ############## Admin Notification ############
    Route::controller(AdminNotificationController::class)->group(function () {
        Route::get('/adminNotification',  'adminNotificationIndex')->name('adminNotification.index');
        Route::post('/adminNotification-create',  'adminNotificationCreate')->name('adminNotification.create');
        // Route::get('/privateNotesData',  'privateNotesData')->name('privateNotes.get');
        // Route::get('/privateNotes/{id}',  'showPrivateNotes')->name('privateNotes.show');
        // Route::post('/privateNotesUpdate/{id}',  'updatePrivateNotes')->name('privateNotes.update');
        // Route::get('/privateNotes/delete/{id}',  'deletePrivateNotes')->name('privateNotes.delete');
    });

    // ############## Orders ############
    Route::controller(OrderController::class)->group(function () {
        Route::get('/orderData',  'orderData')->name('order.get')->middleware('permission:Orders');
        Route::get('/order',  'orderIndex')->name('order.index')->middleware('permission:Orders');
        Route::get('/orders/{id}/status',  'getStatus')->name('orders.status')->middleware('permission:Orders');
        Route::post('/orders/{id}/update-status',  'updateStatus')->name('orders.update-status')->middleware('permission:Orders');
        Route::get('/order/delete/{id}',  'deleteOrder')->name('order.delete')->middleware('permission:Orders');
        Route::get('/order/counter',  'getOrderCount')->name('orders.count')->middleware('permission:Orders');
        Route::get('/order/details/{id}',  'getOrderDetails')->name('order.details')->middleware('permission:Orders');
        Route::post('/order/salesAganet/{id}',  'saveSalesAgent')->name('salesAgent.status')->middleware('permission:Orders');
        ### InVoice ####
        Route::get('/order/invoice/{id}',  'getInVoiceDetails')->name('invoice.index')->middleware('permission:Orders');
    });

    Route::controller(ReportsController::class)->group(function () {
        Route::get('/reports',  'reportsIndex')->name('reports.index')->middleware('permission:Reports');
        Route::get('reports/data/', 'getReportsData')->name('admin.reports.data')->middleware('permission:Reports');
        ### InVoice ####
        Route::get('/reports/invoice/{id}',  'getReportInVoiceDetails')->name('reportsinvoice.index')->middleware('permission:Reports');
    });

    // ############## Wallet WithDraw Limit ############
    Route::controller(WithDrawLimitController::class)->group(function () {
        Route::post('/withdrawLimitData',  'withdrawLimitData')->name('withdrawLimit.get')->middleware('permission:Wallet WithDrawal Limit');
        Route::get('/withdrawLimit',  'withdrawLimitIndex')->name('withdrawLimit.index')->middleware('permission:Wallet WithDrawal Limit');
        Route::post('/withdrawLimit-create',  'withDrawLimitCreate')->name('withDrawLimit.create')->middleware('permission:Wallet WithDrawal Limit');
        Route::get('/withdrawLimit/{id}',  'showwithDrawLimit')->name('withDrawLimit.show')->middleware('permission:Wallet WithDrawal Limit');
        Route::post('/withDrawLimitUpdate/{id}',  'updatewithDrawLimit')->name('withDrawLimit.update')->middleware('permission:Wallet WithDrawal Limit');
        Route::get('/withdrawLimit/delete/{id}',  'deletewithDrawLimit')->name('withDrawLimit.delete')->middleware('permission:Wallet WithDrawal Limit');
    });

    // ############## Wallet WithDraw Limit ############
    Route::controller(WithDrawRequestController::class)->group(function () {
        Route::post('/paymentRequestData',  'paymentRequestData')->name('paymentRequest.get')->middleware('permission:Withdrawal Requests');
        Route::get('/paymentRequest',  'paymentRequestIndex')->name('paymentRequest.index')->middleware('permission:Withdrawal Requests');
        Route::post('/paymentRequest-create',  'paymentRequestCreate')->name('paymentRequest.create')->middleware('permission:Withdrawal Requests');
        Route::get('/paymentRequest/{id}',  'showPaymentRequest')->name('paymentRequest.show')->middleware('permission:Withdrawal Requests');
        Route::post('/paymentRequestUpdate/{id}',  'updatePaymentRequest')->name('paymentRequest.update')->middleware('permission:Withdrawal Requests');
        Route::get('/paymentRequest/delete/{id}',  'deletePaymentRequest')->name('paymentRequest.delete')->middleware('permission:Withdrawal Requests');
        Route::get('/paymentRequestCounter',  'getPaymentRequestCount')->name('paymentRequest.count')->middleware('permission:Withdrawal Requests');
        // User Account Details
        Route::get('/paymentRequest/bankInfo/{userId}',  'getAccountDetails')->name('paymentAccount.index')->middleware('permission:Withdrawal Requests');
    });

    // ############## Discounts Code  ############
    Route::controller(DiscountCodeController::class)->group(function () {
        Route::get('/discountsCodeData',  'discountsCodeData')->name('discountsCode.get')->middleware('permission:Discount Codes');
        Route::get('/discountsCode',  'discountsCodeIndex')->name('discountsCode.index')->middleware('permission:Discount Codes');
        Route::post('/discountsCode-create',  'discountsCodeCreate')->name('discountsCode.create')->middleware('permission:Discount Codes');
        Route::get('/discountsCode/{id}',  'showDiscountsCode')->name('discountsCode.show')->middleware('permission:Discount Codes');
        Route::post('/discountsCodeUpdate/{id}',  'updateDiscountsCode')->name('discountsCode.update')->middleware('permission:Discount Codes');
        Route::get('/discountsCode/delete/{id}',  'deleteDiscountsCode')->name('discountsCode.delete')->middleware('permission:Discount Codes');
        Route::post('/update-discountcode-status/{id}',  'updateDiscountCodeStatus')->name('discountsCodedBlock.update')->middleware('permission:Discount Codes');
    });
    //  ######################### FAQ #########################
    Route::controller(FaqController::class)->group(function () {
        Route::get('/faq',  'faqIndex')->name('faq.index')->middleware('permission:FAQ`s');
        Route::post('/faq-create',  'faqCreate')->name('faq.create')->middleware('permission:FAQ`s');
        Route::get('/faqData',  'faqData')->name('faq.get')->middleware('permission:FAQ`s');
        Route::get('/faq/{id}',  'showFaq')->name('faq.show')->middleware('permission:FAQ`s');
        Route::post('/faqUpdate/{id}',  'updateFaq')->name('faq.update')->middleware('permission:FAQ`s');
        Route::get('/faq/delete/{id}',  'deleteFaq')->name('faq.delete')->middleware('permission:FAQ`s');
        Route::post('/faq/reorder',  'faqReorder')->name('faq.updateOrder')->middleware('permission:FAQ`s');
    });
});

require __DIR__ . '/agent.php';

// ############ React routes ############
