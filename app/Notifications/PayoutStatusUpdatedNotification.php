<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PayoutStatusUpdatedNotification extends Notification
{
    use Queueable;

    public $payoutRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(\App\Models\PayoutRequest $payoutRequest)
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
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'payout_request_id' => $this->payoutRequest->id,
            'amount' => $this->payoutRequest->requested_amount,
            'status' => $this->payoutRequest->status,
            'message' => 'Your Payout Request of ' . number_format($this->payoutRequest->requested_amount, 2) . ' USD has been ' . ucfirst($this->payoutRequest->status) . '.',
            'url' => route('seller.payout.requests', ['status' => $this->payoutRequest->status]),
        ];
    }
}
