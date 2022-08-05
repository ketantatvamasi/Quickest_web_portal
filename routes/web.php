<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\{UserController, RoleController};
use App\Http\Controllers\Auth\{LoginController, AdminLoginController};
use App\Http\Controllers\{PermissionController,
    GoogleSocialiteController,
    DashboardController,
    TestController,
//    UnitController,
    CountryController,
    StateController,
    CityController,
    ProductController,
    TestimonialController,
    AdminController,
    ItemController,
    CustomerController,
    EstimateController,
    EventController,
    ProposalController,
    PdfController};
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\admin\CompanyCategoryController;
use App\Http\Middleware\CheckStatus;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;


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
Route::get('health', [HealthCheckResultsController::class,'__invoke']);
Route::get('firebase-phone-authentication', [\App\Http\Controllers\FirebaseController::class, 'index']);
Route::view('/test1', 'test');
Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::get('auth/google', [GoogleSocialiteController::class, 'redirectToGoogle']);
    Route::get('callback/google', [GoogleSocialiteController::class, 'handleCallback']);
});
Route::get('pdf', [PdfController::class, 'index']);
Route::get('quotes/preview/{id}/{flg?}', [EstimateController::class, 'preview'])->name('quotes.preview');
Auth::routes(['verify' => true]);

Route::get('quotes/generate-link/{id}', [EstimateController::class, 'generateLink'])->name('quotes.generate-link');
Route::post('quotes/update-status', [EstimateController::class, 'updateStatus'])->name('quotes.update-status');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile');
    Route::post('get-states-by-country', [ProfileController::class, 'getState'])->name('bind-state');
    Route::post('get-cities-by-state', [ProfileController::class, 'getCity'])->name('bind-city');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'verified', CheckStatus::class])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        //Estimate
        Route::get('quotes', [EstimateController::class, 'index'])->name('quotes.index');
        Route::get('quotes/new', [EstimateController::class, 'create'])->name('quotes.new');
        Route::get('quotes/edit/{id}/{flg?}', [EstimateController::class, 'edit'])->name('quotes.edit');
        Route::post('quotes/new-store', [EstimateController::class, 'store'])->name('quotes.new-store');
        Route::post('quotes/update', [EstimateController::class, 'update'])->name('quotes.update');
        Route::post('delete-quotes', [EstimateController::class, 'destroy'])->name('quotes.delete');
        Route::get('quotes/show/{id}', [EstimateController::class, 'show'])->name('quotes.show');
        Route::post('edit-quotes-status', [EstimateController::class, 'editStatus'])->name('quotes.edit-status');
        Route::get('quotes/pdf', [EstimateController::class, 'createPDF'])->name('quotes.createPDF');
        Route::get('quotes-get-estimate-number', [EstimateController::class, 'getEstimateNumber'])->name('quotes.getEstimateNumber');
        Route::post('quotes/update-estimate-number', [EstimateController::class, 'updateEstimateNumber'])->name('quotes.updateEstimateNumber');
        Route::get('get-recent-activities', [EstimateController::class, 'getRecentActivities'])->name('event.getRecentActivities');
        Route::get('estimate-pdf-info', [EstimateController::class, 'estimatePdfInfo'])->name('quotes.estimatePdfInfo');
        Route::post('estimate-duplicate', [EstimateController::class, 'estimateDuplicate'])->name('quotes.estimateDuplicate');
        Route::get('test', [EstimateController::class, 'test'])->name('test');
        Route::post('test-post', [EstimateController::class, 'testPost'])->name('test-post');

        Route::get('template/proposal', [ProposalController::class, 'index'])->name('proposal.index');
        Route::get('template/proposal/create', [ProposalController::class, 'create'])->name('proposal.create');
        Route::get('template/proposal/new-create', [ProposalController::class, 'newCreate'])->name('proposal.new-create');
        Route::get('template/proposal/pdf-preview', [ProposalController::class, 'pdfPreview'])->name('proposal.pdf-preview');
        Route::post('template/proposal/store', [ProposalController::class, 'store'])->name('proposal.store');

