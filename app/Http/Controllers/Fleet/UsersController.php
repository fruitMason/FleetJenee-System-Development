<?php

namespace App\Http\Controllers\Fleet;

use App\DataTables\CarRequestDataTable;
use App\DataTables\DriverOdometerHistoryDataTable;
use App\DataTables\UserDataTable;
use App\DataTables\UserPermissionsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Imports\UserImport;
use App\Models\Department;
use App\Models\User;
use App\Models\Vendor;
use App\Traits\GlobalValueCore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
// use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
    use GlobalValueCore;
    public function showUsers(Request $request, UserDataTable $dataTable)
    {
        $departments = Department::where('is_archived', "0")->orderBy('name')->get();
        $roles = Role::all();
        $vendors = Vendor::all();
        return $dataTable->render('settings.users.index', [
            'departments' => $departments,
            'roles' => $roles,
            'vendors' => $vendors
        ]);
    }

    public function storeUsers(CreateUserRequest $request)
    {
        $data = $request->validated();

        if ($data['type'] == 'MECHANIC') {
            $request->validate([
                'vendor_id' => 'nullable|exists:vendors,id',
            ]);
        }


        // Check if email already exists
        $existingUser = User::where('email', $data['email'])->first();
        if ($existingUser) {
            return back()->with('error', 'Email address already exists.');
        }

        // Generate password and set status
        $password = Str::random(8);
        $data['password'] = bcrypt($password);
        $data['status'] = 'active';

        // Set driver_type only if the user is a DRIVER
        if ($data['type'] === 'DRIVER') {
            $data['driver_type'] = $request->driver_type;
        }

        if ($request->hasFile('file')) {
            // $media_name = $request->file('file')->getClientOriginalName();
            // $name = $attributes['term_id'] . time() . "_event.{$request->image1->extension()}";
            // $image1 = $request->file('image1')->storeAs('up_events', $name, 'public');
            // $image1  =  "/storage/{$image1}";
            // Log::info('event photo saved into storage');


            $path = $request->file('file')->store('/public/uploads/users');
            $data['photo'] = str_replace('public', 'storage', $path);
            Log::info('path :: ' . $path);
            Log::info('path :: ' . $data['photo']);
        }



        // Create the user, including driver_type
        $user = User::create($data);

        // Assign the role to the user
        $role = Role::findById($request->role);
        $user->assignRole($role);

        // Send email with user's credentials
        $email = $user->email;
        $fullName = $user->full_name();
        $loginUrl = route('dashboard'); // Replace with your actual login route
        //Mail::to($email)->send(new UserRegistrationMail($fullName, $email, $password, $loginUrl));
        $emailStatus = "";
        // try {
        //     //  Mail::to($email)->send(new TestMail($fullName, $email, $password, $loginUrl));
        //     // $emailStatus = "email sent";
        // } catch (\Exception $e) {
        //     $emailStatus = "email not sent : " . $e->getMessage();
        //     Log::error('email error' . $e->getMessage());
        // }

        try {
            //Mail::to($email)->send(new TestMail($fullName, $email, $password, $loginUrl));
            $emailStatus =   $emailStatus . " || sms sent";
            GlobalValueCore::SendSMS_ViaHubtelAPI($request->mobile, 'Please+login+to+the+autoSpa+platform+with+the+following+details, Email: ' . $email . ', Password: ' . $password);
        } catch (\Exception $e) {
            $emailStatus = $emailStatus . "|| sms not sent : " . $e->getMessage();
            Log::error('sms-error' . $e->getMessage());
        }


        return back()->with('success', 'User was ADDED successfully! : Username: ' . $fullName . '| email: ' .
            $email . '| password: ' . $password . '| email status : ' . $emailStatus);
    }



    public function storeUsersBulk(Request $request)
    {
        $_user = Auth::user(); // auth()->user();
        if ($request->isMethod('POST')) {
            if ($request->hasFile("file")) {

                $file = $request->file('file');
                try {
                    Excel::import(new UserImport(), $file);
                } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                    $failures = $e->failures();
                    return Redirect::back()->with(['errors' => $e->validator->errors()]);
                }
                return back()->with('success', 'Users have been imported successfully!');
            }
            return back()->withErrors(['msg' => 'Upload a file!']);
        }
    }

    public function userPermissions(UserPermissionsDataTable $dataTable, $user_id, $role_id)
    {
        return $dataTable->with(['user_id' => $user_id, 'role_id' => $role_id])->render('settings.users.permission');
    }

    public function viewUsers(Request $request, CarRequestDataTable $carRequestDataTable, DriverOdometerHistoryDataTable $driverOdometerHistoryDataTable, $user_id)
    {
        $user = User::query()->find($user_id);
        return view('settings.users.view', [
            'carRequestDataTable' => $carRequestDataTable->with('user_id', $user_id)->html(),
            'driverOdometerHistoryDataTable' => $driverOdometerHistoryDataTable->with('user_id', $user_id)->html(),
            'user' => $user
        ]);
    }

    public function getUsers(Request $request)
    {
        $data = User::query()->with(['roles'])->find($request->id);
        return $this->sendSuccessJsonResponse($data);
    }

    public function updateUsers(Request $request)
    {
        $resource = User::query()->find($request->id);
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|unique:users,email,' . $resource->id,
            'password' => 'nullable|string',
            'mobile' => 'nullable',
            'role' => 'nullable',
            'department_id' => 'required',
            'type' => 'required',
            'license_class' => 'nullable|string',
            'license_number' => 'nullable|string',
            'license_expiry' => 'nullable|date',
            'vendor_id' => 'nullable',
            'driver_type' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendFailureJsonResponse($validator->errors());
        }

        $data = $validator->validated();
        $resource->update($data);
        $resource->roles()->detach();
        $role = Role::findById($request->role);
        $resource->assignRole($role);

        return $this->sendSuccessJsonResponse('User was UPDATED successfully!');
    }

    public function deleteUsers(Request $request)
    {
        $resource = User::query()->find($request->id);
        $resource->delete();
        return $this->sendSuccessJsonResponse('User was DELETED successfully!');
    }
}
