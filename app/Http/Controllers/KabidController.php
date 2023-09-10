<?php

namespace App\Http\Controllers;

use App\Models\Abonment;
use App\Models\Abtype;
use Carbon\Carbon;
use App\Models\Arret;
use App\Models\Blacklist;
use App\Models\Blackphone;
use App\Models\Bus;
use App\Models\Cart;
use App\Models\Client;
use App\Models\Cont;
use App\Models\E_ticket;
use App\Models\Flixy;
use App\Models\Frod;
use App\Models\Ftoken;
use App\Models\V_ticket;
use App\Models\Kabid;
use App\Models\Ligne;
use App\Models\Logininfo;
use App\Models\P_Cart;
use App\Models\PV_ticket;
use App\Models\Server;
use App\Models\Spcart;
use App\Models\Valideur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Faker\Guesser\Name;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Services\FCMService;
use Faker\Core\Uuid;
use Illuminate\Support\Facades\Http;

class KabidController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum','auth:kabids'])->only('getblack', 'sync_ticket','transfer','histo','validation','getlines','getbalance','gettickets','gettrans','checktoken','logout');
    }
///////////////////////////////////////////////////
    public function kabidtrans ($id)
    {
        return view('ui.kabidtrans',['cart'=>$id]);
    }

/////////////////////////////////////////////////////////
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
                     'abn' => NULL,
                     'type' => 'default',
                     'ticket' =>$tik ,
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
            $v = 1;
        } else {
            $dateab ='غير صالحة للاستعمال' ;
            $v= 0;
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
                     'ticket' =>$tik ,
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
                  $line = $bus ->ligne()->first();
                  if ($bus && $v) {
                     $idb = $bus->id;
                     $line = $bus ->ligne()->first();
                  if ($line && $v) {
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
                  'abn' => $dateab,
                  'type' => $typename,
                    ], 200);
              }  
        }
        ///////////////////////////////////////////////////////////////////
        
     }


}
///////////////////////////////////////////////////
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
    
