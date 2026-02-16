<?php

namespace App\Jobs;

use App\Services\WebhookService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeliverWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [60, 300, 900]; // 1min, 5min, 15min

    protected $deliveryId;

    public function __construct(int $deliveryId)
    {
        $this->deliveryId = $deliveryId;
    }

    public function handle(WebhookService $webhookService): void
    {
        $webhookService->deliver($this->deliveryId);
    }

    public function failed(\Throwable $exception): void
    {
        logger()->error('Webhook delivery job failed permanently', [
            'delivery_id' => $this->deliveryId,
            'error' => $exception->getMessage(),
        ]);
    }
}
