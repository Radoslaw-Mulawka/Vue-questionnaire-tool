<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailVerification extends Notification
{
    use Queueable;

    private $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
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
          //->view('email')
          ->subject('Rejestracja użytkownika '.config('app.name'))
          ->greeting('Witaj')
          ->line('Aby dokończyć rejestrację w systemie '.config('app.name').' prosimy o weryfikację adresu e mail.')
          ->line('Możesz to zrobić klikając w poniższy przycisk.')
          ->action('Zweryfikuj adres e-mail', url(env('APP_URL_APP').'/verify/'.$this->user->verification_hash))
          ->line('Jeśli nie rejestrowałeś się na naszej stronie, nie wykonuj żadnych akcji.')
          ->line('Ze względów bezpieczeństwa łącze wygaśnie w ciągu 24 godzin od momentu jego wysłania.')
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
