<?php

namespace App\Http\Controllers\Driver;

use App\DataTables\DriverOdometerHistoryDataTable;
use App\DataTables\MyCarRequestsDataTable;
use App\DataTables\WaybillDataTable;
use App\Http\Controllers\Controller;
use App\Models\CarRequest;
use App\Models\Car;
use App\Models\OdometerHistory;
use App\Models\User;
use App\Models\Waybill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Notifications;
use App\Traits\GlobalValueCore;
use Illuminate\Support\Facades\Log;

use function back;

class DriverManagerController extends Controller
{
    use GlobalValueCore;
    public function showOdometer(Request $request, DriverOdometerHistoryDataTable $dataTable)
    {
        return $dataTable->render('drivers.odometer');
    }

    public function storeOdometer(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'car_id' => 'required|exists:cars,id',
            'new_value' => 'required|regex:/^\d+(\.\d{1,2})?$/'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->with(['errors' => $validator->errors()]);
        }

        $car = Car::query()->find($request->car_id);

        $data = $validator->validated();
        $data['old_value'] = $car->odometer;

        $create = OdometerHistory::query()->create($data);
        $car->update(['odometer' => $create->new_value]);

        $odometerLevel = $car->odometer_level;

        // Check if odometer value exceeds 10,000
        if ($create->new_value > $odometerLevel) {
            // Send notification to the driver
            Notifications::createNotification(
                $request->user_id,  // Notifying the specific user (driver)
                'Car Maintenance Status Updated',
                'The car with number ' . $car->car_number . ' has exceeded ' . number_format($odometerLevel) . ' km. Current value is ' . number_format($create->new_value) . 'km. Please schedule maintenance.'
            );

            Notifications::createNotification(
                '1',  // Notifiy also fleet manager
                'Car Maintenance Status Updated',
                'The car with number ' . $car->car_number . ' has exceeded ' . number_format($odometerLevel) . ' km. Current value is ' . number_format($create->new_value) . 'km. Please schedule maintenance.'
            );




            $message = 'The car with number ' . $car->car_number . ' has exceeded ' . number_format($odometerLevel)  . ' km. Current value is '.number_format($create->new_value) .' km. Please schedule maintenance.';
            $naloMes = str_replace(' ', '+', $message);
            $mobile = User::find(1)->mobile;

            if ($car->odometer_status == 'Active') {
                try {
                    $SMSStatus =    "SMS Send To " . $mobile;
                    GlobalValueCore::SendSMS_ViaHubtelAPI($mobile, $naloMes);
                } catch (\Exception $e) {
                    $SMSStatus = $SMSStatus . "|| sms not sent : " . $e->getMessage();
                    Log::error('sms-error' . $e->getMessage());
                }
            }
            $car->update(['odometer_status' => 'Overdue']);
        }
        return back()->with('success', 'Odometer was ADDED successfully!');
    }

    public function showCarRequests(Request $request, MyCarRequestsDataTable $dataTable)
    {
        return $dataTable->render('drivers.requests.car');
    }

    public function approveRequest(Request $request)
    {
        // Find the car request by ID
        $carRequest = CarRequest::query()->find($request->approve_car_request_id);

        if (!$carRequest) {
            return back()->withErrors(['error' => 'Car request not found.']);
        }

        // Check if the car request is assigned to the logged-in user
        if ($carRequest->user_id !== $request->user()->id()) {
            return back()->withErrors(['error' => 'You are not authorized to approve this request.']);
        }

        // Update the car request status to approved
        $carRequest->status = 'approved';
        $carRequest->save();

        // Prepare the notification message
        $message = 'You have approved the car request for ' . $carRequest->date_needed . '. Thank you!';

        // Send notifications
        Notifications::createNotification($carRequest->user_id, 'Car Request Approved', $message);

        return back()->with('success', 'Car Request was APPROVED successfully!');
    }

    public function rejectRequest(Request $request)
    {
        // Find the car request by ID
        $carRequest = CarRequest::query()->find($request->id);

        if (!$carRequest) {
            return back()->withErrors(['error' => 'Car request not found.']);
        }

        // Check if the car request is assigned to the logged-in user
        if ($carRequest->user_id !== $request->user()->id()) {
            return back()->withErrors(['error' => 'You are not authorized to reject this request.']);
        }

        // Update the car request status to rejected
        $carRequest->status = 'rejected';
        $carRequest->save();

        return back()->with('success', 'Car Request was REJECTED successfully!');
    }


    public function showWaybill(Request $request, WaybillDataTable $dataTable)
    {
        return $dataTable->render('drivers.waybill.index');
    }

    public function respondWaybill(Request $request)
    {
        $id = $request->id;
        $action_type = $request->action_type;
        $comment = $request->reason;

        $data = Waybill::query()->find($id);

        if (strtolower($action_type) == 'accept') {
            $data->status = 'ongoing';
        } elseif (strtolower($action_type) == 'reject') {
            $data->status = 'rejected';
        } elseif (strtolower($action_type) == 'complete') {
            $data->status = 'completed';
        }
        $data->comment = $comment;
        $data->save();
        return $this->sendSuccessJsonResponse('Waybill Action Successful');
    }
}
