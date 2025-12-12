<?php

use App\Http\Controllers\Mechanic\MechanicManagerController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::prefix('mechanic')->group(function () {

    Route::get('dashboard', function () {

        $total_car_maintenances = \App\Models\CarMaintenance::query()->whereBelongsTo(Auth::user(), 'mechanic')->where('fin_status','approved')->count();
        $pending_car_maintenances = \App\Models\CarMaintenance::isPending()->whereBelongsTo(Auth::user(), 'mechanic')->where('fin_status','approved')->count();
        $ongoing_car_maintenances = \App\Models\CarMaintenance::isOngoing()->whereBelongsTo(Auth::user(), 'mechanic')->where('fin_status','approved')->count();
        $completed_car_maintenances = \App\Models\CarMaintenance::isCompleted()->whereBelongsTo(Auth::user(), 'mechanic')->where('fin_status','approved')->count();

        return view('mechanics.dashboard', [
            'total_car_maintenances' => $total_car_maintenances,
            'pending_car_maintenances' => $pending_car_maintenances,
            'ongoing_car_maintenances' => $ongoing_car_maintenances,
            'completed_car_maintenances' => $completed_car_maintenances,
        ]);
    })->name('mechanic.dashboard');

    Route::prefix('garage')->group(function () {
        Route::get('/', [MechanicManagerController::class, 'showGarage'])->name('mechanic.garage');
        Route::get('{id}/order/details', [MechanicManagerController::class, 'showOrderDetail'])->name('mechanic.garage.order.detail');
        Route::post('confirm/receipt', [MechanicManagerController::class, 'confirmReceipt'])->name('mechanic.garage.receipt.confirm');
        Route::get('confirm/receipt/{id}', [MechanicManagerController::class, 'showGarageConfirmReceipt'])->name('mechanic.garage.receipt.confirm.view');
        Route::post('upload/diagnosis', [MechanicManagerController::class, 'uploadDiagnosis'])->name('mechanic.garage.diagnosis.upload');
        Route::get('view/diagnosis/{id}', [MechanicManagerController::class, 'showGarageConfirmDiagnosis'])->name('mechanic.garage.diagnosis.view');
        Route::get('invoice/create/{id}', [MechanicManagerController::class, 'showCreateInvoice'])->name('mechanic.garage.invoice.create');
        Route::post('confirm/completed', [MechanicManagerController::class, 'confirmCompleted'])->name('mechanic.garage.confirm.completed');
        Route::get('view/completed/{id}', [MechanicManagerController::class, 'showGarageCompletedReceipt'])->name('mechanic.garage.completed.view');
    });
});
