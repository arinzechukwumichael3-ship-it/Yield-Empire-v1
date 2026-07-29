<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CryptoWallet;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
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
            'coin_name' => 'required|string|max:100',
            'symbol' => 'required|string|max:20',
            'network' => 'nullable|string|max:100',
            'wallet_address' => 'required|string|max:255',
            'user_id' => 'nullable|integer|exists:users,id',
            'purpose' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validate();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            try {
                $image = get_files_from_fileholder($request, 'logo');
                $uploadLogo = upload_files_from_path_dynamic($image, 'crypto-logos');
                $data['logo'] = $uploadLogo;
            } catch (Exception $e) {
                return back()->withErrors($validator)->withInput()->with(['error' => ['Logo upload failed! Please try again.']]);
            }
        }

        try {
            CryptoWallet::create($data);

            return back()->with(['success' => ['Crypto address saved successfully!']]);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again.']])->withInput();
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'target' => 'required|integer|exists:crypto_wallets,id',
            'coin_name' => 'required|string|max:100',
            'symbol' => 'required|string|max:20',
            'network' => 'nullable|string|max:100',
            'wallet_address' => 'required|string|max:255',
            'purpose' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('modal', 'crypto-address-edit');
        }

        $validated = $validator->validate();
        $wallet = CryptoWallet::findOrFail($validated['target']);

        $updateData = [
            'coin_name' => $validated['coin_name'],
            'symbol' => $validated['symbol'],
            'network' => $validated['network'],
            'wallet_address' => $validated['wallet_address'],
            'purpose' => $validated['purpose'],
            'color' => $validated['color'] ?? null,
        ];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            try {
                $image = get_files_from_fileholder($request, 'logo');
                $uploadLogo = upload_files_from_path_dynamic($image, 'crypto-logos');
                $updateData['logo'] = $uploadLogo;
            } catch (Exception $e) {
                return back()->withErrors($validator)->withInput()->with(['error' => ['Logo upload failed! Please try again.']]);
            }
        }

        // Handle old logo removal if new one uploaded
        if ($request->hasFile('logo') && $wallet->logo && $request->filled('remove_old_logo')) {
            try {
                $oldPath = get_files_path('crypto-logos').'/'.$wallet->logo;
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            } catch (Exception $e) {
                // fail silently
            }
        }

        try {
            $wallet->update($updateData);

            return back()->with(['success' => ['Crypto address updated successfully!']]);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again.']])->withInput();
        }
    }

    public function statusToggle(Request $request, $id)
    {
        $wallet = CryptoWallet::findOrFail($id);
        $wallet->is_active = ! $wallet->is_active;
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
