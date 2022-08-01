<?php

use App\Http\Controllers\API\{AuthController, EstimateController, ProposalController, CompanyCategoryController};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

//API route for register new user
Route::post('/register', [AuthController::class, 'register']);

//API route for login user
Route::post('/login', [AuthController::class, 'login']);
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/verify-otp-register', [AuthController::class, 'verifyOtpRegister']);
Route::get('/business-category', [CompanyCategoryController::class, '__invoke']);
//Protecting Routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/is-check-user-status', [AuthController::class, 'isCheckUserStatus']);
    Route::get('/is-user-verified', [AuthController::class, 'isUserVerified']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/company-profile-update', [AuthController::class, 'companyProfileUpdate']);
    Route::get('/proposal-template', [ProposalController::class, '__invoke']);

    // API route for logout user
    Route::post('/logout', [AuthController::class, 'logout']);

    // Unit
    Route::controller(API\UnitController::class)->prefix('/unit')->group(function () {
        Route::get('/', '__invoke');
        Route::get('/show', 'show');
        Route::post('/store', 'store');
        Route::put('/edit-status', 'editStatus');
        Route::delete('/delete', 'destroy');
    });

    // Dashboard
    Route::controller(API\DashboardController::class)->prefix('/dashboard')->group(function () {
        Route::get('/{assign_user}', '__invoke');
        Route::get('/bar-chart/{date}/{assign_user}', 'barChart');
        Route::get('/sales-performance-chart/{date}/{assign_user}', 'salesPerformanceChart');
        Route::get('/calendar-data/{start}/{end}/{assign_user}', 'calendarData');
        Route::get('/get-date-wise-follow-up-list/{date}/{assign_user}', 'getDateWiseFollowUpList');
        Route::post('/create-next-folloup', 'createNextFolloup');
        Route::post('todo/create', 'createTodo');
        Route::post('todo/update', 'updateTodo');
        Route::delete('todo/delete', 'destroyTodo');
    });

    // Estimate
    Route::get('/get-country', [EstimateController::class, 'getCountry']);
    Route::get('/get-state/{country_id}', [EstimateController::class, 'getState']);
    Route::get('/get-city/{state_id}', [EstimateController::class, 'getCity']);

    Route::get('/get-customer/{search?}', [EstimateController::class, 'customerAutocomplete']);
    Route::post('/customer-store', [EstimateController::class, 'customerStore']);

    Route::get('/get-item/{search?}', [EstimateController::class, 'itemAutocomplete']);
    Route::post('/item-store', [EstimateController::class, 'itemStore']);

    Route::get('/get-testimonial/{search?}', [EstimateController::class, 'testimonialAutocomplete']);
    Route::post('/testimonial-store', [EstimateController::class, 'testimonialStore']);

    Route::get('/get-product/{search?}', [EstimateController::class, 'productAutocomplete']);
    Route::post('/product-store', [EstimateController::class, 'productStore']);

    Route::get('/get-estimate/{assign_user}/{status}/{search?}', [EstimateController::class, 'getEstimateList']);
    Route::get('/get-Followup-by-estimate-id/{id}', [EstimateController::class, 'getFollowUpByEstimateId']);
    Route::get('/generate-link/{id}', [EstimateController::class, 'getGenerateLink']);
    Route::post('estimate-duplicate', [EstimateController::class, 'postEstimateDuplicate']);
    Route::post('estimate/create', [EstimateController::class, 'postEstimateCreate']);
    Route::post('estimate/update', [EstimateController::class, 'postEstimateUpdate']);
    Route::post('estimate/delete', [EstimateController::class, 'postEstimateDelete']);
    Route::get('estimate/number', [EstimateController::class, 'getEstimateNumber']);
    Route::get('estimate/salesman', [EstimateController::class, 'getSalesman']);
    Route::get('estimate/edit/{id}', [EstimateController::class, 'getEstimateSingle']);
});
