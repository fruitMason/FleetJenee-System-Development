<?php

namespace App\Http\Controllers\Driver;

use App\DataTables\CarRegistrationDataTable;
use App\DataTables\CarRequestDataTable;
use App\DataTables\MyCarRequestsDataTable;
use App\Helpers\Notifications;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCarRequest;
use App\Models\Car;
use App\Models\CarRequest;
use App\Models\User;
use App\Traits\GlobalValueCore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RequestController extends Controller
{
    use GlobalValueCore;
    public function index(Request $request, CarRequestDataTable $dataTable)
    {
        // $users = User::all();
        $users = User::where('driver_type', 'EMPLOYED_DRIVER')->get();
        // $cars = Car::all();

        // Fetch only pool cars
        $cars = Car::where('car_group', 'pool')->get();

        // Count pool cars
        $poolCarCount = Car::where('car_group', 'pool')->count();

        // Most requested cars
        $mostRequestedCars = CarRequest::select('user_id', DB::raw('count(*) as total'))
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->take(5) // Limit to top 5 most requested users
            ->get();

        // Prepare data for the chart
        $carIds = $mostRequestedCars->pluck('car_id'); // Get an array of car IDs

        // Load the cars in a single query
        $loadedCars = Car::whereIn('id', $carIds)->get()->keyBy('id'); // Keyed by car ID for easy access

        // Map the car names based on the most requested cars
        $carNames = $mostRequestedCars->map(function ($request) use ($loadedCars) {
            return isset($loadedCars[$request->car_id]) ? $loadedCars[$request->car_id]->name : 'Unknown Car'; // Adjust based on your Car model
        });

        $requestCounts = $mostRequestedCars->pluck('total');

        // Get all users with the role of fleet_management
        $fleetManagers = User::where('role', 'fleet_management')->get();

        // Get today's date
        $today = now();

        // Get car requests to check for notifications
        $carRequests = CarRequest::with('user')->where('status', 'pending')->get();

        foreach ($carRequests as $request) {
            // Convert date_needed to a Carbon instance
            $dateNeeded = Carbon::parse($request->date_needed);

            // Check if the date_needed is 2 days away or due
            if ($dateNeeded->isToday() || $dateNeeded->isTomorrow() || ($dateNeeded->diffInDays($today) == 2)) {
                foreach ($fleetManagers as $fleetManager) {
                    Notifications::createNotification(
                        $fleetManager->id,  // Notifying each fleet manager
                        'Car Request Due Soon',
                        'The car request for ' . ($request->user ? $request->user->full_name() : 'unknown user') .
                            ' is due in 2 days (Date Needed: ' . $dateNeeded->format('D, d F Y') . '). Please review it.'
                    );
                }
            }

            // Check if the date_needed is overdue
            if ($dateNeeded < $today) {
                foreach ($fleetManagers as $fleetManager) {
                    Notifications::createNotification(
                        $fleetManager->id,  // Notifying each fleet manager
                        'Car Request Overdue',
                        'The car request for ' . ($request->user ? $request->user->full_name() : 'unknown user') .
                            ' is overdue (Date Needed: ' . $dateNeeded->format('D, d F Y') . '). Please approve or reject it.'
                    );
                }
            }
        }

        return $dataTable->render('vehicle.requests.index', [
            'users' => $users,
            'cars' => $cars,
            'poolCarCount' => $poolCarCount,
            'carRequests' => $carRequests,
            'carNames' => $carNames,
            'requestCounts' => $requestCounts,
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'date_needed' => 'required|date',
            'return_date' => 'required|date',
            'request_reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->with(['errors' => $validator->errors()]);
        }

        $data = $validator->validated();
        $data['status'] = 'pending';
        CarRequest::query()->create($data);

        return back()->with('success', 'Car Request was ADDED successfully!');
    }

    public function myRequests(Request $request, MyCarRequestsDataTable $dataTable)
    {
        $users = User::all();
        return $dataTable->render('vehicle.requests.my', [
            'users' => $users
        ]);
    }

    public function approve(Request $request)
    {

        //return $request;
        $validator = Validator::make($request->all(), [
            'approve_car_request_id' => 'required',
            'approve_car_user_id' => 'required',
            'car_id' => 'required',
            'status' => 'required|string',
            'comment' => 'nullable',
            'user_id' => 'required'
        ]);


        if (CarRequest::where('user_id', $request->user_id)->where('trip_status', '!=', 'ended')->where('status', 'approved')->exists()) {
            return Redirect::back()->withErrors(['error' => 'Selected user already has an unended trip!']);
        }

        if ($validator->fails()) {
            return Redirect::back()->with(['errors' => $validator->errors()]);
        }
        $data = $validator->validated();

        // Find the car request by ID
        $carRequest = CarRequest::query()->find($request->approve_car_request_id);

        if (!$carRequest) {
            return back()->withErrors(['error' => 'Car request not found.']);
        }

        // Fetch the requested car ensuring it's a pool car
        $car = Car::query()
            ->where('car_group', 'pool') // Ensure filtering for pool cars
            ->where('id', $request->car_id) // Match the requested car ID
            ->first();
        $mobile =  $carRequest->user->mobile ?? 'NA';

        if (!$car) {
            return back()->withErrors(['error' => 'The requested car is not a pool car or does not exist.']);
        }

        // Update the car's user ID
        $car->update(['user_id' => $request->user_id, 'car_group' => 'assigned']);

        // Update the car request status to approved
        $carRequest->status = $data['status'];
        $carRequest->auth_by = $request->user()->id;
        $carRequest->auth_comment = $data['comment'];
        $carRequest->car_id = $data['car_id'];
        $carRequest->user_id = $data['approve_car_user_id'];
        $carRequest->save();



        // Prepare the notification message
        $message = 'Congratulations, your car request has been approved and ' . $car->model . ' (' . $car->car_number . ') has been assigned to you. Thank you!';

        $naloMessaage = str_replace(" ", "+", $message);
        // Send notifications
        Notifications::createNotification($carRequest->user_id, 'Car Request Approved', $message);
        if ($carRequest->user_id != $request->user_id)
            Notifications::createNotification($request->user_id, 'Car Request Approved', $message);
        try {
            GlobalValueCore::SendSMS_ViaHubtelAPI($mobile, $naloMessaage);
        } catch (\Exception $e) {
            Log::error('sms-error' . $e->getMessage());
        }


        return back()->with('success', 'Car Request was APPROVED successfully!');
    }


    public function reject(Request $request)
    {
        $carRequest = CarRequest::query()->find($request->id);

        if (!$carRequest) {
            return back()->withErrors(['error' => 'Car request not found.']);
        }

        $carRequest->status = 'rejected';
        $carRequest->save();

        return $this->sendSuccessJsonResponse('Car Request was REJECTED successfully!');
    }
}
