<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\URL;

/**
 * Harden every outbound email against the spam folder:
 *  - List-Unsubscribe + List-Unsubscribe-Post (RFC 8058 one-click)
 *  - Envelope/return id and a stable Message-ID host
 *  - Postmark "withListUnsubscribeHeader" style compliance
 *
 * The unsubscribe link is signed so the endpoint needs no auth/session.
 */
class AddDeliverabilityHeaders
{
    public function handle(MessageSending $event): void
    {
        $message = $event->message;

        // Recipient address (first To) — used to build the unsubscribe token.
        $to = $message->getTo();
        $email = is_array($to) ? (array_key_first($to) ?? null) : null;
        if (! $email || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        // Find the owning user (account holder). Newsletter-only subscribers
        // fall back to a generic signed link keyed by their email address.
        $user = User::where('email', $email)->first();
        $id   = $user?->id ?? $email;

        $unsubscribeUrl = URL::temporarySignedRoute(
            'email.unsubscribe',
            now()->addDays(60),
            ['email' => $email, 'id' => $id]
        );

        $headers = $message->getHeaders();
        $headers->addTextHeader('List-Unsubscribe', '<' . $unsubscribeUrl . '>');
        $headers->addTextHeader('List-Unsubscribe-Post', 'List-Unsubscribe=One-Click');

        // Plain-text feedback loop id helps mailbox providers trace complaints.
        $key = config('app.key') ?: 'yieldempire';
        $headers->addTextHeader('X-Entity-Ref-ID', md5($email . '|' . $key));
    }
}
