<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\PublicSslCommerzPaymentController;
use Auth;
use Session;
use App\Wallet;
use App\BankInfo;

class WalletController extends Controller
{
    public function index()
    {
        $wallets = Wallet::where('user_id', Auth::user()->id)->paginate(9);
        $banks = BankInfo::where('status', 'Active')->get();

        return view('frontend.wallet', compact('wallets', 'banks'));
    }

    public function recharge(Request $request)
    {
        $data['amount'] = $request->amount;
        $data['payment_method'] = $request->payment_option;

        // dd($data);

        $request->session()->put('payment_type', 'wallet_payment');
        $request->session()->put('payment_data', $data);

        if($request->payment_option == 'paypal'){
            $paypal = new PaypalController;
            return $paypal->getCheckout();
        }
        elseif ($request->payment_option == 'stripe') {
            $stripe = new StripePaymentController;
            return $stripe->stripe();
        }
        elseif ($request->payment_option == 'sslcommerz') {
            $sslcommerz = new PublicSslCommerzPaymentController;
            return $sslcommerz->index($request);
        }
    }

    public function bank_recharge(Request $request) {

        $user = Auth::user();
        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->bank_id = $request->bank_id;
        $wallet->amount = $request->amount;
        $wallet->payment_method = 'bank payment';
        $wallet->transaction_id = $request->transaction_id;
        $wallet->status = 'pending';
        $wallet->save();

        /*$user = Auth::user();
        $user->balance = $user->balance + $payment_data['amount'];
        $user->save();

        Session::forget('payment_data');
        Session::forget('payment_type');*/

        flash(__('Payment completed. Please wait for admin approval'))->success();
        return redirect()->route('wallet.index');

    }

    public function wallet_payment_done($payment_data, $payment_details){
        $user = Auth::user();
        $user->balance = $user->balance + $payment_data['amount'];
        $user->save();

        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->amount = $payment_data['amount'];
        $wallet->payment_method = $payment_data['payment_method'];
        $wallet->payment_details = $payment_details;
        $wallet->save();

        Session::forget('payment_data');
        Session::forget('payment_type');

        flash(__('Payment completed'))->success();
        return redirect()->route('wallet.index');
    }
}
