<?php

namespace App\Http\Controllers\Fleet;

use App\DataTables\AutoPartRequestsDataTable;
use App\DataTables\Fleet\InventoryDataTable;
use App\DataTables\Fleet\InventoryPurchasesDataTable;
use App\Helpers\Notifications;
use App\Http\Controllers\Controller;
use App\Models\AutoPart; 
use App\Models\AutoPartPurchaseHistory; 
use App\Models\AutoPartRequest;
use App\Models\AutoPartStatement;
use App\Models\Car;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class InventoryController extends Controller
{
    //invetory
    public function index(InventoryDataTable $dataTable)
    {
        return $dataTable->render('main_fleet.inventory.inventory-index');
    }


    public function show($id)
    {

        $auto = AutoPart::with(['AutoPartStatement' => function ($query) {
            $query->orderBy('created_at', 'desc')
                ->limit(10);
        }])->find($id);

        return view('main_fleet.inventory.inventory-show', compact('auto'));
    }

    public function purchaseIndex(InventoryPurchasesDataTable $dataTable)
    {
        return $dataTable->render('main_fleet.inventory.inventory-purchase-index');
    }


    public function purchaseCreate()
    {
        $autoParts = AutoPart::select('id', 'name', 'unit_cost')->where('is_archived', '0')->orderBy('name')->get();

        return view('main_fleet.inventory.inventory-purchase', compact('autoParts'));
    }


    public function purchaseShow($id)
    {
        return AutoPart::with('AutoPartStatement')->where('id', $id)->get();
        //return $inventory;
        //$autoParts = AutoPart::select('id', 'name', 'unit_cost')->where('is_archived', '0')->orderBy('name')->get();

        return view('main_fleet.inventory.inventory-purchase-show', compact('inventory'));
    }


    // public function purchaseStore(Request $request)
    // {
    //     // validate request
    //     $data  = $request->validate([
    //         'totalValue' => 'required|min:1',
    //         'purchase_date' => 'required|date',
    //         'items' => 'required|array',
    //         'reference' => 'required',
    //         'message' => 'nullable'
    //     ]);



    //     $inputDate = $data['purchase_date'];
    //     $dateObj = DateTime::createFromFormat('d/m/Y', $inputDate);
    //     $formattedDate = $dateObj->format('Y-m-d');



    //     //create auto parts purchase
    //     $auto_purchase =   AutoPartPurchase::create([
    //         'request_date' => $formattedDate,
    //         'vendor' => '1',
    //         'reference' => $data['reference'],
    //         'message' => $data['message'],
    //         'sub_total' => $data['totalValue'],
    //         'tax_amount' => 0,
    //         'net_amount' => $data['totalValue'] + 0,
    //         'status' => 'pending',
    //         'fin_status' => 'pending',
    //         'user_id' => $request->user()->id,
    //         'fin_user_id' => '',
    //     ]);
    //     //insert payment request
    //     $user_ass = "Unassigned";
    //     PaymentRequest::create([
    //         'user_id' => $request->user()->id,
    //         'car_id' => null,
    //         'request_date' => now(),
    //         'payment_type' => 'auto part',
    //         'description' => 'Auto part purchase. Desc: ' . $data['message'] . '. Ref:' . $data['reference'],
    //         'amount' => $data['totalValue'],
    //         'status' => 'pending',
    //         'amount_paid' => 0,
    //         'for_user_id' => '1',
    //         'car_assigned' => 'Unassigned',
    //         'invoice_no' => $auto_purchase->id
    //     ]);

    //     foreach ($data['items'] as $key => $value) {
    //         Log::info($value['unit_price']);
    //         AutoPartPurchaseItem::create([
    //             'auto_part_purchase_id' => $auto_purchase->id,
    //             'auto_part_id' => $value['auto_part_id'],
    //             'unit_price' => $value['unit_price'],
    //             'quantity' => $value['quantity'],
    //             'sub_total' => ($value['quantity'] * $value['unit_price']),
    //             'tax' => 0,
    //             'total' => $value['total'] + 0,
    //         ]);
    //     }

    //     $finance = User::role('FINANCE')->first();
    //     if ($finance) {
    //         Notifications::createNotification(
    //             $finance->id,
    //             'Auto Part Purchase Request',
    //             'New auto parts purchase request for ¢' . number_format($data['totalValue'], 2) . ' successfully by ' . $request->user()->name
    //         );
    //     }

    //     Notifications::createNotification(
    //         '1',  // Assuming you are not notifying a specific user
    //         'Auto Part Purchase Request',
    //         'New auto parts purchase request for ¢' . number_format($data['totalValue'], 2) . ' successfully by ' . $request->user()->name
    //     );

    //     return back()->with('success', 'Auto purchase submitted successfully !',);
    // }



    /////////USAGE ENPOINTS /////////////////
    public function usageIndex(AutoPartRequestsDataTable $dataTable)
    {
        // $autoParts = AutoPart::orderBy('name')->get();
        return $dataTable->render('main_fleet.inventory.inventory-usage-index');
    }


    public function usageShow(AutoPartRequest $auto_part_request)
    {
        //return $auto_part_request;
        $price_tag = AutoPartPurchaseHistory::select('cost', 'created_at')
            ->where('auto_part_id', $auto_part_request->auto_part_id)
            ->orderBy('created_at', 'desc')
            ->limit(5)->get();

        return view('main_fleet.inventory.inventoty-usage-auth', [
            'auto_part_request' => $auto_part_request,
            'price_tag' =>  $price_tag,
            'status' => [
                (object)['value' => 'approved', 'text' => 'Approve'],
                (object)['value' => 'declined', 'text' => 'Decline'],
                (object)['value' => 'delete', 'text' => 'Delete']
            ]
        ]);
    }

    public function usageAuth(Request $request)
    {

        //validate
        $request->validate([
            'id' => 'required|exists:auto_part_requests,id',
            'status' => 'required',
            'qnt_approved' => 'required|numeric|min:1', //|max:1024
            'fin_comment' => 'required',
            'price_tag' => 'required'
        ]);

        $req = AutoPartRequest::find($request->id);
        $req->auto_part->balance;

        if ($req->status != 'pending') {
            throw ValidationException::withMessages(['error_field' => 'This request is no longer pending.']);
        }

        if ($request->status == 'delete') {
            $req->delete();
            Notifications::createNotification(
                $req->user_id,
                'Auto Part Usage Approval',
                'Your auto part usage request for ' .  $req->qnt_requested . ' ' . $req->auto_part->name . '(s) has been deleted, Please contact your fleet manager !'
            );

            return redirect()->route('inventory.usage.index')->with('success', 'Auto part usage deleted successfully !',);
        }

        if ($request->qnt_approved > $req->qnt_requested) {
            throw ValidationException::withMessages(['error_field' => 'Approved quantity is more than requested quantity !']);
        }

        //check if balance is less than amount to be paid
        $auto = AutoPart::find($req->auto_part_id);
        $bal = $req->qnt_requested - $auto->balance;
        if ($auto->balance <  $req->qnt_requested) {
            throw ValidationException::withMessages(['error_field' => 'Number of availble auto part is less that authorized quantity. Process aborted !']);
        }


        $req->status = $request->status;
        $req->qnt_approved = $request->qnt_approved;
        $req->cost = $request->price_tag * $request->qnt_approved;
        $req->reason_for_decline = $request->fin_comment;
        $req->auth_by = $request->user()->id;
        $req->save();

        if ($request->status == 'approved') {

            $auto->balance = $auto->balance - $request->qnt_approved;
            $auto->save();

            AutoPartStatement::create([
                'auto_part_id' => $req->auto_part_id,
                'user_id' => $req->user_id,
                'stock_in' => 0,
                'stock_out' => $request->qnt_approved,
                'trans_type' => 'Usage',
                'narration' => 'Auto Part Usage - Reason : ' . $req->reason_for_request,
                'trans_status' => 'active',
            ]);
            Notifications::createNotification(
                $req->user_id,
                'Auto Part Usage Approval',
                'Your auto part usage request  for ' .  $req->qnt_requested . ' ' . $req->auto_part->name . '(s) has been approved !'
            );
        } else {
            Notifications::createNotification(
                $req->user_id,
                'Auto Part Usage Approval',
                'Your auto part usage request for ' .  $req->qnt_requested . ' ' . $req->auto_part->name . '(s) has been declined. Decline message : ' . $req->fin_comment . '!'
            );
        }

        //reduce balance





        return back()->with('success', 'Auto purchase submitted successfully !',);
    }


    public function usageRequestAdmin()
    {
        $cars =  Car::select('id', 'model', 'car_number', 'car_group','user_id')->orderBy('model')->get();
        $autoParts = AutoPart::orderBy('name')->get();
        return view('main_fleet.inventory.inventory-usage-request-admin', ['autoParts' => $autoParts, 'cars' => $cars]);
    }



    public function usageRequestStore(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'auto_part_id' => 'required|exists:auto_parts,id',
            'qnt_requested' => 'required|numeric|min:1',
            'reason_for_request' => 'required',
            'car_id'=>'required|exists:cars,id'
        ]);

        if ($validator->fails()) {
            return back()->with(['errors' => $validator->errors()]);
        }

        $data = $validator->validated();
        $data['request_type'] = 'Admin Request';
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
}
