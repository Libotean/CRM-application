<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\VehicleCategory;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use App\Models\Vehicle;


class VehicleSeeder extends Seeder
{
    public function run()
    {
        // curatam tabelele vechi ca sa nu se dubleze datele
        DB::statement('PRAGMA foreign_keys=OFF;');
        Vehicle::truncate();
        VehicleModel::truncate();
        VehicleMake::truncate();
        VehicleCategory::truncate();
        DB::statement('PRAGMA foreign_keys=ON;');

        $suv = VehicleCategory::create(['name' => 'SUV']);
        $sedan = VehicleCategory::create(['name' => 'Sedan']);

        $mercedes = VehicleMake::create(['name' => 'Mercedes-Benz', 'code_prefix' => 110]);
        $bmw = VehicleMake::create(['name' => 'BMW', 'code_prefix' => 120]);

        $eqa = VehicleModel::create(['vehicle_make_id' => $mercedes->id, 'vehicle_category_id' => $suv->id, 'name' => 'EQA']);
        $x5 = VehicleModel::create(['vehicle_make_id' => $bmw->id, 'vehicle_category_id' => $suv->id, 'name' => 'X5']);

        // Mercedes EQA (Rulat)
        Vehicle::create([
            'vehicle_make_id' => $mercedes->id,
            'vehicle_model_id' => $eqa->id,
            'client_id' => null,
            'internal_catalog_number' => '110001',
            'version_name' => '250+',
            'power_hp' => 190,
            'fuel_type' => 'Electric',
            'transmission' => 'Automata',
            'traction' => 'Fata',
            'color' => 'Negru',
            'manufacturing_year' => 2025,
            'mileage' => 1500,
            'condition' => 'rulat',
            'stock_entry_date' => now()->subDays(10),
            'price_eur' => 47735.00,
            'old_price_eur' => 60292.00,
            'vat_deductible' => true,
        ]);

        // BMW X5 (Nou)
        Vehicle::create([
            'vehicle_make_id' => $bmw->id,
            'vehicle_model_id' => $x5->id,
            'client_id' => null,
            'internal_catalog_number' => '120001',
            'version_name' => 'xDrive30d',
            'power_hp' => 286,
            'fuel_type' => 'Diesel',
            'transmission' => 'Automata',
            'traction' => '4x4',
            'color' => 'Alb',
            'manufacturing_year' => 2024,
            'mileage' => 0,
            'condition' => 'nou',
            'stock_entry_date' => now()->subDays(2),
            'price_eur' => 85000.00,
            'old_price_eur' => null,
            'vat_deductible' => true,
        ]);
    }
}
