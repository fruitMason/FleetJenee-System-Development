<?php

namespace App\Http\Controllers\Fleet;

use App\Http\Controllers\Controller;
use App\Models\AutoPart;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(): View
    {
        return view('main_fleet.inventory.inventory-index');
    }


    public function create()
    {


        $autoParts = AutoPart::select('id', 'name', 'unit_cost')->where('is_archived', '0')->orderBy('name')->get();


        // [
        //     ['id' => 1, 'name' => 'Oil Filter', 'price' => 12.99],
        //     ['id' => 2, 'name' => 'Air Filter', 'price' => 9.99],
        //     ['id' => 3, 'name' => 'Brake Pads', 'price' => 34.99],
        //     ['id' => 4, 'name' => 'Spark Plug', 'price' => 4.99],
        //     ['id' => 5, 'name' => 'Battery', 'price' => 89.99],
        // ];



        return view('main_fleet.inventory.inventory-purchase', compact('autoParts'));
    }


    public function store(Request $request)
    {
        return $request;
    }
}
