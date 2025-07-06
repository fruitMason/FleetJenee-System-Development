<?php

use App\Http\Controllers\Account\AccountDashboardController;
use App\Http\Controllers\Account\AccountWorkOrdersController;
use App\Http\Controllers\Account\FinderController;
use App\Http\Controllers\Account\PaymentProcessingController;

use Illuminate\Support\Facades\Route;

Route::prefix('accounts')->middleware('accountOnly')->group(function () {

    Route::get('dashboard', [AccountDashboardController::class, 'dashboard'])->name('account.dashboard');

    // Route::get('dashboard', [AccountDashboardController::class, 'dashboard'])->name('account.dashboard');accounts/
    //Route::get('/', [AccountDashboardController::class, 'dashboard'])->name('accounts.orders');
    Route::get('orders', [AccountWorkOrdersController::class, 'workOrders'])->name('accounts.orders');
    Route::get('orders/{id}/view', [AccountWorkOrdersController::class, 'workOrderViewAuth'])->name('accounts.orders.details.show');
    Route::post('orders/details/auth', [AccountWorkOrdersController::class, 'workOrderAuth'])->name('accounts.orders.details.update');
    Route::get('payments', [AccountDashboardController::class, 'payments'])->name('accounts.payments');
    Route::get('invoice', [AccountDashboardController::class, 'invoice'])->name('accounts.invoice');


    //finder
    Route::get('old-cars', [FinderController::class, 'oldCars'])->name('accounts.old.cars');
    Route::get('finder/car', [FinderController::class, 'indexCar'])->name('accounts.finder.home.car');
    Route::get('finder/car/{car}/details', [FinderController::class, 'carFinder'])->name('accounts.finder.car.details');  
      
    Route::get('finder/user', [FinderController::class, 'indexUser'])->name('accounts.finder.home.user');
    Route::get('finder/user/{user}/details', [FinderController::class, 'userFinder'])->name('accounts.finder.user.details');   
    Route::get('finder/vendor', [FinderController::class, 'vendorFinder'])->name('accounts.finder.vendor');


    //payment processing
    Route::get('payment/requests', [PaymentProcessingController::class, 'paymentRequests'])->name('accounts.payment.requests');
    Route::get('payment/history', [PaymentProcessingController::class, 'paymentHistory'])->name('accounts.payment.history');
    Route::get('payment/requests/{payid}/pay', [PaymentProcessingController::class, 'processPayment'])->name('accounts.payment.process.payment');
    Route::post('payment/requests/pay', [PaymentProcessingController::class, 'actualPayment'])->name('accounts.payment.process.pay');




});
