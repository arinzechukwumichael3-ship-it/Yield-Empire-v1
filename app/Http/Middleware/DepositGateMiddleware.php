<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Transaction;
use App\Services\DepositGateService;

class DepositGateMiddleware
{
    const REFERRAL_MIN_DEPOSIT = 600;

    /**
     * Handle an incoming request.
     *
     * @param  string  $gate  'card' or 'withdrawal'
     */
    public function handle($request, Closure $next, string $gate)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('user.login');
        }

        if ($gate === 'card') {
            if (!DepositGateService::isCardUnlocked($user)) {
                return redirect()->route('user.strowallet.virtual.card.locked');
            }
        } elseif ($gate === 'crypto') {
            if (!$user->crypto_status) {
                return redirect()->route('user.dashboard')
                    ->with(['error' => [__('Crypto deposit is currently disabled for your account.')]]);
            }
        } elseif ($gate === 'withdrawal') {
            if (!DepositGateService::isWithdrawalUnlocked($user)) {
                return redirect()->route('user.money-out.locked');
            }
        }

        return $next($request);
    }
}
