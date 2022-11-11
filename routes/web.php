<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Api\SocialiteController;
use App\Http\Controllers\Api\V1\CasualATMController as V1CasualATMController;
use App\Http\Controllers\Api\V1\CasualMonitoringController as ApiCasualMonitoring;
use App\Http\Controllers\Api\V1\CasualMonitoringRemarksController;
use App\Http\Controllers\Api\V1\CasualPayrollGroupController as ApiCasualGroup;
use App\Http\Controllers\Api\V1\CasualEmployee;
use App\Http\Controllers\Api\V1\CasualDeductionController as ApiDeduction;
use App\Http\Controllers\Api\V1\CasualDeductionExemptController;
use App\Http\Controllers\Api\V1\PayrollCodesController;
use App\Http\Controllers\Api\V1\CasualChargingController as ApiCharging;
use App\Http\Controllers\Api\V1\CasualPayrollAtmController as V1CasualPayrollAtmController;
use App\Http\Controllers\Api\V1\DepartmentController;
use App\Http\Controllers\Api\V1\PayrollSignatoryController as ApiSignatory;
use App\Http\Controllers\Api\V1\PayrollController;
use App\Http\Controllers\Api\V1\CasualWithoutpayController as ApiCasualWithoutPay;
use App\Http\Controllers\Api\V1\ChartOfAccountsController;
use App\Http\Controllers\CasualATMController;
use App\Http\Controllers\CasualPayrollGroupController;
use App\Http\Controllers\CasualMonitoringController;
use App\Http\Controllers\CasualDeductionController;
use App\Http\Controllers\CasualChargingController;
use App\Http\Controllers\CasualPayrollAtmController;
use App\Http\Controllers\CasualWithoutpayController;
use App\Http\Controllers\PayrollSignatoryController;
use App\Models\payroll_signatory;
use App\Models\casual_deduction_exempt;





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

