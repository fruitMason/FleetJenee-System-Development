<?php

use App\Http\Controllers\Driver\RequestController;
use App\Http\Controllers\Fleet\AutoPartController;
use App\Http\Controllers\Fleet\DepartmentsController;
use App\Http\Controllers\Fleet\FinanceController;
use App\Http\Controllers\Fleet\FleetAccidentReportController;
use App\Http\Controllers\Fleet\FleetELogReportController;
use App\Http\Controllers\Fleet\FleetVehicleController;
use App\Http\Controllers\Fleet\FleetWaybillController;
use App\Http\Controllers\Fleet\FinanceRequestsController;
use App\Http\Controllers\Fleet\FleetManagerDashaboardController;
use App\Http\Controllers\Fleet\InventoryController;
use App\Http\Controllers\Fleet\OdometerSettingController;
use App\Http\Controllers\Fleet\RegionsController;
use App\Http\Controllers\Fleet\RolePermissionController;
use App\Http\Controllers\Fleet\SectorsController;
use App\Http\Controllers\Fleet\SettingsController;
use App\Http\Controllers\Fleet\UsersController;
use App\Http\Controllers\Fleet\RegistrationController;
use App\Http\Controllers\Fleet\VendorsController;
use App\Models\OdometerSetting;
use App\Models\User;
use Illuminate\Support\Facades\Route;



Route::get('/', [FleetManagerDashaboardController::class, 'index'])->name('dashboard')->middleware('fleetManagerOnly');

