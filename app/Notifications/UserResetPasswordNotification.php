<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
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
          ->subject('Link resetujący hasło '.config('app.name'))
          ->greeting('Witaj')
          ->line('Aby zresetować hasło, kliknij poniższy przycisk.')
          ->action('Resetuj Hasło', url(env('APP_URL_APP').'/password/reset/'.$this->token))
          ->salutation('Dziękujemy, <br/> zespół '.config('app.name'))
          ->attach(realpath('img/tell-it-us_logo_mail.png'), ['as' => 'tell-it-us_logo_mail', 'mime' => 'image/png']);
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
