<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\InvestmentPlan;
use App\Models\UserInvestment;
use App\Models\CryptoWallet;
use App\Models\EarningsLog;
use App\Models\UserWallet;
use App\Models\Transaction;
use App\Constants\PaymentGatewayConst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class InvestController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
    }

    /**
     * Show new investment plan page
     */
    public function newPlan()
    {
        $page_title = "New Investment Plan";
        $plans = InvestmentPlan::active()->get();
        $wallets = CryptoWallet::active()->get()->groupBy('symbol');

        return view('user.rise.invest-new', compact('page_title', 'plans', 'wallets'));
    }

    /**
     * Show wallet address for selected crypto + plan
     */
    public function deposit(Request $request)
    {
        $request->validate([
            'plan_id'  => 'required|exists:investment_plans,id',
            'amount'   => 'required|numeric|min:0.01',
            'method'   => 'required|string',
            'network'  => 'required|string',
        ]);

        $plan = InvestmentPlan::active()->findOrFail($request->plan_id);
        $wallet = CryptoWallet::active()
            ->where('symbol', $request->method)
            ->where('network', $request->network)
            ->firstOrFail();

        // Validate amount against plan limits
        $amount = floatval($request->amount);
        if ($amount < floatval($plan->min_amount)) {
            return back()->with(['error' => ['Minimum investment is $' . $plan->min_amount . ' for ' . $plan->name . '.']]);
        }
        if ($plan->max_amount && $amount > floatval($plan->max_amount)) {
            return back()->with(['error' => ['Maximum investment is $' . $plan->max_amount . ' for ' . $plan->name . '.']]);
        }

        $returnAmount = $amount + ($amount * floatval($plan->roi_percent) / 100);

        return view('user.rise.invest-deposit', compact(
            'page_title', 'plan', 'wallet', 'amount', 'returnAmount'
        ));
    }

    /**
     * Submit payment proof
     */
    public function submitProof(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id'   => 'required|exists:investment_plans,id',
            'amount'    => 'required|numeric',
            'method'    => 'required|string',
            'network'   => 'required|string',
            'wallet_address_used' => 'required|string',
            'tx_hash'   => 'required|string',
            'proof'     => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $plan = InvestmentPlan::active()->findOrFail($request->plan_id);
        $amount = floatval($request->amount);
        $returnAmount = $amount + ($amount * floatval($plan->roi_percent) / 100);

        $proofUrl = null;
        if ($request->hasFile('proof')) {
            $proofUrl = 'uploads/invest-proof/' . $request->file('proof')->hashName();
            $request->file('proof')->move(public_path('uploads/invest-proof'), $request->file('proof')->hashName());
        }

        DB::beginTransaction();
        try {
            $investment = UserInvestment::create([
                'user_id'            => $this->user->id,
                'plan_id'            => $plan->id,
                'amount'             => $amount,
                'payment_method'     => $request->method . ' (' . $request->network . ')',
                'wallet_address_used' => $request->wallet_address_used,
                'tx_hash'            => $request->tx_hash,
                'proof_url'          => $proofUrl,
                'status'             => 'pending',
                'expected_return'    => $returnAmount,
                'maturity_date'      => now()->addDays($plan->duration_days),
            ]);

            DB::commit();

            return redirect()->route('user.invest.confirmation', $investment->id);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with(['error' => ['Something went wrong. Please try again.']]);
        }
    }

    /**
     * Show confirmation screen
     */
    public function confirmation($id)
    {
        $investment = UserInvestment::auth()->with('plan')->findOrFail($id);
        $page_title = "Investment Submitted";

        return view('user.rise.invest-confirmation', compact('page_title', 'investment'));
    }

    /**
     * My investments portfolio
     */
    public function portfolio()
    {
        $page_title = "My Investments";
        $investments = UserInvestment::auth()->with('plan')->latest()->get();
        $totalInvested = UserInvestment::auth()->whereIn('status', ['active', 'completed'])->sum('amount');
        $activeCount = UserInvestment::auth()->where('status', 'active')->count();
        $totalEarnings = EarningsLog::auth()->where('type', 'credited')->sum('amount');

        return view('user.rise.invest-portfolio', compact(
            'page_title', 'investments', 'totalInvested', 'activeCount', 'totalEarnings'
        ));
    }

    /**
     * Earnings history
     */
    public function earnings()
    {
        $page_title = "Earnings";
        $earnings = EarningsLog::auth()->with('investment.plan')->latest()->paginate(20);
        $totalEarned = EarningsLog::auth()->where('type', 'credited')->sum('amount');

        return view('user.rise.invest-earnings', compact('page_title', 'earnings', 'totalEarned'));
    }
}
