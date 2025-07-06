<?php

namespace App\Http\Controllers\Fleet;


use App\DataTables\WaybillDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Waybill;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class FleetWaybillController extends Controller
{
    public function showWaybill(Request $request, WaybillDataTable $dataTable){
        $drivers = User::isDriver()->whereHas('car')->get();
        return $dataTable->render('vehicle.waybill.index', [
            'drivers' => $drivers,
        ]);
    }

    public function storeWaybill(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'destination' => 'required',
            'item' => 'required',
            'no_of_packages' => 'nullable',
            'description' => 'nullable',
            'weight' => 'nullable'
        ]);

        if($validator->fails()){
            return Redirect::back()->with(['errors' => $validator->errors()]);
        }

        $data = $validator->validated();
        $create = Waybill::query()->create($data);

        if ($request->hasFile('file')) {
            $media_name = $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->store('/public/uploads/waybill');

            $create->media()->create([
                'name' => $media_name,
                'description' => 'Waybill for item '.$create->item.' to destination '.$create->destination.' on the '.Carbon::parse($create->created_at)->format('D, d F Y'),
                'path' => $path
            ]);
        }

        return back()->with('success', 'Waybill was ADDED successfully!');
    }
}
