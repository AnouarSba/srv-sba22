<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use App\Models\Arret;
use App\Models\Bus;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;
use App\Models\E_ticket;
use App\Models\Ftoken;
use App\Models\Ligne;
use App\Models\Ps_cart;
use App\Models\Server;
use App\Models\Sold_cart;
use App\Models\Flixy;
use App\Models\Vent;
use Faker\Guesser\Name;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\FCMService;
use Illuminate\Support\Facades\DB;
class ClientController extends Controller

{

 public function resendpin(Request $request){

    $fields = $request->validate([
        'deviceid' => 'required|string',
    ]);

    $user = auth()->user();

    if (!$user) {
        return response()->json([
            'message' => 'مستخدم غير موجود',
        ], 401);
    }

    $user = auth()->user();
    if (!$user->tokenCan('password:reset')) {
        $response = [
            'message' =>'روح اودي' ,
        ];
        return response($response, 401); 
    }
    
    if ($user->device_id == $fields['deviceid']) {
        $user->pin = rand(10000,99999);
        $user->save();
        $checkIfEmailExist = Ftoken::where('device_id',$user->device_id)->where('type','cl')->first();

        if ($checkIfEmailExist) {
            $res=FCMService::send(
                $checkIfEmailExist->ftoken,
                [
                    'title' => 'ACTIVATION',
                    'body' => 'CODE:'.$user->pin,
                ],
                [
                    'message' => 'Extra Notification Data'
                ],
            );
        }
         if ($res) {

            return response()->json([
                'success' => true,
                'message' => 'تفقد الاشعارات',
                
            ], 200);
         } else {
            return response()->json([
                'message' => 'اسمح للتطبيق باستعمال الاشعارات و اعد المحاولة',
            ], 401);
         }
    }else {
        $user->pin = rand(10000,99999);
        $user->save();
        $email_data = array(
            'name' =>  $user->name,
            'email' =>  $user->email,
            'pin' => $user->pin,
            'srvname'=>config('etus.server.name')
        );
    
      Mail::send('emails.pin', $email_data, function ($message) use ($email_data) {
            $message->to($email_data['email'], $email_data['name'])
                ->subject('Le code de Vérification')
                ->from('contact@deeperTech.dz', $email_data['srvname']);
        });

            return response()->json([
                'success' => true,
                'message' => 'تفقد بريدك الالكتروني',
                
            ], 200);
    }

 }
 public function pinchapass(Request $request){
    $fields = $request->validate([
        'deviceid' => 'required|string',
        'pin' => 'required|integer',
        'password' => 'required|min:6|string|confirmed'
    ]);
    $user = auth()->user();
    if (!$user->tokenCan('password:reset')) {
        $response = [
            'message' =>'روح اودي' ,
        ];
        return response($response, 401); 
    }
    
    if ($user->pin != $fields['pin']) {
        $response = [
            'message' =>'كود التفعيل خاطئ' ,
        ];
        return response($response, 401);  
    }

    $user->password = bcrypt($fields['password']);
    $user->pin = rand(10000,99999);
    $rn = rand(0,999);
    $en = config('etus.server.code');
    $data = "$en,cl,$user->id,$rn";
    $user->uuid = (new EncryptionController)->privEncrypt($data);
    $user->device_id = $fields['deviceid'];
    $user->save();
    $token = $user->createToken($fields['deviceid'])->plainTextToken;
    return response([
        'success' => true,
        'client' => $user,
        'wallet'=>$user->balance,
        'token' => $token,
        'message' => ' تم تغيير كلمة السر بنجاح'
    ], 200);

 }


