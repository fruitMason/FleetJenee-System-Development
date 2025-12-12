<?php

namespace App\Http\Controllers\Fleet;

use App\Http\Controllers\Controller;
use App\Models\OdometerSetting;
use Illuminate\Http\Request;

class OdometerSettingController extends Controller
{
    public function index()
    {
        $val = OdometerSetting::find(1);
        return view('settings.odometer-setting', [
            'odo' => $val
        ]);
    }


    public function update(Request $request)
    {
        $user = $request->user()->id;
        $up =  $request->validate([
            'value' => 'required|numeric|min:1',
        ]);
        $up['updated_by'] = $user;

         $val = OdometerSetting::find(1);
         $val->value = $up['value'];
         $val->updated_by = $up['updated_by'];
         $val->save();
         

         return back()->with('success', 'Default Overdue Odometer Setting !',);
    }
}
