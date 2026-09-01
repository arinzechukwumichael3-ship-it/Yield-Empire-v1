<?php

namespace App\Notifications\User\Auth;

use App\Mail\WelcomeNotificationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Thin notification wrapper that sends the branded, multipart welcome email
 * (App\Mail\WelcomeNotificationMail). Kept as a Notification so callers can
 * keep using `$user->notify(new WelcomeNotification())`.
 *
 * Sends synchronously (no queue) so the welcome mail never fails silently if
 * the queue worker is down — matches the OTP email behaviour.
 */
class WelcomeNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return new WelcomeNotificationMail($notifiable);
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
