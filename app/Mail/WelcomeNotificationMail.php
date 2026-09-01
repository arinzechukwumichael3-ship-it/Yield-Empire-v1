<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

/**
 * Branded, multipart (HTML + plain-text) welcome email sent once after a
 * new account passes email verification. Carries a visible unsubscribe link
 * so it is CAN-SPAM / CASL compliant and Gmail/Outlook trust it more.
 */
class WelcomeNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public string $unsubscribeUrl;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->unsubscribeUrl = URL::temporarySignedRoute(
            'email.unsubscribe',
            now()->addDays(60),
            ['email' => $user->email, 'id' => $user->id]
        );
    }

    public function build()
    {
        $rows = [
            ['Bank Name', $this->user->network_bank_name ?? 'YieldEmpire'],
            ['Account Number', $this->user->network_account_number],
            ['IBAN', $this->user->network_iban],
            ['SWIFT / BIC', $this->user->network_swift ?? 'YELDUS33'],
        ];

        return $this->subject('Welcome to YieldEmpire - Your Account is Ready!')
            ->view('mail-templates.user._welcome_international')
            ->text('mail-templates.user._welcome_international_text')
            ->with([
                'user' => $this->user,
                'rows' => $rows,
                'unsubscribe_url' => $this->unsubscribeUrl,
            ]);
    }
}
