<?php

namespace App\Http\Controllers;

use App\Models\Abonment;
use App\Models\Abtype;
use App\Models\Aevent;
use App\Models\Bus;
use App\Models\Ligne;
use App\Models\Arret;
use App\Models\P_Cart;
use App\Models\Cart;
use App\Models\Vent;
use App\Models\Cont;
use App\Models\Client;
use App\Models\Control;
use App\Models\Ftoken;
use App\Models\Blacklist;
use App\Models\E_ticket;
use App\Models\Flixy;
use App\Models\Logininfo;
use App\Models\Spcart;
use App\Models\V_ticket;
use App\Models\Valideur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\FCMService;
use Carbon\Carbon;

class ControlController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:sanctum','auth:controls'])->only('contro', 'vent','transfer','gettrans','checktoken','logout','getbalance');
    }
    public function controltrans ($id)
    {
        return view('ui.controltrans',['cart'=>$id]);
    }



//////////////////////////////////////////////////////////////////////////////////
public function activeabn(Request $request){

    $kabid=auth()->user();
         $fields = $request->validate([
        'deviceid' => 'required|string',
        'token' => 'required|string',
        ]);

        if ($kabid->device_id != $fields['deviceid'] ) {
         return response([
            'message' => 'عليك بتسجيل الخروج و إعادة الدخول مع تغيير كلمة السر '
        ], 401); 
       }
       $spc = Spcart::where('token',$fields['token'])->first();
       if ($spc) {
        $aptype = Abtype::where('code',$spc->type)->first();
        if ($aptype->amount == 0) {
            return response([
                'message' => 'لا تملك الصلاحية لتفعيل البطاقة'
            ], 401); 
        }
        $ab= Abonment::where('abon_type',get_class($spc))->where('abon_id',$spc->id)->whereDate('end', '>', Carbon::today()->toDateString())->latest()->first();
        if ($ab) {
            return response([
                'message' => 'هذه البطاقة تملك عرض مفعل '
            ], 401); 
        }
        if ($spc->balance >= $aptype->amount) {
            $spc->forceWithdraw($aptype->amount, ['action' => 'Activation de labonnement','user_id' => auth()->user()->id]);
            $nab = new Abonment();
            $nab->abon_type = get_class($spc);
            $nab->abon_id = $spc->id;
            $nab->type = $spc->type;
            $nab->amount = $aptype->amount;
            $nab->start = Carbon::now();
            if ($spc->type == 'HAND') {
                $nab->end =  new carbon('01 january '.$nab->start->format('Y')+1);

            } else {
            $nab->end = Carbon::now()->addDays($aptype->dur);

            }
            
             
            $nab->save();
            $event = new Aevent();
            $event->cat ='spc';
            $event->type ='active_abn';
            $event->user_type = Control::class;
            $event->user_id = auth()->user()->id;
            $event->class_type = get_class($spc);
            $event->class_id = $spc->id;
            $event->amount = $aptype->amount;
            $event->info = 'activeation abonment ';
            $event->save();
            return response([
                'name'=>$spc->name,
                'balance'=>$spc->balance,
                'end' =>$nab->end->format('Y-m-d H:i:s'),
                'type' => $aptype->name,
                'message' => ' تم تفعيل الاشتراك بنجاح '
            ],200); 
          }else {
            return response([
                'message' => ' رصيد البطاقة غير كافي '
            ], 401); 
          }

       }else {
        return response([
            'message' => 'رمز غير صالح'
        ], 401); 
       }
        
     }