///////////////////////////////////////////////////
public function sync_ticket(Request $request){
    $en = config('etus.server.code');
    $kabid=auth()->user();
    $fields = $request->validate([
        'deviceid' => 'required|string',
        'data' => 'required',
    ]);
    if ($kabid->device_id != $fields['deviceid'] ) {
        return response([
            'message' => 'عليك بتسجيل الخروج و إعادة الدخول مع تغيير كلمة السر '
        ], 401); 
    }

    $dd=json_decode($fields['data']);
  
    foreach ($dd as $token) {
        $dec = (new EncryptionController)->publicDecrypt($token->token);
        if ($dec != NULL) {
            $record = explode(",",$dec);
            if ($record[0] == $en) {
                ////// client validation
                if ($record[1] == 'cl') {
                 $client = Client :: find($record[2]);
                       if ($client->ban) {
                           // 'message' => ' حساب موقوف عليك الاتصال بمصلحة الزبائن '
                        }
                       if ($client->balance < $token->amount) {
                        $client->ban = true;
                        $client->save();
                        $blacklist =new Blacklist;
                        $blacklist->token =$client->uuid;
                        $blacklist->save();

                        if ($client->device_id != NUll) {
                            $blackp = Blackphone::where('uuid',$client->device_id)->first();
                            if ($blackp) {
                                $blackp->black = $blackp->black+1; 
                                $blackp->save();
                            }else {
                              $blackphone = new Blackphone;
                              $blackphone->uuid = $client->device_id;
                              $blackphone->black = 1;
                              $blackphone->save();
                            }

                        }   
                         $frod = new Frod;
                         $frod->client_type =Client::class;
                         $frod->client_id =$record[2];
                         $frod->amount = $token->amount;
                         $frod->lat =floatval($token->lat) ;
                         $frod->long =floatval($token->long) ;
                         $valideur = Valideur::where('device_id', $fields['deviceid'])->first();
                         $bus = $valideur->bus()->first();
                         $line = $bus ->ligne()->first();
                         $frod->bus_id = $bus->id;
                         $frod->ligne_id = $line->id;
                         $frod->save();
 
                       } else {
                           $trans = $client->forceWithdraw($token->amount, ['action' => 'acheter un ticket sync']);
                           $tik = new E_ticket;
                           $tik->type = 'offligne';
                           $tik->uuid = $trans->uuid;
                           $date = date_create_from_format('Y-m-d H:i:s', $token->createdAt);
                           $tik->end_valid = $date;
                           $tik->forn_type =Kabid::class;
                           $tik->forn_id =$kabid->id;
                           $tik->client_type =Client::class;
                           $tik->client_id =$record[2];
                           $tik->amount = $token->amount;
                           $tik->arret_from_id = $token->arrfrom;
                           $tik->lat =floatval($token->lat) ;
                           $tik->long =floatval($token->long) ;
                           $tik->skey = $en;
                           $valideur = Valideur::where('device_id', $fields['deviceid'])->first();
                           $bus = $valideur->bus()->first();
                           $line = $bus ->ligne()->first();
                           $tik->bus_id = $bus->id;
                           $tik->ligne_id = $line->id;
                           $tik->save();
                           if ($client->device_id != NUll) {
                            $checkIfEmailExist = Ftoken::where('device_id',$client->device_id)->first();
                            if ($checkIfEmailExist) {
                                FCMService::send(
                                    $checkIfEmailExist->ftoken,
                                    [
                                        'title' => '-'.$token->amount,
                                        'body' => 'تمت تاكيد تخليص التذكرة السابقة',
                                    ],
                                    [
                                         'message' => ''
                                    ],
                                                    );
                                 }
                            } 
                       }
                       
                }
                ////// cart validation
                if ($record[1] == 'ca') {
                    $cart = Cart :: find($record[3]);
                        if ($cart->balance < $token->amount) {
                            $cart->ban = true;
                            $cart->save();
                            $blacklist =new Blacklist;
                            $blacklist->token =$cart->token;
                            $blacklist->save();
                            $frod = new Frod;
                            $frod->client_type =Cart::class;
                            $frod->client_id =$record[3];
                            $frod->amount = $token->amount;
                            $frod->lat =floatval($token->lat) ;
                            $frod->long =floatval($token->long) ;
                            $valideur = Valideur::where('device_id', $fields['deviceid'])->first();
                            $bus = $valideur->bus()->first();
                            $line = $bus ->ligne()->first();
                            $frod->bus_id = $bus->id;
                            $frod->ligne_id = $line->id;
                            $frod->save();

                        } else {
                            $trans = $cart->forceWithdraw($token->amount, ['action' => 'acheter un ticket sync']);
                            $tik = new E_ticket;
                            $tik->type = 'offligne';
                            $tik->uuid = $trans->uuid;
                            $date =date_create_from_format('Y-m-d H:i:s', $token->createdAt); 
                            $tik->end_valid = $date;
                            $tik->forn_type =Kabid::class;
                            $tik->forn_id =$kabid->id;
                            $tik->client_type =Cart::class;
                            $tik->client_id =$record[3];
                            $tik->arret_from_id = $token->arrfrom;
                            $tik->amount = $token->amount;
                            $tik->lat =floatval($token->lat) ;
                            $tik->long =floatval($token->long) ;
                            $tik->skey = $en;
                            $valideur = Valideur::where('device_id', $fields['deviceid'])->first();
                            $bus = $valideur->bus()->first();
                            $line = $bus ->ligne()->first();
                            $tik->bus_id = $bus->id;
                            $tik->ligne_id = $line->id;
                            $tik->save();
                        }  
                    }
    
                ///// e_ticket validation
                if ($record[1] == 'vt') {
                $tik= V_ticket::find($record[3]);
                if ($tik->valid == true) {
                    $frod = new Frod;
                    $frod->client_type =V_ticket::class;
                    $frod->client_id =$record[3];
                    $frod->amount = $token->amount;
                    $frod->lat =floatval($token->lat) ;
                    $frod->long =floatval($token->long) ;
                    $valideur = Valideur::where('device_id', $fields['deviceid'])->first();
                    $bus = $valideur->bus()->first();
                    $line = $bus ->ligne()->first();
                    $frod->bus_id = $bus->id;
                    $frod->ligne_id = $line->id;
                    $frod->save();
                } else {
                    $tik->valid = true;
                    $tik->type = 'offline';
                    $tik->forn_type =Kabid::class;
                    $tik->Forn_id =$kabid->id;
                    $tik->amount = $token->amount;
                    $tik->arret_from_id = $token->arrfrom;
                    $tik->lat =floatval($token->lat) ;
                    $tik->long =floatval($token->long) ;
                    $tik->skey = $en;
                    $valideur = Valideur::where('device_id', $fields['deviceid'])->first();
                    $bus = $valideur->bus()->first();
                    $line = $bus ->ligne()->first();
                    $tik->bus_id = $bus->id;
                    $tik->ligne_id = $line->id;
                    $tik->save();
                }
                }
                //////// SPC validation
                if ($record[1] == 'spc') {
                    $cart = Spcart :: find($record[3]);
                    $abon = Abonment::where('abon_type',get_class($cart))->where('abon_id',$cart->id)->whereDate('end', '>', Carbon::today()->toDateString())->latest()->first();
                    if ($abon &&  $cart->cont < $cart->max) {
                        $tik = new E_ticket;
                        $tik->type = 'spc';
                        $tik->uuid = Str::orderedUuid();
                        $date =date_create_from_format('Y-m-d H:i:s', $token->createdAt); 
                        $tik->end_valid = $date;
                        $tik->forn_type =Kabid::class;
                        $tik->forn_id =$kabid->id;
                        $tik->client_type =Spcart::class;
                        $tik->arret_from_id = $token->arrfrom;
                        $tik->client_id =$record[3];
                        $tik->amount = 0;
                        $tik->lat =floatval($token->lat) ;
                        $tik->long =floatval($token->long) ;
                        $tik->skey = $en;
                        $valideur = Valideur::where('device_id', $fields['deviceid'])->first();
                        $bus = $valideur->bus()->first();
                        $line = $bus ->ligne()->first();
                        $tik->bus_id = $bus->id;
                        $tik->ligne_id = $line->id;
                        $tik->save();
                    } else {
                        if ($cart->balance < $token->amount) {
                            $cart->ban = true;
                            $cart->save();
                            $blacklist =new Blacklist;
                            $blacklist->token =$cart->token;
                            $blacklist->save();
                            $frod = new Frod;
                            $frod->client_type =Spcart::class;
                            $frod->client_id =$record[3];
                            $frod->amount = $token->amount;
                            $frod->lat =floatval($token->lat) ;
                            $frod->long =floatval($token->long) ;
                            $valideur = Valideur::where('device_id', $fields['deviceid'])->first();
                            $bus = $valideur->bus()->first();
                            $line = $bus ->ligne()->first();
                            $frod->bus_id = $bus->id;
                            $frod->ligne_id = $line->id;
                            $frod->save();

                        } else {
                            $trans = $cart->forceWithdraw($token->amount, ['action' => 'acheter un ticket sync']);
                            $tik = new E_ticket;
                            $tik->type = 'offligne';
                            $tik->uuid = $trans->uuid;
                            $date =date_create_from_format('Y-m-d H:i:s', $token->createdAt); 
                            $tik->end_valid = $date;
                            $tik->forn_type =Kabid::class;
                            $tik->forn_id =$kabid->id;
                            $tik->client_type =Spcart::class;
                            $tik->client_id =$record[3];
                            $tik->arret_from_id = $token->arrfrom;
                            $tik->amount = $token->amount;
                            $tik->lat =floatval($token->lat) ;
                            $tik->long =floatval($token->long) ;
                            $tik->skey = $en;
                            $valideur = Valideur::where('device_id', $fields['deviceid'])->first();
                            $bus = $valideur->bus()->first();
                            $line = $bus ->ligne()->first();
                            $tik->bus_id = $bus->id;
                            $tik->ligne_id = $line->id;
                            $tik->save();
                        }  
                    }

                    }
               }
            }
        }
        return response([
            'message' => 'تمت المزامنة بنجاح'
        ], 200); 
    }

