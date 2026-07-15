<?php

namespace App\Services;

use App\Models\User;
use App\Models\CryptoDeposit;

class DepositGateService
{
    const MINIMUM_QUALIFYING_AMOUNT = 10;

    /**
     * Check if a user has a qualifying deposit.
     */
    public static function hasQualifyingDeposit(User $user): bool
    {
        return (bool) $user->has_qualifying_deposit;
    }

    /**
     * Check if a user has a pending deposit waiting approval.
     */
    public static function hasPendingDeposit(User $user): bool
    {
        return CryptoDeposit::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();
    }

    /**
     * Check if card is unlocked for a user.
     */
    public static function isCardUnlocked(User $user): bool
    {
        return (bool) $user->card_unlocked;
    }

    /**
     * Check if withdrawal is unlocked for a user.
     */
    public static function isWithdrawalUnlocked(User $user): bool
    {
        return (bool) $user->withdrawal_unlocked;
    }

    /**
     * Unlock all features for a user when a qualifying deposit is confirmed.
     */
    public static function unlockFeatures(User $user, float $amount): void
    {
        $user->update([
            'has_qualifying_deposit'   => true,
            'qualifying_deposit_amount' => $amount,
            'qualifying_deposit_date'  => now(),
            'card_unlocked'            => true,
            'withdrawal_unlocked'      => true,
        ]);
    }

    /**
     * Check if a deposit amount meets the minimum threshold.
     */
    public static function amountQualifies(float $amount): bool
    {
        return $amount >= self::MINIMUM_QUALIFYING_AMOUNT;
    }

    /**
     * Get the minimum qualifying amount formatted.
     */
    public static function getMinimumAmount(): float
    {
        return self::MINIMUM_QUALIFYING_AMOUNT;
    }

    /**
     * Send deposit submitted notification.
     */
    public static function notifyDepositSubmitted(User $user, float $amount, string $coin): void
    {
        self::createNotification($user, [
            'title'   => 'Deposit Received',
            'message' => "⏳ Deposit received! We're reviewing your {$coin} deposit of {$amount}. Estimated confirmation: 1-3 hours.",
        ]);
    }

    /**
     * Send deposit confirmed and unlocked notification.
     */
    public static function notifyDepositConfirmed(User $user, float $amount): void
    {
        self::createNotification($user, [
            'title'   => 'Deposit Confirmed',
            'message' => "🎉 Deposit confirmed! Your virtual card is now active and withdrawals are enabled. Welcome to full EnzoBank access!",
        ]);
    }

    /**
     * Send deposit rejected notification.
     */
    public static function notifyDepositRejected(User $user, float $amount, string $reason = ''): void
    {
        $msg = "❌ Your deposit of {$amount} could not be confirmed.";
        if ($reason) {
            $msg .= " Reason: {$reason}.";
        }
        $msg .= " Please contact support with your transaction hash for assistance.";

        self::createNotification($user, [
            'title'   => 'Deposit Rejected',
            'message' => $msg,
        ]);
    }

    /**
     * Create a user notification record.
     */
    private static function createNotification(User $user, array $data): void
    {
        try {
            \App\Models\UserNotification::create([
                'user_id' => $user->id,
                'type'    => 'DEPOSIT_GATE',
                'message' => $data,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to send deposit gate notification: ' . $e->getMessage());
        }
    }
}
