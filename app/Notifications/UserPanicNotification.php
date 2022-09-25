<?php

namespace App\Notifications;

use App\Panic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserPanicNotification extends Notification
{
    use Queueable;

    protected $panic;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Panic $panic)
    {
        $this->panic = $panic;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function toBroadcast()
    {
        return new BroadcastMessage([
            'user_id' => $this->panic->user_id,
            'order_id' => $this->panic->order_id,
            'location' => [
                'name' => $this->panic->location_name,
                'latitude' => $this->panic->latitude,
                'longitude' => $this->panic->longitude,
            ],
            'time_created' => $this->panic->created_at
        ]);
    }
}
