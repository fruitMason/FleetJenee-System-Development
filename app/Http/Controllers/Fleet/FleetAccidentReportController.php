<?php

namespace App\Http\Controllers\Fleet;

use App\DataTables\DriverAccidentReportDataTable;
use App\Http\Controllers\Controller;
use App\Models\AccidentReport;
use App\Models\Car;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Notifications;
use function back;

class FleetAccidentReportController extends Controller
{
    public function showAccidentReport(Request $request, DriverAccidentReportDataTable $dataTable){
        $users = $users =  User:: 
              where('type', '<>', 'MECHANIC')->orderBy('first_name')->get();
    
        $cars = Car::all();
        return $dataTable->render('vehicle.reports.accident', [
            'users' => $users,
            'cars' => $cars
        ]);
    }

    public function storeAccidentReport(Request $request){
        $validator = Validator::make($request->all(), [
            'date_reported' => 'required',
            'user_id' => 'required|exists:users,id',
            'car_id' => 'required|exists:cars,id',
            'location' => 'required|string',
            'description' => 'nullable|string'
        ]);

        if($validator->fails()){
            return Redirect::back()->with(['errors' => $validator->errors()]);
        }

        $data = $validator->validated();
        $create = AccidentReport::query()->create($data);

        if ($request->hasFile('file')) {
            $media_name = $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->store('/public/uploads/reports/accident');

            $create->media()->create([
                'name' => $media_name,
                'description' => 'Accident at '.$create->location.' on '.Carbon::parse($create->date_reported)->format('D, d F Y'),
                'path' => $path
            ]);
        }

        return back()->with('success', 'Accident Report was ADDED successfully!');
    }

    public function resolveAccidentReport(Request $request)
    {
        $resource = AccidentReport::query()->find($request->id);

        if (!$resource) {
            return $this->sendFailureJsonResponse('Accident Report Not Found!');
        }

        if ($resource->status != 'resolved') {
            $resource->status = 'resolved';
            $resource->save();

            // Fetch the driver using the `user` relationship on the `AccidentReport` model
            $driver = $resource->user; // Correctly get the driver user instance

            if ($driver) {
                Notifications::createNotification(
                    $driver,  // Notify the driver
                    'Accident Report Status Updated',
                    'The status of your accident report for car number ' . $resource->car->car_number . ' has been updated to RESOLVED.'
                );
            }

            return $this->sendSuccessJsonResponse('Accident Report was RESOLVED successfully!');
        }

        return $this->sendFailureJsonResponse('Accident Report Already RESOLVED!');
    }

    
}
