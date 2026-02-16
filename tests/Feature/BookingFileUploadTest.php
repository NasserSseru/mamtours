<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Car;
use App\Models\Booking;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class BookingFileUploadTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_can_create_booking_with_file_upload()
    {
        // Create a test car
        $car = Car::factory()->create([
            'isAvailable' => true,
            'dailyRate' => 100000,
        ]);

        // Create a fake file
        $file = UploadedFile::fake()->image('id.jpg', 640, 480);

        // Prepare booking data
        $bookingData = [
            'carId' => $car->id,
            'customerName' => 'John Doe',
            'customerEmail' => 'john@example.com',
            'customerPhone' => '+256700000000',
            'startDate' => '2026-02-20',
            'endDate' => '2026-02-25',
            'paymentMethod' => 'mtn_mobile_money',
            'mobileMoneyNumber' => '+256700000000',
            'idType' => 'nin',
            'idNumber' => '12345678',
            'idDocument' => $file,
        ];

        // Submit booking
        $response = $this->post('/api/bookings', $bookingData);

        // Assert response
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'booking' => [
                'id',
                'car_id',
                'customerName',
                'customerEmail',
                'customerPhone',
                'startDate',
                'endDate',
                'status',
                'totalPrice',
                'idType',
                'idNumber',
                'idDocument',
            ],
            'redirect_url',
        ]);

        // Assert booking was created
        $this->assertDatabaseHas('bookings', [
            'car_id' => $car->id,
            'customerName' => 'John Doe',
            'customerEmail' => 'john@example.com',
            'customerPhone' => '+256700000000',
            'status' => 'pending',
            'idType' => 'nin',
            'idNumber' => '12345678',
        ]);

        // Assert file was uploaded
        $booking = Booking::where('customerEmail', 'john@example.com')->first();
        $this->assertNotNull($booking->idDocument);
        Storage::disk('public')->assertExists($booking->idDocument);

        // Assert car is now unavailable
        $this->assertFalse($car->fresh()->isAvailable);

        echo "\n✓ Booking created successfully with ID: " . $booking->id;
        echo "\n✓ File uploaded to: " . $booking->idDocument;
        echo "\n✓ Car marked as unavailable";
        echo "\n✓ Total price: " . $booking->totalPrice;
    }

    public function test_booking_validation_fails_without_file()
    {
        $car = Car::factory()->create(['isAvailable' => true]);

        $bookingData = [
            'carId' => $car->id,
            'customerName' => 'John Doe',
            'customerEmail' => 'john@example.com',
            'customerPhone' => '+256700000000',
            'startDate' => '2026-02-20',
            'endDate' => '2026-02-25',
            'paymentMethod' => 'mtn_mobile_money',
            'idType' => 'nin',
            'idNumber' => '12345678',
            // Missing idDocument
        ];

        $response = $this->post('/api/bookings', $bookingData);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => ['idDocument'],
        ]);

        echo "\n✓ Validation correctly rejects booking without file";
    }

    public function test_booking_validation_fails_with_invalid_file_type()
    {
        $car = Car::factory()->create(['isAvailable' => true]);

        // Create a fake text file (invalid)
        $file = UploadedFile::fake()->create('document.txt', 100);

        $bookingData = [
            'carId' => $car->id,
            'customerName' => 'John Doe',
            'customerEmail' => 'john@example.com',
            'customerPhone' => '+256700000000',
            'startDate' => '2026-02-20',
            'endDate' => '2026-02-25',
            'paymentMethod' => 'mtn_mobile_money',
            'idType' => 'nin',
            'idNumber' => '12345678',
            'idDocument' => $file,
        ];

        $response = $this->post('/api/bookings', $bookingData);

        $response->assertStatus(422);

        echo "\n✓ Validation correctly rejects invalid file type";
    }

    public function test_booking_validation_fails_with_oversized_file()
    {
        $car = Car::factory()->create(['isAvailable' => true]);

        // Create a fake file larger than 5MB
        $file = UploadedFile::fake()->image('id.jpg')->size(6000); // 6MB

        $bookingData = [
            'carId' => $car->id,
            'customerName' => 'John Doe',
            'customerEmail' => 'john@example.com',
            'customerPhone' => '+256700000000',
            'startDate' => '2026-02-20',
            'endDate' => '2026-02-25',
            'paymentMethod' => 'mtn_mobile_money',
            'idType' => 'nin',
            'idNumber' => '12345678',
            'idDocument' => $file,
        ];

        $response = $this->post('/api/bookings', $bookingData);

        $response->assertStatus(422);

        echo "\n✓ Validation correctly rejects oversized file";
    }

    public function test_booking_validation_fails_with_invalid_dates()
    {
        $car = Car::factory()->create(['isAvailable' => true]);
        $file = UploadedFile::fake()->image('id.jpg');

        $bookingData = [
            'carId' => $car->id,
            'customerName' => 'John Doe',
            'customerEmail' => 'john@example.com',
            'customerPhone' => '+256700000000',
            'startDate' => '2026-02-25',
            'endDate' => '2026-02-20', // End date before start date
            'paymentMethod' => 'mtn_mobile_money',
            'idType' => 'nin',
            'idNumber' => '12345678',
            'idDocument' => $file,
        ];

        $response = $this->post('/api/bookings', $bookingData);

        $response->assertStatus(422);

        echo "\n✓ Validation correctly rejects invalid dates";
    }

    public function test_booking_fails_if_car_unavailable()
    {
        $car = Car::factory()->create(['isAvailable' => false]);
        $file = UploadedFile::fake()->image('id.jpg');

        $bookingData = [
            'carId' => $car->id,
            'customerName' => 'John Doe',
            'customerEmail' => 'john@example.com',
            'customerPhone' => '+256700000000',
            'startDate' => '2026-02-20',
            'endDate' => '2026-02-25',
            'paymentMethod' => 'mtn_mobile_money',
            'idType' => 'nin',
            'idNumber' => '12345678',
            'idDocument' => $file,
        ];

        $response = $this->post('/api/bookings', $bookingData);

        $response->assertStatus(400);
        $response->assertJsonStructure(['message']);

        echo "\n✓ Booking correctly rejected for unavailable car";
    }

    public function test_booking_with_passport_instead_of_nin()
    {
        $car = Car::factory()->create([
            'isAvailable' => true,
            'dailyRate' => 100000,
        ]);

        $file = UploadedFile::fake()->image('passport.jpg');

        $bookingData = [
            'carId' => $car->id,
            'customerName' => 'Jane Doe',
            'customerEmail' => 'jane@example.com',
            'customerPhone' => '+256700000001',
            'startDate' => '2026-02-20',
            'endDate' => '2026-02-25',
            'paymentMethod' => 'airtel_money',
            'mobileMoneyNumber' => '+256700000001',
            'idType' => 'passport',
            'idNumber' => 'AB123456',
            'idDocument' => $file,
        ];

        $response = $this->post('/api/bookings', $bookingData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('bookings', [
            'customerEmail' => 'jane@example.com',
            'idType' => 'passport',
            'idNumber' => 'AB123456',
        ]);

        echo "\n✓ Booking created successfully with passport";
    }
}