Route::prefix('fleet')->middleware('fleetManagerOnly')->group(function () {
    Route::prefix('vehicle-registration-transfer')->group(function () {
        Route::get('/', [RegistrationController::class, 'index'])->name('fleet.vehicle.registration');
        Route::post('/', [RegistrationController::class, 'store'])->name('fleet.vehicle.registration');
        Route::post('bulk', [RegistrationController::class, 'storeBulk'])->name('fleet.vehicle.registration.bulk');
        Route::post('/cars/archive', [RegistrationController::class, 'archiveCars'])->name('cars.archive');
        Route::get('/cars/archived', [RegistrationController::class, 'archivedCars'])->name('cars.archived');
        Route::post('/cars/unarchive', [RegistrationController::class, 'unarchiveCars'])->name('cars.unarchive');

        Route::post('maintain', [RegistrationController::class, 'maintain'])->name('fleet.vehicle.registration.maintain');


        Route::post('get', [RegistrationController::class, 'getCars'])->name('fleet.vehicle.registration.get');
        Route::post('update', [RegistrationController::class, 'updateCars'])->name('fleet.vehicle.registration.update');
        Route::post('delete', [RegistrationController::class, 'deleteCars'])->name('fleet.vehicle.registration.delete');
    });

    Route::get('dvla-road-worthinesss', [RegistrationController::class, 'dvlaRoadWorthiness'])->name('fleet.vehicle.dvla.road.worthiness');
    Route::get('odometer/overdue', [RegistrationController::class, 'overdueOdometers'])->name('fleet.vehicle.odometer.overdue');
    Route::get('odometer/overdue/{car}/order', [RegistrationController::class, 'overdueOdometersWorkOrder'])->name('fleet.vehicle.odometer.overdue.workorder');
    Route::get('odometer/report', [RegistrationController::class, 'overdueReportOdometers'])->name('fleet.vehicle.odometer.report');
    Route::get('overdue-odometer-entries/export', [RegistrationController::class, 'exportOverdueOdometers'])->name('overdue.odometer.export');
    Route::get('odometer/history/{car_id}', [RegistrationController::class, 'showOdometerHistory'])->name('fleet.vehicle.odometer.history');

    Route::get('insurance', [RegistrationController::class, 'insurance'])->name('fleet.vehicle.insurance');
    Route::get('driver/license', [RegistrationController::class, 'driverLicense'])->name('fleet.vehicle.driver.license');

    Route::prefix('car-requests')->group(function () {
        Route::get('index', [RequestController::class, 'index'])->name('fleet.vehicle.request');
        Route::post('index', [RequestController::class, 'store'])->name('fleet.vehicle.request');
        Route::post('approve', [RequestController::class, 'approve'])->name('fleet.vehicle.request.approve');
        Route::post('reject', [RequestController::class, 'reject'])->name('fleet.vehicle.request.reject');

        Route::get('my-car-requests', [RequestController::class, 'myRequests'])->name('fleet.vehicle.myrequests');
        Route::post('my-car-requests', [RequestController::class, 'store'])->name('fleet.vehicle.myrequests');
    });

    Route::prefix('reports')->group(function () {
        Route::get('accident', [FleetAccidentReportController::class, 'showAccidentReport'])->name('fleet.vehicle.reports.accident');
        Route::post('accident', [FleetAccidentReportController::class, 'storeAccidentReport'])->name('fleet.vehicle.reports.accident');
        Route::post('resolve', [FleetAccidentReportController::class, 'resolveAccidentReport'])->name('fleet.vehicle.reports.accident.resolve');

        Route::get('elog', [FleetELogReportController::class, 'showELogReport'])->name('fleet.vehicle.reports.elog');
        Route::get('elog/activity/{id}', [FleetELogReportController::class, 'viewELogReportActivity'])->name('fleet.vehicle.reports.elog.activity.view');
    });

    Route::prefix('vehicle')->group(function () {
        Route::get('maintenance', [FleetVehicleController::class, 'showMaintenance'])->name('fleet.vehicle.maintenance');
        Route::get('maintenance/{maintenance_id}', [RegistrationController::class, 'viewMaintenance'])->name('fleet.vehicle.maintenance.view');
        Route::delete('maintenance/{maintenance_id}/delete', [RegistrationController::class, 'deleteMaintenance'])->name('fleet.vehicle.maintenance.delete');
        Route::patch('maintenance/{maintenance_id}/complete', [RegistrationController::class, 'completeMaintenance'])->name('fleet.vehicle.maintenance.complete');
        Route::get('garage', [FleetVehicleController::class, 'showGarage'])->name('fleet.vehicle.garage');
        Route::get('maintenance-report', [FleetVehicleController::class, 'maintenanceReport'])->name('fleet.vehicle.maintenance.report');
        Route::get('diagnosis-report', [FleetVehicleController::class, 'diagnosisReport'])->name('fleet.vehicle.diagnosis.report');
    });

    Route::prefix('waybill')->group(function () {
        Route::get('index', [FleetWaybillController::class, 'showWaybill'])->name('fleet.waybill.index');
        Route::post('index', [FleetWaybillController::class, 'storeWaybill'])->name('fleet.waybill.store');
    });




    //-----------  FINANCE REQUESTS -----------------
    Route::get('finance-requests/index', [FinanceRequestsController::class, 'index'])->name('finance.requests.home');
    Route::get('finance-requests/distribution', [FinanceRequestsController::class, 'distribution'])->name('finance.requests.distribution');
    Route::get('finance-requests/general/create', [FinanceRequestsController::class, 'createGeneral'])->name('finance.requests.general.create');
    Route::post('finance-requests/general/store', [FinanceRequestsController::class, 'storeGeneral'])->name('finance.requests.general.store');

    Route::get('finance-parts/purchase', [AutoPartController::class, 'purchaseIndex'])->name('parts.purchase.order');
    Route::get('auto-parts/index', [AutoPartController::class, 'index'])->name('auto.parts.index');
    Route::get('auto-parts/create', [AutoPartController::class, 'create'])->name('auto.parts.create');
    Route::get('auto-parts/{autopart}/edit', [AutoPartController::class, 'edit'])->name('auto.parts.edit');
    Route::post('auto-parts/store', [AutoPartController::class, 'store'])->name('auto.parts.store');
    Route::patch('auto-parts/{autopart}/update', [AutoPartController::class, 'update'])->name('auto.parts.update');
    Route::delete('auto-parts/{autopart}/destroy', [AutoPartController::class, 'destroy'])->name('auto.parts.destroy');




    //-------------------INVENTORY MANAGER---------------
    Route::prefix('inventory')->group(function () {
        Route::get('index', [InventoryController::class, 'index'])->name('inventory.index');
        Route::get('{id}/details', [InventoryController::class, 'show'])->name('inventory.show');

        //purchase
        Route::get('purchase/index', [InventoryController::class, 'purchaseIndex'])->name('inventory.purchase.index');

        // Route::get('purchase/create', [InventoryController::class, 'purchaseCreate'])->name('inventory.purchase.create');
        // Route::get('purchase/{id}/show', [InventoryController::class, 'purchaseShow'])->name('inventory.purchase.show');
        // Route::post('purchase/store', [InventoryController::class, 'purchaseStore'])->name('inventory.purchase.store');

        //usage
        Route::get('usage/index', [InventoryController::class, 'usageIndex'])->name('inventory.usage.index');
        Route::get('usage/{auto_part_request}/show', [InventoryController::class, 'usageShow'])->name('inventory.usage.show');
        Route::post('usage/auth', [InventoryController::class, 'usageAuth'])->name('inventory.usage.auth');
        //admin usage
        Route::get('usage/index/create', [InventoryController::class, 'usageRequestAdmin'])->name('inventory.usage.admin');
        Route::post('usage/index/store', [InventoryController::class, 'usageRequestStore'])->name('inventory.usage.store');
        //damage
    });
});

