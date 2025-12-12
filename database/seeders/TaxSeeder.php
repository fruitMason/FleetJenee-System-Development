<?php

namespace Database\Seeders;

use App\Models\OdometerSetting;
use App\Models\Tax;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $taxes = Tax::query()->insertOrIgnore([
            [
                'name' => 'VAT',
                'description' => 'VAT',
                'percentage' => 12.5
            ],
            [
                'name' => 'Flat VAT',
                'description' => 'Flat VAT',
                'percentage' => 3
            ],
            [
                'name' => 'NHIL',
                'description' => 'NHIL',
                'percentage' => 2.5
            ],
            [
                'name' => 'GETFund',
                'description' => 'GETFund',
                'percentage' => 2.5
            ],
            [
                'name' => 'Withholding Tax 7.5%',
                'description' => 'Withholding Tax 7.5%',
                'percentage' => 7.5
            ],
            [
                'name' => 'Withholding Tax 3%',
                'description' => 'Withholding Tax 3%',
                'percentage' => 3
            ],
            [
                'name' => 'Withholding Tax 8%',
                'description' => 'Withholding Tax 8%',
                'percentage' => 8
            ],
            [
                'name' => 'Withholding 10%',
                'description' => 'Withholding 10%',
                'percentage' => 10
            ],
            [
                'name' => 'CST',
                'description' => 'CST',
                'percentage' => 5
            ],
            [
                'name' => 'Withholding Tax VAT',
                'description' => 'Withholding Tax VAT',
                'percentage' => 7
            ],
            [
                'name' => 'COVID 19 TAX',
                'description' => 'COVID 19 TAX',
                'percentage' => 1
            ],
        ]);

        OdometerSetting::create(['odometer' => 'Odometer', 'value' => 10000, 'updated_by' => 'sys_admin']);
    }
}
