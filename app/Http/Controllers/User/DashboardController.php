<?php
namespace App\Http\Controllers\User;
use Exception;
use Carbon\Carbon;
use App\Models\UserWallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Constants\PaymentGatewayConst;

class DashboardController extends Controller
{
    public function index()
    {
        return redirect()->route('user.rise.home');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('user.login');
    }

    public function deleteAccount(Request $request) {
        $user = auth()->user();
        try{
            $user->status = 0;
            $user->save();
            Auth::logout();
            return redirect()->route('index')->with(['success' => ['Your account deleted successfully!']]);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something Went Wrong! Please Try Again.']]);
        }
    }

    public function checkPin(Request $request){
        $pin = $request->pin;
        $user = auth()->user();
        if($pin != $user->pin_code){
            $data = 0;
            return response($data);
        }else{
            $data = 1;
            return response( $data);
        }
    }
}
