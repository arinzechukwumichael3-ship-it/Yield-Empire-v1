<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegister extends Mailable
{
    use Queueable, SerializesModels;

    public $first_name;
    public $code;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($first_name, $code)
    {
        $this->first_name = $first_name;
        $this->code = $code;
    }

    public function build()
    {
        $unsubscribeUrl = email_unsubscribe_url(base64_decode($this->code));

        return $this->from('otp@yieldempire.org', 'YieldEmpire')
            ->replyTo('support@yieldempire.org', 'YieldEmpire Support')
            ->view('mail-templates.user._registration')
            ->text('mail-templates.user._registration_text')
            ->with([
                'name' =>  $this->first_name,
                'code' => $this->code,
                'unsubscribe_url' => $unsubscribeUrl,
            ]);
    }
}
