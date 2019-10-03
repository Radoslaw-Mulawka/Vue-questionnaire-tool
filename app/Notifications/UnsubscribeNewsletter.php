<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UnsubscribeNewsletter extends Notification {

  use Queueable;

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
    
    return (new MailMessage)
        ->subject('Newsletter ' . config('app.name'))
        ->greeting('Witaj')
        ->line('Zrezygnowałeś z korzystania z powiadomień Tell-it.us. Od tej pory nie będziesz dostawać maili informujących o nowościach w systemie.')
        ->salutation('Dziękujemy, <br/> zespół ' . config('app.name'))
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
