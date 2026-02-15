<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendBookingSmsNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [60, 300, 900]; // 1min, 5min, 15min
    public $timeout = 120;

    protected Booking $booking;
    protected string $messageType;

    /**
     * Create a new job instance.
     */
    public function __construct(Booking $booking, string $messageType = 'confirmation')
    {
        $this->booking = $booking;
        $this->messageType = $messageType;
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationService $notificationService): void
    {
        try {
            switch ($this->messageType) {
                case 'confirmation':
                    $notificationService->sendBookingConfirmationSms($this->booking);
                    break;
                case 'reminder':
                    $notificationService->sendBookingReminderSms($this->booking);
                    break;
                case 'payment':
                    $notificationService->sendPaymentConfirmationSms($this->booking);
                    break;
                default:
                    throw new \InvalidArgumentException("Unknown message type: {$this->messageType}");
            }
            
            Log::info('Booking SMS sent', [
                'booking_id' => $this->booking->id,
                'type' => $this->messageType,
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to send booking SMS', [
                'booking_id' => $this->booking->id,
                'type' => $this->messageType,
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        Log::error('Booking SMS job failed after all retries', [
            'booking_id' => $this->booking->id,
            'type' => $this->messageType,
            'error' => $exception->getMessage(),
        ]);

        if (app()->bound('sentry')) {
            app('sentry')->captureException($exception);
        }
    }
}
