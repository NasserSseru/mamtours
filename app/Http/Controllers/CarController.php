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

        // Handle car picture upload only if file is present
        $carPicturePath = null;
        if ($request->hasFile('car_picture') && $request->file('car_picture')->isValid()) {
            try {
                $file = $request->file('car_picture');
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                
                // Store in storage/app/public/cars directory (Laravel standard)
                $path = $file->storeAs('cars', $filename, 'public');
                $carPicturePath = $path; // Will be like 'cars/filename.jpg'
                
                \Log::info('Image uploaded successfully: ' . $carPicturePath);
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
            
            return response()->json(['message' => 'Car added successfully', 'car' => $car], 201);
        } catch (\Exception $e) {
            \Log::error('Car creation failed: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Failed to create vehicle', 
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => basename($e->getFile())
            ], 500);
        }
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
            // Delete old car picture if exists
            if ($car->carPicture && \Storage::disk('public')->exists($car->carPicture)) {
                \Storage::disk('public')->delete($car->carPicture);
            }

            // Store new picture in storage/app/public/cars directory
            $file = $request->file('car_picture');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $path = $file->storeAs('cars', $filename, 'public');
            
            // Set the path (column name is 'carPicture')
            $validated['carPicture'] = $path;
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
