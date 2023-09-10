<?php

namespace App\Http\Controllers;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Models\Cart;
use App\Models\Control;
use App\Models\Kabid;
use App\Models\P_Cart;
use App\Models\Vendeur;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PCartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ui.pcarts');
    }
    public function allcarts()
    {
        return view('ui.allcarts');
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
     * @param  \App\Models\P_Cart  $p_Cart
     * @return \Illuminate\Http\Response
     */
    public function show(P_Cart $p_Cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\P_Cart  $p_Cart
     * @return \Illuminate\Http\Response
     */
    public function edit(P_Cart $p_Cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\P_Cart  $p_Cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, P_Cart $p_Cart)
    {
        //
    }
    public function printauthcartco($id)
    {
      
        
      $pak = Control::find($id);
      
      $fpdf = new Fpdf();
      $fpdf->SetFont('helvetica','',10);
      $fpdf->AddPage("P", ['637', '1012']);
      $fpdf->Image(storage_path("app/public/authcart/controller.png"), 0, 0, $fpdf->GetPageWidth(), $fpdf->GetPageHeight());
      if ($pak->photo != NULL) {
        $fpdf->Image(storage_path("app/public/photos/$pak->photo"), 380, 350, 305, 296);
      }
      $fpdf->SetFontSize(140);
      $fpdf->Ln(550);
      $fpdf->Cell(0,70,"$pak->name",0,1,'C');
      $fpdf->SetFontSize(200);

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
      $fpdf->Text(23, 953, "$zero$pak->id");
      $fpdf->SetFontSize(80);
      $filename = 'new.png'; // the name of the file to save the QR code to
      $path = storage_path('app/public/qrcodes/' . $filename); // the path to the file
      QrCode::format('png')->size(800)->generate($pak->authtoken, $path);
    $fpdf->Image($path,  340, 720);
     // $fpdf->QR($pak->authtoken, 335,250, 220);
     
     $fpdf->Output("$pak->name.pdf",'I');
     exit;
      
    }
//vandeur cart print
    public function printauthcartva($id)
    {
      
        
      $pak = Vendeur::find($id);
      
      $fpdf = new Fpdf();
      $fpdf->SetFont('helvetica','',10);
      $fpdf->AddPage("P", ['637', '1012']);
      $fpdf->Image(storage_path("app/public/authcart/receveur.png"), 0, 0, $fpdf->GetPageWidth(), $fpdf->GetPageHeight());
      if ($pak->photo != NULL) {
        $fpdf->Image(storage_path("app/public/photos/$pak->photo"), 170, 195, 305, 296);
      }
      $fpdf->SetFontSize(140);
      $fpdf->Ln(550);
      $fpdf->Cell(0,70,"$pak->name",0,1,'C');
      $fpdf->SetFontSize(200);

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
      $fpdf->Text(23, 953, "$zero$pak->id");
      $fpdf->SetFontSize(80);
     // $fpdf->QR($pak->authtoken, 335,250, 220);
     $filename = 'new.png'; // the name of the file to save the QR code to
     $path = storage_path('app/public/qrcodes/' . $filename); // the path to the file
     QrCode::format('png')->size(335)->generate($pak->authtoken, $path);
    $fpdf->Image($path,  250, 220);
     $fpdf->Output("$pak->name.pdf",'I');
     exit;
      
    }

    public function printauthcart($id)
    {
        
      $pak = Kabid::find($id);
      
      $fpdf = new Fpdf();
      $fpdf->SetFont('helvetica','',10);
      $fpdf->AddPage("P", ['637', '1012']);
      $fpdf->Image(storage_path("app/public/authcart/receveur.png"), 0, 0, $fpdf->GetPageWidth(), $fpdf->GetPageHeight());
      if ($pak->photo != NULL) {
        $fpdf->Image(storage_path("app/public/photos/$pak->photo"), 170, 195, 305, 296);
      }
      $fpdf->SetFontSize(140);
      $fpdf->Ln(550);
      $fpdf->Cell(0,70,"$pak->name",0,1,'C');
      $fpdf->SetFontSize(200);

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
      $fpdf->Text(23, 953, "$zero$pak->id");
      $fpdf->SetFontSize(80);
     // $fpdf->QR($pak->authtoken, 335,250, 220);
     $filename = 'new.png'; // the name of the file to save the QR code to
     $path = storage_path('app/public/qrcodes/' . $filename); // the path to the file
     QrCode::format('png')->size(800)->generate($pak->authtoken, $path);
    $fpdf->Image($path,  340, 720);
     $fpdf->Output("$pak->name.pdf",'I');
     exit;
      
    }
    
    public function printcart($id)
    {
        $srvname=config('etus.server.name');
        
        switch ($id) {
            case ($id <= 9):
              $zero = '00000000';
              break;
            case ($id <= 99):
              $zero = '0000000';
              break;
            case ($id <= 999):
              $zero = '000000';
              break;
            case ($id <= 9999):
                $zero = '00000';
                break;
            case ($id <= 99999):
                $zero = '0000';
                break;
            case ($id <= 999999):
            $zero = '000';
            break;
            case ($id <= 9999999):
              $zero = '00';
              break;
              case ($id <= 99999999):
              $zero = '0';
              break;
              case ($id <= 999999999):
                $zero = '';
                break;
           }
        $pak = P_Cart::find($id);
        $bons = Cart::where('pc_id',$id)->get();
        $imagename = $pak ->image;
        $fpdf = new Fpdf();
        $fpdf->SetFont('helvetica','',10);
        $fpdf->AddPage("L", ['1012', '637']);
        
        $fpdf->SetFontSize(80);
        $fpdf->Cell(0,100,"",0,1,'C');
        $fpdf->Cell(0,10,"Paquet de cartes N:$zero$id",0,1,'C');
        $fpdf->SetFontSize(80);
        $filename = 'new.png'; // the name of the file to save the QR code to
        $path = storage_path('app/public/qrcodes/' . $filename); // the path to the file
        QrCode::format('png')->size(1280)->generate($pak->token, $path);
       $fpdf->Image($path,  335, 161);
      //  $fpdf->QR($pak->token, 80,161, 350);
     // 330,161, 350
        foreach($bons as $bon){

              foreach($bons as $bon){
                switch ($bon->id) {
                  case ($bon->id <= 9):
                    $zer = '00000000';
                    break;
                  case ($bon->id <= 99):
                    $zer = '0000000';
                    break;
                  case ($bon->id <= 999):
                    $zer = '000000';
                    break;
                  case ($bon->id <= 9999):
                      $zer = '00000';
                      break;
                  case ($bon->id <= 99999):
                      $zer = '0000';
                      break;
                  case ($bon->id <= 999999):
                  $zer = '000';
                  break;
                  case ($bon->id <= 9999999):
                    $zer = '00';
                    break;
                    case ($bon->id <= 99999999):
                    $zer = '0';
                    break;
                    case ($bon->id <= 999999999):
                      $zer = '';
                      break;
              }
            $fpdf->AddPage("L", ['1012', '637']);
            $fpdf->Image(storage_path("app/public/carts/$imagename"), 0, 0, $fpdf->GetPageWidth(), $fpdf->GetPageHeight());
            $fpdf->SetFontSize(80);
            $fpdf->Text(315, 560, "N:$zer$bon->id / P:$zero$id");
            $fpdf->SetFontSize(80);
            $filename = 'new.png'; // the name of the file to save the QR code to
            $path = storage_path('app/public/qrcodes/' . $filename); // the path to the file
            QrCode::format('png')->size(80)->generate($pak->token, $path);
           $fpdf->Image($path,  335, 161);
          //  $fpdf->QR($bon->token, 80,161, 350);
        }
       $fpdf->Output("NP$id.pdf",'I');
       exit;
        }
     }

}
