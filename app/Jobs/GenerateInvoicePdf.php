<?php

namespace App\Jobs;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GenerateInvoicePdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [30, 60, 120]; // 30s, 1min, 2min
    public $timeout = 180;

    protected Booking $booking;

    /**
     * Create a new job instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Generate PDF
            $pdf = Pdf::loadView('invoices.booking', [
                'booking' => $this->booking->load('car', 'user'),
            ]);

            // Store PDF
            $filename = "invoice_{$this->booking->id}_" . time() . ".pdf";
            $path = "invoices/{$filename}";
            
            Storage::disk('local')->put($path, $pdf->output());

            // Update booking with invoice path
            $this->booking->update([
                'invoice_path' => $path,
            ]);

            Log::info('Invoice PDF generated', [
                'booking_id' => $this->booking->id,
                'path' => $path,
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to generate invoice PDF', [
                'booking_id' => $this->booking->id,
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
        Log::error('Invoice PDF generation job failed after all retries', [
            'booking_id' => $this->booking->id,
            'error' => $exception->getMessage(),
        ]);

        if (app()->bound('sentry')) {
            app('sentry')->captureException($exception);
        }
    }
}