////////////////////////////////////////////////////////////////////////////////////////////
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

                          $trans = $kabid->transfer($client, $fields['amount'], ['action' => 'Rechargement du solde', 'class' => 'App' , 'cl_name' => $client->email , 'user_class' => 'receveur' , 'user_name' => $kabid->name]);
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
    

                        $trans = $kabid->transfer($cart, $fields['amount'], ['action' => 'Rechargement du solde', 'class' => 'Cart' , 'cl_name' => $cart->id , 'user_class' => 'receveur' , 'user_name' => $kabid->name]);
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
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function histo(){
        $res_id = auth()->user()->id;

        $last = E_ticket::where('forn_type', Kabid::class )->where('forn_id',$res_id )->OrderBy('id', 'desc')->take(30)->get();
        
        foreach($last as $las){
          $nums['uuid'] = $las['uuid'];
          $nums['amount'] = $las['amount'];

          if ($las->client_type == Client::class) {
          $client = Client::find($las->client_id);
          $nums['name'] = $client->email;
          } 
          if ($las->client_type == Cart::class) {
            $client = Cart::find($las->client_id);
            switch ($client->id) {

                case ($client->id <= 9):
                  $zero = '00000000';
                  break;
                case ($client->id <= 99):
                  $zero = '0000000';
                  break;
                case ($client->id <= 999):
                  $zero = '000000';
                  break;
                case ($client->id <= 9999):
                    $zero = '00000';
                    break;
                case ($client->id <= 99999):
                    $zero = '0000';
                    break;
                case ($client->id <= 999999):
                $zero = '000';
                break;
                case ($client->id <= 9999999):
                  $zero = '00';
                  break;
                  case ($client->id <= 99999999):
                  $zero = '0';
                  break;
                  case ($client->id <= 999999999):
                    $zero = '';
                    break;
               }
            $nums['name'] ="N:$zero$client->id";
          }
          if ($las->client_type == Spcart::class) {
            $client = Spcart::find($las->client_id);
            switch ($client->id) {

                case ($client->id <= 9):
                  $zero = '00000000';
                  break;
                case ($client->id <= 99):
                  $zero = '0000000';
                  break;
                case ($client->id <= 999):
                  $zero = '000000';
                  break;
                case ($client->id <= 9999):
                    $zero = '00000';
                    break;
                case ($client->id <= 99999):
                    $zero = '0000';
                    break;
                case ($client->id <= 999999):
                $zero = '000';
                break;
                case ($client->id <= 9999999):
                  $zero = '00';
                  break;
                  case ($client->id <= 99999999):
                  $zero = '0';
                  break;
                  case ($client->id <= 999999999):
                    $zero = '';
                    break;
               }
            $nums['name'] ="SPN:$zero$client->id";
          } 
          $nums['date'] = $las['created_at']->format('Y-m-d H:i:s');
          $dates[]= $nums;
        }
        return response($dates, 200);
    }
