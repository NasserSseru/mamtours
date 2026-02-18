<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\AuditLog;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::all();
        return response()->json($cars);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'numberPlate' => [
                'required',
                'string',
                'max:50',
                'unique:cars',
                'regex:/^(U[A-Z]{2}\s?\d{3}[A-Z]|UG\s?\d{2}\s?\d{5})$/i'
            ],
            'dailyRate' => 'required|numeric|min:1',
            'seats' => 'required|integer|min:1|max:50',
            'category' => 'nullable|string|max:50',
        ], [
            'numberPlate.regex' => 'Invalid number plate format. Use UAJ 979B (legacy) or UG 32 00042 (digital) format.'
        ]);

        // Handle car picture upload
        $carPicturePath = null;
        if ($request->hasFile('car_picture')) {
            try {
                $file = $request->file('car_picture');
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                
                // Ensure images directory exists
                if (!file_exists(public_path('images'))) {
                    mkdir(public_path('images'), 0755, true);
                }
                
                // Move to public/images directory (same as existing cars)
                $file->move(public_path('images'), $filename);
                $carPicturePath = 'images/' . $filename;
            } catch (\Exception $e) {
                \Log::error('Image upload failed: ' . $e->getMessage());
                return response()->json(['error' => 'Failed to upload image: ' . $e->getMessage()], 500);
            }
        }

        $carData = [
            'brand' => $validated['brand'],
            'model' => $validated['model'],
            'numberPlate' => strtoupper($validated['numberPlate']),
            'dailyRate' => $validated['dailyRate'],
            'seats' => $validated['seats'],
            'category' => $validated['category'] ?? null,
            'isAvailable' => true,
        ];

        // Add image path if uploaded (column name is 'carPicture')
        if ($carPicturePath) {
            $carData['carPicture'] = $carPicturePath;
        }

        try {
            $car = Car::create($carData);
        } catch (\Exception $e) {
            \Log::error('Car creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create vehicle: ' . $e->getMessage()], 500);
        }

        AuditLog::create([
            'action' => 'car.create',
            'details' => ['carId' => $car->id, 'plate' => $car->numberPlate],
            'at' => now(),
        ]);

        return response()->json(['message' => 'Car added successfully', 'car' => $car], 201);
    }

    public function show($id)
    {
        $car = Car::find($id);
        if (!$car) {
            return response()->json(['error' => 'Car not found'], 404);
        }
        
        // Track car view
        app(AnalyticsService::class)->trackCarView($car->id, [
            'brand' => $car->brand,
            'model' => $car->model,
            'daily_rate' => $car->dailyRate,
        ]);
        
        return response()->json($car);
    }
    

    public function update(Request $request, $id)
    {
        $car = Car::find($id);
        if (!$car) {
            return response()->json(['error' => 'Car not found'], 404);
        }

        // Handle both PUT/PATCH and POST with _method override
        $method = $request->input('_method', $request->method());

        $validated = $request->validate([
            'car_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'numberPlate' => [
                'nullable',
                'string',
                'max:50',
                'unique:cars,numberPlate,' . $id,
                'regex:/^(U[A-Z]{2}\s?\d{3}[A-Z]|UG\s?\d{2}\s?\d{5})$/i'
            ],
            'dailyRate' => 'nullable|numeric|min:1',
            'seats' => 'nullable|integer|min:1|max:50',
            'isAvailable' => 'nullable|boolean',
            'category' => 'nullable|string|max:50',
        ], [
            'numberPlate.regex' => 'Invalid number plate format. Use UAJ 979B (legacy) or UG 32 00042 (digital) format.'
        ]);

        // Handle car picture upload
        if ($request->hasFile('car_picture')) {
            // Delete old car picture if exists in public/images
            if ($car->carPicture && file_exists(public_path($car->carPicture))) {
                unlink(public_path($car->carPicture));
            }

            // Store new picture in public/images directory
            $file = $request->file('car_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);
            
            // Set the path (column name is 'carPicture')
            $validated['carPicture'] = 'images/' . $filename;
        }

        if (isset($validated['numberPlate'])) {
            $validated['numberPlate'] = strtoupper($validated['numberPlate']);
        }

        // Remove _method from validated data if present
        unset($validated['_method']);

        $car->update($validated);

        AuditLog::create([
            'action' => 'car.update',
            'details' => ['carId' => $car->id, 'plate' => $car->numberPlate],
            'at' => now(),
        ]);

        return response()->json(['message' => 'Car updated successfully', 'car' => $car]);
    }

    public function destroy($id)
    {
        $car = Car::find($id);
        if (!$car) {
            return response()->json(['error' => 'Car not found'], 404);
        }

        $car->delete();
        return response()->json(['message' => 'Car deleted successfully']);
    }
}
