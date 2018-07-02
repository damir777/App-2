<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class rejectBooking extends Notification
{
    use Queueable;

    private $booking;
    private $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public function __construct($booking, $message)
    {
        $this->booking = $booking;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if ($this->booking->user_id)
        {
            $booking_user = $this->booking->user->full_name;
        }
        else
        {
            $booking_user = $this->booking->customer->full_name;
        }

        return (new MailMessage)
            ->subject('Odbijanje rezervacije '.$this->booking->villa->name)
            ->greeting(trans('main.dear').' '.$booking_user.',')
            ->line($this->message)
            ->salutation(trans('main.email_salutation').'<br>xx');
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
}
