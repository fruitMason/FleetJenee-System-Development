<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Region;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ##create permissions
        // $role = Permission::query()->insertOrIgnore([
        //     [
        //         'name' => 'fleet_management',
        //         'guard_name' => 'web'
        //     ],
        //     [
        //         'name' => 'settings_management',
        //         'guard_name' => 'web'
        //     ],
        //     [
        //         'name' => 'mechanic_management',
        //         'guard_name' => 'web'
        //     ],
        //     [
        //         'name' => 'driver_management',
        //         'guard_name' => 'web'
        //     ],
        //     [
        //         'name' => 'account_management',
        //         'guard_name' => 'web'
        //     ]
        // ]);

        // ##create roles
        // $role = Role::query()->insertOrIgnore([
        //     [
        //         'name' => 'FLEET MANAGER',
        //         'guard_name' => 'web'
        //     ],
        //     [
        //         'name' => 'DRIVER',
        //         'guard_name' => 'web'
        //     ],
        //     [
        //         'name' => 'MECHANIC',
        //         'guard_name' => 'web'
        //     ],
        //     [
        //         'name' => 'FINANCE',
        //         'guard_name' => 'web'
        //     ]
        // ]);
        // //
        // ##syncing for fleet manager
        // $role = Role::findByName('FLEET MANAGER');
        // $permissions = Permission::query()->whereIn('name', ['fleet_management', 'settings_management'])->get();
        // $role->syncPermissions($permissions);

        // ##syncing for driver
        // $role = Role::findByName('DRIVER');
        // $permissions = Permission::query()->whereIn('name', ['driver_management'])->get();
        // $role->syncPermissions($permissions);

        // ##syncing for mechanic
        // $role = Role::findByName('MECHANIC');
        // $permissions = Permission::query()->whereIn('name', ['mechanic_management'])->get();
        // $role->syncPermissions($permissions);

        // ##syncing for mechanic
        // $role = Role::findByName('FINANCE');
        // $permissions = Permission::query()->whereIn('name', ['account_management'])->get();
        // $role->syncPermissions($permissions);

        #default department for fleet manager

        Region::create([
            'name' => 'HQ - Region',
            'description' => 'Fleet HQ Region',
            'status' => 'active',
            'is_archived' => '0'
        ]);

        Sector::create([
            'name' => 'HQ - Zone',
            'description' => 'Fleet HQ Zone',
            'status' => 'active',
            'is_archived' => '0',
            'region_id' => '1',
        ]);

       

        Department::create([
            'name' => 'Transport',
            'description' => 'Fleet HQ Department',
            'status' => 'active',
            'region_id' => '1',
            'is_archived' => '0'
        ]);



        ##create fleet manager and assign role
        $user = User::query()->create([
            'first_name' => 'Abena',
            'middle_name' => 'A.',
            'last_name' => 'Sowah',
            'email' => 'niiokosowah@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('passWORD@fleet.new'),
            'mobile' => '233209366367',
            'status' => 'Active',
            'department_id' => '1',
            'gender' => 'Male',
            'type' => 'ADMINISTRATOR',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        //$role = Role::findByName('FLEET MANAGER');
        //$user->assignRole($role);
    }
}



// user: niiokosowah@gmail.com
// pssword: passWORD@fleet.new