<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class confirmAccount extends Notification
{
    use Queueable;

    private $token;
    private $redirect_url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public function __construct($token, $redirect_url)
    {
        $this->token = $token;
        $this->redirect_url = $redirect_url;
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
        return (new MailMessage)
            ->subject('Potvrda korisničkog računa')
            ->greeting(trans('main.email_greeting').',')
            ->line('zahvaljujemo se na registraciji. Da bi potvrdili svoj korisnički račun, molimo kliknite na Potvrdi račun.')
            ->action('Potvrdi račun', url(config('app.url').'/auth/confirm/'.$this->token.'?redirect_url='.$this->redirect_url))
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
