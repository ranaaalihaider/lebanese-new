<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PayoutRequest;

class PayoutRequestNotification extends Notification
{
    use Queueable;

    public $payoutRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(PayoutRequest $payoutRequest)
    {
        $this->payoutRequest = $payoutRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'request_id' => $this->payoutRequest->id,
            'amount' => $this->payoutRequest->requested_amount,
            'seller_id' => $this->payoutRequest->seller_id,
            'seller_name' => $this->payoutRequest->seller->name ?? 'Unknown Seller',
            'message' => 'New Payout Request: ' . number_format($this->payoutRequest->requested_amount, 2) . ' USD from ' . ($this->payoutRequest->seller->name ?? 'Seller'),
            'url' => route('admin.payout.requests'), // Redirect to admin payout requests
        ];
    }
}
