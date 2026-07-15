<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\DepositGateService;

class DepositGateMiddleware
{
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
        } elseif ($gate === 'withdrawal') {
            if (!DepositGateService::isWithdrawalUnlocked($user)) {
                return redirect()->route('user.money-out.locked');
            }
        }

        return $next($request);
    }
}
