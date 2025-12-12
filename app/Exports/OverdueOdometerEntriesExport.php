<?php

namespace App\Exports;

use App\Models\Car;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OverdueOdometerEntriesExport implements FromQuery, WithHeadings
{
    public function query()
    {
        return Car::query()->whereHas('user')->whereHas('latestOdometerHistory', function ($q) {
            $q->havingRaw('DATEDIFF(now(), max(created_at)) >= 4');
        });
    }

    public function headings(): array
    {
        return [
            'Last Input Date',
            'Value',
            'Car Model',
            'Car Number',
            'Zone',
            'Assigned User',
            'Phone Number',
            'Department',
        ];
    }
}

