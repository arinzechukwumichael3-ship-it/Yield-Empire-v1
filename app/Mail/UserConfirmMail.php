<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserConfirmMail extends Mailable
{
    use Queueable, SerializesModels;

    public $first_name;
    public $email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($first_name, $email)
    {
        $this->first_name = $first_name;
        $this->email = $email;
    }

    public function build()
    {
        $unsubscribeUrl = email_unsubscribe_url($this->email);

        return $this->from('otp@yieldempire.org', 'YieldEmpire')
            ->replyTo('support@yieldempire.org', 'YieldEmpire Support')
            ->view('mail-templates.user._user_mail_confirm')
            ->text('mail-templates.user._user_mail_confirm_text')
            ->with([
                'name' =>  $this->first_name,
                'email' => $this->email,
                'unsubscribe_url' => $unsubscribeUrl,
            ]);
    }
}
