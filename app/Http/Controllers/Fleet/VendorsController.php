<?php

namespace App\Http\Controllers\Fleet;

use App\DataTables\VendorDataTable;
use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class VendorsController extends Controller
{
    public function showVendors(VendorDataTable $dataTable)
    {
        $regions = Region::all();
        return $dataTable->render('settings.vendor', [
            'regions' => $regions
        ]);
    }

    public function storeVendors(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required|string',
            'phone_number'=> 'required|string',
            'email'=> 'required|unique:vendors',
            'address'=> 'nullable|string',
            'region_id' => 'nullable|exists:regions,id',
            'service_type'=> 'required|string',
        ]);

        if($validator->fails()){
            return Redirect::back()->with(['errors' => $validator->errors()]);
        }

        $data = $validator->validated();
        $role = Vendor::query()->create($data);

        return back()->with('success', 'Service Provider was ADDED successfully!');
    }
}
