<?php

namespace App\Http\Controllers;

use App\Models\AutoPart;
use App\Models\Car;
use App\Models\Tax;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    public function getProductsAndServices()
    {
        Log::info('hit');
        return   AutoPart::orderBy('name')->get();//Car::find($id);//  Car::inRepairs()->get(); //AutoPart::orderBy('name')->get();//
    }

    public function getTaxes()
    {
        return Tax::all();
    }
}
