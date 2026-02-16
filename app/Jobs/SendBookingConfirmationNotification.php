<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendBookingConfirmationNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $booking;
    protected $status;

    public function __construct(Booking $booking, $status = 'confirmed')
    {
        $this->booking = $booking;
        $this->status = $status;
    }

    public function handle()
    {
        try {
            $booking = $this->booking;
            
            // Send email notification
            $this->sendEmailNotification($booking);
            
            // Send SMS notification
            $this->sendSmsNotification($booking);
            
            Log::info("Booking confirmation notification sent for booking #{$booking->id}");
        } catch (\Exception $e) {
            Log::error("Failed to send booking confirmation: " . $e->getMessage());
            throw $e;
        }
    }

    private function sendEmailNotification($booking)
    {
        $subject = $this->status === 'confirmed' 
            ? 'Your Booking is Confirmed! 🎉' 
            : 'Booking Status Update';
        
        $message = $this->status === 'confirmed'
            ? "Your booking for {$booking->car->brand} {$booking->car->model} has been confirmed!"
            : "Your booking status has been updated.";
        
        $emailContent = $this->buildEmailContent($booking, $message);
        
        try {
            Mail::send('emails.booking-confirmation', [
                'booking' => $booking,
                'status' => $this->status,
                'message' => $message
            ], function ($mail) use ($booking, $subject) {
                $mail->to($booking->customerEmail)
                    ->subject($subject)
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });
            
            Log::info("Email sent to {$booking->customerEmail} for booking #{$booking->id}");
        } catch (\Exception $e) {
            Log::error("Failed to send email: " . $e->getMessage());
        }
    }

    private function sendSmsNotification($booking)
    {
        if (!$booking->customerPhone) {
            return;
        }
        
        $message = $this->status === 'confirmed'
            ? "MAM Tours: Your booking for {$booking->car->brand} {$booking->car->model} from {$booking->startDate} to {$booking->endDate} is confirmed! Total: UGX " . number_format($booking->totalPrice) . ". Thank you!"
            : "MAM Tours: Your booking #{$booking->id} status has been updated to {$this->status}.";
        
        try {
            // Using Africa's Talking SMS
            $this->sendAfricasTalkingSms($booking->customerPhone, $message);
            
            Log::info("SMS sent to {$booking->customerPhone} for booking #{$booking->id}");
        } catch (\Exception $e) {
            Log::error("Failed to send SMS: " . $e->getMessage());
        }
    }

    private function sendAfricasTalkingSms($phone, $message)
    {
        $username = config('services.africas_talking.username');
        $apiKey = config('services.africas_talking.api_key');
        
        if (!$username || !$apiKey) {
            Log::warning("Africa's Talking credentials not configured");
            return;
        }
        
        $url = 'https://api.sandbox.africastalking.com/version1/messaging';
        
        $data = [
            'username' => $username,
            'to' => $phone,
            'message' => $message
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded',
            'apiKey: ' . $apiKey
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
    }

    private function buildEmailContent($booking, $message)
    {
        $startDate = \Carbon\Carbon::parse($booking->startDate)->format('M d, Y');
        $endDate = \Carbon\Carbon::parse($booking->endDate)->format('M d, Y');
        $days = \Carbon\Carbon::parse($booking->startDate)->diffInDays($booking->endDate);
        
        return [
            'customerName' => $booking->customerName,
            'carBrand' => $booking->car->brand,
            'carModel' => $booking->car->model,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'days' => $days,
            'dailyRate' => $booking->car->dailyRate,
            'totalPrice' => $booking->totalPrice,
            'message' => $message,
            'status' => $this->status
        ];
    }
}
