<?php

namespace App\Http\Controllers;

use App\Models\Ps_cart;
use App\Models\Sold_cart;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;

class PsCartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ui.soldecarts');
    }
    public function print($id)
    {
        $srvname=config('etus.server.name');
        $pak = Ps_cart::find($id);
        $bons = Sold_cart::where('ps_id',$id)->get();
        $fpdf = new Fpdf('P','mm',array(100,40));
        $fpdf->SetFont('helvetica','',10);
          $fpdf->AddPage("P");
          $fpdf->SetFontSize(7);
          $fpdf->Cell(0,4," NP:$id",1,1,'C');
          $fpdf->Cell(0,4,"",0,1,'C');
          $fpdf->Cell(0,5,'--------------------------------------------------------------------------------',0,1,'C');
          $fpdf->Cell(0,4," NP:$id",1,1,'C');
          $fpdf->SetFontSize(15);
          $fpdf->Cell(0,10,$srvname,0,1,'C');
          $fpdf->SetFontSize(10);
          $fpdf->Cell(0,5,"Carte De Recharge",0,1,'C');
          $fpdf->QR($pak->token, 5,50, 30);
        foreach($bons as $bon){
          $fpdf->AddPage("P");
          if ($bon->valid) {
            $fpdf->SetFontSize(7);
            $fpdf->Cell(0,5,"NC:$bon->id/ NP:$id",0,1,'C');
            $fpdf->Cell(0,10,"",0,1,'C');
            $fpdf->Cell(0,10,'--------------------------------------------------------------------------------',0,1,'C');
            $fpdf->Cell(0,5,"NC:$bon->id/ NP:$id",0,1,'C');
            $fpdf->SetFontSize(15);
            $fpdf->Cell(0,8,$srvname,0,1,'C');
            $fpdf->SetFontSize(10);
            $fpdf->Cell(0,5,"Carte De Recharge",0,1,'C');
            $am = $bon->sold + 0 ;
            $fpdf->SetFontSize(15);
            $fpdf->Cell(0,5,"$am DA",0,1,'C');
            $fpdf->SetFontSize(5);
            $fpdf->Text(6,95,"scanner l'image avec votre telephone");
            $fpdf->QR($bon->token,5, 60, 30);
          } else {
            $fpdf->SetFontSize(7);
            $fpdf->Cell(0,5,"NC:$bon->id/ NP:$id",0,1,'C');
            $fpdf->Cell(0,10,"",0,1,'C');
            $fpdf->Cell(0,10,'--------------------------------------------------------------------------------',0,1,'C');
            $fpdf->Cell(0,5,"NC:$bon->id/ NP:$id",0,1,'C');
            $fpdf->SetFontSize(12);
            $fpdf->Cell(0,10,"Carte Non Valid",0,1,'C');
            $fpdf->QR($bon->uuid,10, 70, 20);
            $fpdf->SetFontSize(10);
            $fpdf->Text(3,50,$bon->client_type);
            $fpdf->Text(3,55,$bon->client_id);
            $fpdf->Text(3,60,$bon->skey);
          }
          

        }
       $fpdf->Output("NP$id.pdf",'I');
       exit;
    }
    public function printx($start,$end)
    {   
        
        $srvname=config('etus.server.name');
        $paknum=$end - $start +1;
        $fpdf = new Fpdf('P','mm',array(100,40));
        $fpdf->SetFont('helvetica','',10);
        for ($i=0; $i < $paknum ; $i++) { 
            $pak = Ps_cart::find($start+$i);
            $fpdf->AddPage("P");
            $fpdf->SetFontSize(7);
            $fpdf->Cell(0,4," NP:$pak->id",1,1,'C');
            $fpdf->Cell(0,4,"",0,1,'C');
            $fpdf->Cell(0,5,'--------------------------------------------------------------------------------',0,1,'C');
            $fpdf->Cell(0,4," NP:$pak->id",1,1,'C');
            $fpdf->SetFontSize(15);
            $fpdf->Cell(0,8,$srvname,0,1,'C');
            $fpdf->SetFontSize(10);
            $fpdf->Cell(0,5,"Carte De Recharge",0,1,'C');
            $fpdf->QR($pak->token, 5, 60, 30);
        }
        $ptnum = $pak->end - $pak->start +1;
        for ($j=0; $j < $ptnum ; $j++) { 
           for ($i=0; $i < $paknum ; $i++) { 
            $vtk = Sold_cart::where('ps_id',$start+$i)->skip($j)->take(1)->first();
            $fpdf->AddPage("P");
            $fpdf->SetFontSize(7);
            $fpdf->Cell(0,5,"NC:$vtk->id/ NP:$vtk->ps_id",0,1,'C');
            $fpdf->Cell(0,10,"",0,1,'C');
            $fpdf->Cell(0,10,'--------------------------------------------------------------------------------',0,1,'C');
            $fpdf->Cell(0,5,"NC:$vtk->id/ NP:$vtk->ps_id",0,1,'C');
            $fpdf->SetFontSize(15);
            $fpdf->Cell(0,8,$srvname,0,1,'C');
            $fpdf->SetFontSize(10);
            $fpdf->Cell(0,5,"Carte De Recharge",0,1,'C');
            $am = $vtk->sold + 0 ;
            $fpdf->SetFontSize(15);
            $fpdf->Cell(0,5,"$am DA",0,1,'C');
            $fpdf->SetFontSize(5);
            $fpdf->Text(6,95,"scanner l'image avec votre telephone");
            $fpdf->QR($vtk->token,5, 60, 30);
           }
        }

        $fpdf->Output("NP0000$paknum.pdf",'I');
        exit;
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
     * @param  \App\Models\Ps_cart  $ps_cart
     * @return \Illuminate\Http\Response
     */
    public function show(Ps_cart $ps_cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ps_cart  $ps_cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Ps_cart $ps_cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ps_cart  $ps_cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ps_cart $ps_cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ps_cart  $ps_cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ps_cart $ps_cart)
    {
        //
    }
}