/////////////////////////////////////////////////////////////////////////////////////////////////////

public function validation(Request $request){
    $en = config('etus.server.code');
    $kabid=auth()->user();
    $fields = $request->validate([
        'deviceid' => 'required|string',
        'token' => 'required|string',
        'lat' => 'nullable|string',
        'long' => 'nullable|string',
        'amount' => 'required|numeric|min:10',
        'arret' => 'nullable|integer',
    ]);

    if ($kabid->device_id != $fields['deviceid'] ) {
        return response([
            'message' => 'عليك بتسجيل الخروج و إعادة الدخول مع تغيير كلمة السر '
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
                            $valideur = Valideur::where('device_id', $fields['deviceid'])->first();
                            $bus = $valideur->bus()->first();
                            $line = $bus ->ligne()->first();
                            $trans = $client->forceWithdraw($fields['amount'], ['action' => 'acheter un ticket','bus' => $bus->id,'line' => $line->id]);
                            $tik = new E_ticket;
                            $tik->uuid = $trans->uuid;
                            $date = Carbon::now()->addHour();
                            $tik->end_valid = $date;
                            $tik->forn_type =Kabid::class;
                            $tik->forn_id =$kabid->id;

                            if ($fields['arret'] != 0) {
                                $tik->arret_from_id =$fields['arret'];
                            }
                            
                            $tik->client_type =Client::class;
                            $tik->client_id =$record[2];
                            $tik->amount = $fields['amount'];
                            $tik->lat =$fields['lat'] ;
                            $tik->long =$fields['long'] ;
                            $tik->skey = $en;
                            
                            $tik->bus_id = $bus->id;
                            $tik->ligne_id = $line->id;
                            $tik->save();
                            return response([
                             'message' => 'تمت المصادقة بنجاح'
                         ], 200);
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
                            $valideur = Valideur::where('device_id', $fields['deviceid'])->first();
                            $bus = $valideur->bus()->first();
                            $line = $bus ->ligne()->first();
                            $trans = $cart->forceWithdraw($fields['amount'], ['action' => 'acheter un ticket','bus' => $bus->id,'line' => $line->id]);
                            $tik = new E_ticket;
                            $tik->uuid = $trans->uuid;
                            $date = Carbon::now()->addHour();
                            $tik->end_valid = $date;
                            $tik->forn_type =Kabid::class;
                            $tik->forn_id =$kabid->id;
                            if ($fields['arret'] != 0) {
                                $tik->arret_from_id =$fields['arret'];
                            }
                            $tik->client_type =Cart::class;
                            $tik->client_id =$record[3];
                            $tik->amount = $fields['amount'];
                            $tik->lat = $fields['lat']??'0.000000';
                            $tik->long = $fields['long']??'0.000000';
                            $tik->skey = $en;
                            
                            $tik->bus_id = $bus->id;
                            $tik->ligne_id = $line->id;
                            $tik->save();
                            return response([
                                'message' =>  " تمت المصادقة بنجاح الرصيد المتبقي : $cart->balance دج "
                            ], 200);
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

                        $tik= V_ticket::find($record[3]);
                        
                        if ($tik) {
        
                            if ($tik->valid==true) {
                                return response([
                                    'message' => 'تذكرة مستعملة'
                                   ], 401);
                            }
                             if ($tik->amount < $fields['amount']) {
                                    return response([
                                        'message' => 'تمن التذكرة اقل من ثمن الرحلة'
                                       ], 401);
                                }
                                 $pak = PV_ticket::find($tik->pak_id); 
                                if ($pak->forn_type != get_class($kabid) || $pak->Forn_id != $kabid->id) {
                                    return response([
                                        'message' => 'تذكرة غير مسندة اليك'
                                       ], 401);
                                }
                                    $date = Carbon::now()->addHour();
                                    $tik->valid =true;
                                    $tik->skey = $en;
                                    $tik->end_valid = $date;
                                    $tik->forn_type =Kabid::class;
                                    $tik->forn_id =$kabid->id;
                                    if ($fields['arret'] != 0) {
                                        $tik->arret_from_id =$fields['arret'];
                                    }
                                    $tik->lat = $fields['lat']??'0.000000';
                                    $tik->long = $fields['long']??'0.000000';
                                    $valideur = Valideur::where('device_id', $fields['deviceid'])->first();
                                    $bus = $valideur->bus()->first();
                                    $line = $bus->ligne()->first();
                                    $tik->bus_id = $bus->id;
                                    $tik->ligne_id = $line->id;
                                    $tik->save();
                                    return response([
                                     'message' => 'تمت المصادقة بنجاح'
                                 ], 200);
                                
                            
                        } else {
                            return response([
                                'message' => 'خاصية غير مفعلة '
                               ], 401);
                        }
                        
                    
                    break;
                case 'ab':    ///// abonnement validation
                    return response([
                        'message' => 'خاصية غير مفعلة '
                    ], 401);
                    break;
                case 'spc':    ///// spc validation
                        $spc = Spcart :: find($record[3]);
                        if ($spc->token <> $fields['token']) {
                            return response([
                                'message' => 'رمز المصادقة منتهي الصلاحية'
                            ], 401); 
                        }
                        if ($spc->ban) {
                            return response([
                                'message' => ' حساب موقوف عليك الاتصال بمصلحة الزبائن '
                            ], 401); 
                        }
                        if (!$spc->valid) {
                            return response([
                                'message' => ' بطاقة غير مفعلة  '
                            ], 401); 
                        }
                        if ($spc->max <> NULL) {
                            if ($spc->cont >= $spc->max) {
                                if ($spc->balance >= $fields['amount']) {
                                    $valideur = Valideur::where('device_id', $fields['deviceid'])->first();
                                    $bus = $valideur->bus()->first();
                                    $line = $bus ->ligne()->first();
                                    $trans = $spc->forceWithdraw($fields['amount'], ['action' => 'acheter un ticket','bus' => $bus->id,'line' => $line->id]);
                                    $tik = new E_ticket;
                                    $tik->uuid = $trans->uuid;
                                    $date = Carbon::now()->addHour();
                                    $tik->end_valid = $date;
                                    $tik->forn_type =Kabid::class;
                                    $tik->forn_id =$kabid->id;
                                    if ($fields['arret'] != 0) {
                                        $tik->arret_from_id =$fields['arret'];
                                    }
                                    $tik->client_type =Spcart::class;
                                    $tik->client_id =$spc->id;
                                    $tik->amount = $fields['amount'];
                                    $tik->lat = $fields['lat']??'0.000000';
                                    $tik->long = $fields['long']??'0.000000';
                                    $tik->skey = $en;
                                    $tik->bus_id = $bus->id;
                                    $tik->ligne_id = $line->id;
                                    $tik->save();
                                    return response([
                                        'message' =>  " تمت المصادقة بنجاح الرصيد المتبقي : $spc->balance دج "
                                    ], 200);
                                } else {                            
                                    return response([
                                        'message' => 'تجاوزت الحد اليومي المسموح به'
                                    ], 401); 
                                }
                            }
                        }

                        $abon = Abonment::where('abon_type',get_class($spc))->where('abon_id',$spc->id)->whereDate('end', '>', Carbon::today()->toDateString())->latest()->first();
                        if (!$abon) {
                            if ($spc->balance >= $fields['amount']) {
                                $valideur = Valideur::where('device_id', $fields['deviceid'])->first();
                                $bus = $valideur->bus()->first();
                                $line = $bus ->ligne()->first();
                                $trans = $spc->forceWithdraw($fields['amount'], ['action' => 'acheter un ticket','bus' => $bus->id,'line' => $line->id]);
                                $tik = new E_ticket;
                                $tik->uuid = $trans->uuid;
                                $date = Carbon::now()->addHour();
                                $tik->end_valid = $date;
                                $tik->forn_type =Kabid::class;
                                $tik->forn_id =$kabid->id;
                                if ($fields['arret'] != 0) {
                                    $tik->arret_from_id =$fields['arret'];
                                }
                                $tik->client_type =Spcart::class;
                                $tik->client_id =$spc->id;
                                $tik->amount = $fields['amount'];
                                $tik->lat = $fields['lat']??'0.000000';
                                $tik->long = $fields['long']??'0.000000';
                                $tik->skey = $en;
                                $tik->bus_id = $bus->id;
                                $tik->ligne_id = $line->id;
                                $tik->save();
                                return response([
                                    'message' =>  " تمت المصادقة بنجاح الرصيد المتبقي : $spc->balance دج "
                                ], 200);
                            } else {                            
                            return response([
                                'message' => ' لا تملك عرض مفعل و لا تملك رصيد كافي '
                            ], 401); 
                            }

                        }else {
                            $valideur = Valideur::where('device_id', $fields['deviceid'])->first();
                            $bus = $valideur->bus()->first();
                            $line = $bus ->ligne()->first();
                           
                            $tik = new E_ticket;
                            $tik->uuid = Str::orderedUuid();
                            $date = Carbon::now()->addHour();
                            $tik->type = 'spc';
                            $tik->end_valid = $date;
                            $tik->forn_type =Kabid::class;
                            $tik->forn_id =$kabid->id;
                            if ($fields['arret'] != 0) {
                                $tik->arret_from_id =$fields['arret'];
                            }
                            $tik->client_type =Spcart::class;
                            $tik->client_id =$spc->id;
                            $tik->amount = 0;
                            $tik->lat = $fields['lat']??'0.000000';
                            $tik->long = $fields['long']??'0.000000';
                            $tik->skey = $en;
                            $tik->bus_id = $bus->id;
                            $tik->ligne_id = $line->id;
                            $tik->save();
                            $spc->cont = $spc->cont + 1;
                            $spc->save();
                            return response([
                                'message' =>  "تمت المصادقة بنجاح تفقد البطاقة يدويا"
                            ], 200);
                        }
                        return response([
                            'message' => 'خطا غير معرف اعد المحاولة'
                        ], 401); 
                    break;
                default:
                return response([
                    'message' => 'رمز غير مخصص للمصادقة'
                ], 401); 
              }

        }else {
            $cli = Server::where('key',$record[0])->first();
            if (!$cli) {
                return response([
                    'message' => 'سرفر غير موجود'
                ], 401);
            }
            if (!$cli->connect || $cli->token == NULL || !$cli->enable) {
                return response([
                    'message' => ' سرفر غير مقترن '
                ], 401);
            }
            $data=[
                'token' => $fields['token'],
                'amount'=> $fields['amount']
            ];
           
            $link = 'https://'.$cli->url.'/api/server_validation';
            $response = Http::withToken($cli->token)->post($link,$data);
            $res = json_decode($response->body());
            if ($response->status() == 202) {
                $valideur = Valideur::where('device_id', $fields['deviceid'])->first();
                $bus = $valideur->bus()->first();
                $line = $bus ->ligne()->first();
                $tik = new E_ticket;
                $tik->uuid =  $res->uuid;
                $date = Carbon::now()->addHour();
                $tik->end_valid = $date;
                $tik->forn_type =Kabid::class;
                $tik->forn_id =$kabid->id;
                if ($fields['arret'] != 0) {
                    $tik->arret_from_id =$fields['arret'];
                }
                $tik->client_type =$res->class;
                $tik->client_id =$res->id;
                $tik->amount = $fields['amount'];
                $tik->lat = $fields['lat']??'0.000000';
                $tik->long = $fields['long']??'0.000000';
                $tik->skey = $cli->key;
                $tik->bus_id = $bus->id;
                $tik->ligne_id = $line->id;
                $tik->save();
                return response([
                 'message' => 'تمت المصادقة بنجاح'
             ], 200);    
               
            }else {
                return response([
                    'message' => $res->message
                ], 401);
            }

        }
    }


}
/////////////////////////////////////////////////////////////////////////////////////////////////////
    public function  getarrets(){
        $lines = Arret::all();
        if ($lines ) {
            foreach($lines as $las){
                $nums['id'] = $las['id'];
                $nums['name'] = $las['name'];
                $nums['lat'] = $las['lat'];
                $nums['lang'] = $las['long'];
                $dates[]= $nums;
              }
              return response($dates, 200);
        } else {
            return response()->json([],201);
        }
    }
    ////////////////////////////////////////////////////
    public function  getlines(){
        $lines = Ligne::all();
        if ($lines ) {
            foreach($lines as $las){
                $nums['id'] = $las['id'];
                $nums['name'] = $las['name'];
                $nums['arrets'] = json_encode($las['arrets']);
                $dates[]= $nums;
              }
              return response($dates, 200);
        } else {
            return response()->json([],201);
        }
    }
    ////////////////////////////////////////////////////
    public function gettickets(Request $request){
        $user=auth()->user();
        $id =auth()->user()->id;
        $clas = class_basename($user);
        $tiks = E_ticket::where('forn_id',$id)->where('forn_type',$user)->orderBy('id', 'desc')->take(20)->get();
        if ($tiks) {
            foreach($tiks as $las){
                $nums['id'] = $las['id'];
                $nums['type'] = $las['type']?? 'default';
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
///////////////////////////////////////////////////////////////////////////
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
   //////////////////////////////////////////////////////////////////////////////
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
            $login->type ='logout';
            $login->deviceid = $user->device_id;
            $login->save();
            $user->device_id=NULL;
            $user->save();

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
            $user = Kabid::where('email', $fields['email'])->first();
            if (!$user) {
                return response([
                    'message' => ' انت لا تملك حساب  '
                ], 401);
            }
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
            $valideur = Valideur::where('device_id', $fields['deviceid'])->first();
            if (!$valideur) {
                return response([
                    'message' => 'هاتف غير مصرح به'
                ], 401);
            }else {
                if ($valideur->bus_id == NULL) {
                    return response([
                        'message' => 'هاتف غير مسند لاي حافلة'
                    ], 401);
                }
             $user->device_id=$fields['deviceid'];
             $user->save();
             $bus = $valideur->bus()->first();
             $line = $bus ->ligne()->first();
             if (!$line) {
                return response([
                    'message' => 'حافلة غير مسندة باي خط اتصل بالادارة فورا'
                ], 401);
            }
             
             $arrets = Arret::whereIn('id', $line->arrets)->get(array('id', 'name', 'lat', 'long'));
            }

    
            // Check password

            $login = new Logininfo();
            $login->user_type = get_class($user);
            $login->user_id = $user->id;
            $login->bus_id = $bus->id;
            $login->type ='login';
            $login->deviceid = $fields['deviceid'];
            $login->save();

            $token = $user->createToken($fields['deviceid'])->plainTextToken;
            $tok = config('etus.key.tok');
            $iv = config('etus.key.iv');
            $srvcode = config('etus.server.code');
            $response = [
                'success' => true,
                'kabid' => $user,
                'arrets' => $arrets,
                'wallet'=>$user->balance,
                'token' => $token,
                'bus' => $bus->name,
                'ligne' =>$line->name,
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
                    $user = Kabid::where('authtoken', $fields['token'])->first();
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
                    $valideur = Valideur::where('device_id', $fields['deviceid'])->first();
                    if (!$valideur) {
                        return response([
                            'message' => 'هاتف غير مصرح به'
                        ], 401);
                    }else {
                     
                        if ($valideur->bus_id == NULL) {
                            return response([
                                'message' => 'هاتف غير مسند لاي حافلة'
                            ], 401);
                        }
                     $user->device_id=$fields['deviceid'];
                     $user->save();
                     $bus = $valideur->bus()->first();
                     $line = $bus ->ligne()->first();
                     $im =implode(',', $line->arrets);
                     $arrets = Arret::whereIn('id', $line->arrets)->get(array('id', 'name', 'lat', 'long'));

                    }

                    $login = new Logininfo();
                    $login->user_type = get_class($user);
                    $login->user_id = $user->id;
                    $login->bus_id = $bus->id;
                    $login->type ='login';
                    $login->deviceid = $fields['deviceid'];
                    $login->save();

                    $token = $user->createToken($fields['deviceid'])->plainTextToken;
                    $tok =config('etus.key.tok');
                    $iv = config('etus.key.iv');
                    $srvcode = config('etus.server.code');
                    $response = [
                        'success' => true,
                        'kabid' => $user,
                        'arrets' => $arrets,
                        'wallet'=>$user->balance,
                        'token' => $token,
                        'bus' => $bus->name,
                        'ligne' =>$line->name,
                        'tok' =>$tok,
                        'iv' =>$iv,
                        'srvcode' =>$srvcode,
                    ];
            
                    return response($response, 200);
                }
//////////////////////////////////////////////////////////////////////////////////
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ui.kabid');
    }

}
