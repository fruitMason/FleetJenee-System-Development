<?php

namespace App\Http\Controllers\Account;

use App\DataTables\Account\AccountsWorkOrderDataTable;
use App\Helpers\Notifications;
use App\Http\Controllers\Controller;
use App\Models\CarMaintenance;
use App\Models\CarMaintenanceNote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccountWorkOrdersController extends Controller
{

    public function workOrders(Request $request, AccountsWorkOrderDataTable $dataTable)
    {

        $orders = CarMaintenance::with(['car', 'mechanic'])->where('status', 'pending')->orderByDesc('start_date')->get();

        return $dataTable->render('accounts.work-orders', [
            'orders' => $orders
        ]);
    }

    public function workOrderViewAuth($maintenance_id)
    {
        $maintenance = CarMaintenance::with(['car', 'mechanic'])->find($maintenance_id);
        if (!$maintenance) {
            return redirect()->route('fleet.vehicle.maintenance')->with('error', 'Maintenance record not found.');
        }

        $orderNotes = CarMaintenanceNote::select(
            'car_maintenance_notes.receipt_date',
            'car_maintenance_notes.receipt_comment',
            'car_maintenance_notes.status',
            'u.first_name',
            'u.middle_name',
            'u.last_name',
            DB::raw("(select path from car_maintenance_media where car_maintenance_note_id = car_maintenance_notes.id) as media")
        )
            ->join('users as u', 'car_maintenance_notes.user_email', '=', 'u.id')
            ->where('car_maintenance_id', $maintenance->id)->orderByDesc('car_maintenance_notes.created_at')->get();

        return view('accounts.work-order-auth', [
            'maintenance' => $maintenance,
            'car' => $maintenance->car,
            'mechanic' => $maintenance->mechanic,
            'notes' => $orderNotes,
            'fin_status' => [
                (object)['value' => 'approved', 'text' => 'Approve'],
                (object)['value' => 'declined', 'text' => 'Decline']
            ]
        ]);
    }

    public function workOrderAuth(Request $request)
    {

        //{"id":"4","_token":"6Hk3IDlRqYsuxBbrbIfaUCbUzawsktJX5WFoM0Wa","":"aaaaa","":"approved"}
        $attributes = $request->validate([
            'id' => 'required',
            'fin_comment' => 'required',
            'fin_status' => 'required',
        ]);
        $order =  CarMaintenance::find($request->id);
        $order->fin_comment = $request->fin_comment;
        $order->fin_status = $request->fin_status;
        $order->fin_user = $request->user()->id;
        $order->fin_date = Carbon::now();

        $order->save();

        if ($request->fin_status == 'approved') {
            //Log::info('approved-hit notyi');
            Notifications::createNotification(
                $order->mechanic_id,
                'Work Order Authorized',
                'New work order for ' . $order->car->model . '(' . $order->car->car_number . ') has been authorized. Please proceed to work on it'
            );
        }

        return redirect()->back()->with('success', 'Work order successfully ' . $request->fin_status);
    }
}
