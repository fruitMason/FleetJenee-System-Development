<?php

namespace App\Imports;

use App\Models\Car;
use App\Models\Department;
use App\Models\Sector;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Throwable;

/**
 * @method import(array|\Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[]|null $file, $null, string $XLSX)
 */
class CarImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
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
            'car_model' => 'nullable',
            'car_number' => 'nullable|unique:cars',
            'year' => 'nullable',
            'odometer' => 'nullable',
            'road_worthy_start_date' => 'nullable',
            'road_worthy_end_date' => 'nullable',
            'insurance_start_date' => 'nullable',
            'insurance_expiry' => 'nullable',
            'department' => 'nullable',
            'zone' => 'nullable',
        ];
    }

    /**
     * @param array $row
     *
     * @return Car
     */
    public function model(array $row)
    {
        if (!array_filter($row)) {
            return null;
        }

        ini_set('memory_limit','1024M');
        set_time_limit(3000000);

        $data = $this->getData($row);
        return new Car($data);
    }

    private function getData($row)
    {
//        $row['department_id'] = Department::query()->where('name', '=', $row['department'])->first()->id ?? 0;
        $dept = Department::query()->where('name', '=', $row['department'])->first();
        if(!is_null($dept)){
            $row['department_id'] = $dept->id;
        }
        else{
            if(!empty( $row['department'])){
                $create = Department::query()->create(['name' => $row['department'], 'description' => $row['department']]);
                $row['department_id'] = $create->id;
            }
            else{
                $row['department_id'] = 0;
            }
        }
        $zone = Sector::query()->where('name', '=', $row['zone'])->first();
        if(!is_null($zone)){
            $row['zone_id'] = $zone->id;
        }
        else{
            if(!empty( $row['zone'])){
                $create = Sector::query()->create(['name' => $row['zone'], 'description' => $row['zone']]);
                $row['zone_id'] = $create->id;
            }
            else{
                $row['zone_id'] = 0;
            }
        }
//        $row['zone_id'] = Sector::query()->where('name', '=', $row['zone'])->first()->id ?? 0;
        $row['user_id'] = User::query()->where('email', '=', $row['user_assigned'])->first()->id ?? 0;
        $row['model'] = $row['car_model'];
        $row['status'] = 'active';
        try {
            $row['road_worthy_start_date'] = Str::contains($row['road_worthy_start_date'], '/') && strlen($row['road_worthy_start_date']) == 10 && !empty($row['road_worthy_start_date']) ? Carbon::createFromFormat('d/m/Y', $row['road_worthy_start_date'])->toDateString() : null;
            $row['road_worthy_expiry_date'] = Str::contains($row['road_worthy_end_date'], '/') && strlen($row['road_worthy_end_date']) == 10 ? Carbon::createFromFormat('d/m/Y', $row['road_worthy_end_date'])->toDateString() : null;
            $row['insurance_start_date'] = Str::contains($row['insurance_start_date'], '/') && strlen($row['insurance_start_date']) == 10 ? Carbon::createFromFormat('d/m/Y', $row['insurance_start_date'])->toDateString() : null;
            $row['insurance_expiry'] = Str::contains($row['insurance_expiry'], '/') && strlen($row['insurance_expiry']) == 10 ? Carbon::createFromFormat('d/m/Y', $row['insurance_expiry'])->toDateString() : null;
        }
        catch (\Exception $exception){
            $row['road_worthy_start_date'] = null;
            $row['road_worthy_expiry_date'] = null;
            $row['insurance_start_date'] = null;
            $row['insurance_expiry'] = null;
        }

        return $row;
    }
}
