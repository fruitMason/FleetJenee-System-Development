<?php

namespace App\Providers;

use App\Models\Car;
use App\Models\CarRequest;
use App\Models\Department;
use App\Models\Driver;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Region;
use App\Models\Sector;
use App\Models\Tax;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {}

    public function boot()
    {

        View::composer(['layouts.*'], function ($view) {
            $total_sectors = Sector::query()->count();
            $total_regions = Region::query()->count();
            $total_departments = Department::query()->where('is_archived', '0')->count();
            $total_users = User::query()->count();
            $car_requests_history_total = CarRequest::query()->whereBelongsTo(auth()->user())->count();
            $total_roles = Role::query()->count();
            $total_permissions = Permission::query()->count();
            $total_vendors = Vendor::query()->count();
             $noti_count = Notification::where('to_user_id', auth()->id())->where('unread',1)->count();
            $notifications = Gate::allows('is-fleet-manager') ?
                Notification::query()->where('unread', 1)->orderByDesc('created_at')->limit(10)->get() :
                Notification::query()->where('unread', 1)->where('to_user_id', auth()->id())->orderByDesc('created_at')->limit(10)->get();
            $total_taxes = Tax::query()->count();
            $invoices = Invoice::query()->where('status', '=', 'pending')->count();
            $odo_overdue = Car::where('odometer_status', 'Overdue')->count();

            $view->with([
                'total_sectors' => $total_sectors,
                'total_regions' => $total_regions,
                'total_departments' => $total_departments,
                'total_users' => $total_users,
                'car_requests_history_total' => $car_requests_history_total,
                'total_roles' => $total_roles,
                'total_permissions' => $total_permissions,
                'total_vendors' => $total_vendors,
                'notifications' => $notifications,
                'total_taxes' => $total_taxes,
                'invoices' => $invoices,
                'odo_overdue' => $odo_overdue,
                'noti_count'=>$noti_count
            ]);
        });
    }
}
