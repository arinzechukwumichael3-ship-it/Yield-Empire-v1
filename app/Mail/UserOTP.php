<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class UserOTP extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $data;
    public $unsubscribeUrl;

    public function __construct($user, $data)
    {
        $this->user = $user;
        $this->data = $data;
        $this->unsubscribeUrl = URL::temporarySignedRoute(
            'email.unsubscribe',
            now()->addDays(60),
            ['email' => $user->email, 'id' => $user->id]
        );
    }

    public function build()
    {
        return $this->from('support@yieldempire.org', 'YieldEmpire')
            ->replyTo('support@yieldempire.org', 'YieldEmpire Support')
            ->subject('Verify your YieldEmpire account')
            ->view('mail-templates.user._otp_verify')
            ->with([
                'user' => $this->user,
                'data' => $this->data,
                'unsubscribe_url' => $this->unsubscribeUrl,
            ]);
    }
}