//        Route::controller(EventController::class)->group(function () {
//            Route::post('store-event', 'store')->name('event.store');
//            Route::get('event', 'index')->name('event.index');
//            Route::get('calendar-event', 'calendarEvent')->name('event.calendar');
//            Route::post('calendar-event/create', 'create')->name('event.create');
//            Route::post('calendar-event/update', 'update')->name('event.update');
//            Route::post('calendar-event/delete',  'destroy')->name('event.delete');
//        });


        Route::post('store-event', [EventController::class, 'store'])->name('event.store');
        Route::get('event', [EventController::class, 'index'])->name('event.index');
        Route::get('calendar-event', [EventController::class, 'calendarEvent'])->name('event.calendar');
        Route::post('calendar-event/create', [EventController::class, 'create'])->name('event.create');
        Route::post('calendar-event/update', [EventController::class, 'update'])->name('event.update');
        Route::post('calendar-event/delete', [EventController::class, 'destroy'])->name('event.delete');
        Route::get('donut-chart', [DashboardController::class, 'donutChart'])->name('event.donutChart');
        Route::get('bar-chart', [DashboardController::class, 'barChart'])->name('event.barChart');
        Route::get('sales-performance-chart', [DashboardController::class, 'salesPerformanceChart'])->name('chart.salesPerformanceChart');



        Route::get('get-date-wise-follow-up-list', [EventController::class, 'getDateWiseFollowUpList'])->name('event.get-date-wise-follow-up-list');
        Route::get('follow-up-history', [EventController::class, 'followUpHistoryIndex'])->name('event.follow-up-history');


        //Unit
//        Route::middleware(['permissionCheck:unit_view'])->group(function () {
//            Route::get('unit', [UnitController::class, 'index'])->name('unit.index');
//            Route::get('unit-show', [UnitController::class, 'show'])->name('unit.show');
//            Route::post('store-unit', [UnitController::class, 'store'])->name('unit.store');
//            Route::post('edit-unit-status', [UnitController::class, 'editStatus'])->name('unit.edit-status');
//            Route::post('delete-unit', [UnitController::class, 'destroy'])->name('unit.delete');
//        });

        Route::controller(UnitController::class)->name('unit.')->group(function () { //middleware(['permissionCheck:unit_view'])->
            Route::get('unit', 'index')->name('index');
            Route::get('unit-show', 'show')->name('show');
            Route::post('store-unit', 'store')->name('store');
            Route::post('edit-unit-status', 'editStatus')->name('edit-status');
            Route::post('delete-unit', 'destroy')->name('delete');

        });

        //GST
        Route::controller(GstController::class)->name('gst.')->group(function () { //middleware(['permissionCheck:unit_view'])->
            Route::get('gst', 'index')->name('index');
//            Route::get('unit-show', 'show')->name('show');
            Route::post('store-gst', 'store')->name('store');
//            Route::post('edit-unit-status', 'editStatus')->name('edit-status');
//            Route::post('delete-unit', 'destroy')->name('delete');

        });

        //Customer
//        Route::middleware(['permissionCheck:customer_view'])->group(function () {
        Route::get('customer', [CustomerController::class, 'index'])->name('customer.index');
        Route::get('customer-show', [CustomerController::class, 'show'])->name('customer.show');
        Route::post('store-customer', [CustomerController::class, 'store'])->name('customer.store');
        Route::post('edit-customer-status', [CustomerController::class, 'editStatus'])->name('customer.edit-status');
        Route::post('delete-customer', [CustomerController::class, 'destroy'])->name('customer.delete');
        Route::post('customer-autocomplete', [CustomerController::class, 'customerAutocomplete'])->name('customerAutocomplete');
//        });

        //Product
//        Route::middleware(['permissionCheck:product_view'])->group(function () {
        Route::get('product', [ProductController::class, 'index'])->name('product.index');
        Route::get('product-show', [ProductController::class, 'show'])->name('product.show');
        Route::post('store-product', [ProductController::class, 'store'])->name('product.store');
        Route::post('edit-product-status', [ProductController::class, 'editStatus'])->name('product.edit-status');
        Route::post('delete-product', [ProductController::class, 'destroy'])->name('product.delete');
        Route::post('product-autocomplete', [ProductController::class, 'productAutocomplete'])->name('productAutocomplete');
//        });

        //Item
