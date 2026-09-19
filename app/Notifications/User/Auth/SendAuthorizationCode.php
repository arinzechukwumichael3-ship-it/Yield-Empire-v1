<?php

namespace App\Notifications\User\Auth;

use App\Mail\UserOTP;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

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
        return (new UserOTP($notifiable, $this->data))
            ->to($notifiable->email);
    }
}