//////////////////////////////////////////////////////////////////////////////////
public function contro(Request $request){

    $kabid=auth()->user();
    $fields = $request->validate([
        'deviceid' => 'required|string',
        'token' => 'required|string',
        'lat' => 'nullable|string',
        'long' => 'nullable|string',
    ]);

    if ($kabid->device_id != $fields['deviceid'] ) {
        return response([
            'message' => 'عليك بتسجيل الخروج و إعادة الدخول مع تغيير كلمة السر '
        ], 401); 
    }

    $isUuid = Str::isUuid($fields['token']);
    if ($isUuid) {
        $tick = E_ticket::where('uuid',$fields['token'])->first();
        if ($tick) {
            $en = config('etus.server.code');
            $srv = config('etus.server.name');
            $vent = new Cont();
            $vent->c_id = $kabid->id;
            $vent->app_id = $tick->client_id;
            if ($fields['lat'] != NULL) {
              $vent->lat  = floatval( $fields['lat']);
            }
            if ($fields['long'] != NULL) {
              $vent->long  = floatval($fields['long']); 
            }
            $vent->save();

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
                $tik['SER']=$srv;
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
               return response($response, 205);
    
              }else{
                $response = [
                    'message' =>'externel ticket' ,
                ];
                return response($response, 401);
              }
          
        }else{
                return response([
                    'message' => 'رمز غير صالح '
                ], 401);
          }
    } else {
        $pcart =  P_Cart::where('token','like',$fields['token'])->first();
        if ($pcart) {
            if ($pcart->Forn_id <> NULL) {
                return response([
                    'message' => 'حزمة مسندة مسبقا'
                ], 401); 
            } else {
                $pcart->forn_type = Control::class;
                $pcart->Forn_id = $kabid->id;
                $pcart->status = 'Forned';
                $pcart->save();
                return response([
                    'message' => 'تم اسناد الحزمة اليك بنجاح'
                ], 402);
            }
            
        }
        ////////////////////////////////////////////////////////////////////
        $cart =  Cart::where('token','like',$fields['token'])->first();
        if ($cart) {
             if ($cart->ban) {
                return response([
                    'message' => 'البطاقة محضورة'
                ], 401);
              }
    
               $tick = E_ticket::where('client_type',Cart::class)->where('client_id',$cart->id)->latest()->first();
              if ($tick) {
                $en = config('etus.server.code');
                $srv =  config('etus.server.name');
                $vent = new Cont();
                $vent->c_id = $kabid->id;
                $vent->cart_id = $cart->id;
                if ($fields['lat'] != NULL) {
                  $vent->lat  =  $fields['lat'];
                }
                if ($fields['long'] != NULL) {
                  $vent->long  =  $fields['long'];
                }
                $vent->save();
    
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
                    $tik['SER']=$srv;
                    $tik['uuid']=$tick->uuid;
                    $tik['bus']=$busname;
                    $tik['ligne']=$lignename;
                    $tik['arret_from']=$arret_from;
                    $tik['arret_to']=$arret_to;
                    $tik['date']=$tick->created_at->format('Y-m-d H:i:s');
                    $tik['amount']=$tick->amount;
                   $response = [
                     'balance' => $cart->balance,
                     'pin' => $cart->pin,
                     'ticket' =>$tik ,
                     'abn' => NULL,
                     'type' => 'default',
                   ];
                   return response($response, 201);
        
                  }else{
                    $response = [
                        'balance' =>$cart->balance,
                        'pin' => $cart->pin,
                        'abn' => NULL,
                        'type' => 'default',
                        'message' =>'externel ticket' ,
                    ];
                    return response($response, 202);
                  }
              
               }else{
                  $vent = new Cont();
                  $vent->c_id = $kabid->id;
                  $vent->cart_id = $cart->id;
                  if ($fields['lat'] != NULL) {
                  $vent->lat  =  $fields['lat'];
                   }
                   if ($fields['long'] != NULL) {
                  $vent->long  =  $fields['long'];
                  }
                  $vent->save();
                    return response([
                  'balance' => $cart->balance,
                  'pin' => $cart->pin,
                  'abn' => NULL,
                  'type' => 'default',
                    ], 200);
              }  
        }
        ////////////////////////////////////////////////////////////////////
        $tick= V_ticket::where('token','like',$fields['token'])->first();
        if ($tick) {
            $en = config('etus.server.code');
            $srv = config('etus.server.name');
            $vent = new Cont();
            $vent->c_id = $kabid->id;
            $vent->vt_id = $tick->id;
            if ($fields['lat'] != NULL) {
              $vent->lat  = floatval( $fields['lat']);
            }
            if ($fields['long'] != NULL) {
              $vent->long  = floatval($fields['long']); 
            }
            $vent->save();
             if ($tick->valid == false) {
                return response([
                    'message' => ' تذكرة غير مصادقة '
                ], 401); 
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
                $tik['SER']=$srv;
                $tik['uuid']=$tick->token;
                $tik['bus']=$busname;
                $tik['ligne']=$lignename;
                $tik['arret_from']=$arret_from;
                $tik['arret_to']=$arret_to;
                $tik['date']=$tick->updated_at->format('Y-m-d H:i:s');
                $tik['amount']=$tick->amount;
               $response = [

                 'ticket' =>$tik ,
               ];
               return response($response, 202);
    
              }

        }
        ///////////////////////////////////////////////////////////////////
        $spcart =  Spcart::where('token','like',$fields['token'])->first();
        $abon = Abonment::where('abon_type',get_class($spcart))->where('abon_id',$spcart->id)->whereDate('end', '>', Carbon::today()->toDateString())->latest()->first();
        $typename = Abtype::where('code',$spcart->type)->first()->name;
        if ($abon) {
            $dateab = $abon->end;
        } else {
            $dateab ='غير صالحة للاستعمال' ;
        }
        if ($spcart) {
             if ($spcart->ban) {
                return response([
                    'message' => 'البطاقة محضورة'
                ], 401);
              }
    
               $tick = E_ticket::where('client_type',Spcart::class)->where('client_id',$spcart->id)->latest()->first();
              if ($tick) {
                $en = config('etus.server.code');
                $srv =  config('etus.server.name');
                $vent = new Cont();
                $vent->c_id = $kabid->id;
                $vent->spcart_id = $spcart->id;
                if ($fields['lat'] != NULL) {
                  $vent->lat  =  $fields['lat'];
                }
                if ($fields['long'] != NULL) {
                  $vent->long  =  $fields['long'];
                }
                $vent->save();
    
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
                    $tik['SER']=$srv;
                    $tik['uuid']=$tick->uuid;
                    $tik['bus']=$busname;
                    $tik['ligne']=$lignename;
                    $tik['arret_from']=$arret_from;
                    $tik['arret_to']=$arret_to;
                    $tik['date']=$tick->created_at->format('Y-m-d H:i:s');
                    $tik['amount']=$tick->amount;
                   $response = [
                     'balance' => $spcart->balance,
                     'pin' => NULL,
                     'abn' => $dateab,
                     'type' => $typename,
                     'ticket' =>$tik,
                   ];
                   return response($response, 201);
        
                  }else{
                    $response = [
                        'balance' =>$spcart->balance,
                        'pin' => NULL,
                        'abn' => $dateab,
                        'type' => $typename,
                        'message' =>'externel ticket' ,
                    ];
                    return response($response, 202);
                  }
              
               }else{
                  $vent = new Cont();
                  $vent->c_id = $kabid->id;
                  $vent->spcart_id = $spcart->id;
                  if ($fields['lat'] != NULL) {
                  $vent->lat  =  $fields['lat'];
                   }
                   if ($fields['long'] != NULL) {
                  $vent->long  =  $fields['long'];
                  }
                  $valideur = Valideur::where('device_id', $fields['deviceid'])->first();
                         $bus = $valideur->bus()->first();
                         if ($bus) {
                            $idb = $bus->id;
                            $line = $bus ->ligne()->first();
                         if ($line) {
                            $idl = $line->id;
                         }
                         else $idl= NULL;
                         }
                         else {$idb = Null;
                            $idl= NULL;
                        }
                         $vent->bus_id = $idb;
                         $vent->ligne_id = $idl;
                  $vent->save();
                  return response([
                  'balance' => $spcart->balance,
                  'pin' => NULL,
                  'abn' => $dateab.' '.$idb.' '.$idl,
                  'type' => $typename,
                    ], 200);
              }  
        }
        ///////////////////////////////////////////////////////////////////
        
     }


}
 ////////////////////////////////////////////////////////////////////////////////
    public function vent(Request $request){

        $kabid=auth()->user();
        $fields = $request->validate([
            'deviceid' => 'required|string',
            'token' => 'required|string',
            'cname' => 'nullable|string',
            'cphone' => 'nullable|string',
        ]);
    
        if ($kabid->device_id != $fields['deviceid'] ) {
            return response([
                'message' => 'عليك بتسجيل الخروج و إعادة الدخول مع تغيير كلمة السر '
            ], 401); 
        }
         
        $cart =  Cart::where('token','like',$fields['token'])->first();
        $vent =  Vent::where('cart_id',$cart->id)->first();
        
        if ($vent) {
            return response([
                'message' => ' لا يمكن اعادة بيع بطاقة مباعة اصلا '
            ], 401);
        }

        if ($cart->paquet()->forn_type != get_class($kabid)  || $cart->paquet()->Forn_id != $kabid->id ) {
            return response([
                'message' => 'بطاقة خاصة ببائع اخر'
            ], 401); 
        }
        if (!$cart) {
            return response([
                'message' => 'بطاقة غير صالحة'
            ], 401);
        }else {
          if ($cart->valid) {
            return response([
                'message' => ' لا يمكن اعادة بيع بطاقة مباعة اصلا '
            ], 401);
          }
          if ($cart->ban) {
            return response([
                'message' => 'البطاقة محضورة'
            ], 401);
          }
          $cart->valid  = true;
          $vent = new Vent();
          $vent->c_type = get_class($kabid);
          $vent->c_id = $kabid->id;
          $vent->cart_id = $cart->id;
          $vent->save();
          if ($fields['cname'] != NULL) {
            $cart->cname  =  $fields['cname'];
          }
          if ($fields['cphone'] != NULL) {
            $cart->cphone  =  $fields['cphone'];
          }
          $cart->save();
          return response([
            'message' => 'تم تفعيل البطاقة بنجاح'
        ], 200);
        }
    }

