<?php

namespace App\Http\Controllers;

use App\Services\WebhookService;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    protected $webhookService;

    public function __construct(WebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    /**
     * Register a new webhook endpoint
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url',
            'events' => 'nullable|array',
            'events.*' => 'string|in:booking.created,booking.confirmed,booking.cancelled,car.created,car.updated',
        ]);

        $webhook = $this->webhookService->registerEndpoint(
            $validated['url'],
            $validated['events'] ?? []
        );

        return response()->json([
            'message' => 'Webhook registered successfully',
            'webhook' => $webhook,
        ], 201);
    }

    /**
     * Receive incoming webhook (for external services)
     */
    public function receive(Request $request)
    {
        $signature = $request->header('X-Webhook-Signature');
        $secret = config('app.webhook_secret');

        if (!$signature || !$this->webhookService->verifySignature($request->getContent(), $signature, $secret)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $eventType = $request->header('X-Webhook-Event');
        $payload = $request->all();

        // Process the webhook based on event type
        logger()->info('Webhook received', [
            'event_type' => $eventType,
            'payload' => $payload,
        ]);

        return response()->json(['message' => 'Webhook received'], 200);
    }

    /**
     * Get webhook delivery status
     */
    public function status($deliveryId)
    {
        $status = $this->webhookService->getDeliveryStatus($deliveryId);

        if (!$status) {
            return response()->json(['error' => 'Delivery not found'], 404);
        }

        return response()->json($status);
    }
}