  public function forgpass(Request $request){
    $fields = $request->validate([
        'deviceid' => 'required|string',
        'email' => 'required|string',
    ]);

    $user = Client::where('email', $fields['email'])->first();

    if (!$user) {
        return response()->json([
            'message' => 'مستخدم غير موجود',
        ], 401);
    }
    
    if ($user->device_id == $fields['deviceid']) {
        $user->pin = rand(10000,99999);
        $user->save();
        $checkIfEmailExist = Ftoken::where('device_id',$user->device_id)->where('type','cl')->first();

        if ($checkIfEmailExist) {
            $res=FCMService::send(
                $checkIfEmailExist->ftoken,
                [
                    'title' => 'ACTIVATION',
                    'body' => 'CODE:'.$user->pin,
                ],
                [
                    'message' => 'Extra Notification Data'
                ],
            );
        }
         if ($res) {
            $user->tokens()->delete();
            $token = $user->createToken('reset',['password:reset'])->plainTextToken;
            return response()->json([
                'success' => true,
                'token' => $token,
                'message' => 'تفقد الاشعارات',
                
            ], 200);
         } else {
            return response()->json([
                'message' => 'اسمح للتطبيق باستعمال الاشعارات و اعد المحاولة',
            ], 401);
         }
    }else {
        $user->pin = rand(10000,99999);
        $user->save();
        $email_data = array(
            'name' =>  $user->name,
            'email' =>  $user->email,
            'pin' => $user->pin,
            'srvname'=>config('etus.server.name')
        );

      Mail::send('emails.pin', $email_data, function ($message) use ($email_data) {
            $message->to($email_data['email'], $email_data['name'])
                ->subject('Le code de Vérification')
                ->from('contact@deeperTech.dz', $email_data['srvname']);
        });
            $user->tokens()->delete();
            $token = $user->createToken('reset',['password:reset'])->plainTextToken;
            return response()->json([
                'success' => true,
                'token' => $token,
                'message' => 'تفقد بريدك الالكتروني',
                
            ], 200);
    }

  }

    public function changepass(Request $request){
       
        $fields = $request->validate([
            'deviceid' => 'required|string',
            'pass' => 'required|string',
            'password' => 'required|min:6|string|confirmed'
        ]);
        $user = auth()->user();

        if ($user->device_id != $fields['deviceid']) {
            $response = [
                'message' =>'حدث خطا ما؟ سجل الدخول من جديد' ,
            ];
            return response($response, 401);  
        }

        if(!$user || !Hash::check($fields['pass'], $user->password)) {
            return response([
                'message' => 'كلمة السر خاطئة'
            ], 401);
        }

        $user->password = bcrypt($fields['password']);
        $user->save();

        return response([
            'message' => ' تم تغيير كلمة السر بنجاح'
        ], 200);

    }
    
