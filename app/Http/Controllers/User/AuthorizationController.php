<?php

namespace App\Http\Controllers\User;

use Exception;
use App\Models\UserWallet;
use App\Models\UserAuthorization;
use Illuminate\Http\Request;
use App\Constants\GlobalConst;
use App\Http\Controllers\Controller;
use App\Providers\Admin\BasicSettingsProvider;
use Illuminate\Support\Facades\Validator;

class AuthorizationController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function showMailFrom($token) {
        $page_title = "Mail Authorization";
        $resend_time = 0;
        if (BasicSettingsProvider::get()->mail_config) {
            $resend_time = BasicSettingsProvider::get()->otp_exp_seconds ?? GlobalConst::DEFAULT_TOKEN_EXP_SEC;
        }else{
            $resend_time = BasicSettingsProvider::get()->otp_exp_seconds ?? GlobalConst::DEFAULT_TOKEN_EXP_SEC;
        }
        return view($this->activeTemplate . 'user.auth.authorize.verify-mail',compact('page_title','token','resend_time'));
    }

    public function mailResendToken($token) {
        $page_title = "Mail Authorization";
        $resend_time = 0;
        if (BasicSettingsProvider::get()->mail_config) {
            $resend_time = BasicSettingsProvider::get()->otp_exp_seconds ?? GlobalConst::DEFAULT_TOKEN_EXP_SEC;
        }else{
            $resend_time = BasicSettingsProvider::get()->otp_exp_seconds ?? GlobalConst::DEFAULT_TOKEN_EXP_SEC;
        }
        return view($this->activeTemplate . 'user.auth.authorize.verify-mail',compact('page_title','token','resend_time'));
    }

    public function mailVerify(Request $request,$token)
    {
        $request->merge(['token' => $token]);
        $request->validate([
            'token'     => "required|string|exists:user_authorizations,token",
            'code'      => "required|array",
            'code.*'    => "required|integer",
        ]);

        $code = implode($request->code);

        $otp_exp_sec = BasicSettingsProvider::get()->otp_exp_seconds ?? GlobalConst::DEFAULT_TOKEN_EXP_SEC;
        $auth_column = UserAuthorization::where("token",$request->token)->where("code",$code)->first();

        if(!$auth_column) {
            $this->authLogout($request);
            return redirect()->route('user.login')->with(['error' => ['Invalid verification code. Please try again.']]);
        }

        if($auth_column->created_at->addSeconds($otp_exp_sec) < now()) {
            $this->authLogout($request);
            return redirect()->route('user.login')->with(['error' => ['Session expired. Please try again']]);
        }

        try{
            $auth_column->user->update([
                'email_verified'    => true,
            ]);
            $auth_column->delete();
        }catch(Exception $e) {
            $this->authLogout($request);
            return redirect()->route('user.login')->with(['error' => ['Something went wrong! Please try again']]);
        }

        return redirect()->intended(route("user.dashboard"))->with(['success' => ['Account successfully verified']]);
    }

    public function authLogout(Request $request) {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function showKycFrom() {
        $page_title = "KYC Verification";
        return view($this->activeTemplate . 'user.auth.authorize.verify-kyc',compact('page_title'));
    }

    public function kycSubmit(Request $request) {
        $user = auth()->user();
        $kyc_data = $user->kyc;
        if($kyc_data == null) {
            return redirect()->route('user.authorize.kyc')->with(['error' => ['Please apply for KYC first']]);
        }
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'email'      => 'required|email|max:100',
            'phone'      => 'required|string|max:20',
            'address'    => 'required|string|max:255',
            'city'       => 'required|string|max:50',
            'state'      => 'required|string|max:50',
            'zip'        => 'required|string|max:10',
            'country'    => 'required|string|max:50',
            'image'      => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
        ]);

        // KYC submit logic
        try{
            $user->kyc = [
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'address'    => $request->address,
                'city'       => $request->city,
                'state'      => $request->state,
                'zip'        => $request->zip,
                'country'    => $request->country,
            ];
            $user->kyc_verified = GlobalConst::PENDING;
            $user->save();
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return redirect()->route('user.dashboard')->with(['success' => ['KYC submitted successfully. Please wait for admin approval.']]);
    }

    public function showGoogle2FAForm() {
        $page_title = "Google 2FA";
        return view($this->activeTemplate . 'user.auth.authorize.verify-google-2fa',compact('page_title'));
    }

    public function google2FASubmit(Request $request) {
        $request->validate([
            'code'      => 'required|array',
            'code.*'    => 'required|integer',
        ]);

        $code = implode($request->code);

        $user = auth()->user();
        if(!$user->two_factor_secret) {
            return back()->with(['error' => ['Google 2FA is not enabled']]);
        }

        $google2fa = new \PragmaRX\Google2FA\Google2FA();
        $valid = $google2fa->verifyKey($user->two_factor_secret, $code);

        if(!$valid) {
            return back()->with(['error' => ['Invalid authentication code']]);
        }

        $user->two_factor_verified = true;
        $user->save();

        return redirect()->intended(route('user.dashboard'))->with(['success' => ['Authentication successful']]);
    }
}
