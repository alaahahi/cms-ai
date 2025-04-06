<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\FormRegistrationController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\Api\AppSettingsController;
use App\Http\Controllers\Api\CardsController;
use App\Http\Controllers\DashboardController;


Route::get('/clear-config-cache', function () {

    
    //return ;

    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    //Artisan::call('command:cache_most_visited');
    //$content_controller = new ContentEntityRepository();
    //$content_controller->log_visit_cache_job([]);
    return "Configuration cache file removed";
});
Route::post('/search-vins', [DashboardController::class, 'searchVINs'])->name('search-vins');

Route::apiResource('upload', UploadController::class);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {return $request->user();});
Route::get('/user/{id}', function (Request $request) { return  User::find($request->id)->massage;});
Route::get('/user/{id}',[UserController::class, 'getMassages']);
Route::get('/getUserMassages/{id}/{user}',[UserController::class, 'getUserMassages']);
Route::middleware('auth:api')->get('/user', function (Request $request) { return $request->user();});
Route::post('login',[UserController::class, 'login']);
Route::get('getcontact/{id}',[UserController::class, 'getcontact']);
Route::get('receiveCard',[UserController::class, 'receiveCard']);

Route::get('/checkCard',[FormRegistrationController::class, 'checkCard']);

Route::get('ackUserMassages/{sender}/{receiver}/{date}',[UserController::class, 'ackUserMassages']);

Route::post('formRegistration',[FormRegistrationController::class, 'store'])->name('formRegistration');


Route::post('salesCard',[AccountingController::class, 'salesCard'])->name('salesCard');
Route::post('salesDebt',[AccountingController::class, 'salesDebt'])->name('salesDebt');
Route::post('delTransactions',[AccountingController::class, 'delTransactions'])->name('delTransactions');

Route::post('/make-payment', [PaymentController::class, 'makePayment'])->name('makePayment');
Route::post('/payment-webhook', [WebhookController::class, 'handleWebhook'])->name('payment-webhook');
Route::group(['prefix' => 'v1'], function() {

Route::middleware('auth:api')->group(function () {
    Route::get('/cards/me', [CardsController::class, 'activeCardsMe']);
    Route::get('/profile/me', [UserController::class, 'profile']);
    Route::delete('/profile/delete', [UserController::class, 'delProfile']);
    Route::post('/profile/update', [UserController::class, 'profileUpdate']);
    Route::post('/appointment/store', [HospitalController::class, 'storeAppointment']);
    Route::get('/appointment/me', [HospitalController::class, 'appointment']);
    Route::post('/appointment/update/{id}', [HospitalController::class, 'updateAppointment']);
    Route::post('/appointment/delete/{id}', [HospitalController::class, 'deleteAppointment']);

    Route::post('/appointment/canBookAppointment', [CardsController::class, 'canBookAppointment']);

    

});

Route::get('/cards/active', [CardsController::class, 'activeCards']);
Route::get('/card-services/active', [CardsController::class, 'activeCardServices']);
Route::get('/card-services/search', [CardsController::class, 'searchCardServices']);
Route::get('card-services/{card_id}', [CardsController::class, 'getServicesByCard']);

Route::get('/get-popular-service', [CardsController::class, 'activeCardServicesPopular']);
Route::post('/send-verification-code', [UserController::class, 'sendVerificationCode']);
Route::post('/verify-code', [UserController::class, 'verifyCode']);
Route::post('/verify-code-sms', [UserController::class, 'verifyCodeSms'])->name('verify-code-sms');

Route::get('/settings', [AppSettingsController::class, 'index']);

// جلب إعداد معين باستخدام المفتاح
Route::get('/settings/{key}', [AppSettingsController::class, 'show']);

Route::post('/request-card', [CardsController::class, 'requestCard']);
Route::post('deletePendingRequest', [CardsController::class, 'deletePendingRequest'])->name('deletePendingRequest');



});

