<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdmissionRewardApproved extends Notification implements ShouldQueue
{
    use Queueable;
    private $institution_id;
    private $amount;
    /**
     * Create a new notification instance.
     */
    public function __construct($institution_id, $amount)
    {
        $this->institution_id = $institution_id;
        $this->amount = $amount;
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
            'institution_id' => $this->institution_id,
            'message' => 'Your have new admission commission due of Rs. '.$this->amount,
        ];
    }
}
