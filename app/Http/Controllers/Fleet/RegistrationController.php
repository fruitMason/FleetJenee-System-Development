<?php

namespace App\Http\Controllers\Fleet;

use App\DataTables\CarRegistrationDataTable;
use App\DataTables\DriverLicenseDataTable;
use App\DataTables\ArchivedCarRegistrationDataTable;
use App\DataTables\DvlaRoadWorthinessDataTable;
use App\DataTables\InsuranceDataTable;
use App\DataTables\OdometerHistoryDataTable;
use App\DataTables\OverdueOdometerEntryDataTable;
use App\DataTables\OverdueOdometerReportDataTable;
use App\Helpers\Notifications;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCarRequest;
use App\Imports\CarImport;
use App\Models\Car;
use App\Models\CarMaintenance;
use App\Models\CarMaintenanceNote;
use App\Models\Department;
use App\Models\OdometerHistory;
use App\Models\OdometerSetting;
use App\Models\Region;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class RegistrationController extends Controller
{
    public function index(Request $request, CarRegistrationDataTable $dataTable)
    {
        $users = User::all();
        $cars = Car::all();
        $mechanics = User::isMechanic()->get();
        return $dataTable->render('vehicle.index', [
            'users' => $users,
            'cars' => $cars,
            'mechanics' => $mechanics
        ]);
    }

    public function archiveCars(Request $request)
    {
        $resource = Car::query()->find($request->id);
        if (!$resource) {
            return $this->sendFailureJsonResponse('Car not found.');
        }

        $resource->is_archived = true;
        $resource->save();

        return $this->sendSuccessJsonResponse('Car was archived successfully!');
    }

    public function archivedCars(Request $request, ArchivedCarRegistrationDataTable $dataTable)
    {
        return $dataTable->render('vehicle.archived_cars');
    }
    public function unarchiveCars(Request $request)
    {
        $resource = Car::query()->find($request->id);
        if (!$resource) {
            return $this->sendFailureJsonResponse('Car not found.');
        }

        $resource->is_archived = false; // Set to false to unarchive
        $resource->save();

        return $this->sendSuccessJsonResponse('Car was unarchived successfully!');
    }



    public function dvlaRoadWorthiness(Request $request, DvlaRoadWorthinessDataTable $dataTable)
    {
        return $dataTable->render('vehicle.dvla_road_worthiness');
    }

    public function overdueOdometers(Request $request, OverdueOdometerEntryDataTable $dataTable)
    {
        return $dataTable->render('vehicle.odometer.overdue');
    }

    public function overdueOdometersWorkOrder(Car $car)
    {
        $mechanics = User::isMechanic()->get();
        return view('vehicle.odometer.overdue-maintain', [
            'car' => $car,
            'mechanics' => $mechanics
        ]);
    }

    public function overdueReportOdometers(Request $request, OverdueOdometerReportDataTable $dataTable)
    {
        $departments = Department::all();
        $regions = Region::all();
        return $dataTable->render('vehicle.odometer.report', [
            'departments' => $departments, // Fix the typo here
            'regions' => $regions // Fix the typo here
        ]);
    }


    public function showOdometerHistory($car_id, OdometerHistoryDataTable $dataTable)
    {

        // Fetch the car to display its details
        $car = Car::with('odometerHistories')->findOrFail($car_id);

        Log::info($car);
        // Set the car_id for the DataTable
        $dataTable->setCarId($car_id);

        // Render the OdometerHistory DataTable
        return $dataTable->render('vehicle.odometer.history', compact('car'));
    }





    public function insurance(Request $request, InsuranceDataTable $dataTable)
    {
        return $dataTable->render('vehicle.insurance');
    }

    public function driverLicense(Request $request, DriverLicenseDataTable $dataTable)
    {
        return $dataTable->render('vehicle.driver_license', [
            'filter' => $request->input('filter')
        ]);
    }

    public function store(CreateCarRequest $request)
    {
        $data = $request->validated();


        $data['odometer_level'] = OdometerSetting::find(1)->value;
        $data['odometer_status'] = 'Active';
        $data['created_by'] = $request->user()->id;

        if ($data['user_id'] > 0) {
            $data['car_group'] = 'assigned';
        }

        $create = Car::query()->create($data);
        if (!is_null($create->user)) {
            $create->odometerHistory()->create([
                'user_id' => $create->user_id,
                'old_value' => $create->odometer,
                'new_value' => $create->odometer,
            ]);
        }

        return back()->with('success', 'Car was ADDED successfully!');
    }

    public function storeBulk(Request $request)
    {
        $_user = Auth::user();
        if ($request->isMethod('POST')) {
            if ($request->hasFile("file")) {

                $file = $request->file('file');
                try {
                    Excel::import(new CarImport(), $file);
                } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                    $failures = $e->failures();
                    //                    return redirect()->back()->with(compact('failures'));
                    return Redirect::back()->with(['errors' => $e->validator->errors()]);
                }

                //                $_user->log("IMPORTED Cars Successfully: ");
                //                request()->session()->flash('success', 'Cars have been imported successfully!');
                return back()->with('success', 'Cars have been imported successfully!');
            }
            return back()->withErrors(['msg' => 'Upload a file!']);
        }
    }

    public function maintain(Request $request)
    {


        $car = Car::query()->find($request->car_id);

        //check if car work order has been raised
        if (DB::table('car_maintenances')->where('fin_status', 'Pending')->where('car_id', $request->car_id)
            ->exists()
        ) {
            if ($request->overdue) {
                return back()->with('error', 'There is a pending work order for finance authorization on selected car !');
            } else {
                return $this->sendFailureJsonResponse('There is a pending work order for finance authorization on selected car !');
            }
        }

        Log::info($car);
        Log::info($car->isInMaintenance());
        if ($car->isInMaintenance()) {
            if ($request->overdue) {
                return back()->with('error', 'Car Already in Maintenance');
            } else {
                return $this->sendFailureJsonResponse('Car Already in Maintenance');
            }
        }



        $validator = Validator::make($request->all(), [
            'car_id' => 'required',
            'type' => 'required',
            'mechanic_id' => 'required|exists:users,id',
            'comment' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'overdue' => 'nullable',
            'odometer' => 'nullable'
        ]);


        //adding new fields
        $data = $validator->validated();
        $data['fin_status'] = 'pending';
        $data['fin_date'] = Carbon::now();
        $data['fin_user'] = '';
        $data['fin_comment'] = '';
        $data['user_id'] = $request->user()->id;
        $data['normal_overdue'] = $request->overdue ?? 'Normal';


        $car_maintenance = CarMaintenance::query()->create($data);

        if ($request->odometer > 0) {
            $oldVal = $car->odometer;

            $create = OdometerHistory::query()->create([
                'car_id' => $car->id,
                'new_value' => $request->odometer,
                'old_value' =>  $oldVal,
                'created_at' => now(),
                'user_id' => $request->user()->id
            ]);
            $car->odometer = $request->odometer;
            $car->save();
        }



        if ($request->overdue) {
            return back()->with('success', 'Car maintenance work order raised, pending authorization from finance');
        }
        return response()->json(['code' => 200, 'message' => 'Car work order raised, pending authorization from finance', 'url' => 'NO']);
    }

    public function viewMaintenance(Request $request, $maintenance_id)
    {
        $orderDetail = CarMaintenance::with(['car', 'mechanic'])->find($maintenance_id);
        if (!$orderDetail) {
            return redirect()->route('fleet.vehicle.maintenance')->with('error', 'Maintenance record not found.');
        }
        $orderNotes = CarMaintenanceNote::with('media')->where('car_maintenance_id', $maintenance_id)->orderByDesc('created_at')->get();

        $finUser = $orderDetail->fin_user ? User::find($orderDetail->fin_user)->full_name() : 'N/A';

        return  view('shared.work_order_detail', [
            'maintenance' => $orderDetail,
            'crumbHeading' => 'Fleet Maintenance',
            'prevUrl' => 'fleet.vehicle.maintenance',
            'notes' => $orderNotes,
            'finUser' =>  $finUser
        ]);
    }

    public function deleteMaintenance(Request $request, $maintenance_id)
    {
        $orderDetail = CarMaintenance::find($maintenance_id);


        $orderDetail->delete();
        return redirect()->route('fleet.vehicle.maintenance')->with('sucess', 'Work order deleted sucessfully!');
        //fleet.vehicle.maintenance.view
    }
    public function completeMaintenance(Request $request, $maintenance_id)
    {
        $garage = CarMaintenance::find($maintenance_id);

        $garage->status = 'completed';
        $garage->save();


        $note = CarMaintenanceNote::create([
            'car_maintenance_id' => $maintenance_id,
            'status' => 'Work Completed',
            'receipt_comment' => 'Work completed - By Fleet Manager',
            'receipt_date' => date('Y-m-d'),
            'user_email' => $request->user()->id,
        ]);

        $garage->car()->update(['status' => 'active']);


        // Notify fleet management about the status update
        Notifications::createNotification(
            '1',
            'Car Maintenance Status Updated',
            'The car maintenance status for car number ' . $garage->car->car_number . ' has been updated to completed.'
        );



        return back()->with('sucess', 'Work order completed sucessfully - admin !');
    }

    public function viewDiagnosisOnly(Request $request, $maintenance_id)
    {

        $orderNotes = CarMaintenanceNote::with('media')->where('car_maintenance_id', $maintenance_id)->orderByDesc('created_at')->get();



        return  view('shared.work_order_mech_history', [

            'crumbHeading' => 'Fleet Maintenance',
            'prevUrl' => 'fleet.vehicle.garage',
            'notes' => $orderNotes,

        ]);
    }

    // public function odometerHistory(Request $request, OdometerHistoryDataTable $dataTable, $car)
    // {
    //     $car = Car::query()->find($car);
    //     abort_if(is_null($car->user), 403);
    //     return $dataTable->with(['car_id' => $car->id])->render('vehicle.odometer_history', [
    //         'car' => $car
    //     ]);
    // }

    public function getCars(Request $request)
    {
        $data = Car::query()->with(['user'])->find($request->id);
        return $this->sendSuccessJsonResponse($data);
    }

    public function updateCars(Request $request)
    {
        // Fetch the car resource
        $resource = Car::query()->find($request->id);

        // Check if the car exists
        if (!$resource) {
            return $this->sendFailureJsonResponse('Car not found.');
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'model' => 'required|string|max:255',
            'year' => 'required',
            'body_style' => 'nullable|string|max:255',
            'trim_level' => 'nullable',
            'color' => 'nullable|string|max:255',
            'car_number' => 'required|unique:cars,car_number,' . $resource->id,
            'chassis' => 'nullable|string',
            'odometer' => 'required|string|max:255',
            'car_group' => 'required|in:pool,assigned',
            'engine_capacity' => 'nullable|string',
            'fuel_type' => 'nullable|string',
            'tank_size' => 'nullable|string',
            'car_cost' => 'nullable|numeric',
            'purchase_date' => 'nullable|date',
            'condition' => 'nullable|string',
            'dvla_code' => 'nullable|string',
            'dvla_expiry' => 'nullable|date',
            'road_worthy_start_date' => 'required|date',
            'road_worthy_expiry_date' => 'required|date',
            'status' => 'nullable|string',
            'comment' => 'nullable|string',
            'insurance_start_date' => 'nullable|date',
            'insurance_expiry' => 'nullable|date',
            'user_id' => 'nullable|numeric'
        ]);

        // Handle validation failure
        if ($validator->fails()) {
            return $this->sendFailureJsonResponse($validator->errors());
        }

        // Update the resource with validated data
        $data = $validator->validated();
        $userid = $data['user_id'];
        if ($userid) {
            Log::info($userid.'--user id');
            $data['car_group'] = 'assigned';
        }

        $resource->update($data);

        return $this->sendSuccessJsonResponse('Car was UPDATED successfully!');
    }


    public function deleteCars(Request $request)
    {
        $resource = Car::query()->find($request->id);
        $resource->delete();
        return $this->sendSuccessJsonResponse('Car was DELETED successfully!');
    }
}