Route::prefix('settings')->middleware('fleetManagerOnly')->group(function () {
    Route::prefix('odometer')->group(function () {
        Route::get('/', [OdometerSettingController::class, 'index'])->name('settings.odometer');
        Route::post('/', [OdometerSettingController::class, 'update'])->name('settings.odometer.update');
    });
    Route::prefix('sectors')->group(function () {
        Route::get('/', [SectorsController::class, 'showSectors'])->name('settings.sectors');
        Route::post('/', [SectorsController::class, 'storeSectors'])->name('settings.sectors');
        Route::post('get', [SectorsController::class, 'getSectors'])->name('settings.sectors.get');
        Route::post('update', [SectorsController::class, 'updateSectors'])->name('settings.sectors.update');
        Route::post('delete', [SectorsController::class, 'deleteSectors'])->name('settings.sectors.delete');
        Route::post('/archive', [SectorsController::class, 'archiveSectors'])->name('sectors.archive');
        Route::get('/archived', [SectorsController::class, 'archivedSectors'])->name('sectors.archived');
        Route::post('/unarchive', [SectorsController::class, 'unarchiveSectors'])->name('sectors.unarchive');
    });

    Route::prefix('regions')->group(function () {
        Route::get('/', [RegionsController::class, 'showRegions'])->name('settings.regions');
        Route::post('/', [RegionsController::class, 'storeRegions'])->name('settings.regions');
        Route::post('get', [RegionsController::class, 'getRegions'])->name('settings.regions.get');
        Route::post('update', [RegionsController::class, 'updateRegions'])->name('settings.regions.update');
        Route::post('delete', [RegionsController::class, 'deleteRegions'])->name('settings.regions.delete');
        Route::post('/archive', [RegionsController::class, 'archiveRegions'])->name('regions.archive');
        Route::get('/archived', [RegionsController::class, 'archivedRegions'])->name('regions.archived');
        Route::post('/unarchive', [RegionsController::class, 'unarchiveRegions'])->name('regions.unarchive');
    });

    Route::prefix('departments')->group(function () {
        Route::get('/', [DepartmentsController::class, 'showDepartments'])->name('settings.departments');
        Route::post('/', [DepartmentsController::class, 'storeDepartments'])->name('settings.departments');
        Route::post('get', [DepartmentsController::class, 'getDepartments'])->name('settings.departments.get');
        Route::post('update', [DepartmentsController::class, 'updateDepartments'])->name('settings.departments.update');
        Route::post('delete', [DepartmentsController::class, 'deleteDepartments'])->name('settings.departments.delete');
        Route::post('/archive', [DepartmentsController::class, 'archiveDepartments'])->name('departments.archive');
        Route::get('/archived', [DepartmentsController::class, 'archivedDepartments'])->name('departments.archived');
        Route::post('/unarchive', [DepartmentsController::class, 'unarchiveDepartments'])->name('departments.unarchive');
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UsersController::class, 'showUsers'])->name('settings.users');
        Route::post('/', [UsersController::class, 'storeUsers'])->name('settings.users');
        Route::post('bulk', [UsersController::class, 'storeUsersBulk'])->name('settings.users.bulk');
        Route::get('{user_id}/role/{role_id}', [UsersController::class, 'userPermissions'])->name('settings.user.permissions');
        Route::get('view/{user_id}', [UsersController::class, 'viewUsers'])->name('settings.users.view');
        Route::post('get', [UsersController::class, 'getUsers'])->name('settings.users.get');
        Route::post('update', [UsersController::class, 'updateUsers'])->name('settings.users.update');
        Route::post('delete', [UsersController::class, 'deleteUsers'])->name('settings.users.delete');
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [RolePermissionController::class, 'showRoles'])->name('settings.roles');
        Route::post('/', [RolePermissionController::class, 'storeRoles'])->name('settings.roles');
        Route::get('{role_id}', [RolePermissionController::class, 'viewRoles'])->name('settings.roles.view');
        Route::post('permissions/sync', [RolePermissionController::class, 'syncRolesPermissions'])->name('settings.role.permissions.sync');
        Route::post('/archive', [RolePermissionController::class, 'archiveRoles'])->name('roles.archive');
        Route::get('/archived', [RolePermissionController::class, 'archivedRoles'])->name('roles.archived');
        Route::post('/unarchive', [RolePermissionController::class, 'unarchiveRoles'])->name('roles.unarchive');
    });

    Route::prefix('permissions')->group(function () {
        Route::get('/', [RolePermissionController::class, 'showPermissions'])->name('settings.permissions');
        Route::post('/', [RolePermissionController::class, 'storePermissions'])->name('settings.permissions');
        Route::get('/run', [RolePermissionController::class, 'loadOnce'])->name('settings.permissions.loadonce');
        Route::get('/rerun', [RolePermissionController::class, 'ReRun'])->name('settings.permissions.loadonce');
    });

    Route::prefix('vendors')->group(function () {
        Route::get('/', [VendorsController::class, 'showVendors'])->name('settings.vendors');
        Route::post('/', [VendorsController::class, 'storeVendors'])->name('settings.vendors');
        Route::post('get', [SectorsController::class, 'getSectors'])->name('settings.sectors.get');
        Route::post('update', [SectorsController::class, 'updateSectors'])->name('settings.sectors.update');
        Route::post('delete', [SectorsController::class, 'deleteSectors'])->name('settings.sectors.delete');
    });

    Route::prefix('taxes')->group(function () {
        Route::get('/', [SettingsController::class, 'showTaxes'])->name('settings.taxes');
        Route::post('/', [SettingsController::class, 'storeTaxes'])->name('settings.taxes');
    });
});

