<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class newBookingForRenter extends Notification
{
    use Queueable;

    private $booking;
    private $renter;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public function __construct($booking, $renter)
    {
        $this->booking = $booking;
        $this->renter = $renter;
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
            $booking_user = $this->booking->user->full_name.', '.$this->booking->user->userCountry->name.'<br>'.
                $this->booking->user->phone.', '.$this->booking->user->email;
        }
        else
        {
            $booking_user = $this->booking->customer->full_name.', '.$this->booking->customer->customerCountry->name.'<br>'.
                $this->booking->customer->phone.', '.$this->booking->customer->email;
        }

        return (new MailMessage)
            ->subject('Nova rezervacija')
            ->greeting(trans('main.dear').' '.$this->renter->full_name.',')
            ->line('napravljena je nova rezervacija za vašu vilu.<br><br>')
            ->line($this->booking->villa->name.'<br>'.date('d.m.Y.', strtotime($this->booking->start_date)).
                ' - '.date('d.m.Y.', strtotime($this->booking->end_date)).'<br><br>'.$booking_user.'<br><br>Način plaćanja: '.
                trans('main.'.$this->booking->downpaymentType->code).', '. trans('main.'.$this->booking->remainingPaymentType->code))
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
