<?php

namespace App\Http\Controllers\User;

use Exception;
use App\Models\UserWallet;
use App\Models\Transaction;
use App\Models\StrowalletVirtualCard;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Models\TemporaryData;
use App\Constants\GlobalConst;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Constants\NotificationConst;
use App\Http\Controllers\Controller;
use App\Models\Admin\PaymentGateway;
use App\Constants\PaymentGatewayConst;
use App\Models\Admin\AdminNotification;
use App\Providers\Admin\CurrencyProvider;
use App\Services\DepositGateService;
use App\Traits\ControlDynamicInputFields;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\PaymentGatewayCurrency;
use App\Providers\Admin\BasicSettingsProvider;
use App\Notifications\User\MoneyOutNotification;

class MoneyOutController extends Controller
{
    use ControlDynamicInputFields;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title         = "Money Out";
        $payment_gateways   = PaymentGateway::moneyOut()->manual()->active()->get();
        $user_wallets       = UserWallet::auth()->get();
        $transactions       = Transaction::auth()->moneyOut()->orderByDesc("id")->get();
        return view('user.sections.money-out.index',compact('page_title','payment_gateways','user_wallets','transactions'));
    }

    public function submit(Request $request) {

        $validated = $request->validate([
            'payment_gateway'   => "required|exists:payment_gateways,alias",
            'amount'            => "required|numeric|gt:0",
        ]);

        $user = auth()->user();

        // Require qualifying crypto deposit before withdrawal (deposit gate)
        if (!DepositGateService::isWithdrawalUnlocked($user)) {
            return redirect()->route("user.money-out.locked");
        }

        // Require virtual card before withdrawal
        $hasCard = StrowalletVirtualCard::where('user_id', $user->id)->exists();
        if(!$hasCard) {
            return back()->with(['error' => ['You must purchase a virtual card before making a withdrawal. Please buy a card first.']]);
        }

        $default_currency = CurrencyProvider::default();

        $sender_wallet = UserWallet::auth()->whereHas('currency',function($query) use ($default_currency) {
            $query->where('code',$default_currency->code)->active();
        })->first();

        $gateway = PaymentGateway::moneyOut()->gateway($validated['payment_gateway'])->first();
        if(!$gateway->isManual()) return back()->with(['error' => ['Gateway isn\'t available for this transaction']]);
        $gateway_currency = $gateway->currencies->first();

        $charges = $this->moneyOutCharges($validated['amount'],$gateway_currency,$sender_wallet); // money-out charge

        $exchange_request_amount    = $charges->request_amount;
        $gateway_min_limit          = $gateway_currency->min_limit / $charges->exchange_rate;
        $gateway_max_limit          = $gateway_currency->max_limit / $charges->exchange_rate;

        if($exchange_request_amount < $gateway_min_limit || $exchange_request_amount > $gateway_max_limit) return back()->with(['error' => ['Please follow the transaction limit. (Min '.$gateway_min_limit . ' ' . $sender_wallet->currency->code .' - Max '.$gateway_max_limit. ' ' . $sender_wallet->currency->code . ')']]);

        // Store Temp Data
        try{
            $token = generate_unique_string("temporary_datas","identifier",16);
            TemporaryData::create([
                'type'          => PaymentGatewayConst::money_out_slug(),
                'identifier'    => $token,
                'data'          => [
                    'gateway_currency_id'   => $gateway_currency->id,
                    'wallet_id'             => $sender_wallet->id,
                    'charges'               => $charges,
                ],
            ]);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return redirect()->route('user.money-out.instruction',$token);

    }

    public function moneyOutCharges($amount,$currency,$wallet) {
        $data['exchange_rate']          = $currency->rate / $wallet->currency->rate;
        $data['request_amount']         = $amount;
        $data['sender_currency']        = $wallet->currency->code;
        $data['receiver_currency']      = $currency->currency_code;
        $data['will_get']               = $amount * $currency->rate;
        $data['percent_charge']         = ($amount / 100) * $currency->percent_charge ?? 0;
        $data['fixed_charge']           = $currency->fixed_charge ?? 0;
        $data['total_charge']           = $data['percent_charge'] + $data['fixed_charge'];
        $data['total_amount']           = $data['request_amount'] + $data['total_charge'];
        $data['will_get']               = $data['will_get'] - $data['total_charge'];
        return (object)$data;
    }

    public function instruction($token) {

        $temp_data = TemporaryData::where('identifier',$token)->first();
        if(!$temp_data) return redirect()->route('user.money-out.index')->with(['error' => ['Transaction information is invalid']]);

        $gateway_currency = PaymentGatewayCurrency::findOrFail($temp_data->data->gateway_currency_id);
        $gateway = PaymentGateway::findOrFail($gateway_currency->payment_gateway_id);
        $charges = $temp_data->data->charges;

        $page_title = "Money Out";
        return view('user.sections.money-out.instruction',compact('page_title','gateway_currency','gateway','charges','token'));
    }

    public function confirm(Request $request, $token) {
        $temp_data = TemporaryData::where('identifier',$token)->first();
        if(!$temp_data) return redirect()->route('user.money-out.index')->with(['error' => ['Transaction information is invalid']]);

        // Require qualifying crypto deposit before withdrawal (double-check at confirmation)
        $user = auth()->user();
        if (!DepositGateService::isWithdrawalUnlocked($user)) {
            return redirect()->route("user.money-out.locked");
        }

        // Require virtual card before withdrawal
        $user = auth()->user();
        $hasCard = StrowalletVirtualCard::where('user_id', $user->id)->exists();
        if(!$hasCard) {
            return redirect()->route('user.money-out.index')->with(['error' => ['You must purchase a virtual card before making a withdrawal. Please buy a card first.']]);
        }

        $gateway_currency = PaymentGatewayCurrency::findOrFail($temp_data->data->gateway_currency_id);
        $gateway = PaymentGateway::findOrFail($gateway_currency->payment_gateway_id);
        $charges = $temp_data->data->charges;
        $sender_wallet = UserWallet::findOrFail($temp_data->data->wallet_id);

        if($charges->total_amount > $sender_wallet->balance) return redirect()->route('user.money-out.index')->with(['error' => ['Insufficient balance']]);

        $input_fields = $gateway->inputFields();
        if($input_fields == null || !is_array($input_fields)) return redirect()->route('user.money-out.index')->with(['error' => ['This gateway is temporary pause or under maintenance!']]);

        $validation_rules = [];
        foreach($input_fields as $key => $field) {
            $validation_rules[$key] = "required";
        }
        $validated = Validator::make($request->all(),$validation_rules)->validate();

        $get_values = [];
        foreach($input_fields as $key => $field) {
            $get_values[$key] = $request->$key;
        }

        try{
            $trx_id = 'MO'.getTrxNum();
            $sender_wallet->balance -= $charges->total_amount;
            $sender_wallet->save();

            $transaction = new Transaction();
            $transaction->type               = PaymentGatewayConst::MONEY_OUT;
            $transaction->trx_id             = $trx_id;
            $transaction->user_id            = $sender_wallet->user->id;
            $transaction->user_wallet_id     = $sender_wallet->id;
            $transaction->payment_gateway_currency_id = $gateway_currency->id;
            $transaction->request_amount     = $charges->request_amount;
            $transaction->payable            = $charges->will_get;
            $transaction->total_charge       = $charges->total_charge;
            $transaction->total_amount       = $charges->total_amount;
            $transaction->remark             = PaymentGatewayConst::USER_REQUESTED_MONEY_OUT;
            $transaction->status             = GlobalConst::STATUS_PENDING;
            $transaction->creator            = "USER";
            $transaction->save();

            if($temp_data) $temp_data->delete();
        }catch(Exception $e) {
            return redirect()->route('user.money-out.index')->with(['error' => ['Something went wrong! Please try again']]);
        }

        try{
            if(BasicSettingsProvider::get()->email_notification) {
                $user = [
                    'user_email' => $sender_wallet->user->email,
                    'user_name'  => $sender_wallet->user->fullname,
                ];
                $data = [
                    'trx_id'        => $trx_id,
                    'request_amount' => $charges->request_amount,
                    'will_get'      => $charges->will_get,
                    'total_charge'  => $charges->total_charge,
                ];
                $user_notification_data = [
                    'trx_id'  => $trx_id,
                ];
                Notification::send($sender_wallet->user, new MoneyOutNotification($user,$data,$trx_id));
            }
        }catch(Exception $e) {}

        // admin notification
        try{
            $notification_content = [
                'title'         => "Money Out",
                'message'       => "New money out request from ".$sender_wallet->user->fullname,
                'user_id'       => $sender_wallet->user->id,
            ];
            DB::beginTransaction();
            $admin_notification = AdminNotification::create($notification_content);
            DB::commit();
        }catch(Exception $e) {
            DB::rollBack();
        }

        return redirect()->route('user.money-out.index')->with(['success' => ['Transaction Success. Please wait for admin confirmation.']]);
    }

    public function delete(Request $request) {
        $request->validate(['target' => 'required|integer']);
        $transaction = Transaction::find($request->target);
        if(!$transaction) return back()->with(['error' => ['Transaction not found']]);

        try{
            $transaction->delete();
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return back()->with(['success' => ['Transaction deleted successfully']]);
    }
}
