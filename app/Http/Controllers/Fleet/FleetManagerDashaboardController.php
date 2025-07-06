<?php

namespace App\Http\Controllers\Fleet;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\User;

class FleetManagerDashaboardController extends Controller
{
    public function index()
    {
        $total_cars = \App\Models\Car::count();
        $total_active_cars = \App\Models\Car::isActive()->count() + \App\Models\Car::dueMaintenance()->count();
        $total_inactive_cars = \App\Models\Car::isInactive()->count() + \App\Models\Car::inRepairs()->count();
        $total_drivers = \App\Models\User::isDriver()->count();

        $total_car_requests = \App\Models\CarRequest::count();
        $pending_car_requests = \App\Models\CarRequest::isPending()->count();
        $approved_car_requests = \App\Models\CarRequest::isApproved()->count();
        $rejected_car_requests = \App\Models\CarRequest::isRejected()->count();

        $total_maintenances = \App\Models\CarMaintenance::count();
        $pending_car_maintenances = \App\Models\CarMaintenance::isPending()->count();
        $ongoing_car_maintenances = \App\Models\CarMaintenance::isOngoing()->count();
        $completed_car_maintenances = \App\Models\CarMaintenance::isCompleted()->count();

        $total_vendors = \App\Models\Vendor::count();

        $road_worthy_pending = Car::query()->with(['user'])->whereDate('road_worthy_expiry_date', '<=', now()->toDateString())->where('road_worthy_expiry_date', '=', now()->toDateString())->count();
        $road_worthy_active = Car::query()->with(['user'])->whereDate('road_worthy_expiry_date', '>', now()->toDateString())->where('road_worthy_expiry_date', '>', now()->toDateString())->count();
        $road_worthy_expired = Car::query()->with(['user'])->whereDate('road_worthy_expiry_date', '<=', now()->toDateString())->where('road_worthy_expiry_date', '<', now()->toDateString())->count();

        $total_users = User::all()->count();
        $total_registered_drivers_licenses = User::query()->whereNotNull('license_expiry')->count();
        $active_drivers_licenses = User::query()->whereNotNull('license_expiry')->whereDate('license_expiry', '>=', now()->toDateString())->count();
        $expired_drivers_licenses = User::query()->whereNotNull('license_expiry')->whereDate('license_expiry', '<', now()->toDateString())->count();

        $active_car_insurance = Car::query()->whereNotNull('insurance_expiry')->whereDate('insurance_expiry', '>=', now()->toDateString())->count();
        $expired_car_insurance = Car::query()->whereNotNull('insurance_expiry')->whereDate('insurance_expiry', '<', now()->toDateString())->count();

        $total_vendors_paid = \App\Models\Invoice::query()->whereHas('vendor')->where('status', '=', 'paid')->count();
        $total_vendors_owed = \App\Models\Invoice::query()->whereHas('vendor')->where('status', '=', 'pending')->orWhere('status', '=', 'partially_paid')->orWhere('status', '=', 'partial')->count();

        return view('dashboard', [
            'total_cars' => $total_cars,
            'total_active_cars' => $total_active_cars,
            'total_inactive_cars' => $total_inactive_cars,
            'total_drivers' => $total_drivers,
            'total_car_requests' => $total_car_requests,
            'pending_car_requests' => $pending_car_requests,
            'approved_car_requests' => $approved_car_requests,
            'rejected_car_requests' => $rejected_car_requests,
            'total_maintenances' => $total_maintenances,
            'pending_car_maintenances' => $pending_car_maintenances,
            'ongoing_car_maintenances' => $ongoing_car_maintenances,
            'completed_car_maintenances' => $completed_car_maintenances,
            'total_vendors' => $total_vendors,
            'road_worthy_pending' => $road_worthy_pending,
            'road_worthy_active' => $road_worthy_active,
            'road_worthy_expired' => $road_worthy_expired,
            'total_users' => $total_users,
            'total_registered_drivers_licenses' => $total_registered_drivers_licenses,
            'active_drivers_licenses' => $active_drivers_licenses,
            'expired_drivers_licenses' => $expired_drivers_licenses,
            'active_car_insurance' => $active_car_insurance,
            'expired_car_insurance' => $expired_car_insurance,
            'total_vendors_paid' => $total_vendors_paid,
            'total_vendors_owed' => $total_vendors_owed
        ]);
    }
}
