<?php

use App\Http\Controllers\Driver\AccidentReportController;
use App\Http\Controllers\Driver\DriverManagerController;
use App\Http\Controllers\Driver\RequestController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::prefix('driver')->middleware('driverOnly')->group(function () {

    Route::get('dashboard', function () {
        $total_car_requests = \App\Models\CarRequest::query()->whereBelongsTo(Auth::user())->count();
        $pending_car_requests = \App\Models\CarRequest::isPending()->whereBelongsTo(Auth::user())->count();
        $approved_car_requests = \App\Models\CarRequest::isApproved()->whereBelongsTo(Auth::user())->count();
        $rejected_car_requests = \App\Models\CarRequest::isRejected()->whereBelongsTo(Auth::user())->count();

        $total_waybills = \App\Models\Waybill::query()->whereBelongsTo(Auth::user(), 'driver')->count();
        $pending_waybills = \App\Models\Waybill::isPending()->whereBelongsTo(Auth::user(), 'driver')->count();
        $ongoing_waybills = \App\Models\Waybill::isOngoing()->whereBelongsTo(Auth::user(), 'driver')->count();
        $rejected_waybills = \App\Models\Waybill::isRejected()->whereBelongsTo(Auth::user(), 'driver')->count();
        $completed_waybills = \App\Models\Waybill::isCompleted()->whereBelongsTo(Auth::user(), 'driver')->count();

        return view('drivers.dashboard', [
            'total_car_requests' => $total_car_requests,
            'pending_car_requests' => $pending_car_requests,
            'approved_car_requests' => $approved_car_requests,
            'rejected_car_requests' => $rejected_car_requests,
            'total_waybills' => $total_waybills,
            'pending_waybills' => $pending_waybills,
            'ongoing_waybills' => $ongoing_waybills,
            'rejected_waybills' => $rejected_waybills,
            'completed_waybills' => $completed_waybills
        ]);
    })->name('driver.dashboard');

    Route::prefix('odometer')->group(function () {
        Route::get('/', [DriverManagerController::class, 'showOdometer'])->name('driver.odometer');
        Route::post('/', [DriverManagerController::class, 'storeOdometer'])->name('driver.odometer');
    });

    Route::prefix('car-requests')->group(function () {
        Route::get('index', [DriverManagerController::class, 'showCarRequests'])->name('driver.vehicle.request');
        Route::post('approve', [DriverManagerController::class, 'approveRequest'])->name('driver.vehicle.request.approve');
        Route::post('reject', [DriverManagerController::class, 'rejectRequest'])->name('driver.vehicle.request.reject');
        Route::post('index', [RequestController::class, 'store'])->name('driver.vehicle.request');
    });

    Route::prefix('report')->group(function () {
        Route::get('accident', [AccidentReportController::class, 'showAccidentReport'])->name('driver.report.accident');
        Route::post('accident', [AccidentReportController::class, 'storeAccidentReport'])->name('driver.report.accident');
        Route::get('elog', [AccidentReportController::class, 'showELogReport'])->name('driver.report.elog');
        Route::post('elog', [AccidentReportController::class, 'storeELogReport'])->name('driver.report.elog');
        Route::get('elog/activity/{id}', [AccidentReportController::class, 'viewELogReportActivity'])->name('driver.report.elog.activity.view');
        Route::post('elog/get', [AccidentReportController::class, 'getELogReport'])->name('driver.report.elog.get');
        Route::post('elog/update', [AccidentReportController::class, 'updateELogReportActivity'])->name('driver.report.elog.update');
        Route::post('elog/end-trip', [AccidentReportController::class, 'endELogTrip'])->name('driver.report.elog.end_trip');
    });

    Route::prefix('waybill')->group(function () {
        Route::get('index', [DriverManagerController::class, 'showWaybill'])->name('driver.waybill.index');
        Route::post('respond', [DriverManagerController::class, 'respondWaybill'])->name('driver.waybill.respond');
    });
});
