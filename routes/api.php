<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\UserInfoController;
use App\Http\Controllers\Api\V1\RegisterController;
use App\Http\Controllers\Api\V1\AddressController;
use App\Http\Controllers\Api\V1\HDQController;


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

// Route::name('api.')->prefix('v1')->group(function () {
//     Route::post('login', [UserController::class, 'login'])->name('user.login');
//     Route::post('register/clients', [RegisterController::class, 'store'])->name('user.clients');
//     Route::get('municipality', [AddressController::class, 'index'])->name('address.municipality');
//     Route::get('hdq', [HDQController::class, 'show'])->name('HDQ.show');
//     Route::get('phpinfo', function () {
//         echo phpinfo();
//         return false;
//     });
// });

// Route::middleware('auth:sanctum')->prefix('v1')->name('api.')->group(function () {
//     Route::post('update/profile/{id}', [UserInfoController::class, 'update'])->name('user.update.profile');
//     Route::get('profile/qr/{id}', [UserInfoController::class, 'generateQR'])->name('user.generate.qr');


//     Route::post('hdq', [HDQController::class, 'store'])->name('HDQ.store');
//     Route::delete('hdq/{id}', [HDQController::class, 'destroy'])->name('HDQ.destroy');
//     Route::put('hdq/{id}', [HDQController::class, 'update'])->name('HDQ.update');
// });
