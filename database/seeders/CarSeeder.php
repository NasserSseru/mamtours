<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Car;

class CarSeeder extends Seeder
{
    public function run()
    {
        $cars = [
            [
                'brand' => 'Toyota',
                'model' => 'Hilux',
                'numberPlate' => 'UAA 001A',
                'dailyRate' => 200000,
                'seats' => 5,
                'category' => 'SUV',
                'isAvailable' => true,
            ],
            [
                'brand' => 'Toyota',
                'model' => 'Land Cruiser',
                'numberPlate' => 'UAB 002B',
                'dailyRate' => 350000,
                'seats' => 7,
                'category' => 'SUV',
                'isAvailable' => true,
            ],
            [
                'brand' => 'Toyota',
                'model' => 'RAV4',
                'numberPlate' => 'UAC 003C',
                'dailyRate' => 150000,
                'seats' => 5,
                'category' => 'SUV',
                'isAvailable' => true,
            ],
            [
                'brand' => 'Honda',
                'model' => 'CR-V',
                'numberPlate' => 'UAD 004D',
                'dailyRate' => 180000,
                'seats' => 5,
                'category' => 'SUV',
                'isAvailable' => true,
            ],
            [
                'brand' => 'Nissan',
                'model' => 'X-Trail',
                'numberPlate' => 'UAE 005E',
                'dailyRate' => 160000,
                'seats' => 5,
                'category' => 'SUV',
                'isAvailable' => true,
            ],
        ];

        foreach ($cars as $carData) {
            if (!Car::where('numberPlate', $carData['numberPlate'])->exists()) {
                Car::create($carData);
            }
        }
    }
}
