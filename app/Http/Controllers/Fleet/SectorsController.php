<?php

namespace App\Http\Controllers\Fleet;

use App\DataTables\SectorDataTable;
use App\DataTables\ArchivedSectorDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSectorRequest;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SectorsController extends Controller
{
    public function showSectors(Request $request, SectorDataTable $dataTable){
        return $dataTable->render('settings.sector');
    }

    public function archiveSectors(Request $request)
    {
        $resource = Sector::query()->find($request->id);
        if (!$resource) {
            return $this->sendFailureJsonResponse('Sector not found.');
        }

        $resource->is_archived = true;
        $resource->save();

        return $this->sendSuccessJsonResponse('Sector was archived successfully!');
    }

    public function archivedSectors(Request $request, ArchivedSectorDataTable $dataTable)
    {
        return $dataTable->render('settings.archived_sectors');
    }
    public function unarchiveSectors(Request $request)
    {
        $resource = Sector::query()->find($request->id);
        if (!$resource) {
            return $this->sendFailureJsonResponse('Sector not found.');
        }

        $resource->is_archived = false; // Set to false to unarchive
        $resource->save();

        return $this->sendSuccessJsonResponse('Sector was unarchived successfully!');
    }

    public function storeSectors(CreateSectorRequest $request){
        $data = $request->validated();
        $create = Sector::query()->create($data);
        return back()->with('success', 'Zone was ADDED successfully!');
    }

    public function getSectors(Request $request){
        $data = Sector::query()->find($request->id);
        return $this->sendSuccessJsonResponse($data);
    }

    public function updateSectors(Request $request)
    {
        $resource = Sector::query()->find($request->id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:sectors,name,'.$resource->id,
            'description' => 'nullable|string'
        ]);

        if($validator->fails()){
            return $this->sendFailureJsonResponse($validator->errors());
        }

        $data = $validator->validated();
        $resource->update($data);

        return $this->sendSuccessJsonResponse('Zone was UPDATED successfully!');
    }

    public function deleteSectors(Request $request)
    {
        $resource = Sector::query()->find($request->id);
        $resource->delete();
        return $this->sendSuccessJsonResponse('Zone was DELETED successfully!');
    }
}
