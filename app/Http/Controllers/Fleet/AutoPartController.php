<?php

namespace App\Http\Controllers\Fleet;

use App\DataTables\Fleet\AutoPartsDataTable;
use App\Http\Controllers\Controller;
use App\Models\AutoPart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AutoPartController extends Controller
{
    public function purchaseIndex(Request $request)
    {
        return view('main_fleet.inventory.inventory-purchase',['autoParts'=>[]]);
    }

    public function index(AutoPartsDataTable $dataTable)
    {
        return $dataTable->render('main_fleet.auto_parts.index-auto-parts');
    }

    public function create()
    {
        return view('main_fleet.auto_parts.create-auto-parts');
    }

    public function store(Request $request)
    {
        $part =  $this->FormValidation($request);


        AutoPart::create($part); //8f2xgG9A

        return back()->with('success', 'Auto part submitted successfully !',);
    }

    public function update(Request $request, AutoPart $autopart)
    {
        
        $part =  $this->FormValidation($request, $autopart);

        
        $autopart->update($part);

        return back()->with('success', 'Auto part updated successfully !',);
    }


    public function edit(AutoPart $autopart)
    {
        return view('main_fleet.auto_parts.edit-auto-parts', ['autopart' => $autopart]);
    }

    public function destroy(AutoPart $autopart)
    {
        $autopart->delete();
        //check if it in use


        return back()->with('success', 'Auto part submitted successfully !',);
    }

    //FORM VALIDATION
    protected function FormValidation(Request $request, ?AutoPart $auto = null): array
    {
        $auto ??= new AutoPart();
        Log::info($auto);
        $part  = $request->validate([
            'name' => ['required', 'min:2', Rule::unique('auto_parts', 'name')->ignore($auto->id)],
            'description' => 'nullable',
            'unit_cost' => 'required'
        ]);
        $part['status'] = 'Active';
        $part['is_archived'] = false;

        return $part;
    }
}
