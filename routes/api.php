<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\FormRegistrationController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WebhookController;



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
Route::post('/payment-webhook', [WebhookController::class, 'handleWebhook'])->name('payment-webhook');;

