<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class NewSellerRegisteredNotification extends Notification
{
    use Queueable;

    public $seller;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $seller)
    {
        $this->seller = $seller;
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
            'seller_id' => $this->seller->id,
            'name' => $this->seller->name,
            'store_name' => $this->seller->sellerProfile->store_name ?? 'N/A',
            'message' => 'New seller registered: ' . ($this->seller->sellerProfile->store_name ?? $this->seller->name),
            'url' => route('admin.sellers'), // Redirect to sellers list
        ];
    }
}
