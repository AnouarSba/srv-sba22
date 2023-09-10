<?php

namespace App\Http\Controllers;

use App\Models\Abtype;
use App\Models\Cart;
use APP\Models\E_ticket;
use App\Models\Spcart;
use Codedge\Fpdf\Fpdf\Fpdf ;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function carttrans($id)
    {
        return view('ui.carttrans',['cart'=>$id]);
    }

    public function spcarttrans($id)
    {
        return view('ui.spcarttrans',['cart'=>$id]);
    }

    public function cartticket($id)
    {
       
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
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        //
    }

    public function printspcart($id)
    {
      
        
      $pak = Spcart::find($id);
      
      $fpdf = new Fpdf;
      $fpdf->SetFont('helvetica','',10);
      $fpdf->AddPage("L", ['1012', '637']);
      $typee = Abtype::where('code',$pak->type)->first();
      $en = config('etus.server.code');
      if ($en == 'e22') {
        $new=1;
        if ($new) {
          $fpdf->Image(storage_path("app/public/spcarts/$typee->photos"), 0, 0, $fpdf->GetPageWidth(), $fpdf->GetPageHeight());
          if ($pak->photo != NULL) {
            $fpdf->Image(storage_path("app/public/scphotos/$pak->photo"), 29, 80, 200, 220);
          }
          $fpdf->SetFontSize(65);
    
      $year = Spcart::find($id)->created_at->year;
          $cmpt = Spcart::where('id', '<=', $id)->where('type' , $pak->type)->whereYear('created_at', '=', $year)->count();
          if ($year == 2023) {
            $cmpt-=1;
          }
          switch ($cmpt) {
            case ($cmpt <= 9):
              $zero = '00';
              break;
            case ($id <= 99):
              $zero = '0';
              break;
            default;
              $zero = '';
              break;
           }
           $slash= ' / ';
           $fpdf->Text(75, 605, "$zero$cmpt$slash$year");
           $fpdf->SetFontSize(100);
           $fpdf->Text(545, 358, "$pak->date_nes");

           if ($pak->type == 'HAND') {
            $fpdf->Text(700-$fpdf->GetStringWidth($pak->name), 285, "$pak->name");
      
            
            $num = $pak->card_number;
            $prcnt = $pak->hand_prcnt;
            if ($prcnt == 100) {
             $filename = 'yes.png'; // the name of the file to get yes on arabic
           $path = storage_path('app/public/qrcodes/' . $filename); // the path to the file
          $fpdf->Image($path, 700, 475, 60, 60);
            } else {
             $filename = 'no.png'; // the name of the file to get no on arabic
             $path = storage_path('app/public/qrcodes/' . $filename); // the path to the file
            $fpdf->Image($path, 730, 475, 35, 35);
            }
            $fpdf->Text(695, 432, "$prcnt");
              $fpdf->Text(700-$fpdf->GetStringWidth($num), 586, "$num");
    
           } else {
          $fpdf->Text(710-$fpdf->GetStringWidth($pak->name), 285, "$pak->name");
          $fpdf->Text(795-$fpdf->GetStringWidth($pak->father), 510, "$pak->father");
            $fpdf->Text(720-$fpdf->GetStringWidth($pak->phone), 590, "$pak->phone");
            
           }
           
      $fpdf->SetFontSize(80);
          
    
    
          $filename = 'new.png'; // the name of the file to save the QR code to
          $path = storage_path('app/public/qrcodes/' . $filename); // the path to the file
          QrCode::format('png')->size(758)->generate($pak->token, $path);
         $fpdf->Image($path, 29, 370, 200, 200);
         $fpdf->Output("$pak->name.pdf",'I');
         exit;
         
        } else {
          # code...
        }
        
      
      } else {
        
      $fpdf->Image(storage_path("app/public/spcarts/$typee->photos"), 0, 0, $fpdf->GetPageWidth(), $fpdf->GetPageHeight());
      if ($pak->photo != NULL) {
        $fpdf->Image(storage_path("app/public/scphotos/$pak->photo"), 29, 20, 300, 310);
      }
      $fpdf->SetFontSize(140);
      $fpdf->Text(450, 140, "$pak->name");
     
      switch ($pak->id) {
        case ($pak->id <= 9):
          $zero = '00';
          break;
        case ($id <= 99):
          $zero = '0';
          break;
        default;
          $zero = '';
          break;
       }
      $fpdf->Text(450, 212, "$zero$pak->id");
      $fpdf->Text(450, 280, "$pak->date_nes");
      $fpdf->SetFontSize(80);
      $fpdf->QR($pak->token, 758,388, 220);
      
      $fpdf->Output("$pak->name.pdf",'I');
      exit;
      }
      
    }
}
