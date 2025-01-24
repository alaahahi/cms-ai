<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FormRegistrationController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\HospitalController;
use App\Models\SystemConfig;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\AppSettingsController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Api\CardsController;



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
Route::get('link', function () {
    Artisan::call('storage:link');
    return "yes link";
});

Route::resource('/users', UserController::class)->middleware(['auth', 'verified']);

Route::middleware(['web', 'hospital'])->group(function () {
    Route::get('/',[HospitalController::class,'index'])->name('/');
});

Route::get('/cancel', function () {
    return view('cancel');
});
Route::get('/failure', function () {
    return view('failure');
});
Route::get('/success', function () {
    return view('success');
});
Route::get('/order', function () {
    return view('order');
});
Route::post('/make-payment', [PaymentController::class, 'makePayment'])->name('make-payment');

Route::group(['middleware' => ['auth','verified']], function () {
    Route::get('/settings', [AppSettingsController::class, 'index'])->name('settings');
    Route::post('/settings/update', [AppSettingsController::class, 'update'])->name('settings.update');
    
    Route::get('/dashboard', function () {return Inertia::render('Dashboard');})->middleware(['auth', 'verified'])->name('dashboard');

    Route::get('dashboard',[DashboardController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');
    
    Route::get('getIndex',[UserController::class, 'getIndex'])->name("getIndex");
    Route::get('ban/{id}',[UserController::class, 'ban'])->name("ban");
    Route::get('sentToCourt/{id}',[FormRegistrationController::class, 'sentToCourt'])->name("sentToCourt");
    
    Route::get('unban/{id}',[UserController::class, 'unban'])->name("unban");
    Route::get('/userLocation/{id}',[UserController::class, 'userLocation'])->name("userLocation");
    
    Route::get('تسجيل-الاستمارة',[FormRegistrationController::class, 'create'])->name('تسجيل-الاستمارة');
    
    Route::post('formRegistrationstoreEdit/{id}',[FormRegistrationController::class, 'storeEdit'])->name('formRegistrationstoreEdit');
    
    
    Route::get('formRegistration',[FormRegistrationController::class, 'index'])->name('formRegistration');
    Route::get('PendingRequest',[FormRegistrationController::class, 'PendingRequest'])->name('PendingRequest');

    
    Route::get('formRegistrationEdit/{id}',[FormRegistrationController::class, 'formRegistrationEdit'])->name('formRegistrationEdit');
    
    
    Route::get('FormRegistrationCourt',[FormRegistrationController::class, 'court'])->name('FormRegistrationCourt');
    Route::get('FormRegistrationCompleted',[FormRegistrationController::class, 'completed'])->name('FormRegistrationCompleted');
    
    
    Route::get('getIndexFormRegistration',[FormRegistrationController::class, 'getIndex'])->name("getIndexFormRegistration");
    Route::get('getIndexPendingRequest',[FormRegistrationController::class, 'getIndexPendingRequest'])->name("getIndexPendingRequest");

    
    Route::get('getIndexFormRegistrationSaved',[FormRegistrationController::class, 'getIndexSaved'])->name("getIndexFormRegistrationSaved");
    Route::get('getIndexFormRegistrationCourt',[FormRegistrationController::class, 'getIndexCourt'])->name("getIndexFormRegistrationCourt");
    Route::get('getIndexFormRegistrationCompleted',[FormRegistrationController::class, 'getIndexCompleted'])->name("getIndexFormRegistrationCompleted");
    
    
    Route::get('labResults/{id}',[FormRegistrationController::class, 'labResults'])->name('labResults');
    Route::get('labResultsEdit/{id}',[FormRegistrationController::class, 'labResultsEdit'])->name('labResultsEdit');
    
    Route::get('getIndexAccountsSelas',[FormRegistrationController::class, 'getIndexAccountsSelas'])->name('getIndexAccountsSelas');
    
    
    Route::get('doctorResults/{id}',[FormRegistrationController::class, 'doctorResults'])->name('doctorResults');
    Route::get('doctorResultsEdit/{id}',[FormRegistrationController::class, 'doctorResultsEdit'])->name('doctorResultsEdit');
    
    Route::post('results',[ResultsController::class, 'store'])->name('results');
    Route::post('resultsEdit/{id}',[ResultsController::class, 'storeEdit'])->name('resultsEdit');
    Route::post('resultsDoctor',[ResultsController::class, 'storeDoctor'])->name('resultsDoctor');
    Route::post('resultsDoctorEdit/{id}',[ResultsController::class, 'storeDoctorEdit'])->name('resultsDoctorEdit');

    
    
    Route::get('/livesearch', [FormRegistrationController::class, 'getProfiles'])->name('livesearch');
    Route::get('/livesearchSaved', [FormRegistrationController::class, 'getProfilesSaved'])->name('livesearchSaved');
    Route::get('/livesearchCompleted', [FormRegistrationController::class, 'getProfilesCompleted'])->name('livesearchCompleted');

    
    Route::get('/getcount', [DashboardController::class, 'getcountComp'])->name('getcount');
    
    Route::get('/addUserCard/{card_id}/{card}/{user_id}', [UserController::class, 'addUserCard'])->name('addUserCard');
    
    Route::get('/receiveCard', [AccountingController::class, 'receiveCard'])->name('receiveCard');
    Route::get('/paySelse/{id}', [AccountingController::class, 'paySelse'])->name('paySelse');
    Route::get('/payCard', [AccountingController::class, 'payCard'])->name('payCard');

    
    Route::get('hospital',[HospitalController::class, 'hospital'])->name('hospital');
    Route::get('hospitalAdd',[HospitalController::class, 'create'])->name('hospitalAdd');
    Route::get('hospitalEdit/{id}',[HospitalController::class, 'edit'])->name('hospitalEdit');
    Route::get('hospitalStoreEdit',[HospitalController::class, 'index'])->name('hospitalStoreEdit');
    Route::post('hospitalStoreEdit',[HospitalController::class, 'storeEdit'])->name('hospitalStoreEdit');
    Route::post('hospitalAdd',[HospitalController::class, 'store'])->name('hospitalAdd');
    Route::get('getIndexAppointment',[HospitalController::class, 'getIndex'])->name("getIndexAppointment");
    Route::get('livesearchAppointment', [HospitalController::class, 'livesearchAppointment'])->name('livesearchAppointment');
    Route::get('appointmentCome', [HospitalController::class, 'appointmentCome'])->name('appointmentCome');
    Route::get('appointmentCancel', [HospitalController::class, 'appointmentCancel'])->name('appointmentCancel');

    Route::get('accounting',[AccountingController::class, 'index'])->name('accounting');
    Route::get('getIndexAccounting',[AccountingController::class, 'getIndexAccounting'])->name("getIndexAccounting");

    Route::post('deletePendingRequest', [CardsController::class, 'deletePendingRequest'])->name('deletePendingRequest');
    Route::post('AcceptePendingRequest', [CardsController::class, 'AcceptePendingRequest'])->name('AcceptePendingRequest');
    Route::post('EditPendingRequest', [CardsController::class, 'EditPendingRequest'])->name('EditPendingRequest');


 });

 Route::get('document/{id}', [FormRegistrationController::class, 'document'])->name('document');
 Route::get('show/{id}', [FormRegistrationController::class, 'showfile'])->name('show');
 Route::get('card',[FormRegistrationController::class, 'saved'])->name('card');
 Route::get('hospitalPrint',[HospitalController::class, 'hospitalPrint'])->name('hospitalPrint');

 
require __DIR__.'/auth.php';
