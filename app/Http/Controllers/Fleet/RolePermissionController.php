<?php

namespace App\Http\Controllers\Fleet;

use App\DataTables\PermissionsDataTable;
use App\DataTables\RolesDataTable;
use App\DataTables\ArchivedRoleDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function showRoles(RolesDataTable $dataTable)
    {
        $roles = Role::all();
        return $dataTable->render('settings.role', [
            'roles' => $roles
        ]);
    }

    public function archiveSectors(Request $request)
    {
        $resource = Role::query()->find($request->id);
        if (!$resource) {
            return $this->sendFailureJsonResponse('Role not found.');
        }

        $resource->is_archived = true;
        $resource->save();

        return $this->sendSuccessJsonResponse('Role was archived successfully!');
    }

    public function archivedRoles(Request $request, ArchivedRoleDataTable $dataTable)
    {
        return $dataTable->render('settings.archived_roles');
    }
    public function unarchiveRoles(Request $request)
    {
        $resource = Role::query()->find($request->id);
        if (!$resource) {
            return $this->sendFailureJsonResponse('Role not found.');
        }

        $resource->is_archived = false; // Set to false to unarchive
        $resource->save();

        return $this->sendSuccessJsonResponse('Role was unarchived successfully!');
    }

    public function storeRoles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles',
            'guard_name' => 'required|string'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->with(['errors' => $validator->errors()]);
        }

        $data = $validator->validated();
        $role = Role::create($data);

        return back()->with('success', 'Role was ADDED successfully!');
    }

    public function viewRoles(Request $request, $role_id)
    {
        $roles = Role::findById($role_id);
        $permissions = Permission::select('id', 'name', 'module')->orderBy('module')->get();
        return view('settings.view_role', [
            'role' => $roles,
            'permissions' => $permissions
        ]);
    }

    public function syncRolesPermissions(Request $request)
    {
        $role = Role::findById($request->role_id);
        $permissions = Permission::query()->whereIn('id', $request->permissions)->get();
        $role->syncPermissions($permissions);

        return response()->json([
            'status' => 200,
            'message' => 'Permission Successfully Assigned to Role'
        ]);
    }

    public function showPermissions(PermissionsDataTable $dataTable)
    {
        $permissions = Permission::all();
        return $dataTable->render('settings.permission', [
            'permissions' => $permissions
        ]);
    }

    public function storePermissions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles',
            'guard_name' => 'required|string'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->with(['errors' => $validator->errors()]);
        }

        $data = $validator->validated();
        $data['module'] = 'admin';
        $role = Permission::create($data);

        return back()->with('success', 'Permission was ADDED successfully!');
    }



    public function loadOnce()
    {
        try {
            DB::beginTransaction();
            if (DB::table('permissions')->exists()) {
                return 'Data has been seeded already!';
            } else {
                $permissions_finance = [
                    // 'ACCOUNTS MANAGEMENT',
                    'Account Dashboard',
                    'Finder >>',
                    ' Finder-Car',
                    ' Finder-User',
                    'Payments >>',
                    ' Payment Requests',
                    ' Payment History',
                    'Work Orders',
                    'FM Invoices',
                ];

                $permissions_fleet = [
                    // 'FLEET MANAGEMENT',
                    'Admin Dashboard',

                    'Settings >>',
                    ' Zones',
                    ' Regions',
                    ' Departments',
                    ' Users',
                    ' Permissions',
                    ' Taxes',
                    ' Odometer Setting',

                    //'Staff',
                    'Service Providers',
                    'Vehicle Registration',
                    'DVLA Road Worthy',
                    'Overdue Odometers',
                    'Driver License',

                    'Car Requests >>',
                    ' Car Requests',
                    ' Car Request History',

                    'Finance Requests >>',
                    ' General Requests',
                    ' Parts Purchase Request',

                    'Auto Parts Store >>',
                    ' Parts Inventory',
                    ' Parts Usage Request',
                    ' Damaged Parts Processing',
                    ' Auto Parts',

                    'Invoices',

                    'Reports >>',
                    ' Accidents',
                    ' Elog',
                    ' Odometer',
                    ' Maintenance',
                    ' Diagnosis',

                    'Archive >>',
                    ' Car',
                    ' Region',
                    ' Department',
                    ' Zone',

                    'Work Order',
                    'Garage',
                    'Insurance',
                    'Waybills',
                ];

                $permissions_mechanic = [
                    // 'MECHANIC MANAGMENT',
                    'Mechanic Dashboard',
                    'Mechanic Garage',
                ];

                $permissions_driver = [
                    // 'DRIVER MANAGMENT',
                    'Driver Dashboard',
                    'Odometer Manager',
                    'Accident Report',
                    'Elog Report',
                    'Waybill',
                    'Car Requests',
                ];


                //create permissions account
                foreach ($permissions_finance as $key => $value) {
                    Permission::create(['name' => $value, 'module' => 'account']);
                }
                //create permissions fleet
                foreach ($permissions_fleet as $key => $value) {
                    Permission::create(['name' => $value, 'module' => 'admin']);
                }
                //create permissions mechanic 
                foreach ($permissions_mechanic as $key => $value) {
                    Permission::create(['name' => $value, 'module' => 'mechanic']);
                }
                //create permissions mechanic 
                foreach ($permissions_driver as $key => $value) {
                    Permission::create(['name' => $value, 'module' => 'driver']);
                }

                $roleFleet = Role::create(['name' => 'FLEET MANAGER']);
                $roleDriver = Role::create(['name' => 'DRIVER']);
                $roleMechanic = Role::create(['name' => 'MECHANIC']);
                $roleFinance = Role::create(['name' => 'FINANCE']);

                //assign permission to role
                foreach ($permissions_fleet as $key => $perm) {
                    $roleFleet->givePermissionTo($perm);
                }
                foreach ($permissions_finance as $key => $perm) {
                    $roleFinance->givePermissionTo($perm);
                }
                foreach ($permissions_driver as $key => $perm) {
                    $roleDriver->givePermissionTo($perm);
                }
                foreach ($permissions_mechanic as $key => $perm) {
                    $roleMechanic->givePermissionTo($perm);
                }

                //assign users wih super user role
                $user  = User::find(1);
                $user->assignRole($roleFleet->name);

                DB::commit();
                return 'run!';
            }
        } catch (\Exception $ex) {
            Log::info('error :' . $ex);
            DB::rollBack();
        }
    }

    public function ReRun()
    {
        try {
            DB::beginTransaction();

            DB::delete('DELETE FROM role_has_permissions');
            DB::delete('DELETE FROM permissions');
            //Permission::truncate();


            $permissions_finance = [
                // 'ACCOUNTS MANAGEMENT',
                'Account Dashboard',
                'Finder >>',
                ' Finder-Car',
                ' Finder-User',
                'Payments >>',
                ' Payment Requests',
                ' Payment History',
                'Work Orders',
                'FM Invoices',
            ];

            $permissions_fleet = [
                // 'FLEET MANAGEMENT',
                'Admin Dashboard',

                'Settings >>',
                ' Zones',
                ' Regions',
                ' Departments',
                ' Users',
                ' Permissions',
                ' Taxes',
                ' Odometer Setting',

                //'Staff',
                'Service Providers',
                'Vehicle Registration',
                'DVLA Road Worthy',
                'Overdue Odometers',
                'Driver License',

                'Car Requests >>',
                ' Car Requests',
                ' Car Request History',

                'Finance Requests >>',
                ' General Requests',
                ' Parts Purchase Request',

                'Auto Parts Store >>',
                ' Parts Inventory',
                ' Parts Usage Request',
                ' Damaged Parts Processing',
                ' Auto Parts',

                'Invoices',

                'Reports >>',
                ' Accidents',
                ' Elog',
                ' Odometer',
                ' Maintenance',
                ' Diagnosis',

                'Archive >>',
                ' Car',
                ' Region',
                ' Department',
                ' Zone',

                'Work Order',
                'Garage',
                'Insurance',
                'Waybills',
            ];

            $permissions_mechanic = [
                // 'MECHANIC MANAGMENT',
                'Mechanic Dashboard',
                'Mechanic Garage',
            ];

            $permissions_driver = [
                // 'DRIVER MANAGMENT',
                'Driver Dashboard',
                'Odometer Manager',
                'Accident Report',
                'Elog Report',
                'Waybill',
                'Car Requests',
            ];


            //create permissions account
            foreach ($permissions_finance as $key => $value) {
                Permission::create(['name' => $value, 'module' => 'account']);
            }
            //create permissions fleet
            foreach ($permissions_fleet as $key => $value) {
                Permission::create(['name' => $value, 'module' => 'admin']);
            }
            //create permissions mechanic 
            foreach ($permissions_mechanic as $key => $value) {
                Permission::create(['name' => $value, 'module' => 'mechanic']);
            }
            //create permissions mechanic 
            foreach ($permissions_driver as $key => $value) {
                Permission::create(['name' => $value, 'module' => 'driver']);
            }

            $roleFleet = Role::where('name', 'FLEET MANAGER')->first();
            $roleDriver = Role::where('name', 'DRIVER')->first();
            $roleMechanic = Role::where('name', 'MECHANIC')->first();
            $roleFinance = Role::where('name', 'FINANCE')->first();

            /////assign permission to role
            foreach ($permissions_fleet as $key => $perm) {
                $roleFleet->givePermissionTo($perm);
            }
            foreach ($permissions_finance as $key => $perm) {
                $roleFinance->givePermissionTo($perm);
            }
            foreach ($permissions_driver as $key => $perm) {
                $roleDriver->givePermissionTo($perm);
            }
            foreach ($permissions_mechanic as $key => $perm) {
                $roleMechanic->givePermissionTo($perm);
            }

            //assign users wih super user role
            // $user  = User::find(1);
            // $user->assignRole($roleFleet->name);

            DB::commit();
            return 're run!';
        } catch (\Exception $ex) {
            Log::info('error :' . $ex);
            DB::rollBack();
        }
    }
}
