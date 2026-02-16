<?php

namespace App\Jobs;

use App\Models\KycVerification;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyKycStatusChange implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    protected $kycId;
    protected $status;

    public function __construct(int $kycId, string $status)
    {
        $this->kycId = $kycId;
        $this->status = $status;
    }

    public function handle(NotificationService $notificationService): void
    {
        $kyc = KycVerification::with('user')->find($this->kycId);
        
        if (!$kyc || !$kyc->user) {
            return;
        }

        $user = $kyc->user;

        // Send email notification
        $this->sendEmailNotification($kyc, $user);

        // Send SMS notification if phone number exists
        if ($user->phone) {
            $this->sendSmsNotification($kyc, $user, $notificationService);
        }

        // Create in-app notification
        $this->createInAppNotification($kyc, $user);
    }

    protected function sendEmailNotification(KycVerification $kyc, $user): void
    {
        $subject = $this->getEmailSubject();
        $message = $this->getEmailMessage($kyc);

        // Placeholder for actual email sending
        logger()->info('KYC status email sent', [
            'user_id' => $user->id,
            'status' => $this->status,
        ]);
    }

    protected function sendSmsNotification(KycVerification $kyc, $user, NotificationService $notificationService): void
    {
        $message = $this->getSmsMessage($kyc);
        
        $notificationService->sendSms($user->phone, $message);
    }

    protected function createInAppNotification(KycVerification $kyc, $user): void
    {
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'type' => 'kyc_status_change',
            'title' => $this->getNotificationTitle(),
            'message' => $this->getNotificationMessage($kyc),
            'read' => false,
        ]);
    }

    protected function getEmailSubject(): string
    {
        return match($this->status) {
            'verified' => 'KYC Verification Approved',
            'rejected' => 'KYC Verification Rejected',
            'additional_info_required' => 'Additional Information Required for KYC',
            default => 'KYC Status Update',
        };
    }

    protected function getEmailMessage(KycVerification $kyc): string
    {
        return match($this->status) {
            'verified' => 'Your KYC verification has been approved. You can now proceed with bookings.',
            'rejected' => 'Your KYC verification has been rejected. Reason: ' . ($kyc->rejection_reason ?? 'Not specified'),
            'additional_info_required' => 'We need additional information for your KYC verification. ' . ($kyc->additional_info_message ?? ''),
            default => 'Your KYC status has been updated.',
        };
    }

    protected function getSmsMessage(KycVerification $kyc): string
    {
        return match($this->status) {
            'verified' => 'MAM Tours: Your KYC verification has been approved!',
            'rejected' => 'MAM Tours: Your KYC verification was rejected. Please check your email for details.',
            'additional_info_required' => 'MAM Tours: Additional information required for your KYC. Please check your email.',
            default => 'MAM Tours: Your KYC status has been updated.',
        };
    }

    protected function getNotificationTitle(): string
    {
        return match($this->status) {
            'verified' => 'KYC Approved',
            'rejected' => 'KYC Rejected',
            'additional_info_required' => 'Additional Info Required',
            default => 'KYC Update',
        };
    }

    protected function getNotificationMessage(KycVerification $kyc): string
    {
        return $this->getEmailMessage($kyc);
    }
}