    public function resend(Request $request){

        $id = auth()->user()->id;
        $user = Client::find($id);
        $user->pin = rand(10000,99999);
        $user->save();
        $checkIfEmailExist = Ftoken::where('device_id',$user->device_id)->first();

        if ($checkIfEmailExist) {
            $res=FCMService::send(
                $checkIfEmailExist->ftoken,
                [
                    'title' => 'COMPTE ACTIVATION',
                    'body' => 'CODE:'.$user->pin,
                ],
                [
                    'message' => 'Extra Notification Data'
                ],
            );
        }
         if ($res) {
            return response([], 200);
         } else {
            return response()->json([
                'message' => 'اسمح للتطبيق باستعمال الاشعارات و اعد المحاولة',
            ], 401);
         }
         

        }


        
//////////////////////////////////////////////////////////////////
public function link(Request $request){
        return response()->json(200);
}
public function Top(Request $request){
    $req = $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date',
       ]);
       $from= $req['start_date'];	
       $to= $req['end_date'];
    $top = Flixy::query()
    ->with(["client:id,name,phone,email"])->where('client_type','!=','App\Models\Spcart')->whereBetween('created_at', [$from, $to])->select("client_id", DB::raw("sum(amount) as flexy"))
    ->groupBy('client_id')->orderBy('flexy', 'DESC')
    ->get()->take(10);
    $cartvend = Vent::query()
    ->join('controls', 'controls.id', '=', 'vents.c_id')->where('c_type','App\Models\Control')
    ->whereBetween('vents.created_at', [$from, $to])
    ->select("c_id", "name", DB::raw("count(vents.id) as cmpt"))
    ->groupBy('c_id')->orderBy('cmpt', 'DESC')
    ->get();

    /*$top_rec=DB::table('transactions')->join('kabids', 'kabids.id', '=', 'transactions.payable_id')->join('wallets', 'kabids.id', '=', 'wallets.holder_id')->where('payable_type','App\Models\Kabid')->select("payable_id","name", DB::raw("sum(amount) as flexy"))
    ->groupBy('payable_id')->orderBy('flexy', 'DESC')
    ->get();*/

    $top_rec = Flixy::query()
    ->join('kabids', 'kabids.id', '=', 'flixies.flixy_id')->where('flixy_type','App\Models\Kabid')->whereBetween('flixies.created_at', [$from, $to])->select("flixy_id", "name", DB::raw("sum(amount) as flexy"))
    ->groupBy('flixy_id')->orderBy('flexy', 'DESC')
    ->get();


    return view('ui.top',['top'=>$top,'top_rec'=>$top_rec,'cartvent'=>$cartvend,'from'=>$from,'to'=>$to]);
}
//////////////////////////////////////////////////////////////////
    public function ftoken(Request $request){
        $fields = $request->validate([
            'type' => 'required|string',
            'device_id' => 'required|string',
            'ftoken' => 'required|string',
        ]);
        $checkIfEmailExist = Ftoken::where('device_id', $fields['device_id'])->where('type', $fields['type'])->first();
        if ($checkIfEmailExist) {
            $checkIfEmailExist->ftoken = $fields['ftoken'];
            $checkIfEmailExist->save();
            $tarif = config('etus.server.tarif');
            return response()->json(['tarif'=>$tarif], 200);
        }else {
            $user = new Ftoken;
            $user->device_id = $fields['device_id'];
            $user->ftoken = $fields['ftoken'];
            $user->type = $fields['type'];
            $user->save();
            $tarif = config('etus.server.tarif');
            return response()->json(['tarif'=>$tarif], 200);
        }
       
    
    }
    //////////////////////////////////////////////////////////////////////////////////
    public function notification(Request $request){
         $div = Ftoken::All();
         foreach ($div as $dv) {
            $res= FCMService::send(
                $dv->ftoken,
                [
                    'title' => 'test',
                    'body' => 'notitest:'.$dv->type,
                ],
                [
                  'message' => 'Aiisa dadao'
                ],
            );
         }
 
        if ($res) {
            return response()->json([
                'message' => 'http work ',
            ], 200);
        };
    }
    ////////////////////////////////////////////////////////////////////////////////
    public function register(Request $request) {
        $en = config('etus.server.code');
        $fields = $request->validate([
            'deviceid' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|min:6|string|confirmed'
        ]);

        $checkIfEmailExist = Client::where('email', $fields['email'])->first();

        if ($checkIfEmailExist) {
            return response()->json([
                'message' => 'e-mail est disponible ',
            ], 401);
        }
       
        $user = Client::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            
        ]);
        $user->pin = rand(10000,99999);
        $user->device_id=$fields['deviceid'];
        $user->save();
        $rn = rand(0,999);
        $data = "$en,cl,$user->id,$rn";
        $user->uuid = (new EncryptionController)->privEncrypt($data);
        $user->save();
  
        $token = $user->createToken($fields['deviceid'])->plainTextToken;

        $checkIfEmailExist = Ftoken::where('device_id',$user->device_id)->first();

        if ($checkIfEmailExist) {
            $res=FCMService::send(
                $checkIfEmailExist->ftoken,
                [
                    'title' => 'COMPTE ACTIVATION',
                    'body' => 'CODE:'.$user->pin,
                ],
                [
                    'message' => 'Extra Notification Data'
                ],
            );
        }

        $response = [
            'token' => $token,
            'mail' => $user->email,
        ];
       
        return response($response, 200);
    }
