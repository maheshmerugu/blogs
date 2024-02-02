<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\FirebaseMessaging;
use Kreait\Laravel\Firebase\Messaging\FirebaseChannel;

class PushNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
      return [FirebaseChannel::class];
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



   

public function toFirebase($notifiable)
{
    return CloudMessage::new()
        ->withNotification([
            'title' => 'Notification Title',
            'body' => 'Notification Body',
        ])
        ->withData([
            'key1' => 'f6t-KS77TkPbnHPg-aEWcQ:APA91bGWQjgNufqsgE_nDEJlbmgn-BiJFijuoF5KW_LTiSfjEDz0Dj5naS13zXqo4ILVDidJ-eqjnjI10amA4Cly5o-9ZmP8PWy9yAzGj2GvTV60kXB9o2sozWutdLMuDzXr-3_yNEA5',
            'key2' => 'f6t-KS77TkPbnHPg-aEWcQ:APA91bGWQjgNufqsgE_nDEJlbmgn-BiJFijuoF5KW_LTiSfjEDz0Dj5naS13zXqo4ILVDidJ-eqjnjI10amA4Cly5o-9ZmP8PWy9yAzGj2GvTV60kXB9o2sozWutdLMuDzXr-3_yNEA5',
        ]);
}
}
