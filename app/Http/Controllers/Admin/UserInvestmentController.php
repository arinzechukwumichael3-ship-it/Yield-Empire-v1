<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserInvestment;
use App\Models\EarningsLog;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class UserInvestmentController extends Controller
{
    public function index(Request $request)
    {
        $page_title = __('User Investments');
        $q = UserInvestment::with(['user', 'plan'])->latest();

        if ($request->filled('q')) {
            $term = $request->get('q');
            $q->whereHas('user', function ($query) use ($term) {
                $query->where('firstname', 'like', "%{$term}%")
                    ->orWhere('lastname', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            })->orWhereHas('plan', function ($query) use ($term) {
                $query->where('name', 'like', "%{$term}%");
            });
        }

        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

        $investments = $q->paginate(20);
        return view('admin.sections.user-investments.index', compact('page_title', 'investments'));
    }

    public function show($id)
    {
        $investment = UserInvestment::with(['user', 'plan', 'earnings'])->findOrFail($id);
        $page_title = __('Investment Details');
        return view('admin.sections.user-investments.show', compact('page_title', 'investment'));
    }

    public function approve($id)
    {
        $investment = UserInvestment::with(['user', 'plan', 'user.wallet'])->findOrFail($id);
        
        if ($investment->status !== 'pending') {
            return back()->with(['warning' => ['This investment is not pending approval']]);
        }

        try {
            $investment->update([
                'status' => 'active',
                'maturity_date' => now()->addDays($investment->plan->duration_days),
            ]);

            // Update user wallet balance if amount was deducted
            if ($investment->user->wallet) {
                $investment->user->wallet->increment('balance', $investment->amount);
            }

        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong. Please try again']]);
        }

        return back()->with(['success' => ['Investment approved successfully!']]);
    }

    public function reject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'reason' => "required|string|max:1000",
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $investment = UserInvestment::findOrFail($id);
        
        if ($investment->status !== 'pending') {
            return back()->with(['warning' => ['This investment is not pending approval']]);
        }

        try {
            $investment->update([
                'status' => 'cancelled',
            ]);

        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return back()->with(['success' => ['Investment rejected successfully!']]);
    }

    public function earnings(Request $request)
    {
        $page_title = __('Earnings Logs');
        $q = EarningsLog::with(['user', 'investment.plan'])->latest();

        if ($request->filled('q')) {
            $term = $request->get('q');
            $q->whereHas('user', function ($query) use ($term) {
                $query->where('firstname', 'like', "%{$term}%")
                    ->orWhere('lastname', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            });
        }

        $earnings = $q->paginate(20);
        return view('admin.sections.user-investments.earnings', compact('page_title', 'earnings'));
    }
}