//        Route::middleware(['permissionCheck:item_view'])->group(function () {
        Route::get('item', [ItemController::class, 'index'])->name('item.index');
        Route::get('item-show', [ItemController::class, 'show'])->name('item.show');
        Route::post('store-item', [ItemController::class, 'store'])->name('item.store');
        Route::post('edit-item-status', [ItemController::class, 'editStatus'])->name('item.edit-status');
        Route::post('delete-item', [ItemController::class, 'destroy'])->name('item.delete');
        Route::post('item-autocomplete', [ItemController::class, 'itemAutocomplete'])->name('itemAutocomplete');
//        });

        //Country
//        Route::middleware(['permissionCheck:country_view'])->group(function () {
        Route::get('country', [CountryController::class, 'index'])->name('country.index');
        Route::get('country-show', [CountryController::class, 'show'])->name('country.show');
        Route::post('store-country', [CountryController::class, 'store'])->name('country.store');
        Route::post('edit-country-status', [CountryController::class, 'editStatus'])->name('country.edit-status');
        Route::post('delete-country', [CountryController::class, 'destroy'])->name('country.delete');
//        });

        //State
//        Route::middleware(['permissionCheck:state_view'])->group(function () {
        Route::get('state', [StateController::class, 'index'])->name('state.index');
        Route::get('state-show', [StateController::class, 'show'])->name('state.show');
        Route::post('store-state', [StateController::class, 'store'])->name('state.store');
        Route::post('edit-state-status', [StateController::class, 'editStatus'])->name('state.edit-status');
        Route::post('delete-state', [StateController::class, 'destroy'])->name('state.delete');
//        });

        //City
//        Route::middleware(['permissionCheck:city_view'])->group(function () {
        Route::get('city', [CityController::class, 'index'])->name('city.index');
        Route::get('city-show', [CityController::class, 'show'])->name('city.show');
        Route::post('store-city', [CityController::class, 'store'])->name('city.store');
        Route::post('edit-city-status', [CityController::class, 'editStatus'])->name('city.edit-status');
        Route::post('delete-city', [CityController::class, 'destroy'])->name('city.delete');
//        });

        //User
//       Route::middleware(['permissionCheck:user-list'])->group(function () {
        Route::get('user', [UserController::class, 'index'])->name('user.index');
        Route::get('/user-create', [UserController::class, 'create'])->name('user-create');
        Route::get('/user-edit/{id}', [UserController::class, 'edit'])->name('user-edit');
        Route::get('/user-get-permission', [UserController::class, 'getPermission'])->name('roles.show');
        Route::post('/user-create', [UserController::class, 'store'])->name('user-create');
        Route::post('/user-update', [UserController::class, 'update'])->name('user-update');
        Route::post('delete-user', [UserController::class, 'destroy'])->name('user.delete');
        Route::post('edit-user-status', [UserController::class, 'editStatus'])->name('user.edit-status');
//       });

        // Role management
//        Route::middleware(['permissionCheck:roles_view'])->group(function () {
//            Route::get('/role', [RoleController::class, 'index'])->name('role.index');
//            Route::get('/role-create', [RoleController::class, 'create'])->name('role-create');
//            Route::get('/role-edit/{id}', [RoleController::class, 'edit'])->name('role-edit');
//            Route::get('/role-list', [RoleController::class, 'getRoleList'])->name('role-list');
//            Route::get('/role-delete/{id}', [RoleController::class, 'delete'])->name('role-delete');
//            Route::post('/role-create', [RoleController::class, 'store'])->name('role-create');
//            Route::post('/role-update', [RoleController::class, 'update'])->name('role-update');

        Route::get('/role', [RoleController::class, 'index'])->name('role.index');
        Route::get('/role-create', [RoleController::class, 'create'])->name('role-create');
        Route::get('/role-edit/{id}', [RoleController::class, 'edit'])->name('role-edit');
        Route::get('/role-list', [RoleController::class, 'getRoleList'])->name('role-list');
        Route::post('delete-role', [RoleController::class, 'destroy'])->name('role.delete');
        Route::post('/role-create', [RoleController::class, 'store'])->name('role-create');
        Route::post('/role-update', [RoleController::class, 'update'])->name('role-update');

//        });


        //Testimonials
