<?php

namespace App\Http\Controllers\Fleet;
 
use App\DataTables\DriverELogReportActivityDataTable;
use App\DataTables\DriverELogReportDataTable;
use App\Http\Controllers\Controller;
use App\Models\ELog;
use Illuminate\Http\Request;
use function back;

class FleetELogReportController extends Controller
{
    public function showELogReport(Request $request, DriverELogReportDataTable $dataTable){
        return $dataTable->render('vehicle.reports.elog');
    }

    public function viewELogReportActivity(DriverELogReportActivityDataTable $dataTable, $id){
        $elog = ELog::query()->find($id);
        return $dataTable->with(['id' => $id])->render('vehicle.reports.elog_view', [
            'elog' => $elog
        ]);
    }
}
