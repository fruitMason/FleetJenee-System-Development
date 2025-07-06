<?php

namespace App\Http\Controllers\Fleet;

use App\DataTables\DepartmentDataTable;
use App\DataTables\ArchivedDepartmentDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSectorRequest;
use App\Models\Department;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentsController extends Controller
{
    public function showDepartments(Request $request, DepartmentDataTable $dataTable){
        $regions = Region::all();
        return $dataTable->render('settings.department', [
            'regions' => $regions
        ]);
    }

    public function archiveDepartments(Request $request)
    {
        $resource = Department::query()->find($request->id);
        if (!$resource) {
            return $this->sendFailureJsonResponse('Department not found.');
        }

        $resource->is_archived = true;
        $resource->save();

        return $this->sendSuccessJsonResponse('Department was archived successfully!');
    }

    public function archivedDepartments(Request $request, ArchivedDepartmentDataTable $dataTable)
    {
        return $dataTable->render('settings.archived_departments');
    }
    public function unarchiveDepartments(Request $request)
    {
        $resource = Department::query()->find($request->id);
        if (!$resource) {
            return $this->sendFailureJsonResponse('Department not found.');
        }

        $resource->is_archived = false; // Set to false to unarchive
        $resource->save();

        return $this->sendSuccessJsonResponse('Department was unarchived successfully!');
    }

    public function storeDepartments(CreateSectorRequest $request){
        $data = $request->validated();
        $create = Department::query()->create($data);
        return back()->with('success', 'Department was ADDED successfully!');
    }

    public function getDepartments(Request $request){
        $data = Department::query()->find($request->id);
        return $this->sendSuccessJsonResponse($data);
    }

    public function updateDepartments(Request $request)
    {
        $resource = Department::query()->find($request->id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:sectors,name,'.$resource->id,
            'description' => 'nullable|string',
            'region_id' => 'nullable'
        ]);

        if($validator->fails()){
            return $this->sendFailureJsonResponse($validator->errors());
        }

        $data = $validator->validated();
        $resource->update($data);

        return $this->sendSuccessJsonResponse('Department was UPDATED successfully!');
    }

    public function deleteDepartments(Request $request)
    {
        $resource = Department::query()->find($request->id);
        $resource->delete();
        return $this->sendSuccessJsonResponse('Department was DELETED successfully!');
    }
}
