<?php

namespace App\Notifications\User\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetEmail extends Notification
{
    use Queueable;

    public $user;
    public $password_reset;

    public function __construct($user, $password_reset)
    {
        $this->user = $user;
        $this->password_reset = $password_reset;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $user = $this->user;
        $password_reset = $this->password_reset;

        return (new MailMessage)
            ->subject('Password Reset Code - YieldEmpire')
            ->view('mail-templates.user._password_reset', [
                'user' => $user,
                'password_reset' => $password_reset,
            ]);
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
