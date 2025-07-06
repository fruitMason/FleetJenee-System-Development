<?php

namespace App\Http\Controllers\Fleet;

use App\DataTables\Fleet\FinanceRequestsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Payment;
use App\Models\PaymentRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceRequestsController extends Controller
{
    public function index(FinanceRequestsDataTable $dataTable)
    {
        // $users = User::select('first_name', 'middle_name', 'last_name', 'id')->orderBy('first_name')->get();
        // $cars = Car::select('model', 'car_number', 'year', 'id')->orderBy('model')->get();
        // $vendors = Vendor::select('name', 'id')->orderBy('name')->get();


        return $dataTable->render('main_fleet.finance_requests.index-fin-requests', [
            // 'users' => $users,
            // 'cars' => $cars,
            // 'vendors' => $vendors
        ]);
    }

    public function distribution(Request $request)
    {
        $year = date('Y');
        $insurance_y2d = Payment::join('payment_requests as req', 'req.id', '=', 'payments.payment_request_id')
            ->where('payment_type', 'insurance')->whereYear('payment_date', $year)
            ->sum('payments.amount_paid');

        $maintenance_y2d = Payment::join('payment_requests as req', 'req.id', '=', 'payments.payment_request_id')
            ->where('payment_type', 'maintenance')->whereYear('payment_date', $year)
            ->sum('payments.amount_paid');

        $road_worthy_y2d = Payment::join('payment_requests as req', 'req.id', '=', 'payments.payment_request_id')
            ->where('payment_type', 'road worthy')->whereYear('payment_date', $year)
            ->sum('payments.amount_paid');

        $fuel_y2d = Payment::join('payment_requests as req', 'req.id', '=', 'payments.payment_request_id')
            ->where('payment_type', 'fuel')->whereYear('payment_date', $year)
            ->sum('payments.amount_paid');

        $others_y2d = Payment::join('payment_requests as req', 'req.id', '=', 'payments.payment_request_id')
            ->where('payment_type', 'other')->whereYear('payment_date', $year)
            ->sum('payments.amount_paid');

        $department_cost = Payment::select(
            DB::raw("SUM(payments.amount_paid) as total_amount_paid"),
            'dep.name as department_name'
        )
            ->join('payment_requests as req', 'payments.payment_request_id', '=', 'req.id')
            ->join('users', 'req.for_user_id', '=', 'users.id')
            ->join('departments as dep', 'users.department_id', '=', 'dep.id')
            ->whereYear('payment_date', $year)
            ->where('payments.payment_status', 'paid')
            ->groupBy('dep.id', 'dep.name') // Group by both id and name for consistency
            ->get();

        $maintenance_line =  $this->maintenanceData();
        return view(
            'main_fleet.finance_requests.index-fin-distribution',
            [
                'insurance_y2d' => $insurance_y2d,
                'maintenance_y2d' => $maintenance_y2d,
                'road_worthy_y2d' => $road_worthy_y2d,
                'fuel_y2d' => $fuel_y2d,
                'other_y2d' => $others_y2d,
                'department_cost' => $department_cost,
                'maintenance_line' => $maintenance_line
            ]
        );
    }

    protected function maintenanceData()
    {
        $year = date('Y');
        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        $payments = Payment::select(
            DB::raw("DATE_FORMAT(payment_date, '%M') as month"),
            DB::raw("SUM(amount_paid) as amount")
        )
            ->whereYear('payment_date', $year)
            ->groupBy('month')
            ->orderBy(DB::raw("MONTH(payment_date)"))
            ->get()
            ->keyBy('month');

        return collect($months)->map(function ($month) use ($payments) {
            return (object)[
                'month' => $month,
                'amount' => $payments->has($month) ? $payments[$month]->amount : 0
            ];
        });
    }

    public function createGeneral(Request $request)
    {
        $pay_type = collect([
            ['id' => 'insurance', 'name' => 'Insurance Cost'],
            ['id' => 'road worthy', 'name' => 'Road Worthy Cost'],
            ['id' => 'fuel', 'name' => 'Fuel Cost'],
            ['id' => 'other', 'name' => 'Other Costs'],
        ])->map(function ($item) {
            return (object) $item; // Convert each array to an object
        });

        $cars =  Car::select('id', 'model', 'car_number', 'car_group')->orderBy('model')->get();

        // $users =  User::select(
        //     'id',
        //     'first_name',
        //     'middle_name',
        //     'last_name',
        //     'type'
        // )->where('type', '<>', 'MECHANIC')->orderBy('first_name')->get();

        return view(
            'main_fleet.finance_requests.create-fin-request',
            [
                'pay_type' => $pay_type,
                'cars' => $cars,
                // 'users' => $users
            ]
        );
    }

    public function storeGeneral(Request $request)
    {

        $pay_req =  $request->validate([
            'car_id' => 'required|exists:cars,id',
            'for_user_id' => 'nullable',
            'request_date' => 'required|date',
            'amount' => 'numeric|min:1',
            'payment_type' => 'required',
            'description' => 'required',
        ]);

        $car = Car::find($request->car_id);
        $user = "1";
        $user_ass = "Unassigned";
        if ($car->user) {
            $user = $car->user->id;
            $user_ass = "Assigned";
        }
        $pay_req['for_user_id'] = $user;
        $pay_req['car_assigned'] = $user_ass;

        PaymentRequest::create($pay_req); //8f2xgG9A

        return back()->with('success', ucwords($pay_req['payment_type']) . ' payment submitted successfully !',);
    }
}
