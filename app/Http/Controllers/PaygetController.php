<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Payget;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
use App\Services\FCMService;
class PaygetController extends Controller
{

    public function getwaytrans ($id)
    {
        return view('ui.getwaytrans',['cart'=>$id]);
    }
    
    public function index()
    {
        return view('ui.payget');
    }
    
    public function satimcharge(Request $request){
        $pay=auth()->user();
        $fields = $request->validate([
            'amount' => 'required|string',
            'client'=>'required|string',
            'clien_type'=>'required|string',
            'orderid'=>'required|string',
        ]);
        $amount = intval($fields['amount'])/100;
        $order = $fields['orderid'];
        if ($fields['clien_type'] == 'app') {
            $user = Client::where('email', $fields['client'])->first();

            if (!$user) {
                return response([
                    'message' => 'حساب غير موجود'
                ], 401);
            }

            if ($user->ban) {
                return response([
                    'message' => ' حسابك معطل لسبب امني اتصل بمصلحة الزبائن',
                ], 401);
            }

            $price = DB::table('transactions')->where('payable_type',Client::class)->where('payable_id',$user->id)->where('confirmed',true)->sum('amount');

            if ($price != $user->balance) {
                $user->ban = true;
                $user->save();
                return response([
                    'message' => ' تم تعطيل حسابك الرجاء الاتصال بمصلحة الزبائن ',
                ], 401);
            }
            
            $ord = DB::table('transfers')->where('orderid',$order)->first();
            if ($ord) {
                return response([
                    'message' => 'تم تحويل الاموال بالفعل '
                ], 401);
            }

             $trans= $pay->forceTransfer($user, $amount,['action' => ' Satim transfer ' ,'class' => 'App' ,'cl_name' => $user->email]);
             $trans->orderid = $order;
             $trans->save();
            // if ($user->) {
                 # code...
            // }
             return response()->json(['uuid' => $trans->uuid ],200);
        } 
        if ($fields['clien_type'] == 'cart') {
            $user = Cart::where('id', $fields['client'])->first();

            if (!$user) {
                return response([
                    'message' => 'حساب غير موجود'
                ], 401);
            }

            if ($user->ban) {
                return response([
                    'message' => ' حسابك معطل لسبب امني اتصل بمصلحة الزبائن',
                ], 401);
            }

            $price = DB::table('transactions')->where('payable_type',Cart::class)->where('payable_id',$user->id)->where('confirmed',true)->sum('amount');

            if ($price != $user->balance) {
                $user->ban = true;
                $user->save();
                return response([
                    'message' => ' تم تعطيل حسابك الرجاء الاتصال بمصلحة الزبائن ',
                ], 401);
            }
            
            $ord = DB::table('transfers')->where('orderid',$order)->first();
            if ($ord) {
                return response([
                    'message' => 'تم تحويل الاموال بالفعل '
                ], 401);
            }

             $trans= $pay->forceTransfer($user, $amount,['action' => ' Satim transfer ','class' => 'Cart' ,'cl_name' => $user->id ]);
             $trans->orderid = $order;
             $trans->save();
             return response()->json(['uuid' => $trans->uuid ],200);
        }
    }

    public function checkcl(Request $request){
        $fields = $request->validate([
            'client'=>'required|string',
            'clien_type'=>'required|string',   
        ]);

        if ($fields['clien_type'] == 'app') {
            $user = Client::where('email', $fields['client'])->first();
            if (!$user) {
                return response([
                    'message' => 'حساب غير موجود'
                ], 401);
            }
            if ($user->ban) {
                return response([
                    'message' => ' حسابك معطل لسبب امني اتصل بمصلحة الزبائن',
                ], 401);
            }
            $price = DB::table('transactions')->where('payable_type',Client::class)->where('payable_id',$user->id)->where('confirmed',true)->sum('amount');

            if ($price != $user->balance) {
                $user->ban = true;
                $user->save();
                return response([
                    'message' => ' تم تعطيل حسابك الرجاء الاتصال بمصلحة الزبائن ',
                ], 401);
            }
            
             return response()->json([],202);
        }

        if ($fields['clien_type'] == 'cart') {

            $user = Cart::where('id', intval($fields['client']))->first();
            if (!$user) {
                return response([
                    'message' => 'بطاقة غير معرفة'
                ], 401);
            }
            if ($user->ban) {
                return response([
                    'message' => ' حسابك معطل لسبب امني اتصل بمصلحة الزبائن',
                ], 401);
            }
            $price = DB::table('transactions')->where('payable_type',Cart::class)->where('payable_id',$user->id)->where('confirmed',true)->sum('amount');

            if ($price != $user->balance) {
                $user->ban = true;
                $user->save();
                return response([
                    'message' => ' تم تعطيل حسابك الرجاء الاتصال بمصلحة الزبائن ',
                ], 401);
            }
            
             return response()->json([],202);
        }
    }
 
    public function checktoken(Request $request){
        return response()->json([],202);
    }

    public function login(Request $request) {
         
        $fields = $request->validate([
            'srvuser' => 'required|string',
            'srvpass' => 'required|string'
        ]);

        // Check email
        $user = Payget::where('username', $fields['srvuser'])->first();

        if (!$user) {
            return response([
                'message' => ' اسم المستخدم غير موجود '
            ], 401);
        }

        // Check password
        if(!$user || !Hash::check($fields['srvpass'], $user->password)) {
            return response([
                'message' => 'كلمة السر خاطئة'
            ], 401);
        }
        if ($user->ban) {
            return response([
                'message' => ' حسابك معطل لسبب امني اتصل بمصلحة الزبائن',
            ], 401);
        }
        $token = $user->createToken('payget')->plainTextToken;
        $response = [ 
            'token' =>$token,
        ];
        return response($response, 200);
    }
}
