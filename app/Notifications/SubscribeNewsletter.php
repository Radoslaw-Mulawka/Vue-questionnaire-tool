<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SubscribeNewsletter extends Notification {

  use Queueable;

  private $unsubscribeHash;
  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct($unsubscribeHash) {
    $this->unsubscribeHash = $unsubscribeHash;
  }

  /**
   * Get the notification's delivery channels.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function via($notifiable) {
    return ['mail'];
  }

  /**
   * Get the mail representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  public function toMail($notifiable) {
     $url = url('unsubscribe', $this->unsubscribeHash);
    return (new MailMessage)
        ->subject('Newsletter ' . config('app.name'))
        ->greeting('Witaj')
        ->line('Dziękujemy Ci za zapisanie się do naszego newslettera.')
        ->line('Dzięki niemu, będziesz zawsze na bieżąco o nowościach w '.config('app.name'))
        ->line('Jeśli, chcesz zrezygnować z korzystania z automatycznych powiadomień  kliknij poniższy przycisk. ')
        ->action('Wypisz się', $url)
        ->salutation('Dziękujemy, <br/>zespół ' . config('app.name'))
        ->attach(realpath('img/tell-it-us_logo_mail.png'), ['as' => 'tell-it-us_logo_mail', 'mime' => 'image/png']);
  }

  /**
   * Get the array representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function toArray($notifiable) {
    return [
      //
    ];
  }

}
