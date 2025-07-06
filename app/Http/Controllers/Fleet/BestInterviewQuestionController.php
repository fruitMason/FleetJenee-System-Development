<?php
namespace App\Http\Controllers\Fleet;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BestInterviewQuestionController extends Controller
{
    public function pdfview(Request $request)
    {
        $items = Invoice::query()->with(['invoiceItem'])->find(1);
        view()->share('data', $items);
        if($request->has('download')){
            $pdf = PDF::setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'enable-local-file-access' => true
            ])->loadView('pdfview')->setPaper('A4')->setOption('enable-local-file-access', true);
            return $pdf->stream('pdfview.pdf');
        }
        return view('pdfview');
    }
}
