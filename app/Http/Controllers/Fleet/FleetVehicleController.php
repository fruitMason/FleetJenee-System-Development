<?php

namespace App\Http\Controllers\Fleet;

use App\DataTables\DiagnosisReportDataTable;
use App\DataTables\GarageDataTable; 
use App\DataTables\MaintenanceReportDataTable;
use App\DataTables\VehicleMaintenanceDataTable;
use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request; 
use App\Models\Department;
use App\Models\Region;

class FleetVehicleController extends Controller
{
    public function showMaintenance(Request $request, VehicleMaintenanceDataTable $dataTable){
        $users = User::all();
        $cars = Car::all();
        $mechanics = User::isMechanic()->get();
        return $dataTable->render('vehicle.reports.maintenance', [
            'users' => $users,
            'cars' => $cars,
            'mechanics' => $mechanics
        ]);
    }

    public function showGarage(Request $request, GarageDataTable $dataTable){
        return $dataTable->render('vehicle.reports.garage');
    }

    public function maintenanceReport(Request $request, MaintenanceReportDataTable $dataTable)
    {
        $departments = Department::all();
        $regions = Region::all();
        return $dataTable->render('vehicle.reports.maintenance_report', [
            'departments' => $departments, // Fix the typo here
            'regions' => $regions // Fix the typo here
        ]);
    }

    public function diagnosisReport(Request $request, DiagnosisReportDataTable $dataTable)
    {
        $departments = Department::all();
        $regions = Region::all();
        return $dataTable->render('vehicle.reports.diagnosis_report', [
            'departments' => $departments, // Fix the typo here
            'regions' => $regions // Fix the typo here
        ]);
    }
}
