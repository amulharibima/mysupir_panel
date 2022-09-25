<?php

namespace App\Notifications;

use App\Panic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserPanicInfoNotification extends Notification
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
        return ['database'];
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

    public function toDatabase($notifiable)
    {
        $jam = $this->panic->created_at->format('H:i');

        return [
            'title' => 'Panic Button',
            'body' => 'Anda telah menggunakan fitur panic button pada jam '.$jam.' dan akan diproses lebih lanjut oleh admin. Mohon maaf atas ketidaknyamanannya dan terima kasih telah menggunakan layanan my supir',
            'panic_id' => $this->panic->id
        ];
    }
}
