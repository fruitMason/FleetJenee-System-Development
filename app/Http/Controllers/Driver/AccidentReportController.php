<?php

namespace App\Http\Controllers\Driver;

use App\DataTables\DriverAccidentReportDataTable;
use App\DataTables\DriverELogReportActivityDataTable;
use App\DataTables\DriverELogReportDataTable;
use App\Http\Controllers\Controller;
use App\Models\AccidentReport;
use App\Models\ELog;
use App\Models\ELogActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use function back;

class AccidentReportController extends Controller
{
    public function showAccidentReport(Request $request, DriverAccidentReportDataTable $dataTable){
        return $dataTable->render('drivers.reports.accident');
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

    public function showELogReport(Request $request, DriverELogReportDataTable $dataTable){
        return $dataTable->render('drivers.reports.elog');
    }

    public function storeELogReport(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'user_id' => 'required|exists:users,id',
            'car_id' => 'required|exists:cars,id',
            'start_odometer' => 'required',
            'date_logged' => 'required',
            'current_location' => 'nullable',
            'destination' => 'required',
            'description' => 'nullable|string'
        ]);

        if($validator->fails()){
            return Redirect::back()->with(['errors' => $validator->errors()]);
        }

        $data = $validator->validated();
        $create = ELog::query()->create($data);

        $activity = $create->activities()->create($data);

        if ($request->hasFile('file')) {
            $media_name = $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->store('/public/uploads/reports/elog');

            $activity->media()->create([
                'name' => $media_name,
                'description' => $create->description ?? $create->title,
                'path' => $path
            ]);
        }

        return back()->with('success', 'ELog Report was ADDED successfully!');
    }

    public function viewELogReportActivity(DriverELogReportActivityDataTable $dataTable, $id){
        $elog = ELog::query()->find($id);
        return $dataTable->with(['id' => $id])->render('drivers.reports.elog_view', [
            'elog' => $elog
        ]);
    }

    public function getELogReport(Request $request){
        $data = ELog::query()->find($request->id);
        return $this->sendSuccessJsonResponse($data);
    }

    public function updateELogReportActivity(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'car_id' => 'required|exists:cars,id',
            'date_logged' => 'required',
            'current_location' => 'nullable',
            'destination' => 'required',
            'description' => 'nullable|string'
        ]);

        if($validator->fails()){
            return Redirect::back()->with(['errors' => $validator->errors()]);
        }

        $data = $validator->validated();
        $data['e_log_id'] = $request->id;
        $create = ELogActivity::query()->create($data);

        if ($request->hasFile('file')) {
            $media_name = $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->store('/public/uploads/reports/elog');

            $create->media()->create([
                'name' => $media_name,
                'description' => $create->description ?? $create->title,
                'path' => $path
            ]);
        }

        return back()->with('success', 'ELog Report was UPDATED successfully!');
    }

    public function getELog(Request $request)
    {
        $elog = ELog::find($request->id);

        if (!$elog) {
            return response()->json(['error' => 'ELog not found'], 404);
        }

        return response()->json(['message' => $elog]);
    }

    public function endELogTrip(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:e_log,id',
            'end_odometer' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $elog = ELog::find($request->id);
        $elog->end_odometer = $request->end_odometer;
        $elog->ended_date = now(); // Capture the current time
        $elog->save();

        return response()->json(['message' => 'ELog trip ended successfully!']);
    }


}