//        Route::middleware(['permissionCheck:product_view'])->group(function () {
        Route::get('testimonial', [TestimonialController::class, 'index'])->name('testimonial.index');
        Route::get('testimonial-show', [TestimonialController::class, 'show'])->name('testimonial.show');
        Route::post('store-testimonial', [TestimonialController::class, 'store'])->name('testimonial.store');
        Route::post('edit-testimonial-status', [TestimonialController::class, 'editStatus'])->name('testimonial.edit-status');
        Route::post('delete-testimonial', [TestimonialController::class, 'destroy'])->name('testimonial.delete');
        Route::post('testimonial-autocomplete', [TestimonialController::class, 'testimonialAutocomplete'])->name('testimonialAutocomplete');
//        });
    });

});


//Admin Route
Route::prefix('admin')->name('admin.')->group(function () {
    Route::group(['middleware' => 'admin.guest'], function () {
        Route::get('/', [AdminController::class, 'index'])->name('login');
        Route::get('/login', [AdminController::class, 'index'])->name('login');
        Route::post('/login', [AdminController::class, 'login'])->name('auth');

    });

    Route::group(['middleware' => 'admin.auth', 'name' => 'admin.'], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

        //User(Client)
        Route::get('/client', [\App\Http\Controllers\admin\ClientController::class, 'index'])->name('client.index');
        Route::post('/edit-client-status', [\App\Http\Controllers\admin\ClientController::class, 'editStatus'])->name('client.edit-status');
        Route::post('/delete-client', [\App\Http\Controllers\admin\ClientController::class, 'destroy'])->name('client.delete');

        //Comapny Category
        Route::get('/business-category', [CompanyCategoryController::class, 'index'])->name('business-category.index');
        Route::get('/business-category-show', [CompanyCategoryController::class, 'show'])->name('business-category.show');
        Route::post('/store-business-category', [CompanyCategoryController::class, 'store'])->name('business-category.store');
        Route::post('/edit-business-category-status', [CompanyCategoryController::class, 'editStatus'])->name('business-category.edit-status');
        Route::post('/delete-business-category', [CompanyCategoryController::class, 'destroy'])->name('business-category.delete');

        Route::get('user', [UserController::class, 'index'])->name('user.index');
        Route::get('/user-create', [UserController::class, 'create'])->name('user-create');
        Route::get('/user-edit/{id}', [UserController::class, 'edit'])->name('user-edit');
        Route::get('/user-get-permission', [UserController::class, 'getPermission'])->name('roles.show');
        Route::post('/user-create', [UserController::class, 'store'])->name('user-create');
        Route::post('/user-update', [UserController::class, 'update'])->name('user-update');
        Route::post('delete-user', [UserController::class, 'destroy'])->name('user.delete');
        Route::post('edit-user-status', [UserController::class, 'editStatus'])->name('user.edit-status');

        // Role management
        Route::get('/role', [RoleController::class, 'index'])->name('role.index');
        Route::get('/role-create', [RoleController::class, 'create'])->name('role-create');
        Route::get('/role-edit/{id}', [RoleController::class, 'edit'])->name('role-edit');
        Route::get('/role-list', [RoleController::class, 'getRoleList'])->name('role-list');
        Route::post('delete-role', [RoleController::class, 'destroy'])->name('role.delete');
        Route::post('/role-create', [RoleController::class, 'store'])->name('role-create');
        Route::post('/role-update', [RoleController::class, 'update'])->name('role-update');

        // Permission management
//        Route::get('/permission', [PermissionController::class, 'index'])->name('permissions.index');
//        Route::get('/permission-create', [PermissionController::class, 'create'])->name('permission-create');
//        Route::get('/permission-edit/{id}', [PermissionController::class, 'edit'])->name('permission-edit');
//        Route::get('/permission-list', [PermissionController::class, 'getList'])->name('permission-list');
//        Route::get('/permission-delete/{id}', [PermissionController::class, 'delete'])->name('permission-delete');
//        Route::post('/permission-create', [PermissionController::class, 'store'])->name('permission-create');
//        Route::post('/permission-update', [PermissionController::class, 'update'])->name('permission-update');

        //Permission
        Route::get('/permission', [PermissionController::class, 'index'])->name('permission.index');
        Route::get('/permission-show', [PermissionController::class, 'show'])->name('permission.show');
        Route::post('/store-permission', [PermissionController::class, 'store'])->name('permission.store');
        Route::post('/delete-permission', [PermissionController::class, 'destroy'])->name('permission.delete');

        // Intake form
        Route::get('/form-customize', [\App\Http\Controllers\admin\IntakeformController::class, 'index'])->name('form-customize.index');


    });
});





