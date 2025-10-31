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
use App\Http\Controllers\PhoneController;
use App\Http\Controllers\QueueController;


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
Route::post('/update-image', [FormRegistrationController::class, 'updateImage'])->name('update-image');

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
Route::get('/unassigned-numbers', [PhoneController::class, 'unassignedNumbers'])->name('unassigned-numbers');
Route::post('/assign-numbers', [PhoneController::class, 'assignNumbers'])->name('assign-numbers');
Route::post('/number-decision', [PhoneController::class, 'numberDecision'])->name('number-decision');
Route::post('/add-user', [PhoneController::class, 'addUser'])->name('add-user');
Route::post('/add-user-custom', [UserController::class, 'addUserCustom'])->name('add-user-custom');
Route::post('/edit-user-custom', [UserController::class, 'editUserCustom'])->name('edit-user-custom');
Route::post('/send-text-phone', [PhoneController::class, 'sendTextPhone'])->name('send-text-phone');
Route::post('/check-whatsapp-numbers', [PhoneController::class, 'checkWhatsAppNumbers'])->name('check-whatsapp-numbers');
Route::post('/check-single-whatsapp', [PhoneController::class, 'checkSingleWhatsAppNumber'])->name('check-single-whatsapp');
Route::get('/whatsapp-stats', [PhoneController::class, 'getWhatsAppStats'])->name('whatsapp-stats');
Route::post('/phones-status', [PhoneController::class, 'getPhonesStatus'])->name('phones-status');
Route::get('/unchecked-phones', [PhoneController::class, 'getUncheckedPhones'])->name('unchecked-phones');
Route::post('/import-phones', [PhoneController::class, 'importPhones'])->name('import-phones');
Route::post('/upload-process-csv', [App\Http\Controllers\ImportController::class, 'uploadAndProcess'])->name('upload-process-csv');
Route::post('/filter-import-data', [App\Http\Controllers\ImportController::class, 'filterData'])->name('filter-import-data');
Route::post('/import-check-phones', [App\Http\Controllers\ImportController::class, 'importAndCheck'])->name('import-check-phones');
Route::post('/get-file-columns', [App\Http\Controllers\ImportController::class, 'getColumns'])->name('get-file-columns');
Route::post('/start-import-progress', [App\Http\Controllers\ImportController::class, 'startImportWithProgress'])->name('start-import-progress');
Route::get('/import-progress', [App\Http\Controllers\ImportController::class, 'getImportProgress'])->name('import-progress');
Route::post('/import-to-data-cv', [App\Http\Controllers\ImportController::class, 'importToDataCv'])->name('import-to-data-cv');
Route::post('/split-file', [App\Http\Controllers\ImportController::class, 'splitFile'])->name('split-file');
Route::post('/import-split-part', [App\Http\Controllers\ImportController::class, 'importSplitPart'])->name('import-split-part');
Route::post('/check-whatsapp-data-cv', [App\Http\Controllers\ImportController::class, 'checkWhatsAppDataCv'])->name('check-whatsapp-data-cv');
Route::post('/check-whatsapp-data-cv-batch', [App\Http\Controllers\ImportController::class, 'checkWhatsAppDataCvBatch'])->name('check-whatsapp-data-cv-batch');

// Queue Management API
Route::get('/queue/stats', [QueueController::class, 'getStats'])->name('queue.stats');
Route::get('/queue/pending', [QueueController::class, 'getPendingJobs'])->name('queue.pending');
Route::get('/queue/failed', [QueueController::class, 'getFailedJobs'])->name('queue.failed');
Route::post('/queue/retry', [QueueController::class, 'retryFailedJobs'])->name('queue.retry');
Route::post('/queue/clear', [QueueController::class, 'clearFailedJobs'])->name('queue.clear');
Route::post('/queue/delete-pending', [QueueController::class, 'deletePendingJob'])->name('queue.delete-pending');
Route::post('/move-data-cv-to-extracted', [App\Http\Controllers\ImportController::class, 'moveDataCvToExtracted'])->name('move-data-cv-to-extracted');
Route::post('/move-data-cv-to-extracted-batch', [App\Http\Controllers\ImportController::class, 'moveDataCvToExtractedBatch'])->name('move-data-cv-to-extracted-batch');
Route::post('/delete-data-cv', [App\Http\Controllers\ImportController::class, 'deleteDataCv'])->name('delete-data-cv');
Route::post('/delete-data-cv-batch', [App\Http\Controllers\ImportController::class, 'deleteDataCvBatch'])->name('delete-data-cv-batch');
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
    Route::post('/storeWheelResult', [CardsController::class, 'storeWheelResult']);
    Route::get('/my-wins', [CardsController::class, 'getMyWins']);

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
Route::get('/wheelItem ', [CardsController::class, 'wheelItem']);


});

