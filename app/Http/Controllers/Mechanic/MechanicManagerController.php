<?php

namespace App\Http\Controllers\Mechanic;

use App\DataTables\GarageCompletionDataTable;
use App\DataTables\GarageDataTable;
use App\DataTables\GarageDiagnosisDataTable;
use App\DataTables\GarageReceiptDataTable;
use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarMaintenance;
use App\Models\Tax;
use App\Models\Vendor;
use App\Helpers\Notifications;
use App\Models\CarMaintenanceNote;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class MechanicManagerController extends Controller
{
    public function showGarage(Request $request, GarageDataTable $dataTable)
    {
        $cars = Car::all();
        return $dataTable->render('mechanics.garage.index', [
            'cars' => $cars
        ]);
    }

    public function showOrderDetail($id)
    {
        $orderDetail = CarMaintenance::with(['car', 'mechanic'])->find($id);
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
            ->where('car_maintenance_id', $id)->orderByDesc('car_maintenance_notes.created_at')->get();
        if (!$orderDetail) {
            return redirect()->route('mechanic.garage')->with('error', 'Work order record not found.');
        }
        return  view('shared.work_order_detail', [
            'maintenance' => $orderDetail,
            'crumbHeading' => 'Garage',
            'prevUrl' => 'mechanic.garage',
            'notes' => $orderNotes
        ]);
    }



    public function confirmReceipt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receipt_comment' => 'required',
            'car_id' => 'required|exists:cars,id',
            'id' => 'required|exists:car_maintenances,id',
            'receipt_date' => 'required|date',
            'file' => 'nullable'
        ]);



        if ($validator->fails()) {
            return Redirect::back()->with(['errors' => $validator->errors()]);
        }

        $data = $validator->validated();
        $garage = CarMaintenance::find($data['id']);

        if ($garage->status != 'pending') {
            return back()->with('error', 'Car Receipt could not be ADDED successfully!');
        }

        $garage->status = 'ongoing';
        $garage->save();
        $garage->car()->update(['status' => 'in_repairs']);

        //insert into car_maintenance notes
        $note = CarMaintenanceNote::create([
            'car_maintenance_id' => $data['id'],
            'status' => 'Vehicle Received',
            'receipt_comment' => $data['receipt_comment'],
            'receipt_date' => $data['receipt_date'],
            'user_email' => $request->user()->email,
        ]);


        if ($request->hasFile('file')) {
            $media_name = $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->store('/public/uploads/car/garage');

            $garage->media()->create([
                'name' => 'car_' . $garage->car->car_number . '_receipt_' . $media_name,
                'description' => 'Receipt of car on ' . Carbon::parse($garage->receipt_date)->format('D, d F Y'),
                'path' => $path,
                'car_maintenance_note_id' => $note->id
            ]);
        }




        // Notify fleet management about the status update
        Notifications::createNotification(
            '1',  // Assuming you are not notifying a specific user
            'Car Maintenance Status Updated',
            'The car maintenance status for car number ' . $garage->car->car_number . ' has been updated to ongoing.'
        );

        return back()->with('success', 'Car Receipt was ADDED successfully!');
    }

    public function showGarageConfirmReceipt(Request $request, GarageReceiptDataTable $dataTable, $id)
    {
        return $dataTable->with(['id' => $id, 'status' => 'ongoing'])->render('mechanics.garage.view_confirm');
    }

    public function showGarageConfirmDiagnosis(Request $request, GarageDiagnosisDataTable $dataTable, $id)
    {
        return $dataTable->with(['id' => $id, 'status' => 'diagnosed'])->render('mechanics.garage.view_diagnosis');
    }

    public function showGarageCompletedReceipt(Request $request, GarageCompletionDataTable $dataTable, $id)

    {

        $invoice = Invoice::where('car_maintenance_id', $id)->first();

        $payment = Payment::select('payments.payment_date', 'payments.amount_paid', 'payments.payment_mode', 'payments.payment_reference','payments.narration')
            ->join('payment_requests as pay_req', 'pay_req.id', '=', 'payments.payment_request_id')
            ->where('pay_req.invoice_no', $invoice->id)->get();;
        return $dataTable->with(['id' => $id, 'status' => 'completed'])->render('mechanics.garage.view_completion', [
            'invoice' =>   $invoice,
            'payment' => $payment
        ]);
    }

    public function showCreateInvoice(Request $request, $id)
    {
        $car_maintenance = CarMaintenance::query()->find($id);
        if ($car_maintenance->invoice)
            return back()->with('error', 'Invoice Already Generated!');
        $taxes = Tax::all();
        $vendor = Auth::user()->vendor;

        return view('mechanics.garage.invoice.create', [
            'vendor' => $vendor,
            'taxes' => $taxes
        ]);
    }

    public function uploadDiagnosis(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'diagnosis_comment' => 'required',
            'car_id_diagnosis' => 'required|exists:cars,id',
            'id_diagnosis' => 'required|exists:car_maintenances,id',
            'diagnosis_date' => 'required|date',
            'file' => 'nullable'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->with(['errors' => $validator->errors()]);
        }

        $data = $validator->validated();
        $garage = CarMaintenance::query()->find($data['id_diagnosis']);

        if ($garage->status != 'ongoing')
            return back()->with('error', 'Car Diagnosis could not be ADDED successfully!');

        //$update = $garage->update(['status' => 'diagnosed', 'diagnosis_comment' => $data['diagnosis_comment'], 'diagnosis_date' => $data['diagnosis_date']]);

        $garage->status = 'diagnosed';
        $garage->save();

        //insert into car_maintenance notes
        $note = CarMaintenanceNote::create([
            'car_maintenance_id' => $data['id_diagnosis'],
            'status' => 'Vehicle Diagnosed',
            'receipt_comment' => $data['diagnosis_comment'],
            'receipt_date' => $data['diagnosis_date'],
            'user_email' => $request->user()->id,
        ]);

        if ($request->hasFile('file')) {
            $media_name = $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->store('/public/uploads/car/garage/diagnosis');

            $garage->media()->create([
                'name' => 'car_' . $garage->car->car_number . '_diagnosis_' . $media_name,
                'description' => 'Diagnosis of car on ' . Carbon::parse($garage->diagnosis_date)->format('D, d F Y'),
                'path' => $path,
                'car_maintenance_note_id' => $note->id
            ]);
        }

        // Notify fleet management about the status update
        Notifications::createNotification(
            '1',  // Assuming you are not notifying a specific user
            'Car Maintenance Status Updated',
            'The car maintenance status for car number ' . $garage->car->car_number . ' has been updated to diagnosed.'
        );

        // Notifications::sendSMSNotify(
        //     $garage->car,
        //     'The car maintenance status for car number '.$garage->car->car_number.' has been updated to diagnosed.'
        // );

        return \redirect()->route('mechanic.garage.invoice.create', $data['id_diagnosis'])->with('success', 'Car Receipt was ADDED successfully!');

        //        return back()->with('success', 'Car Diagnosis was ADDED successfully!');
    }

    public function confirmCompleted(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'completed_comment' => 'required',
            'car_id_completed' => 'required|exists:cars,id',
            'id_completed' => 'required|exists:car_maintenances,id',
            'completed_date' => 'required|date',
            'file' => 'nullable'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->with(['errors' => $validator->errors()]);
        }

        $data = $validator->validated();
        $garage = CarMaintenance::query()->find($data['id_completed']);

        if ($garage->status != 'diagnosed')
            return back()->with('error', 'Car Completion could not be ADDED successfully. It needs to be diagnosed prior this process!');


        $garage->status = 'completed';
        $garage->save();

        //insert into car_maintenance notes
        $note = CarMaintenanceNote::create([
            'car_maintenance_id' => $data['id_completed'],
            'status' => 'Work Completed',
            'receipt_comment' => $data['completed_comment'],
            'receipt_date' => $data['completed_date'],
            'user_email' => $request->user()->id,
        ]);

        // $update = $garage->update(['status' => 'completed', 'completed_comment' => $data['completed_comment'], 'completed_date' => $data['completed_date']]);
        $garage->car()->update(['status' => 'active']);

        if ($request->hasFile('file')) {
            $media_name = $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->store('/public/uploads/car/garage/completed');

            $garage->media()->create([
                'name' => 'car_' . $garage->car->car_number . '_complete_' . $media_name,
                'description' => 'Completion of car on ' . Carbon::parse($garage->completed_date)->format('D, d F Y'),
                'path' => $path,
                'car_maintenance_note_id' => $note->id
            ]);
        }

        // Notify fleet management about the status update
        Notifications::createNotification(
            '1',  // Assuming you are not notifying a specific user
            'Car Maintenance Status Updated',
            'The car maintenance status for car number ' . $garage->car->car_number . ' has been updated to completed.'
        );

        // Notifications::sendSMSNotify(
        //     $garage->car,
        //     'The car maintenance status for car number '.$garage->car->car_number.' has been updated to completed.'
        // );

        return back()->with('success', 'Car Completion was ADDED successfully!');
    }
}
