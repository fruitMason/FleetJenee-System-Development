<?php

namespace App\Http\Controllers\Driver;

use App\DataTables\AutoPartRequestsDataTable;
use App\Http\Controllers\Controller;
use App\Helpers\Notifications;
use App\Models\AutoPart;
use App\Models\AutoPartRequest;
use App\Traits\GlobalValueCore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

use function back;

class AutoPartRequestController extends Controller
{
    use GlobalValueCore;
    public function index(AutoPartRequestsDataTable $dataTable)
    {
        $autoParts = AutoPart::orderBy('name')->get();
        return $dataTable->render('drivers.requests.auto-part', ['autoParts' => $autoParts]);
    }

    // public function create(){
    //     $autoParts
    // }


    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'auto_part_id' => 'required|exists:auto_parts,id',
            'qnt_requested' => 'required|numeric|min:1',
            'reason_for_request' => 'required',
            'car_id'=>'required|exists:cars,id'

        ]);

        if ($validator->fails()) {
            return Redirect::back()->with(['errors' => $validator->errors()]);
        }

        $data = $validator->validated();
        $data['request_type'] = 'Driver Request';
        $data['qnt_approved'] = 0;
        $data['status'] = 'pending';
        $data['auth_by'] = '0';
        $data['reason_for_decline'] = '';



        $part = AutoPartRequest::create($data);

        Notifications::createNotification(
            '1',  // Notifiy also fleet manager
            'Auto Part Usage Request',
            'New auto part (' . $part->auto_part->name . ') usage request submitted by ' . $request->user()->full_name()
        );


        return back()->with('success', 'Auto part usage request ADDED successfully!');
    }

    public function destroy(AutoPartRequest $auto_part_request)
    {
        $auto_part_request->delete();
        return back()->with('success', 'Auto part usage request DELETED successfully!');
    }

    // public function showCarRequests(Request $request, MyCarRequestsDataTable $dataTable)
    // {
    //     return $dataTable->render('drivers.requests.car');
    // }

    // public function approveRequest(Request $request)
    // {
    //     // Find the car request by ID
    //     $carRequest = CarRequest::query()->find($request->approve_car_request_id);

    //     if (!$carRequest) {
    //         return back()->withErrors(['error' => 'Car request not found.']);
    //     }

    //     // Check if the car request is assigned to the logged-in user
    //     if ($carRequest->user_id !== $request->user()->id()) {
    //         return back()->withErrors(['error' => 'You are not authorized to approve this request.']);
    //     }

    //     // Update the car request status to approved
    //     $carRequest->status = 'approved';
    //     $carRequest->save();

    //     // Prepare the notification message
    //     $message = 'You have approved the car request for ' . $carRequest->date_needed . '. Thank you!';

    //     // Send notifications
    //     Notifications::createNotification($carRequest->user_id, 'Car Request Approved', $message);

    //     return back()->with('success', 'Car Request was APPROVED successfully!');
    // }

    // public function rejectRequest(Request $request)
    // {
    //     // Find the car request by ID
    //     $carRequest = CarRequest::query()->find($request->id);

    //     if (!$carRequest) {
    //         return back()->withErrors(['error' => 'Car request not found.']);
    //     }

    //     // Check if the car request is assigned to the logged-in user
    //     if ($carRequest->user_id !== $request->user()->id()) {
    //         return back()->withErrors(['error' => 'You are not authorized to reject this request.']);
    //     }

    //     // Update the car request status to rejected
    //     $carRequest->status = 'rejected';
    //     $carRequest->save();

    //     return back()->with('success', 'Car Request was REJECTED successfully!');
    // }


    // public function showWaybill(Request $request, WaybillDataTable $dataTable)
    // {
    //     return $dataTable->render('drivers.waybill.index');
    // }

    // public function respondWaybill(Request $request)
    // {
    //     $id = $request->id;
    //     $action_type = $request->action_type;
    //     $comment = $request->reason;

    //     $data = Waybill::query()->find($id);

    //     if (strtolower($action_type) == 'accept') {
    //         $data->status = 'ongoing';
    //     } elseif (strtolower($action_type) == 'reject') {
    //         $data->status = 'rejected';
    //     } elseif (strtolower($action_type) == 'complete') {
    //         $data->status = 'completed';
    //     }
    //     $data->comment = $comment;
    //     $data->save();
    //     return $this->sendSuccessJsonResponse('Waybill Action Successful');
    // }
}
