<?php

namespace App\Http\Controllers;

use App\Http\Livewire\Payget;
use App\Models\Bus;
use App\Models\Cart;
use App\Models\Client;
use App\Models\Cont;
use App\Models\Control;
use App\Models\E_ticket;
use App\Models\Flixy;
use App\Models\Kabid;
use App\Models\Logininfo;
use App\Models\Ps_cart;
use App\Models\Sold_cart;
use App\Models\User;
use App\Models\V_ticket;
use App\Models\Vendeur;
use App\Models\Vent;
use App\Models\Spcart;
use Barryvdh\DomPDF\Facade\Pdf;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class StatController extends Controller
{
    public function index()
    {
        return view('ui.stats');
    }  

    public function  stat1(Request $request)
    {
        $req = $request->validate([
            'type' => 'required|string',
            'type_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
           ]);
           $from= $req['start_date'];
           $to= $req['end_date'];

           if ($req['type'] == Control::class) {
            $user= Control::find($req['type_id']);
            $solde = Sold_cart::whereBetween('sold_carts.updated_at', [$from, $to])
            ->where('valid',false)->
            join('ps_carts', 'sold_carts.ps_id', '=', 'ps_carts.id')
            ->where('ps_carts.forn_type', $req['type'])
            ->where('ps_carts.forn_id', $req['type_id'])
            ->sum('sold_carts.sold');

            $solde2 = Sold_cart::whereBetween('sold_carts.updated_at', [$from, $to])
            ->where('valid',false)->
            join('ps_carts', 'sold_carts.ps_id', '=', 'ps_carts.id')
            ->where('ps_carts.forn_type', $req['type'])
            ->where('ps_carts.forn_id', $req['type_id'])
            ->count();

            $vent = Vent::where('c_type',Control::class)->where('c_id',$req['type_id'])->whereBetween('created_at', [$from, $to])->get();

            $flixy = Flixy::where('flixy_type',Control::class)->where('flixy_id',$req['type_id'])->whereBetween('created_at', [$from, $to])->sum('amount');
            $info = Logininfo::where('user_type',$req['type'])->where('user_id',$req['type_id'])->latest()->take(4)->get();
            $cartvend = Vent::where('c_type',$req['type'])->where('c_id',$req['type_id'])->whereBetween('created_at', [$from, $to])->count();
            $cont_cart = Cont::where('cart_id','!=',NULL)->where('c_id',$req['type_id'])->whereBetween('created_at', [$from, $to])->count();
            $cont_vt = Cont::where('vt_id','!=',NULL)->where('c_id',$req['type_id'])->whereBetween('created_at', [$from, $to])->count();
            $cont_app = Cont::where('app_id','!=',NULL)->where('c_id',$req['type_id'])->whereBetween('created_at', [$from, $to])->count();
            $trans=$user->transactions()->whereBetween('created_at', [$from, $to])->latest()->get();
            $pdf = Pdf::loadView('pdf.pdf1',['cartvent'=>$cartvend,'cont_cart'=>$cont_cart,'cont_vt'=>$cont_vt,'cont_app'=>$cont_app,'trans'=>$trans,'user'=>$user,'from'=>$from,'to'=>$to,'info'=>$info ,'solde'=>$solde ,'solde2'=>$solde2,'flixy'=>$flixy,'vent'=>$vent]);
            return $pdf->stream($user->id."-".$from."_C_".$to.".pdf");
           }

           if ($req['type'] == Kabid::class) {
            $user= Kabid::find($req['type_id']);
            $solde = Sold_cart::whereBetween('sold_carts.updated_at', [$from, $to])
            ->where('valid',false)->
            join('ps_carts', 'sold_carts.ps_id', '=', 'ps_carts.id')
            ->where('ps_carts.forn_type', $req['type'])
            ->where('ps_carts.forn_id', $req['type_id'])
            ->sum('sold_carts.sold');

            $solde2 = Sold_cart::whereBetween('sold_carts.updated_at', [$from, $to])
            ->where('valid',false)->
            join('ps_carts', 'sold_carts.ps_id', '=', 'ps_carts.id')
            ->where('ps_carts.forn_type', $req['type'])
            ->where('ps_carts.forn_id', $req['type_id'])
            ->count();
            $flixy = Flixy::where('flixy_type',Kabid::class)->where('flixy_id',$req['type_id'])->whereBetween('created_at', [$from, $to])->sum('amount');
            $info = Logininfo::where('user_type',$req['type'])->where('user_id',$req['type_id'])->latest()->take(4)->get();
            $alltickets = E_ticket::where('forn_type',$req['type'])->where('Forn_id',$req['type_id'])->whereBetween('created_at', [$from, $to])->count();
            $all_sum = E_ticket::where('forn_type',$req['type'])->where('Forn_id',$req['type_id'])->whereBetween('created_at', [$from, $to])->sum('amount');

            $alltickets_0 = E_ticket::where('forn_type',$req['type'])->where('Forn_id',$req['type_id'])->whereBetween('created_at', [$from, $to])->where('amount',0)->count();
            $all_sum_0 = E_ticket::where('forn_type',$req['type'])->where('Forn_id',$req['type_id'])->whereBetween('created_at', [$from, $to])->where('amount',0)->sum('amount');

            $alltickets_15 = E_ticket::where('forn_type',$req['type'])->where('Forn_id',$req['type_id'])->whereBetween('created_at', [$from, $to])->where('amount',15)->count();
            $all_sum_15 = E_ticket::where('forn_type',$req['type'])->where('Forn_id',$req['type_id'])->whereBetween('created_at', [$from, $to])->where('amount',15)->sum('amount');


            $alltickets_20 = E_ticket::where('forn_type',$req['type'])->where('Forn_id',$req['type_id'])->whereBetween('created_at', [$from, $to])->where('amount',20)->count();
            $all_sum_20 = E_ticket::where('forn_type',$req['type'])->where('Forn_id',$req['type_id'])->whereBetween('created_at', [$from, $to])->where('amount',20)->sum('amount');


            $alltickets_25 = E_ticket::where('forn_type',$req['type'])->where('Forn_id',$req['type_id'])->whereBetween('created_at', [$from, $to])->where('amount',25)->count();
            $all_sum_25 = E_ticket::where('forn_type',$req['type'])->where('Forn_id',$req['type_id'])->whereBetween('created_at', [$from, $to])->where('amount',25)->sum('amount');

            $alltickets_30 = E_ticket::where('forn_type',$req['type'])->where('Forn_id',$req['type_id'])->whereBetween('created_at', [$from, $to])->where('amount',30)->count();
            $all_sum_30 = E_ticket::where('forn_type',$req['type'])->where('Forn_id',$req['type_id'])->whereBetween('created_at', [$from, $to])->where('amount',30)->sum('amount');


            $allvtickets = V_ticket::where('forn_type',$req['type'])->where('Forn_id',$req['type_id'])->where('valid',true)->whereBetween('updated_at', [$from, $to])->count();
            $all_vsum = V_ticket::where('forn_type',$req['type'])->where('Forn_id',$req['type_id'])->where('valid',true)->whereBetween('updated_at', [$from, $to])->sum('amount');

            $allvtickets_15 = V_ticket::where('forn_type',$req['type'])->where('Forn_id',$req['type_id'])->where('valid',true)->whereBetween('updated_at', [$from, $to])->where('amount',15)->count();
            $all_vsum_15 = V_ticket::where('forn_type',$req['type'])->where('Forn_id',$req['type_id'])->where('valid',true)->whereBetween('updated_at', [$from, $to])->where('amount',15)->sum('amount');

            $allvtickets_20 = V_ticket::where('forn_type',$req['type'])->where('Forn_id',$req['type_id'])->where('valid',true)->whereBetween('updated_at', [$from, $to])->where('amount',20)->count();
            $all_vsum_20 = V_ticket::where('forn_type',$req['type'])->where('Forn_id',$req['type_id'])->where('valid',true)->whereBetween('updated_at', [$from, $to])->where('amount',20)->sum('amount');

            $allvtickets_25 = V_ticket::where('forn_type',$req['type'])->where('Forn_id',$req['type_id'])->where('valid',true)->whereBetween('updated_at', [$from, $to])->where('amount',25)->count();
            $all_vsum_25 = V_ticket::where('forn_type',$req['type'])->where('Forn_id',$req['type_id'])->where('valid',true)->whereBetween('updated_at', [$from, $to])->where('amount',25)->sum('amount');

            $allvtickets_30 = V_ticket::where('forn_type',$req['type'])->where('Forn_id',$req['type_id'])->where('valid',true)->whereBetween('updated_at', [$from, $to])->where('amount',30)->count();
            $all_vsum_30 = V_ticket::where('forn_type',$req['type'])->where('Forn_id',$req['type_id'])->where('valid',true)->whereBetween('updated_at', [$from, $to])->where('amount',30)->sum('amount');


            
            $all_vt_has=V_ticket::whereIn('pak_id', function($query)use ($req){
                $query->select('id')
                ->from('p_v_tickets')
                ->where('forn_type',$req['type'])
                ->where('Forn_id',$req['type_id']);
            })->count();

            $all_vt_vent=V_ticket::where('forn_type',$req['type'])->where('Forn_id',$req['type_id'])->where('valid',true)->count();

            $cont_app = Cont::where('app_id','!=',NULL)->where('c_id',$req['type_id'])->whereBetween('created_at', [$from, $to])->count();
            $trans=$user->transactions()->whereBetween('created_at', [$from, $to])->latest()->get();
            $pdf = Pdf::loadView('pdf.pdf2',['all_sum'=>$all_sum,'alltickets'=>$alltickets,'all_sum_0'=>$all_sum_0,'all_sum_15'=>$all_sum_15,'alltickets_0'=>$alltickets_0,'alltickets_15'=>$alltickets_15,'all_sum_20'=>$all_sum_20,'alltickets_20'=>$alltickets_20,'all_sum_25'=>$all_sum_25,'alltickets_25'=>$alltickets_25,'all_sum_30'=>$all_sum_30,'alltickets_30'=>$alltickets_30,'allvtickets'=>$allvtickets,'all_vsum'=>$all_vsum,'allvtickets_15'=>$allvtickets_15,'all_vsum_15'=>$all_vsum_15,'allvtickets_20'=>$allvtickets_20,'all_vsum_20'=>$all_vsum_20,'allvtickets_25'=>$allvtickets_25,'all_vsum_25'=>$all_vsum_25,'allvtickets_30'=>$allvtickets_30,'all_vsum_30'=>$all_vsum_30,'trans'=>$trans,'user'=>$user,'from'=>$from,'to'=>$to,'info'=>$info ,'solde'=>$solde ,'solde2'=>$solde2,'flixy'=>$flixy,'all_vt_vent'=>$all_vt_vent,'all_vt_has'=>$all_vt_has]);
            return $pdf->stream($user->id."-".$from."_K_".$to.".pdf");
           }


          if ($req['type'] == Vendeur::class) {
            
           }

    } 

   
    public function  stat2(Request $request)
    {
        $req = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
           ]);
           $from= $req['start_date'];	
           $to= $req['end_date'];
           $en = config('etus.server.code');

            $etick = E_ticket::whereBetween('created_at', [$from, $to])->where('skey',$en)->get();

            $etick_ext_app_in = E_ticket::whereBetween('created_at', [$from, $to])->where('skey','<>',$en)->where('client_type','cl')->get();
            $etick_ext_cart_in = E_ticket::whereBetween('created_at', [$from, $to])->where('skey','<>',$en)->where('client_type','ca')->get();

            $etick_ext_app_out = E_ticket::whereBetween('created_at', [$from, $to])->where('skey','<>',$en)->where('client_type',Client::class)->get();
            $etick_ext_cart_out = E_ticket::whereBetween('created_at', [$from, $to])->where('skey','<>',$en)->where('client_type',Cart::class)->get();


            $etick_0 = E_ticket::whereBetween('created_at', [$from, $to])->where('skey',$en)->where('amount',0)->get();
            $etick_15 = E_ticket::whereBetween('created_at', [$from, $to])->where('skey',$en)->where('amount',15)->get();
            $etick_20 = E_ticket::whereBetween('created_at', [$from, $to])->where('skey',$en)->where('amount',20)->get();
            $etick_25 = E_ticket::whereBetween('created_at', [$from, $to])->where('skey',$en)->where('amount',25)->get();
            $etick_30 = E_ticket::whereBetween('created_at', [$from, $to])->where('skey',$en)->where('amount',30)->get();

            $etick_0_app = E_ticket::whereBetween('created_at', [$from, $to])->where('client_type',Client::class)->where('skey',$en)->where('amount',0)->get();
            $etick_15_app = E_ticket::whereBetween('created_at', [$from, $to])->where('client_type',Client::class)->where('skey',$en)->where('amount',15)->get();
            $etick_20_app = E_ticket::whereBetween('created_at', [$from, $to])->where('client_type',Client::class)->where('skey',$en)->where('amount',20)->get();
            $etick_25_app = E_ticket::whereBetween('created_at', [$from, $to])->where('client_type',Client::class)->where('skey',$en)->where('amount',25)->get();
            $etick_30_app = E_ticket::whereBetween('created_at', [$from, $to])->where('client_type',Client::class)->where('skey',$en)->where('amount',30)->get();

            $etick_0_cart = E_ticket::whereBetween('created_at', [$from, $to])->where('client_type',Cart::class)->where('skey',$en)->where('amount',0)->get();
            $etick_15_cart = E_ticket::whereBetween('created_at', [$from, $to])->where('client_type',Cart::class)->where('skey',$en)->where('amount',15)->get();
            $etick_20_cart = E_ticket::whereBetween('created_at', [$from, $to])->where('client_type',Cart::class)->where('skey',$en)->where('amount',20)->get();
            $etick_25_cart = E_ticket::whereBetween('created_at', [$from, $to])->where('client_type',Cart::class)->where('skey',$en)->where('amount',25)->get();
            $etick_30_cart = E_ticket::whereBetween('created_at', [$from, $to])->where('client_type',Cart::class)->where('skey',$en)->where('amount',30)->get();
            


            $vtick = V_ticket::where('valid',true)->whereBetween('updated_at', [$from, $to])->count();
            $vsum = V_ticket::where('valid',true)->whereBetween('updated_at', [$from, $to])->sum('amount');

            $vtick_15 = V_ticket::where('valid',true)->whereBetween('updated_at', [$from, $to])->where('amount',15)->get();
            $vtick_20 = V_ticket::where('valid',true)->whereBetween('updated_at', [$from, $to])->where('amount',20)->get();
            $vtick_25 = V_ticket::where('valid',true)->whereBetween('updated_at', [$from, $to])->where('amount',25)->get();
            $vtick_30 = V_ticket::where('valid',true)->whereBetween('updated_at', [$from, $to])->where('amount',30)->get();

            $flixy = Flixy::whereBetween('created_at', [$from, $to])->get();

            $solde = Sold_cart::whereBetween('updated_at', [$from, $to])
            ->where('valid',false)
            ->sum('sold');
            $solde_count = Sold_cart::whereBetween('updated_at', [$from, $to])
            ->where('valid',false)
            ->count();

            $cartvend = Vent::whereBetween('created_at', [$from, $to])->count();

            $satim_count = Transaction::where('payable_type','App\Models\Payget')->where('type','withdraw')->whereBetween('created_at', [$from, $to])->count();
            $satim_sum =Transaction::where('payable_type','App\Models\Payget')->where('type','withdraw')->whereBetween('created_at', [$from, $to])->sum('amount');
            $pdf = Pdf::loadView('pdf.pdf3',[
            'etick'=>$etick,
            'vtick'=>$vtick,'vsum'=>$vsum,
            'flixy_count'=>$flixy->count(),
            'flixy_sum'=>$flixy->sum('amount'),
            'from'=>$from,'to'=>$to,
            'solde'=>$solde,
            'solde_count'=>$solde_count ,
            'cartvend'=>$cartvend,
            'satim_count'=>$satim_count,
            'satim_sum'=>$satim_sum,
            'etick_ext_app_in'=>$etick_ext_app_in,
            'etick_ext_cart_in'=>$etick_ext_cart_in,
            'etick_ext_app_out'=>$etick_ext_app_out,
            'etick_ext_cart_out'=>$etick_ext_cart_out,
            'etick_0'=>$etick_15,
            'etick_15'=>$etick_15,
            'etick_20'=>$etick_20,
            'etick_25'=>$etick_25,
            'etick_30'=>$etick_30,
            'etick_0_app'=>$etick_0_app,
            'etick_15_app'=>$etick_15_app,
            'etick_20_app'=>$etick_20_app,
            'etick_25_app'=>$etick_25_app,
            'etick_30_app'=>$etick_30_app,
            'etick_0_cart'=>$etick_0_cart,
            'etick_15_cart'=>$etick_15_cart,
            'etick_20_cart'=>$etick_20_cart,
            'etick_25_cart'=>$etick_25_cart,
            'etick_30_cart'=>$etick_30_cart,
            'vtick_15'=>$vtick_15,
            'vtick_20'=>$vtick_20,
            'vtick_25'=>$vtick_25,
            'vtick_30'=>$vtick_30

            ]);
            return $pdf->stream($from."-".$to.".pdf");
           }

        
           public function  stat3(Request $request)
           {
                $req = $request->validate([
                   'start_date' => 'required|date',
                   'end_date' => 'required|date',
                  ]);
                  $from= $req['start_date'];	
                  $to= $req['end_date'];
                  $en = config('etus.server.code');
                  $et = E_ticket::where('skey',$en)->whereBetween('created_at', [$from, $to])->groupBy('ligne_id')->get('ligne_id');


                 
                  $pdf = Pdf::loadView('pdf.pdf4',[
                   'et'=> $et,
                   'from'=> $from,
                   'to'=> $to,
                   'en'=> $en
                   ])->setPaper('a4');
                   return $pdf->stream($from."-".$to.".pdf");
                
            

        }

            
        public function  stat4(Request $request)
        {
             $req = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date',
               ]);
               $from= $req['start_date'];	
               $to= $req['end_date'];
               $en = config('etus.server.code');
               $et = V_ticket::where('skey',$en)->where('valid',true)->whereBetween('updated_at', [$from, $to])->groupBy('ligne_id')->get('ligne_id');
              
               $pdf = Pdf::loadView('pdf.pdf5',[
                'et'=> $et,
                'from'=> $from,
                'to'=> $to,
                'en'=> $en
                ])->setPaper('a4');
                return $pdf->stream($from."-".$to.".pdf");
             
         

     }
     public function  stat5(Request $request)
     {
          $req = $request->validate([
             'start_date' => 'required|date',
             'end_date' => 'required|date',
            ]);
            $from= $req['start_date'];	
            $to= $req['end_date'];
            $en = config('etus.server.code');
  $et = Cont::where('spcart_id' , '!=', null)->with(['spcart'])->whereRelation('spcart', 'type', '=', 'ELEV')->whereBetween('updated_at', [$from, $to])->where('bus_id', '!=', NULL);
  $ht = Cont::where('spcart_id' , '!=', null)->with(['spcart'])->whereRelation('spcart', 'type', '=', 'HAND')->whereBetween('updated_at', [$from, $to])->where('bus_id', '!=', NULL);
  $eh = Cont::where('spcart_id' , '!=', null)->whereBetween('updated_at', [$from, $to])->where('bus_id', '!=', NULL);
          
            $pdf = Pdf::loadView('pdf.pdf6',[
             'et'=> $et,
             'ht'=> $ht,
             'eh'=> $eh,
             'from'=> $from,
             'to'=> $to,
             'en'=> $en
             ])->setPaper('a4');
             return $pdf->stream($from."-".$to.".pdf");
          
     
  }

  public function  stat6(Request $request)
  {
    $req = $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date',
       ]);
       $from= $req['start_date'];	
       $to= $req['end_date'];
       $en = config('etus.server.code');
$et = Spcart::where('type' ,"ELEV")->whereBetween('updated_at', [$from, $to]);

     
       $pdf = Pdf::loadView('pdf.pdf7',[
        'et'=> $et->get(),
        
        'from'=> $from,
        'to'=> $to,
        'en'=> $en
        ])->setPaper('a4');
        return $pdf->stream($from."-".$to.".pdf");
     
   

}


}
