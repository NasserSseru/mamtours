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
                
                // Upload to ImgBB if API key is configured
                $imgbbApiKey = env('IMGBB_API_KEY');
                if ($imgbbApiKey) {
                    $client = new \GuzzleHttp\Client();
                    $response = $client->post('https://api.imgbb.com/1/upload', [
                        'form_params' => [
                            'key' => $imgbbApiKey,
                            'image' => base64_encode(file_get_contents($file->getRealPath())),
                        ]
                    ]);
                    
                    $result = json_decode($response->getBody(), true);
                    if ($result['success']) {
                        $carPicturePath = $result['data']['url'];
                        \Log::info('Image uploaded to ImgBB successfully: ' . $carPicturePath);
                    } else {
                        throw new \Exception('ImgBB upload failed');
                    }
                } else {
                    // Fallback to local storage if ImgBB is not configured
                    $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                    $path = $file->storeAs('cars', $filename, 'public');
                    $carPicturePath = $path;
                    \Log::info('Image uploaded to local storage: ' . $carPicturePath);
                }
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
            // Delete old car picture if exists (only for local storage)
            if ($car->carPicture && !filter_var($car->carPicture, FILTER_VALIDATE_URL)) {
                if (\Storage::disk('public')->exists($car->carPicture)) {
                    \Storage::disk('public')->delete($car->carPicture);
                }
            }

            // Upload new picture
            $file = $request->file('car_picture');
            
            // Upload to ImgBB if API key is configured
            $imgbbApiKey = env('IMGBB_API_KEY');
            if ($imgbbApiKey) {
                try {
                    $client = new \GuzzleHttp\Client();
                    $response = $client->post('https://api.imgbb.com/1/upload', [
                        'form_params' => [
                            'key' => $imgbbApiKey,
                            'image' => base64_encode(file_get_contents($file->getRealPath())),
                        ]
                    ]);
                    
                    $result = json_decode($response->getBody(), true);
                    if ($result['success']) {
                        $validated['carPicture'] = $result['data']['url'];
                        \Log::info('Image uploaded to ImgBB successfully: ' . $validated['carPicture']);
                    } else {
                        throw new \Exception('ImgBB upload failed');
                    }
                } catch (\Exception $e) {
                    \Log::error('ImgBB upload failed: ' . $e->getMessage());
                    // Fallback to local storage
                    $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                    $path = $file->storeAs('cars', $filename, 'public');
                    $validated['carPicture'] = $path;
                }
            } else {
                // Fallback to local storage if ImgBB is not configured
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $path = $file->storeAs('cars', $filename, 'public');
                $validated['carPicture'] = $path;
            }
        }

        if (isset($validated['numberPlate'])) {
            $validated['numberPlate'] = strtoupper($validated['numberPlate']);
        }

        // Remove _method and car_picture from validated data if present
        unset($validated['_method']);
        unset($validated['car_picture']); // Remove the file input field name

        try {
            $car->update($validated);

            AuditLog::create([
                'action' => 'car.update',
                'details' => ['carId' => $car->id, 'plate' => $car->numberPlate],
                'at' => now(),
            ]);

            return response()->json(['message' => 'Car updated successfully', 'car' => $car]);
        } catch (\Exception $e) {
            \Log::error('Car update failed: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Failed to update vehicle',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => basename($e->getFile())
            ], 500);
        }
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