Route::prefix('finance')->middleware('fleetManagerOnly')->group(function () {
    Route::prefix('invoice')->group(function () {
        Route::get('index', [FinanceController::class, 'showInvoice'])->name('finance.invoice.index');
        Route::get('create', [FinanceController::class, 'showCreateInvoice'])->name('finance.invoice.create');
        ///Route::get('get-orders-by-vendor', [FinanceController::class, 'workOrderByVendor'])->name('finance.invoice.orders.by.vendor');
        Route::post('store', [FinanceController::class, 'storeInvoice'])->name('finance.invoice.store');
        Route::delete('destroy/{invoice}', [FinanceController::class, 'destroyInvoice'])->name('finance.invoice.destroy');
        Route::post('status/update', [FinanceController::class, 'updateStatus'])->name('finance.invoice.status.update');
        Route::post('status/tofinance', [FinanceController::class, 'submitToFinance'])->name('finance.invoice.submittofinance');
        Route::get('status/{invoice}/tofinance', [FinanceController::class, 'createSubmitToFinance'])->name('finance.invoice.submittofinance.create');
        Route::get('view/{id}', [FinanceController::class, 'viewInvoice'])->name('finance.invoice.view');
        Route::get('print/{id}', [FinanceController::class, 'printInvoice'])->name('finance.invoice.print');
    });
});
