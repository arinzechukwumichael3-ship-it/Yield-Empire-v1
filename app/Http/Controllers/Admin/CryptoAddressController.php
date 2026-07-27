<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use App\Models\CryptoWallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CryptoAddressController extends Controller
{
    public function index()
    {
        $page_title = 'Crypto Addresses';
        $wallets = CryptoWallet::with('user')->latest()->get();
        $users = User::orderBy('firstname')->get();
        return view('admin.sections.crypto-addresses.index', compact('page_title', 'wallets', 'users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coin_name'      => 'required|string|max:100',
            'symbol'         => 'required|string|max:20',
            'network'        => 'nullable|string|max:100',
            'wallet_address' => 'required|string|max:255',
            'user_id'        => 'nullable|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validate();

        try {
            CryptoWallet::create($data);
            return back()->with(['success' => ['Crypto address saved successfully!']]);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again.']])->withInput();
        }
    }

    public function statusToggle(Request $request, $id)
    {
        $wallet = CryptoWallet::findOrFail($id);
        $wallet->is_active = !$wallet->is_active;
        $wallet->save();

        $msg = $wallet->is_active ? 'activated' : 'deactivated';
        return back()->with(['success' => ["Address $msg successfully!"]]);
    }

    public function destroy($id)
    {
        $wallet = CryptoWallet::findOrFail($id);
        $wallet->delete();
        return back()->with(['success' => ['Address deleted successfully!']]);
    }
}
