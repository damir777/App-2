<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class confirmBooking extends Notification
{
    use Queueable;

    private $booking;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public function __construct($booking)
    {
        $this->booking = $booking;
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
            ->subject('Potvrda rezervacije '.$this->booking->villa->name)
            ->greeting(trans('main.dear').' '.$booking_user.',')
            ->line('vaša rezervacija za '.$this->booking->villa->name. ' ('.
                date('d.m.Y.', strtotime($this->booking->start_date)).' - '.
                date('d.m.Y.', strtotime($this->booking->start_date)).') je potvrđena.')
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
