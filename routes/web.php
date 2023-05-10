<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FormRegistrationController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\DashboardController;
use App\Models\SystemConfig;
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
Route::resource('/users', UserController::class)->middleware(['auth', 'verified']);
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'config' => SystemConfig::first(),
        'canLogin' => Route::has('login'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('dashboard',[DashboardController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('getIndex',[UserController::class, 'getIndex'])->name("getIndex");
Route::get('ban/{id}',[UserController::class, 'ban'])->name("ban");
Route::get('sentToCourt/{id}',[FormRegistrationController::class, 'sentToCourt'])->name("sentToCourt");

Route::get('unban/{id}',[UserController::class, 'unban'])->name("unban");
Route::get('/userLocation/{id}',[UserController::class, 'userLocation'])->name("userLocation");

Route::get('تسجيل-الاستمارة',[FormRegistrationController::class, 'create'])->name('تسجيل-الاستمارة');
Route::post('formRegistration',[FormRegistrationController::class, 'store'])->name('formRegistration');

Route::post('formRegistrationstoreEdit/{id}',[FormRegistrationController::class, 'storeEdit'])->name('formRegistrationstoreEdit');


Route::get('formRegistration',[FormRegistrationController::class, 'index'])->name('formRegistration');

Route::get('formRegistrationEdit/{id}',[FormRegistrationController::class, 'formRegistrationEdit'])->name('formRegistrationEdit');


Route::get('FormRegistrationSaved',[FormRegistrationController::class, 'saved'])->name('FormRegistrationSaved');
Route::get('FormRegistrationCourt',[FormRegistrationController::class, 'court'])->name('FormRegistrationCourt');
Route::get('FormRegistrationCompleted',[FormRegistrationController::class, 'completed'])->name('FormRegistrationCompleted');


Route::get('getIndexFormRegistration',[FormRegistrationController::class, 'getIndex'])->name("getIndexFormRegistration");
Route::get('getIndexFormRegistrationSaved',[FormRegistrationController::class, 'getIndexSaved'])->name("getIndexFormRegistrationSaved");
Route::get('getIndexFormRegistrationCourt',[FormRegistrationController::class, 'getIndexCourt'])->name("getIndexFormRegistrationCourt");
Route::get('getIndexFormRegistrationCompleted',[FormRegistrationController::class, 'getIndexCompleted'])->name("getIndexFormRegistrationCompleted");


Route::get('labResults/{id}',[FormRegistrationController::class, 'labResults'])->name('labResults');
Route::get('labResultsEdit/{id}',[FormRegistrationController::class, 'labResultsEdit'])->name('labResultsEdit');

Route::get('doctorResults/{id}',[FormRegistrationController::class, 'doctorResults'])->name('doctorResults');
Route::get('doctorResultsEdit/{id}',[FormRegistrationController::class, 'doctorResultsEdit'])->name('doctorResultsEdit');

Route::post('results',[ResultsController::class, 'store'])->name('results');
Route::post('resultsEdit/{id}',[ResultsController::class, 'storeEdit'])->name('resultsEdit');
Route::post('resultsDoctor',[ResultsController::class, 'storeDoctor'])->name('resultsDoctor');
Route::post('resultsDoctorEdit/{id}',[ResultsController::class, 'storeDoctorEdit'])->name('resultsDoctorEdit');
Route::get('document/{id}', [FormRegistrationController::class, 'document'])->name('document');
Route::get('show/{id}', [FormRegistrationController::class, 'showfile'])->name('show');


Route::get('/livesearch', [FormRegistrationController::class, 'getProfiles'])->name('livesearch');
Route::get('/livesearchSaved', [FormRegistrationController::class, 'getProfilesSaved'])->name('livesearchSaved');
Route::get('/livesearchCompleted', [FormRegistrationController::class, 'getProfilesCompleted'])->name('livesearchCompleted');

Route::get('/getcount', [DashboardController::class, 'getcountComp'])->name('getcount');

require __DIR__.'/auth.php';
