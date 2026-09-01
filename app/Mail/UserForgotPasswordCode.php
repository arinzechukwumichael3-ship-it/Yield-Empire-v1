<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserForgotPasswordCode extends Mailable
{
    use Queueable, SerializesModels;

    public $username;
    public $pwdCode;
    public $email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($username, $pwdCode, $email = null)
    {
        $this->username = $username;
        $this->pwdCode = $pwdCode;
        $this->email = $email;
    }

    public function build()
    {
        $unsubscribeUrl = email_unsubscribe_url($this->email);

        return $this->from('otp@yieldempire.org', 'YieldEmpire')
            ->replyTo('support@yieldempire.org', 'YieldEmpire Support')
            ->view('mail-templates.user._forgot_password')
            ->text('mail-templates.user._forgot_password_text')
            ->with([
                'username' =>  $this->username,
                'code' => $this->pwdCode,
                'unsubscribe_url' => $unsubscribeUrl,
            ]);
    }
}
