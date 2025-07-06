<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\User;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Spatie\Permission\Models\Role;
use Throwable;

/**
 * @method import(array|\Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[]|null $file, $null, string $XLSX)
 */
class UserImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use Importable, SkipsErrors;

    public function onError(Throwable $e): Throwable
    {
        // TODO: Implement onError() method.
        return $e;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
//            'email' => 'nullable|email|unique:users',
            'email' => 'nullable|string',
            'password' => 'nullable|string',
//            'mobile' => 'nullable|unique:users|digits_between:10,15',
            'mobile' => 'nullable',
            'role' => 'nullable',
            'department_id' => 'nullable',
            'type' => 'nullable',
            'license_class' => 'nullable',
            'license_number' => 'nullable',
            'license_expiry' => 'nullable',
        ];
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function model(array $row)
    {
        if (!array_filter($row)) {
            return null;
        }

        ini_set('memory_limit','1024M');
        set_time_limit(3000000);

        $data = $this->getData($row);
        $data['password'] = bcrypt('1234');
        $data['status'] = 'active';
        $data['type'] = $data['type'] ?? 'DRIVER';
        $create = User::query()->create($data);
        $role = Role::findByName($row['role']);
        $create->assignRole($role);
        return $create;
    }

    private function getData($row)
    {
        $dept = Department::query()->where('name', '=', $row['department'])->first();
        if(!is_null($dept)){
            $row['department_id'] = $dept->id;
        }
        else{
            $create = Department::query()->create(['name' => $row['department'], 'description' => $row['department']]);
            $row['department_id'] = $create->id;
        }
        $row['mobile'] = $row['phone_number'];
//        $row['department_id'] = Department::query()->where('name', '=', $row['department'])->first()->id ?? 0;
        return $row;
    }
}
