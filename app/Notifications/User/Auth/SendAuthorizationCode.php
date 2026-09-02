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

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $data = $this->data;

        $unsubscribeUrl = URL::temporarySignedRoute(
            'email.unsubscribe',
            now()->addDays(60),
            ['email' => $notifiable->email, 'id' => $notifiable->id]
        );

        return (new MailMessage)
                    ->subject('Your YieldEmpire verification code: ' . $data->code)
                    ->view('mail-templates.user._otp_verify', [
                        'user' => $notifiable,
                        'data' => $data,
                        'unsubscribeUrl' => $unsubscribeUrl,
                    ]);
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