// SOCIALITE
Route::get('/socialite/{socialite}', [SocialiteController::class, 'socialite'])->name('socialite');
Route::get('/socialite/{socialite}/callback', [SocialiteController::class, 'socialite_callback'])->name('socialite.callback');
Route::get('/update-signatory', function(){
    $signatory = payroll_signatory::all();
    foreach ($signatory as $key => $value) {
        $data = payroll_signatory::where("id",$value->id)->first();
        $data->obr_signatory = $value->department_head;
        $data->obr_signatory_position = $value->department_head_position;
        $data->save();
    }
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    });

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');




    //blade call
    Route::resource('/admin/monitoring/casual', CasualMonitoringController::class);
    Route::get('/api/monitoring/casual', [ApiCasualMonitoring::class, 'index'])->name('monitoring.info.casual');
    Route::post('/api/monitoring/casual/store', [ApiCasualMonitoring::class, 'store'])->name('monitoring.info.store');
    Route::put('/api/monitoring/casual/update/{id}', [ApiCasualMonitoring::class, 'update'])->name('monitoring.info.update');
    Route::delete('/api/monitoring/casual/destroy/{id}', [ApiCasualMonitoring::class, 'destroy'])->name('monitoring.info.destroy');
    Route::post('/api/monitoring/casual/approve', [ApiCasualMonitoring::class, 'approve'])->name('monitoring.info.approve');
    Route::get('/api/monitoring/status', [ApiCasualMonitoring::class, 'payrollStatus'])->name('monitoring.info.status');
    Route::get('/api/monitoring/approve-status', [ApiCasualMonitoring::class, 'payrollApproveStatus'])->name('monitoring.info.approve.status');
    Route::get('/api/monitoring/all-status', [ApiCasualMonitoring::class, 'allStatus'])->name('monitoring.info.all.status');

    Route::get('/api/monitoring/casual/history', [CasualMonitoringRemarksController::class, 'index'])->name('monitoring.history.casual');
    Route::post('/api/monitoring/debit/list', [ApiCasualMonitoring::class, 'listofDebit'])->name('payroll.generate.debit');
    Route::post('/api/monitoring/debit/save', [ApiCasualMonitoring::class, 'saveDebit'])->name('payroll.save.debit');


    Route::resource('/admin/payroll/groups', CasualPayrollGroupController::class);
    Route::resource('/admin/list/chart-accounts', ChartOfAccountsController::class);


    Route::resource('/admin/payroll/casual/deduction', CasualDeductionController::class);
    Route::get('/api/payroll/casual/deduction', [ApiDeduction::class, 'index'])->name('payroll.deduction.save');
    Route::post('/api/payroll/casual/deduction', [ApiDeduction::class, 'store'])->name('payroll.deduction.save');
    Route::delete('/api/payroll/casual/deduction/{id}', [ApiDeduction::class, 'destroy'])->name('payroll.deduction.destroy');
    Route::put('/api/payroll/casual/deduction/{id}', [ApiDeduction::class, 'update'])->name('payroll.deduction.update');


    Route::get('/api/payroll/casual/deduction/employee-deduction-exempt', [CasualDeductionExemptController::class, 'index'])->name('deduction.employee.exempt');
    Route::post('/api/payroll/casual/deduction/employee-deduction-exempt', [CasualDeductionExemptController::class, 'store'])->name('deduction.employee.store');
    Route::delete('/api/payroll/casual/deduction/employee-deduction-exempt/{id}', [CasualDeductionExemptController::class, 'destroy'])->name('deduction.employee.destroy');

    Route::get('/payroll-officer/charging', [CasualChargingController::class, 'index'])->name('charging-index');
    Route::get('/api/payroll-officer/charging', [ApiCharging::class, 'index'])->name('api.charging.index');
    Route::get('/api/payroll-officer/list/charging', [ApiCharging::class, 'listCharging'])->name('api.charging.list');
    Route::post('/api/payroll-officer/charging', [ApiCharging::class, 'store'])->name('api.charging.store');
    Route::put('/api/payroll-officer/charging/{id}', [ApiCharging::class, 'update'])->name('api.charging.update');
    Route::delete('/api/payroll-officer/charging/{id}', [ApiCharging::class, 'destroy'])->name('api.charging.destroy');




    Route::get('/api/monitoring/groups', [ApiCasualGroup::class, 'index'])->name('monitoring.payroll.group');
    Route::get('/api/monitoring/controls', [ApiCasualGroup::class, 'fetchControlNumbers'])->name('api.monitoring.payroll.control.number');


    Route::get('/api/monitoring/generatePayroll/{id}', [PayrollController::class, 'generatePayroll'])->name('payroll.generate');
    Route::get('/api/monitoring/generatePayroll/print/{id}', [PayrollController::class, 'generatePayrollPrint'])->name('payroll.generate.print');
    Route::get('/api/monitoring/generateOBR/{id}', [PayrollController::class, 'generateOBR'])->name('obr.generate');
    Route::get('/api/monitoring/generateOBR/print/{id}', [PayrollController::class, 'generateObrPrint'])->name('obr.generate.print');

    //payroll groups
    Route::post('/api/payroll/groups', [ApiCasualGroup::class, 'store'])->name('casual.group.store');
    Route::put('/api/payroll/groups/{id}', [ApiCasualGroup::class, 'update'])->name('casual.group.update');
    Route::delete('/api/payroll/groups/{id}', [ApiCasualGroup::class, 'destroy'])->name('casual.group.destroy');

    Route::get('/api/employees', [CasualEmployee::class, 'index'])->name('casual.employee.show');
    Route::get('/api/employees/list', [CasualEmployee::class, 'employees'])->name('casual.employee.list');
    Route::post('/api/employees/groups', [CasualEmployee::class, 'setGroup'])->name('monitoring.info.update');
    Route::post('/api/employees/ungroups', [CasualEmployee::class, 'unsetGroup'])->name('monitoring.info.unset');
    Route::post('/api/employees/set', [CasualEmployee::class, 'setEmployee'])->name('monitoring.info.set');
    Route::post('/api/employees/setCharging', [CasualEmployee::class, 'setCharging'])->name('monitoring.info.charging');
    Route::get('/api/groups/department', [ApiCasualGroup::class, 'groupByDept'])->name('monitoring.payroll.group.department');
    Route::get('/api/payroll/codes', [PayrollCodesController::class, 'index'])->name('payroll.codes');

    Route::get('/api/department', [DepartmentController::class, 'index'])->name('department.index');

    Route::resource('/signatory', PayrollSignatoryController::class);
    Route::get('/api/signatory', [ApiSignatory::class, 'index'])->name('signatory.index');
    Route::get('/api/signatory/sync', [ApiSignatory::class, 'sync'])->name('signatory.sync');
    Route::get('/api/account/sync', [ApiSignatory::class, 'account'])->name('signatory.account');
    Route::get('/api/casual-list/sync', [ApiCasualGroup::class, 'sync'])->name('casual.sync');
    Route::get('/api/casual-undertime/sync', [ApiCasualWithoutPay::class, 'sync'])->name('casual.withoutpay.sync');
    Route::post('/api/signatory', [ApiSignatory::class, 'store'])->name('signatory.store');
    Route::delete('/api/signatory/{id}', [ApiSignatory::class, 'destroy'])->name('signatory.destroy');
    Route::put('/api/signatory/{id}', [ApiSignatory::class, 'update'])->name('signatory.update');


    Route::resource('/casual/withoutpay', CasualWithoutpayController::class);
    Route::get('/api/casual/withoutpay', [ApiCasualWithoutPay::class, 'index'])->name('casual.withoutpay.index');
    Route::post('/api/casual/withoutpay', [ApiCasualWithoutPay::class, 'store'])->name('casual.withoutpay.store');
    Route::delete('/api/casual/withoutpay/{id}', [ApiCasualWithoutPay::class, 'destroy'])->name('casual.withoutpay.destroy');
    Route::put('/api/casual/withoutpay/{id}', [ApiCasualWithoutPay::class, 'update'])->name('casual.withoutpay.update');

    Route::resource('/casual/atm', CasualATMController::class);
    Route::get('/api/casual/atm', [V1CasualATMController::class, 'index'])->name('casual.atm.index');
    Route::post('/api/casual/atm', [V1CasualATMController::class, 'store'])->name('casual.atm.store');
    Route::put('/api/casual/atm/{id}', [V1CasualATMController::class, 'update'])->name('casual.atm.update');
    Route::put('/api/casual/atm/unlock/{id}', [V1CasualATMController::class, 'updateLock'])->name('casual.atm.update.lock');
    Route::delete('/api/casual/atm/{id}', [V1CasualATMController::class, 'destroy'])->name('casual.atm.destroy');

    Route::resource('/casual/export', CasualPayrollAtmController::class);
    Route::get('/api/casual/debit/batch', [V1CasualPayrollAtmController::class, 'index'])->name('casual.atm.generate.debit');
    Route::get('/api/casual/debit/generateBatch/{id}', [V1CasualPayrollAtmController::class, 'getATM'])->name('casual.generate.salary');
    Route::delete('/api/casual/debit/{id}', [V1CasualPayrollAtmController::class, 'destroy'])->name('casual.generate.destroy');
    Route::post('/api/casual/debit', [V1CasualPayrollAtmController::class, 'store'])->name('casual.generate.store');
});