////////////////////////////////////////////////////////////////////////////////
    public function  getblack(){
        $lines = Blacklist::all();
        if ($lines){
            foreach($lines as $las){
                $nums['token'] = $las['token'];
                $dates[]= $nums;
              }
              return response($dates, 200);
        } else {
            return response()->json([],200);
        }
    }
    /////////////////////////////////////////////////////////////////////////é
    public function transfer(Request $request){
        $en = config('etus.server.code');
        $kabid=auth()->user();
        $fields = $request->validate([
            'deviceid' => 'required|string',
            'token' => 'required|string',
            'amount' => 'required|numeric|min:10'
        ]);
    
        if ($kabid->device_id != $fields['deviceid'] ) {
            return response([
                'message' => 'عليك بتسجيل الخروج و إعادة الدخول مع تغيير كلمة السر '
            ], 401); 
        }
        if ($kabid->balance < $fields['amount'] ) {
            return response([
                'message' => 'رصيدك غير كافي'
            ], 401); 
        }
        $dec = (new EncryptionController)->publicDecrypt($fields['token']);
        if ($dec == NULL) {
            return response([
                'message' => 'رمز غير صالح'
            ], 401); 
        }else {
            $record = explode(",",$dec);
         if ($record[0] == $en) {
                ////// client validation
                if ($record[1] == 'cl') {
                 $client = Client :: find($record[2]);
                   if ($client->uuid == $fields['token']) {
                    if ($client->ban) {
                        return response([
                            'message' => ' حساب موقوف عليك الاتصال بمصلحة الزبائن '
                        ], 401); 
                          }
                          $trans = $kabid->transfer($client, $fields['amount'], ['action' => 'Rechargement du solde' , 'class' => 'App' , 'cl_name' => $client->email , 'user_class' => 'controller' , 'user_name' => $kabid->name]);
                          $flixy = new Flixy();
                          $flixy->flixy_type = get_class($kabid);
                          $flixy->flixy_id = $kabid->id;
                          $flixy->client_type = get_class($client);
                          $flixy->client_id = $client->id;
                          $flixy->amount = $fields['amount'];
                          $flixy->save();
                          if ($client->device_id != NUll) {
                            $checkIfEmailExist = Ftoken::where('device_id',$client->device_id)->first();
                            if ($checkIfEmailExist) {
                                FCMService::send(
                                    $checkIfEmailExist->ftoken,
                                    [
                                        'title' => '+'.$fields['amount'],
                                        'body' => 'تم شحن رصيدك بنجاح',
                                    ],
                                    [
                                         'message' => ''
                                    ],
                                                    );
                                 }
                            }
                          if ($trans) {
                            return response([
                                'message' => 'تمت المصادقة بنجاح'
                            ], 200);
                          } else {
                            return response([
                                'message' => 'حدث مشكل ما اعد المحاول مرة اخرى'
                            ], 401);
                          }
                           
                   }else {
                    return response([
                        'message' => 'رمز المصادقة منتهي الصلاحية'
                    ], 401); 
                   }
                }
                ////// cart validation
                if ($record[1] == 'ca') {
                    $cart = Cart :: find($record[3]);
                    if (!$cart->valid) {
                        return response([
                            'message' => ' لا يمكنك شحن  بطاقة لم يتم بيعها بعد '
                        ], 401); 
                    }
                    if ($cart->ban) {
                        return response([
                            'message' => 'بطاقة موقوفة عليك بالاتصال بمصلحة الزبائن'
                        ], 401); 
                    }
                    if ($cart->token == $fields['token']) {

                        $trans = $kabid->transfer($cart, $fields['amount'], ['action' => 'Rechargement du solde' , 'class' => 'Cart' , 'cl_name' => $cart->id , 'user_class' => 'controller' , 'user_name' => $kabid->name]);
                        $flixy = new Flixy();
                        $flixy->flixy_type = get_class($kabid);
                        $flixy->flixy_id = $kabid->id;
                        $flixy->client_type = get_class($cart);
                        $flixy->client_id = $cart->id;
                        $flixy->amount = $fields['amount'];
                        $flixy->save();
                        
                        if ($trans) {
                          return response([
                              'message' => 'تمت المصادقة بنجاح'
                          ], 200);
                        } else {
                          return response([
                              'message' => 'حدث مشكل ما اعد المحاول مرة اخرى'
                          ], 401);
                        }
                        
                        
                    }else {
                     return response([
                         'message' => 'رمز المصادقة منتهي الصلاحية'
                     ], 401); 
                    }
    
                }
                ///// e_ticket validation
                if ($record[1] == 'et') {
                    return response([
                        'message' => 'خاصية غير مفعلة '
                    ], 401);
                }
               ////// SPC validation
               if ($record[1] == 'spc') {
                       $cart = Spcart :: find($record[3]);
                       if (!$cart->valid) {
                          return response([
                                    'message' => '  لا يمكنك شحن  بطاقة  غير مفغلة   '
                        ], 401); 
                                }
                       if ($cart->ban) {
                         return response([
                                        'message' => 'بطاقة موقوفة عليك بالاتصال بمصلحة الزبائن'
                                    ], 401); 
                                }
                       if ($cart->token == $fields['token']) {

                         $trans = $kabid->transfer($cart, $fields['amount'], ['action' => 'Rechargement du solde', 'class' => 'SP_cart' , 'cl_name' => $cart->id , 'user_class' => 'receveur' , 'user_name' => $kabid->name]);
                         $flixy = new Flixy();
                         $flixy->flixy_type = get_class($kabid);
                         $flixy->flixy_id = $kabid->id;
                         $flixy->client_type = get_class($cart);
                         $flixy->client_id = $cart->id;
                         $flixy->amount = $fields['amount'];
                         $flixy->save();
                         if ($trans) {
                           return response([
                                  'message' => 'تمت المصادقة بنجاح'
                                    ], 200);
                                    } else {
                                      return response([
                                          'message' => 'حدث مشكل ما اعد المحاول مرة اخرى'
                                      ], 401);
                                    }
                                    
                        }else {
                          return response([
                           'message' => 'رمز المصادقة منتهي الصلاحية'
                                 ], 401); 
                         }
                
                    }



                return response([
                    'message' => 'رمز غير مخصص للمصادقة'
                ], 401); 
    
            }else {
                return response([
                    'message' => 'رمز ينتمي الى مؤسسة اخرى'
                ], 401); 
            }
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////
    public function gettrans(Request $request){
        $trans = auth()->user()->transactions()->orderBy('id', 'desc')->take(100)->get();
        if ($trans) {
            foreach($trans as $las){
                $nums['id'] = $las['id'];
                $nums['action'] = $las['meta']['action'] ?? 'nn';
                $nums['class'] = $las['meta']['class'] ?? 'nn';
                $nums['cl_name'] = $las['meta']['cl_name'] ?? 'nn';
                $nums['date'] = $las['created_at']->format('Y-m-d H:i:s');
                $nums['amount'] = $las['amount'];
                $nums['confirm'] = $las['confirmed'];
                $dates[]= $nums;
              }
              return response($dates, 200);
        } else {
            return response()->json(NULL,201);
        }
        

    }
    public function checktoken(Request $request){
        return response()->json([],200);
    }
    //////////////////////////////////////////////////////////////////////////////
    public function logout(Request $request) {
        $user = auth()->user();
        $request->user()->currentAccessToken()->delete();
        $login = new Logininfo();
        $login->user_type = get_class($user);
        $login->user_id = $user->id;
        $login->deviceid = $user->device_id;
        $login->type ='logout';
        $login->save();
        $user->device_id=NULL;
        $user->save();
       return [
            'success' => true,
            'message' => 'Logged out'
        ]; ;
    }
    ///////////////////////////////////////////////////////////////////////////////////
    public function getbalance(Request $request){
            
        $response = [
            'balance' => auth()->user()->balance,
        ];
        return response($response, 200);
    }
     ///////////////////////////////////////////////////////////////////////////////////


             public function login(Request $request) {
    
          
                $fields = $request->validate([
                    'deviceid' => 'required|string',
                    'email' => 'required|string',
                    'password' => 'required|string'
                ]);
        
                // Check email
                $user = Control::where('email', $fields['email'])->first();
                if (!$user) {
                    return response([
                        'message' => ' انت لا تملك حساب  '
                    ], 401);
                }
                $valideur = Valideur::where('device_id', $fields['deviceid'])->first();
                if (!$valideur) {
                    return response([
                        'message' => 'هاتف غير مصرح به'
                    ], 401);
                }else {
                 $user->device_id=$fields['deviceid'];
                 $user->save();

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
                $srvcode = config('etus.server.code');
                $tok =config('etus.key.tok');
                $iv = config('etus.key.iv');
                $token = $user->createToken($fields['deviceid'])->plainTextToken;
                $login = new Logininfo();
                $login->user_type = get_class($user);
                $login->user_id = $user->id;
                $login->deviceid = $fields['deviceid'];
                $login->type ='login';
                $login->save();
                $response = [
                    
                    'success' => true,
                    'controller' => $user,
                    'wallet'=>$user->balance,
                    'token' => $token,
                    'tok' =>$tok,
                    'iv' =>$iv,
                    'srvcode' =>$srvcode,

                ];
        
                return response($response, 200);
            }
     //////////////////////////////////////////////////////////////////////////////
        
            public function cartlogin(Request $request) {
        
                        $fields = $request->validate([
                            'deviceid' => 'required|string',
                            'token' => 'required|string',
                        ]);
                        // Check email
                        $user = Control::where('authtoken', $fields['token'])->first();
                        $valideur = Valideur::where('device_id', $fields['deviceid'])->first();
                        if (!$valideur) {
                            return response([
                                'message' => 'هاتف غير مصرح به'
                            ], 401);
                        }else {
                         $user->device_id=$fields['deviceid'];
                         $user->save();
                        }
                        if (!$user) {
                            return response([
                                'message' => ' بطاقة غير صالحة '
                            ], 401);
                        }
                
                        if ($user->ban) {
                            return response([
                                'message' => ' حسابك معطل لسبب امني اتصل بمصلحة الزبائن',
                            ], 401);
                        }
                        
                        
                        $login = new Logininfo();
                        $login->user_type = get_class($user);
                        $login->user_id = $user->id;
                        $login->type ='login';
                        $login->deviceid = $fields['deviceid'];
                        $login->save();
                        $srvcode = config('etus.server.code');
                        $tok =config('etus.key.tok');
                        $iv = config('etus.key.iv');
                        $token = $user->createToken($fields['deviceid'])->plainTextToken;
                        $response = [
                            'success' => true,
                            'controller' => $user,
                            'wallet'=>$user->balance,
                            'token' => $token,  
                            'tok' =>$tok,
                            'iv' =>$iv,
                            'srvcode' =>$srvcode,  
                        ];
                
                        return response($response, 200);
                    }
    //////////////////////////////////////////////////////////////////////////////////
    public function index()
    {
        return view('ui.controle');
    }

 
}
