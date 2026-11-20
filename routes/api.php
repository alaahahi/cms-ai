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
use App\Http\Controllers\Api\V1\CardController as V1CardController;
use App\Http\Controllers\Api\V1\CardServiceController as V1CardServiceController;
use App\Http\Controllers\Api\V1\ProfileController as V1ProfileController;
use App\Http\Controllers\Api\V1\SubscriptionController as V1SubscriptionController;
use App\Http\Controllers\Api\V1\ProviderController as V1ProviderController;
use App\Http\Controllers\Api\V1\VisitController as V1VisitController;
use App\Http\Controllers\Api\V1\SalesController as V1SalesController;
use App\Http\Controllers\Api\V1\TicketController as V1TicketController;
use App\Http\Controllers\Api\V1\InvoiceController as V1InvoiceController;
use App\Http\Controllers\Api\V1\AnalyticsController as V1AnalyticsController;
use App\Http\Controllers\Api\V1\DigitalCardController as V1DigitalCardController;
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
Route::get('whatsapp-progress',[FormRegistrationController::class, 'getWhatsAppProgress'])->name('whatsapp-progress');


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
Route::delete('/delete-user-custom/{id}', [UserController::class, 'deleteUserCustom'])->name('delete-user-custom');
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
Route::group(['prefix' => 'v1', 'middleware' => 'throttle:api-v1'], function () {

    // Foundation V1 controllers (Resources + unified response) — parallel-safe shape
    Route::middleware('auth:api')->group(function () {
        Route::get('/cards/me', [V1CardController::class, 'me']);
        Route::get('/profile/me', [V1ProfileController::class, 'me']);

        // Subscription module (Phase 2) — dual-writes Profile + Subscription
        Route::get('/subscriptions', [V1SubscriptionController::class, 'index']);
        Route::post('/subscriptions', [V1SubscriptionController::class, 'store']);
        Route::get('/subscriptions/{id}', [V1SubscriptionController::class, 'show'])->whereNumber('id');

        // Provider / Visit module (Phase 2.2)
        Route::get('/visits', [V1VisitController::class, 'index']);
        Route::get('/visits/{id}', [V1VisitController::class, 'show'])->whereNumber('id');
        Route::post('/visits/lab', [V1VisitController::class, 'storeLab']);
        Route::post('/visits/doctor', [V1VisitController::class, 'storeDoctor']);
        Route::post('/coverage/verify', [V1ProviderController::class, 'verifyCoverage']);

        // Sales module (Phase 2.3)
        Route::get('/sales-reps', [V1SalesController::class, 'reps']);
        Route::get('/sales-reps/{id}', [V1SalesController::class, 'showRep'])->whereNumber('id');
        Route::get('/sales', [V1SalesController::class, 'index']);
        Route::post('/sales', [V1SalesController::class, 'store']);
        Route::get('/commissions', [V1SalesController::class, 'commissions']);
        Route::post('/commissions/{id}/pay', [V1SalesController::class, 'payCommission'])->whereNumber('id');
        Route::get('/targets', [V1SalesController::class, 'targets']);
        Route::post('/targets', [V1SalesController::class, 'storeTarget']);

        // Customer Service module (Phase 2.4)
        Route::get('/ticket-categories', [V1TicketController::class, 'categories']);
        Route::post('/tickets/sync', [V1TicketController::class, 'sync']);
        Route::get('/tickets', [V1TicketController::class, 'index']);
        Route::post('/tickets', [V1TicketController::class, 'store']);
        Route::get('/tickets/{id}', [V1TicketController::class, 'show'])->whereNumber('id');
        Route::post('/tickets/{id}/messages', [V1TicketController::class, 'addMessage'])->whereNumber('id');
        Route::post('/tickets/{id}/transition', [V1TicketController::class, 'transition'])->whereNumber('id');

        // Accounting module (Phase 3)
        Route::post('/invoices/sync', [V1InvoiceController::class, 'sync']);
        Route::get('/invoices', [V1InvoiceController::class, 'index']);
        Route::post('/invoices', [V1InvoiceController::class, 'store']);
        Route::get('/invoices/{id}', [V1InvoiceController::class, 'show'])->whereNumber('id');
        Route::post('/invoices/{id}/pay', [V1InvoiceController::class, 'pay'])->whereNumber('id');
        Route::get('/provider-dues', [V1InvoiceController::class, 'providerDues']);
        Route::post('/provider-dues', [V1InvoiceController::class, 'createProviderDue']);
        Route::post('/provider-dues/{id}/pay', [V1InvoiceController::class, 'payProviderDue'])->whereNumber('id');

        // Analytics module (Phase 3)
        Route::get('/analytics/dashboard', [V1AnalyticsController::class, 'dashboard']);
        Route::post('/analytics/snapshot', [V1AnalyticsController::class, 'snapshot']);

        // Legacy handlers kept until later migration completes
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

    Route::get('/subscription-types', [V1SubscriptionController::class, 'types']);
    Route::get('/subscriptions/by-number/{number}', [V1SubscriptionController::class, 'findByNumber']);
    Route::get('/digital-card/{number}', [V1DigitalCardController::class, 'show']);

    Route::get('/providers', [V1ProviderController::class, 'index']);
    Route::get('/providers/{id}', [V1ProviderController::class, 'show'])->whereNumber('id');
    Route::get('/provider-services', [V1ProviderController::class, 'services']);

    Route::get('/cards/active', [V1CardController::class, 'active']);
    Route::get('/card-services/active', [V1CardServiceController::class, 'active']);
    Route::get('/card-services/search', [V1CardServiceController::class, 'search']);
    Route::get('/card-services/{card_id}', [V1CardServiceController::class, 'byCard']);

    Route::get('/get-popular-service', [CardsController::class, 'activeCardServicesPopular']);
    Route::post('/send-verification-code', [UserController::class, 'sendVerificationCode']);
    Route::post('/verify-code', [UserController::class, 'verifyCode']);
    Route::post('/verify-code-sms', [UserController::class, 'verifyCodeSms'])->name('verify-code-sms');

    Route::get('/settings', [AppSettingsController::class, 'index']);
    Route::get('/settings/{key}', [AppSettingsController::class, 'show']);

    Route::post('/request-card', [CardsController::class, 'requestCard']);
    Route::post('deletePendingRequest', [CardsController::class, 'deletePendingRequest'])->name('deletePendingRequest');
    Route::get('/wheelItem ', [CardsController::class, 'wheelItem']);
});

