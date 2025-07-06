<?php

namespace App\Http\Controllers\Fleet;

use App\DataTables\CarRequestDataTable;
use App\DataTables\DriverOdometerHistoryDataTable;
use App\DataTables\TaxDataTable;
use App\DataTables\VendorDataTable;
use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\Tax;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function showTaxes(TaxDataTable $dataTable)
    {
        return $dataTable->render('settings.tax');
    }

    public function storeTaxes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required|string',
            'description'=> 'nullable|string',
            'percentage'=> 'required|numeric',
        ]);

        if($validator->fails()){
            return Redirect::back()->with(['errors' => $validator->errors()]);
        }

        $data = $validator->validated();
        $create = Tax::query()->create($data);

        return back()->with('success', 'Tax was ADDED successfully!');
    }

    public function showDrivers(VendorDataTable $dataTable)
    {
        $regions = Region::all();
        return $dataTable->render('settings.vendor', [
            'regions' => $regions
        ]);
    }

    public function storeDrivers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required|string',
            'phone_number'=> 'required|string',
            'email'=> 'required|unique:vendors',
            'address'=> 'nullable|string',
            'region_id' => 'nullable|exists:regions,id',
        ]);

        if($validator->fails()){
            return Redirect::back()->with(['errors' => $validator->errors()]);
        }

        $data = $validator->validated();
        $role = Vendor::create($data);

        return back()->with('success', 'Service Provider was ADDED successfully!');
    }

    public function getUserCarRequests(CarRequestDataTable $carRequestDataTable, $user_id)
    {
        return $carRequestDataTable->with('user_id', $user_id)->render('settings.users.view');
    }

    public function getUserOdometerHistory(DriverOdometerHistoryDataTable $driverOdometerHistoryDataTable, $user_id)
    {
        return $driverOdometerHistoryDataTable->with('user_id', $user_id)->render('settings.users.view');
    }
}
