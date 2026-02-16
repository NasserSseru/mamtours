<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Car;

class CarsSeeder extends Seeder
{
    public function run()
    {
        $cars = [
            ['brand' => 'Toyota', 'model' => 'Noah', 'category' => 'Van', 'seats' => 8, 'dailyRate' => 100000, 'numberPlate' => 'UBB 123A', 'carPicture' => 'Noah.jpeg', 'isAvailable' => true],
            ['brand' => 'Toyota', 'model' => 'Prado', 'category' => 'SUV', 'seats' => 7, 'dailyRate' => 150000, 'numberPlate' => 'UBB 124A', 'carPicture' => 'Prado.jpg', 'isAvailable' => true],
            ['brand' => 'Toyota', 'model' => 'Hilux', 'category' => 'Pickup', 'seats' => 5, 'dailyRate' => 120000, 'numberPlate' => 'UBB 125A', 'carPicture' => 'Hilux.jpg', 'isAvailable' => true],
            ['brand' => 'Toyota', 'model' => 'Hiace', 'category' => 'Van', 'seats' => 14, 'dailyRate' => 130000, 'numberPlate' => 'UBB 126A', 'carPicture' => 'Toyota Hiace.jpg', 'isAvailable' => true],
            ['brand' => 'Toyota', 'model' => 'Fortuner', 'category' => 'SUV', 'seats' => 7, 'dailyRate' => 140000, 'numberPlate' => 'UBB 127A', 'carPicture' => 'Toyota Fortuner.jpg', 'isAvailable' => true],
            ['brand' => 'Toyota', 'model' => 'Harrier', 'category' => 'SUV', 'seats' => 5, 'dailyRate' => 135000, 'numberPlate' => 'UBB 128A', 'carPicture' => 'Harrier.jpg', 'isAvailable' => true],
            ['brand' => 'Toyota', 'model' => 'Rav 4', 'category' => 'SUV', 'seats' => 5, 'dailyRate' => 125000, 'numberPlate' => 'UBB 129A', 'carPicture' => 'Rav 4.jpeg', 'isAvailable' => true],
            ['brand' => 'Toyota', 'model' => 'Auris', 'category' => 'Sedan', 'seats' => 5, 'dailyRate' => 80000, 'numberPlate' => 'UBB 130A', 'carPicture' => 'Auris.jpg', 'isAvailable' => true],
            ['brand' => 'Toyota', 'model' => 'Avensis', 'category' => 'Sedan', 'seats' => 5, 'dailyRate' => 85000, 'numberPlate' => 'UBB 131A', 'carPicture' => 'Toyota Avensis.jpg', 'isAvailable' => true],
            ['brand' => 'Toyota', 'model' => 'Fielder', 'category' => 'Station Wagon', 'seats' => 5, 'dailyRate' => 90000, 'numberPlate' => 'UBB 132A', 'carPicture' => 'Toyota Fielder.jpg', 'isAvailable' => true],
            ['brand' => 'Toyota', 'model' => 'Isis', 'category' => 'Van', 'seats' => 7, 'dailyRate' => 110000, 'numberPlate' => 'UBB 133A', 'carPicture' => 'Toyota Isis.jpg', 'isAvailable' => true],
            ['brand' => 'Toyota', 'model' => 'Spacio', 'category' => 'Van', 'seats' => 7, 'dailyRate' => 105000, 'numberPlate' => 'UBB 134A', 'carPicture' => 'Spacio.jpg', 'isAvailable' => true],
            ['brand' => 'Toyota', 'model' => 'Rumion', 'category' => 'Van', 'seats' => 7, 'dailyRate' => 108000, 'numberPlate' => 'UBB 135A', 'carPicture' => 'Rumion.jpg', 'isAvailable' => true],
            ['brand' => 'Toyota', 'model' => 'Runx', 'category' => 'Hatchback', 'seats' => 5, 'dailyRate' => 75000, 'numberPlate' => 'UBB 136A', 'carPicture' => 'Toyota Runx.jpg', 'isAvailable' => true],
            ['brand' => 'Toyota', 'model' => 'Allex', 'category' => 'Hatchback', 'seats' => 5, 'dailyRate' => 70000, 'numberPlate' => 'UBB 137A', 'carPicture' => 'Toyota Allex.jpg', 'isAvailable' => true],
            ['brand' => 'Toyota', 'model' => 'Passo', 'category' => 'Hatchback', 'seats' => 5, 'dailyRate' => 65000, 'numberPlate' => 'UBB 138A', 'carPicture' => 'Passo.jpg', 'isAvailable' => true],
            ['brand' => 'Toyota', 'model' => 'Premio', 'category' => 'Sedan', 'seats' => 5, 'dailyRate' => 82000, 'numberPlate' => 'UBB 139A', 'carPicture' => 'Premio.jpg', 'isAvailable' => true],
            ['brand' => 'Mercedes-Benz', 'model' => 'S Class', 'category' => 'Luxury Sedan', 'seats' => 5, 'dailyRate' => 200000, 'numberPlate' => 'UBB 140A', 'carPicture' => 's class.jpeg', 'isAvailable' => true],
            ['brand' => 'Mercedes-Benz', 'model' => 'GLE', 'category' => 'Luxury SUV', 'seats' => 7, 'dailyRate' => 220000, 'numberPlate' => 'UBB 141A', 'carPicture' => 'Gle.jpeg', 'isAvailable' => true],
            ['brand' => 'Jeep', 'model' => 'Grand Cherokee', 'category' => 'SUV', 'seats' => 5, 'dailyRate' => 160000, 'numberPlate' => 'UBB 142A', 'carPicture' => 'Jeep Grand Cherokee.jpg', 'isAvailable' => true],
            ['brand' => 'Jeep', 'model' => 'Wrangler', 'category' => 'SUV', 'seats' => 5, 'dailyRate' => 155000, 'numberPlate' => 'UBB 143A', 'carPicture' => 'jeep wrangler.jpg', 'isAvailable' => true],
            ['brand' => 'Land Rover', 'model' => 'Land Cruiser', 'category' => 'SUV', 'seats' => 7, 'dailyRate' => 180000, 'numberPlate' => 'UBB 144A', 'carPicture' => 'Land cruiser.jpg', 'isAvailable' => true],
            ['brand' => 'Jaguar', 'model' => 'XF 2015', 'category' => 'Luxury Sedan', 'seats' => 5, 'dailyRate' => 210000, 'numberPlate' => 'UBB 145A', 'carPicture' => 'Jaguar xf 2015.jpg', 'isAvailable' => true],
            ['brand' => 'Nissan', 'model' => 'Alphard', 'category' => 'Van', 'seats' => 8, 'dailyRate' => 115000, 'numberPlate' => 'UBB 146A', 'carPicture' => 'Alphard.jpeg', 'isAvailable' => true],
        ];

        foreach ($cars as $car) {
            Car::firstOrCreate(
                ['numberPlate' => $car['numberPlate']],
                $car
            );
        }

        echo "✓ " . count($cars) . " cars seeded successfully!\n";
    }
}
