<?php

namespace App\Notifications\User\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class SendAuthorizationCode extends Notification
{
    use Queueable;

    protected $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
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
        $fullname = $notifiable->fullname;
        $data = $this->data;

        $unsubscribeUrl = URL::temporarySignedRoute(
            'email.unsubscribe',
            now()->addDays(60),
            ['email' => $notifiable->email, 'id' => $notifiable->id]
        );

        return (new MailMessage)
                    ->subject('Your YieldEmpire verification code')
                    ->greeting("Hello ".$fullname . "!")
                    ->line('Use the code below to verify your account and access your dashboard.')
                    ->line(mail_otp_box($data->code, 'Your verification code'))
                    ->line('If you did not request this code, you can safely ignore this email — no changes will be made to your account.')
                    ->line(new \Illuminate\Support\HtmlString('Prefer not to receive these emails? <a href="'.e($unsubscribeUrl).'">Unsubscribe</a>.'));
    }
    /**
     * Get the array representation of the notification.
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