///////////////////////////////////////////////////////////////////////////////////
    public function clients_active(Request $request){
        $fields = $request->validate([
            'code' => 'required|string',
        ]);

        // Check email
        $user=auth()->user();

        if ($user->pin == $fields['code']) {
            $user->active = true ;
            $user->pin = rand(10000,99999);
            $user->save();
            $token = $user->createToken($user->email)->plainTextToken;
            $response = [
                'client' => $user,
                'wallet'=>$user->balance,
                'token' => $token
            ];
    
            return response($response, 200);
        }

        if ($user->ban) {
            return response([
                'message' => ' حسابك معطل لسبب امني اتصل بمصلحة الزبائن',
            ], 401);
        }

    }
  ////////////////////////////////////////////////////////////////////////////

    public function add_fund(Request $request){
        $en = config('etus.server.code');
        $fields = $request->validate([
            'bon' => 'required|string',
        ]);
        $bon = Sold_cart::where('token','like',$fields['bon'])->first();
        
        if ($bon) {
            $ps = Ps_cart::find($bon->ps_id);
            if ($ps->Forn_id == NULL) {
                $response = [
                    'message' =>' لا يمكن شحن هذه البطاقة حاليا ' ,
                ];
                return response($response, 403);  
            }
            if ($bon->valid) {
                $bon->valid = false;
                $bon->save();
                $user=auth()->user();
                $tran= $user->deposit($bon->sold, ['action' => 'carte de crédit','bon' =>$bon->id]);
                $bon->skey =$en;
                $bon->uuid =$tran->uuid;
                $bon->client_type=Client::class;
                $bon->client_id=$user->id;
                $bon->save();
                return response()->json([],200);
            }else {
                $response = [
                    'message' =>'بطاقة شحن مستعملة ' ,
                ];
                return response($response, 403); 
            } 
        }else {
            $dec=(new EncryptionController)->publicDecrypt($fields['bon']);
            if ($dec == NULL) {
                $response = [
                    'message' =>'بطاقة غير معرفة' ,
                ];
                return response($response, 403);
            } else {
                $record = explode(",",$dec);
                $ser = $record[0];
                $ser1 = $record[1];
                if ($ser1 != 'sc') {
                    $response = [
                        'message' =>" هذه ليست بطاقة تعبئة" ,
                    ];
                }else {
                    $response = [
                        'message' =>" $ser :بطاقة  تابعة لسرفر" ,
                    ];
                }
                return response($response, 403);
            }
            

        }

    }

  /////////////////////////////////////////////////////////////////////////////////
    public function lastticket(){
        $en = config('etus.server.code');
        $srv = config('etus.server.name');
        $user=auth()->user();
        $tick=E_ticket::where('client_type',Client::class)->where('client_id',$user->id)->latest()->first();
        if($tick){
          if($tick->skey == $en){
            if ($tick->bus_id != NULL){
                $bus = Bus::find($tick->bus_id);
                $busname = $bus->name;
            }else{
               $busname = 'NON'; 
            }
            if ($tick->ligne_id != NULL){
               $lin = Ligne::find($tick->ligne_id);
               $lignename = $lin->name;
           }else{
               $lignename = 'NON'; 
            }
           if ($tick->arret_from_id != NULL){
               $lin = Arret::find($tick->arret_from_id);
               $arret_from = $lin->name;
                }else{
               $arret_from = 'NON'; 
            }
           if ($tick->arret_to_id != NULL){
               $lin = Arret::find($tick->arret_to_id);
               $arret_to = $lin->name;
             }else{
               $arret_to = 'NON'; 
            }
            $tik['SER']= $srv ;
            $tik['uuid']=$tick->uuid;
            $tik['bus']=$busname;
            $tik['ligne']=$lignename;
            $tik['arret_from']=$arret_from;
            $tik['arret_to']=$arret_to;
            $tik['date']=$tick->created_at->format('Y-m-d H:i:s');
            $tik['amount']=$tick->amount;
           $response = [
               'ticket' =>$tik ,
           ];
           return response($response, 200);

          }else{
            if ($tick->bus_id != NULL){
                $bus = Bus::find($tick->bus_id);
                $busname = $bus->name;
            }else{
               $busname = 'NON'; 
            }
            if ($tick->ligne_id != NULL){
               $lin = Ligne::find($tick->ligne_id);
               $lignename = $lin->name;
           }else{
               $lignename = 'NON'; 
            }
           if ($tick->arret_from_id != NULL){
               $lin = Arret::find($tick->arret_from_id);
               $arret_from = $lin->name;
                }else{
               $arret_from = 'NON'; 
            }
           if ($tick->arret_to_id != NULL){
               $lin = Arret::find($tick->arret_to_id);
               $arret_to = $lin->name;
             }else{
               $arret_to = 'NON'; 
            }
            $tik['SER']=Server::where('key',$tick->skey)->first()->name;
            $tik['uuid']=$tick->uuid;
            $tik['bus']=$busname;
            $tik['ligne']=$lignename;
            $tik['arret_from']=$arret_from;
            $tik['arret_to']=$arret_to;
            $tik['date']=$tick->created_at->format('Y-m-d H:i:s');
            $tik['amount']=$tick->amount;
           $response = [
               'ticket' =>$tik ,
           ];
           return response($response, 200);
          }
        }else {
            $response = [
                'message' =>'لا تملك تذكرة بعد' ,
            ];
            return response($response, 202);
          }
        

    }
  /////////////////////////////////////////////////////////////////////////////////

    public function gettrans(Request $request){
        $trans = auth()->user()->transactions()->orderBy('id', 'desc')->take(15)->get();
        if ($trans) {
            foreach($trans as $las){
                $nums['id'] = $las['id'];
                $nums['action'] = $las['meta']['action'] ?? 'Indefined';
                $nums['date'] = $las['created_at']->format('Y-m-d H:i:s');
                $nums['amount'] = $las['amount'];
                $nums['confirm'] = $las['confirmed'];
                $dates[]= $nums;
              }
              return response($dates, 200);
        } else {
            return response()->json([],201);
        }
        

    }
   
    public function checktoken(Request $request){
        if (auth()->user()->ban) {
            return response([
                'message' => ' حسابك معطل لسبب امني اتصل بمصلحة الزبائن',
            ], 401);  
        } 
        if (request()->user()->currentAccessToken()->name == 'reset') {
            return response()->json([],202); 
        }

        return response()->json([],200);
        
         
    }

    //////////////////////////////////////////////////////////////////////////////
    public function logout(Request $request) {
       
        $request->user()->currentAccessToken()->delete();

       return [
            'success' => true,
            'message' => 'Logged out'
        ]; ;
    }
