<?php

namespace App\Http\Controllers;

use App\Models\Frontend\Subscribe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

/**
 * One-click + landing unsubscribe endpoint referenced by the
 * List-Unsubscribe / List-Unsubscribe-Post headers we attach to every email.
 *
 * GET  -> friendly confirmation page (user clicked the link).
 * POST -> RFC 8058 one-click (mailbox provider posts here on behalf of user).
 */
class EmailUnsubscribeController extends Controller
{
    public function show(Request $request, string $email, $id = null)
    {
        if (! $this->isValidSignature($request, $email, $id)) {
            abort(403, 'Invalid or expired unsubscribe link.');
        }

        $this->markUnsubscribed($email, $id);

        return response()->view('email.unsubscribed', [
            'email' => $email,
        ]);
    }

    public function oneClick(Request $request, string $email, $id = null)
    {
        if (! $this->isValidSignature($request, $email, $id)) {
            abort(403, 'Invalid or expired unsubscribe link.');
        }

        $this->markUnsubscribed($email, $id);

        // RFC 8058 requires a 200/250-level response for one-click.
        return response()->noContent(200);
    }

    protected function isValidSignature(Request $request, string $email, $id): bool
    {
        if (! URL::hasValidSignature($request)) {
            return false;
        }

        // The id in the signed URL must match the email we resolve.
        if ($id !== null) {
            $user = User::where('email', $email)->first();
            if ($user && (string) $user->id !== (string) $id) {
                return false;
            }
        }

        return true;
    }

    protected function markUnsubscribed(string $email, $id): void
    {
        $user = ($id !== null)
            ? User::where('id', $id)->where('email', $email)->first()
            : User::where('email', $email)->first();

        if ($user && $user->unsubscribed_at === null) {
            $user->update(['unsubscribed_at' => now()]);
        }

        // Newsletter subscribers (no account) are also opted out.
        Subscribe::where('email', $email)->delete();
    }
}
