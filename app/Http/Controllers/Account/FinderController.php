<?php

namespace App\Http\Controllers\Account;

use App\DataTables\Account\CarFinderDataTable;
use App\DataTables\Fleet\OldCardsDataTable;
use App\DataTables\Account\UserFinderDataTable;
use App\Http\Controllers\Controller;
use App\Models\AccidentReport;
use App\Models\Car;
use App\Models\CarMaintenance;
use App\Models\CarRequest;
use App\Models\ELog; 
use App\Models\OdometerHistory;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Waybill;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FinderController extends Controller
{
    public function indexCar(CarFinderDataTable $dataTable)
    {
        $users = User::select('first_name', 'middle_name', 'last_name', 'id')->orderBy('first_name')->get();
        $cars = Car::select('model', 'car_number', 'year', 'id')->orderBy('model')->get();
        $vendors = Vendor::select('name', 'id')->orderBy('name')->get();

        // return view(
        //     'accounts.finder-home',
        //     [
        //         'users' => $users,
        //         'cars' => $cars,
        //         'vendors' => $vendors
        //     ]

        // );
        return $dataTable->render('accounts.finder-home-car', [
            'users' => $users,
            'cars' => $cars,
            'vendors' => $vendors
        ]);
    }

    public function indexUser(UserFinderDataTable $dataTable)
    {
        $users = User::select('first_name', 'middle_name', 'last_name', 'id')->orderBy('first_name')->get();
        $cars = Car::select('model', 'car_number', 'year', 'id')->orderBy('model')->get();
        $vendors = Vendor::select('name', 'id')->orderBy('name')->get();


        return $dataTable->render('accounts.finder-home-user', [
            'users' => $users,
            'cars' => $cars,
            'vendors' => $vendors
        ]);
    }




    //----USER FINDER---------
    public function userFinder(Request $request)
    {
        $user = $request->user;
        $userdata = User::findOrFail($user);


        $work_orders = 'NA';
        //$ = CarRequest::where('user_id', $user)->count();
        //$elog = ELogActivity::where('user_id', $user)->count();
        $invoices = "NA";
        //$odometer =  OdometerHistory::where('user_id', $user)->count();
        //$waybills =  Waybill::where('user_id', $user)->count();

        $from = $request->get('dtp_from') ? Carbon::parse($request->get('dtp_from'))->format('Y-m-d') : 'NA';
        $to = $request->get('dtp_from') ? Carbon::parse($request->get('dtp_to'))->format('Y-m-d') : 'NA';


        $accidents = $this->userFinderDataAccidents($user, $from, $to);
        $car_requests = $this->userFinderDataCarRequests($user, $from, $to);
        $elogs = $this->userFinderDataElog($user, $from, $to);
        $odometer  = $this->userFinderDataOdometer($user, $from, $to);
        $waybills  = $this->userFinderDataWabill($user, $from, $to);

        return view(
            'accounts.finder.user-finder',
            [
                'user_data' => $userdata,
                'accidents' => $accidents,
                'work_orders' => $work_orders,
                'car_requests' => $car_requests,
                'elogs' => $elogs,
                'invoices' => $invoices,
                'odometer' => $odometer,
                'waybills' => $waybills,
            ]

        );
    }


 
    protected function userFinderDataAccidents($user, $from, $to)
    {
        if ($from == 'NA' || $to == 'NA') {
            return AccidentReport::with('car')->where('user_id', $user)->orderBy('created_at', 'desc')->get();
        } else {
            $dFrom = Carbon::parse($from)->format('Y-m-d');
            $dTo = Carbon::parse($to)->format('Y-m-d');

            // Log::info('inside select - ' . $dFrom . '| dto' . $dTo);
            return AccidentReport::with('user')->where('user_id', $user)
                ->whereDate('date_reported', '<=', $dTo)
                ->whereDate('date_reported', '>=', $dFrom)
                ->orderBy('created_at', 'desc')->get();
        }
    }
    protected function userFinderDataCarRequests($user, $from, $to)
    {
        if ($from == 'NA' || $to == 'NA') {
            return CarRequest::with('car')->where('user_id', $user)->orderBy('created_at', 'desc')->get();
        } else {
            $dFrom = Carbon::parse($from)->format('Y-m-d');
            $dTo = Carbon::parse($to)->format('Y-m-d');

            // Log::info('inside select - ' . $dFrom . '| dto' . $dTo);
            return CarRequest::with('car')->where('user_id', $user)
                ->whereDate('date_needed', '<=', $dTo)
                ->whereDate('date_needed', '>=', $dFrom)
                ->orderBy('created_at', 'desc')->get();
        }
    }
    protected function userFinderDataElog($user, $from, $to)
    {
        if ($from == 'NA' || $to == 'NA') {
            return  ELog::with('car')->where('user_id', $user)->orderBy('created_at', 'desc')->get();
        } else {
            $dFrom = Carbon::parse($from)->format('Y-m-d');
            $dTo = Carbon::parse($to)->format('Y-m-d');

            // Log::info('inside select - ' . $dFrom . '| dto' . $dTo);
            return
                ELog::with('car')->where('user_id', $user)
                ->whereDate('date_logged', '<=', $dTo)
                ->whereDate('date_logged', '>=', $dFrom)
                ->orderBy('created_at', 'desc')->get();
        }
    }
    protected function userFinderDataOdometer($user, $from, $to)
    {
        if ($from == 'NA' || $to == 'NA') {
            return  OdometerHistory::with('car')->where('user_id', $user)
                ->orderBy('created_at', 'desc')->get();
        } else {
            $dFrom = Carbon::parse($from)->format('Y-m-d');
            $dTo = Carbon::parse($to)->format('Y-m-d');

            // Log::info('inside select - ' . $dFrom . '| dto' . $dTo);
            return
                OdometerHistory::with('car')->where('user_id', $user)
                ->whereDate('created_at', '<=', $dTo)
                ->whereDate('created_at', '>=', $dFrom)
                ->orderBy('created_at', 'desc')->get();
        }
    }
    protected function userFinderDataWabill($user, $from, $to)
    {
        if ($from == 'NA' || $to == 'NA') {
            return  Waybill::where('user_id', $user)
                ->orderBy('created_at', 'desc')->get();
        } else {
            $dFrom = Carbon::parse($from)->format('Y-m-d');
            $dTo = Carbon::parse($to)->format('Y-m-d');

            // Log::info('inside select - ' . $dFrom . '| dto' . $dTo);
            return
            Waybill::where('user_id', $user)
                ->whereDate('created_at', '<=', $dTo)
                ->whereDate('created_at', '>=', $dFrom)
                ->orderBy('created_at', 'desc')->get();
        }
    }
      //--------END CAR FINDER-----------




    //--------CAR FINDER-----------
    public function carFinder($car, Request $request)
    {
        //$car = $request->car;
        $cardata = Car::findOrFail($car);

        $from = $request->get('dtp_from') ? Carbon::parse($request->get('dtp_from'))->format('Y-m-d') : 'NA';
        $to = $request->get('dtp_from') ? Carbon::parse($request->get('dtp_to'))->format('Y-m-d') : 'NA';


        $accidents = $this->carFinderDataAccidents($car, $from, $to);
        $work_orders = $this->carFinderDataWorkOrders($car, $from, $to);

        $elog =  $this->carFinderDataElog($car, $from, $to);

        $odometer = $this->carFinderDataOdometer($car, $from, $to);


        return view(
            'accounts.finder.car-finder',
            [
                'car' => $cardata,
                'accidents' => $accidents,
                'work_orders' => $work_orders,
                'elog' => $elog,
                'odometer' => $odometer
            ]

        );
    }

    public function oldCars(OldCardsDataTable $dataTable)
    {
        return $dataTable->render('accounts.old-cars', [            
        ]);
    }


    protected function carFinderDataAccidents($car, $from, $to)
    {
        if ($from == 'NA' || $to == 'NA') {
            return AccidentReport::with('user')->where('car_id', $car)->orderBy('created_at', 'desc')->get();
        } else {
            $dFrom = Carbon::parse($from)->format('Y-m-d');
            $dTo = Carbon::parse($to)->format('Y-m-d');

            Log::info('inside select - ' . $dFrom . '| dto' . $dTo);
            return AccidentReport::with('user')->where('car_id', $car)
                ->whereDate('date_reported', '<=', $dTo)
                ->whereDate('date_reported', '>=', $dFrom)
                ->orderBy('created_at', 'desc')->get();
        }
    }

    protected function carFinderDataWorkOrders($car, $from, $to)
    {
        Log::info('inside -- car' . $car . '  date f-' . $from . '  date t -' . $to);
        if ($from == 'NA' || $to == 'NA') {
            return
                CarMaintenance::select(
                    DB::raw("(select count(*) from car_maintenance_notes where car_maintenance_id = car_maintenances.id) as countt"),
                    'car_maintenances.created_at',
                    'car_maintenances.start_date',
                    'car_maintenances.end_date',
                    'car_maintenances.status',
                    'car_maintenances.type',
                    'car_maintenances.fin_status',
                    'car_maintenances.fin_comment',
                    'u.first_name',
                    'u.middle_name',
                    'u.last_name',
                )
                ->join('users as u', 'car_maintenances.mechanic_id', '=', 'u.id')
                ->orderBy('car_maintenances.created_at', 'desc')
                ->where('car_id', $car)->get();
        } else {
            $dFrom = Carbon::parse($from)->format('Y-m-d');
            $dTo = Carbon::parse($to)->format('Y-m-d');

            Log::info('inside select - ' . $dFrom . '| dto' . $dTo);
            return
                CarMaintenance::select(
                    DB::raw("(select count(*) from car_maintenance_notes where car_maintenance_id = car_maintenances.id) as countt"),
                    'car_maintenances.created_at',
                    'car_maintenances.start_date',
                    'car_maintenances.end_date',
                    'car_maintenances.status',
                    'car_maintenances.type',
                    'car_maintenances.fin_status',
                    'car_maintenances.fin_comment',
                    'u.first_name',
                    'u.middle_name',
                    'u.last_name',
                )
                ->join('users as u', 'car_maintenances.mechanic_id', '=', 'u.id')
                ->whereDate('car_maintenances.start_date', '<=', $dTo)
                ->whereDate('car_maintenances.start_date', '>=', $dFrom)
                ->orderBy('car_maintenances.created_at', 'desc')
                ->where('car_id', $car)->get();
        }
    }

    protected function carFinderDataElog($car, $from, $to)
    {
        if ($from == 'NA' || $to == 'NA') {
            return  ELog::with('user')->where('car_id', $car)->orderBy('created_at', 'desc')->get();
        } else {
            $dFrom = Carbon::parse($from)->format('Y-m-d');
            $dTo = Carbon::parse($to)->format('Y-m-d');

            Log::info('inside select - ' . $dFrom . '| dto' . $dTo);
            return
                ELog::with('user')->where('car_id', $car)
                ->whereDate('date_logged', '<=', $dTo)
                ->whereDate('date_logged', '>=', $dFrom)
                ->orderBy('created_at', 'desc')->get();
        }
    }

    protected function carFinderDataOdometer($car, $from, $to)
    {
        if ($from == 'NA' || $to == 'NA') {
            return  OdometerHistory::with('user')->where('car_id', $car)
                ->orderBy('created_at', 'desc')->get();
        } else {
            $dFrom = Carbon::parse($from)->format('Y-m-d');
            $dTo = Carbon::parse($to)->format('Y-m-d');

            Log::info('inside select - ' . $dFrom . '| dto' . $dTo);
            return
                OdometerHistory::with('user')->where('car_id', $car)
                ->whereDate('created_at', '<=', $dTo)
                ->whereDate('created_at', '>=', $dFrom)
                ->orderBy('created_at', 'desc')->get();
        }
    }
}
