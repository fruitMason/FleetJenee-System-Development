<?php

namespace App\Http\Controllers\Fleet;

use App\DataTables\RegionDataTable;
use App\DataTables\ArchivedRegionDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSectorRequest;
use App\Models\Region;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegionsController extends Controller
{
    public function showRegions(Request $request, RegionDataTable $dataTable){
        $sectors = Sector::all();
        return $dataTable->render('settings.region', [
            'sectors' => $sectors
        ]);
    }

    public function archiveRegions(Request $request)
    {
        $resource = Region::query()->find($request->id);
        if (!$resource) {
            return $this->sendFailureJsonResponse('Region not found.');
        }

        $resource->is_archived = true;
        $resource->save();

        return $this->sendSuccessJsonResponse('Region was archived successfully!');
    }

    public function archivedRegions(Request $request, ArchivedRegionDataTable $dataTable)
    {
        return $dataTable->render('settings.archived_regions');
    }
    public function unarchiveRegions(Request $request)
    {
        $resource = Region::query()->find($request->id);
        if (!$resource) {
            return $this->sendFailureJsonResponse('Region not found.');
        }

        $resource->is_archived = false; // Set to false to unarchive
        $resource->save();

        return $this->sendSuccessJsonResponse('Region was unarchived successfully!');
    }
 
    public function storeRegions(CreateSectorRequest $request){
        $data = $request->validated();
        $create = Region::query()->create($data);
        return back()->with('success', 'Region was ADDED successfully!');
    }

    public function getRegions(Request $request){
        $data = Region::query()->find($request->id);
        return $this->sendSuccessJsonResponse($data);
    }

    public function updateRegions(Request $request)
    {
        $resource = Region::query()->find($request->id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:sectors,name,'.$resource->id,
            'description' => 'nullable|string',
            'sector_id' => 'nullable'
        ]);

        if($validator->fails()){
            return $this->sendFailureJsonResponse($validator->errors());
        }

        $data = $validator->validated();
        $resource->update($data);

        return $this->sendSuccessJsonResponse('Region was UPDATED successfully!');
    }

    public function deleteRegions(Request $request)
    {
        $resource = Region::query()->find($request->id);
        $resource->delete();
        return $this->sendSuccessJsonResponse('Region was DELETED successfully!');
    }
}
