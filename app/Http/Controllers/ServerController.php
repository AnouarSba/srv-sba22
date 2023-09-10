<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Client;
use App\Models\E_ticket;
use App\Models\Logininfo;
use App\Models\Server;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ui.servers');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Server  $server
     * @return \Illuminate\Http\Response
     */
    public function show(Server $server)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Server  $server
     * @return \Illuminate\Http\Response
     */
    public function edit(Server $server)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Server  $server
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Server $server)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Server  $server
     * @return \Illuminate\Http\Response
     */
    public function destroy(Server $server)
    {
        //
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'srvuser' => 'required|string',
            'srvpass' => 'required|string'
        ]);

        // Check email
        $user = Server::where('email', $fields['srvuser'])->first();

        if ($user->password != (new EncryptionController)->privEncrypt($fields['srvpass'])) {
            return response([
                'message' => ' كلمة السر خاطئة  '
            ], 401);
        }
        
        if (!$user) {
            return response([
                'message' => '  لا تملك حساب  '
            ], 401);
        }

        if (!$user->enable) {
            return response([
                'message' => 'تم توقيف السرفر الرجاء الاتصال بالادمن'
            ], 401);
        }
        $login = new Logininfo();
        $login->user_type = get_class($user);
        $login->user_id = $user->id;
        $login->type ='login';
        $login->deviceid = $request->ip();
        $login->save();
        
        $token = $user->createToken($user->name)->plainTextToken;
        $response = [
            'token' => $token,    
        ];

        return response($response, 200);
    }

   ///////////////////////////////////////////////////////////////////////////////////
    public function checktoken(Request $request){
    return response()->json([],202);
    }
    ///////////////////////////////////////////////////////////////////////////////////
    public function getbalance(Request $request){
            
        $response = [
            'balance' => auth()->user()->balance,
        ];
        return response($response, 202);
    }
     ///////////////////////////////////////////////////////////////////////////////////

     public function gettrans(Request $request){
        $trans = auth()->user()->transactions()->orderBy('id', 'desc')->get();
        if ($trans) {
              return response($trans, 200);
        } else {
            return response()->json(NULL,201);
        }
        
    }
    
         ///////////////////////////////////////////////////////////////////////////////////

         public function servertrans($id){
            return view('ui.servertrans',['cart'=>$id]);
        }

    ///////////////////////////////////////////////////////////////////////////////////
        public function  server_validation(Request $request){
            $en = config('etus.server.code');
            $fields = $request->validate([
                'token' => 'required|string',
                'amount' => 'required|numeric|min:10'
            ]);

            $dec = (new EncryptionController)->publicDecrypt($fields['token']);
            if ($dec == NULL) {
                return response([
                    'message' => 'رمز غير صالح'
                ], 401); 
            }else {
                $record = explode(",",$dec);
                if ($record[0] == $en) {
                    switch ($record[1]) {
                        case 'cl': ////// client validation
                            $client = Client :: find($record[2]);
                            if ($client->uuid == $fields['token']) {
                             if ($client->ban) {
                                 return response([
                                     'message' => ' حساب موقوف عليك الاتصال بمصلحة الزبائن '
                                 ], 401); 
                             }
                                if ($client->balance < $fields['amount']) {
                                 return response([
                                     'message' => 'الرصيد غير كافي'
                                 ], 401); 
                                } else {
                                    $trans = $client->forceTransfer(auth()->user(), $fields['amount'],['action' => 'acheter un ticket ext','server' => auth()->user()->name]);
                                    $tik = new E_ticket();
                                    $tik->uuid = $trans->uuid;
                                    $tik->client_type =Client::class;
                                    $tik->client_id =$record[2];
                                    $tik->amount = $fields['amount'];
                                    $tik->skey = auth()->user()->key;

                                    $tik->save();
                                    return response([
                                     'uuid' => $trans->uuid,
                                     'class' => 'cl',
                                     'id' => $client->id,
                                 ], 202);
                                }
                                
                            }else {
                             return response([
                                 'message' => 'رمز المصادقة منتهي الصلاحية'
                             ], 401); 
                            }
                          break;
                        case 'ca':   ////// cart validation
                            $cart = Cart :: find($record[3]);
                            if (!$cart->valid) {
                                return response([
                                    'message' => ' لا يمكنك استعمال بطاقة لم يتم بيعها بعد '
                                ], 401); 
                            }
                            if ($cart->ban) {
                                return response([
                                    'message' => 'بطاقة موقوفة عليك بالاتصال بمصلحة الزبائن'
                                ], 401); 
                            }
                            if ($cart->token == $fields['token']) {
            
                                if ($cart->balance < $fields['amount']) {
                                 return response([
                                     'message' => 'الرصيد غير كافي'
                                 ], 401); 
                                } else {
                                    $trans = $cart->forceTransfer(auth()->user(), $fields['amount'],['action' => 'acheter un ticket ext','server' => auth()->user()->name]);
                                    $tik = new E_ticket;
                                    $tik->uuid = $trans->uuid;
                                    $tik->client_type =Cart::class;
                                    $tik->client_id =$record[3];
                                    $tik->amount = $fields['amount'];
                                    $tik->skey = auth()->user()->key;

                                    $tik->save();
                                    return response([
                                        'uuid' => $trans->uuid,
                                        'class' => 'ca',
                                        'id' => $cart->id,
                                 ], 202);
                                }
                                
                            }else {
                             return response([
                                 'message' => 'رمز المصادقة منتهي الصلاحية'
                             ], 401); 
                            }
                          break;
                        case 'et':     ///// e_ticket validation 
                            return response([
                                'message' => 'خاصية غير مفعلة '
                            ], 401);
                           break;
                        case 'vt':     ///// e_ticket validation
                            return response([
                                'message' => 'خاصية غير مفعلة '
                            ], 401);
                           break;
                        case 'ab':    ///// abonnement validation
                            return response([
                                'message' => 'خاصية غير مفعلة '
                            ], 401);
                            break;
                        default:
                        return response([
                            'message' => 'رمز غير مخصص للمصادقة'
                        ], 401); 
                      }
        
                }else {
                    return response([
                        'message' => 'رمز ينتمي لسرفر اخر'
                    ], 401); 
                }
            }
        }

    ///////////////////////////////////////////////////////////////////////////////////
}
