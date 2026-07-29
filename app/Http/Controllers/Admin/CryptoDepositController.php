<?php

namespace App\Http\Controllers\Admin;

use App\Constants\PaymentGatewayConst;
use App\Http\Controllers\Controller;
use App\Models\CryptoDeposit;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserWallet;
use App\Services\DepositGateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CryptoDepositController extends Controller
{
    public function index(Request $request)
    {
        $page_title = 'Crypto Deposits';

        $query = CryptoDeposit::with('user')->latest();

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('coin')) {
            $query->where('coin_symbol', $request->coin);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $deposits = $query->paginate(20);

        $coins = config('crypto_deposit.coins', []);

        return view('admin.sections.crypto-deposits.index', compact(
            'page_title', 'deposits', 'coins'
        ));
    }

    public function show($id)
    {
        $deposit = CryptoDeposit::with('user')->findOrFail($id);
        $page_title = 'Deposit #'.$deposit->id.' - '.$deposit->coin_symbol;

        return view('admin.sections.crypto-deposits.show', compact('page_title', 'deposit'));
    }

    public function approve($id)
    {
        $deposit = CryptoDeposit::findOrFail($id);

        if ($deposit->status !== 'pending') {
            return back()->with(['error' => ['This deposit has already been '.$deposit->status.'.']]);
        }

        DB::beginTransaction();
        try {
            $user = User::find($deposit->user_id);

            // Determine if deposit qualifies for unlock (uses user's vc_fee_override)
            $qualifies = $user ? DepositGateService::amountQualifies($deposit->amount_usd, $user) : false;

            // Update deposit status
            $deposit->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
                'qualifies_for_unlock' => $qualifies,
            ]);

            // If qualifies, unlock features for user
            if ($qualifies && $user) {
                DepositGateService::unlockFeatures($user, $deposit->amount_usd);
                DepositGateService::notifyDepositConfirmed($user, $deposit->amount_usd);
            }

            // Find user USD wallet
            $userWallet = UserWallet::where('user_id', $deposit->user_id)
                ->whereHas('currency', function ($q) {
                    $q->where('code', 'USD');
                })
                ->first();

            if (! $userWallet) {
                throw new \Exception('User USD wallet not found.');
            }

            // Credit wallet
            $userWallet->balance += $deposit->amount_usd;
            $userWallet->save();

            // Create transaction record
            $trx_id = 'CD-'.uniqid();
            Transaction::create([
                'type' => PaymentGatewayConst::TYPEADDMONEY,
                'trx_id' => $trx_id,
                'user_type' => 'USER',
                'user_id' => $deposit->user_id,
                'wallet_id' => $userWallet->id,
                'request_amount' => $deposit->amount_usd,
                'request_currency' => 'USD',
                'exchange_rate' => 1,
                'percent_charge' => 0,
                'fixed_charge' => 0,
                'total_charge' => 0,
                'total_payable' => $deposit->amount_usd,
                'receive_amount' => $deposit->amount_usd,
                'available_balance' => $userWallet->balance,
                'payment_currency' => $deposit->coin_symbol,
                'receiver_id' => $deposit->user_id,
                'attribute' => PaymentGatewayConst::RECEIVED,
                'remark' => 'Crypto Deposit',
                'status' => PaymentGatewayConst::STATUSSUCCESS,
                'details' => json_encode([
                    'crypto_deposit_id' => $deposit->id,
                    'coin_symbol' => $deposit->coin_symbol,
                    'network' => $deposit->network,
                    'wallet_address' => $deposit->wallet_address,
                    'tx_hash' => $deposit->tx_hash,
                    'amount_usd' => $deposit->amount_usd,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return back()->with(['success' => [
                "Deposit approved. \${$deposit->amount_usd} credited to user \"".$deposit->user->username.'".',
            ]]);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with(['error' => ['Approval failed: '.$e->getMessage()]]);
        }
    }

    public function reject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $deposit = CryptoDeposit::findOrFail($id);

        if ($deposit->status !== 'pending') {
            return back()->with(['error' => ['This deposit has already been '.$deposit->status.'.']]);
        }

        $deposit->update([
            'status' => 'rejected',
            'admin_note' => $request->reason,
        ]);

        // Notify user about rejection
        try {
            $user = User::find($deposit->user_id);
            if ($user) {
                DepositGateService::notifyDepositRejected($user, $deposit->amount_usd, $request->reason);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Deposit rejection notification failed: '.$e->getMessage());
        }

        return back()->with(['success' => ["Deposit #{$deposit->id} has been rejected."]]);
    }
}