////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////

    public function getbalance(Request $request){
        
        $response = [
            'balance' => auth()->user()->balance,
        ];
        return response($response, 200);
    }

    //////////////////////////////////////////////////////////////////////////////

    public function login(Request $request) {

        $fields = $request->validate([
            'deviceid' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $user = Client::where('email', $fields['email'])->first();

        if (!$user) {
            return response([
                'message' => ' انت لا تملك حساب  '
            ], 401);
        }

        // Check password
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'كلمة السر خاطئة'
            ], 401);
        }
        if ($user->ban) {
            return response([
                'message' => ' حسابك معطل لسبب امني اتصل بمصلحة الزبائن',
            ], 401);
        }
        if ($user->active == false) {
            $token = $user->createToken('activation')->plainTextToken;
            return response([
                'token'  => $token,
                'mail'  => $user->email,
                
            ], 201);
        }

        $price = DB::table('transactions')->where('payable_type',Client::class)->where('payable_id',$user->id)->where('confirmed',true)->sum('amount');

        if ($price != $user->balance) {
            $user->ban = true;
            $user->save();
            return response([
                'message' => ' تم تعطيل حسابك الرجاء الاتصال بمصلحة الزبائن ',
            ], 401);
        }

        $rn = rand(0,999);
        $en = config('etus.server.code');
        $data = "$en,cl,$user->id,$rn";
        $user->uuid = (new EncryptionController)->privEncrypt($data);
        $user->device_id = $fields['deviceid'];
        $user->save();
        $token = $user->createToken($fields['deviceid'])->plainTextToken;
        $response = [
            'success' => true,
            'client' => $user,
            'wallet'=>$user->balance,
            'token' => $token,

        ];

        return response($response, 200);
    }

    /////////////////////////////////////////////////////////////////////////////

    public function clientstrans ($id)
    {
        return view('ui.clientstrans',['cart'=>$id]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ui.clients');
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
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        //
    }
}
