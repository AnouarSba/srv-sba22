<?php

namespace App\Http\Controllers;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Models\PV_ticket;
use App\Models\V_ticket;
use Illuminate\Http\Request;

class PVTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ui.v-tickets');
    }

    public function print($id)
    {
        $srvname=config('etus.server.name');
        $pak = PV_ticket::find($id);
        $bons = V_ticket::where('pak_id',$id)->get();
        $fpdf = new Fpdf('P','mm',array(70,30));
        $fpdf->SetFont('helvetica','',8);
          $fpdf->AddPage("P");
          $fpdf->SetFontSize(7);
          $fpdf->Ln(-5);
          $fpdf->Cell(0,3," NP:$id",0,0,'C');
          $fpdf->Ln(9);
          $fpdf->Cell(0,3,"",0,1,'C');
          $fpdf->Text(0,15,'--------------------------------------------------------------------------------');
          $fpdf->Ln(1);
          $fpdf->Cell(0,3," NP:$id",0,0,'C');
          $fpdf->QR($pak->token,2,32, 26);
          $fpdf->SetFontSize(12);
          $am = $pak->amount + 0 ;
          $fpdf->Ln(9);
          $fpdf->Cell(0,3,"$srvname",0,1,'C');
          $fpdf->Text(10,63,"$am DA");
        foreach($bons as $bon){
          $fpdf->AddPage("P");
          $fpdf->SetFontSize(7);
          $fpdf->Ln(-5);
          $fpdf->Cell(0,2,"T:$bon->id",0,1,'C');
          $fpdf->Ln(1);
          $fpdf->Cell(0,2,"P:$id",0,1,'C');
          $fpdf->Cell(0,3,"",0,1,'C');
          $fpdf->Text(0,15,'--------------------------------------------------------------------------------');
          $fpdf->Ln(3);
          $fpdf->SetFontSize(7);
          $fpdf->Cell(0,3,"T:$bon->id",0,1,'C');
          $fpdf->Ln(1);
          $fpdf->Cell(0,3,"P:$id",0,1,'C');
          $fpdf->SetFontSize(10);
          $fpdf->Ln(9);
          $fpdf->Cell(0,3,"$srvname",0,1,'C');
          
          $am = $bon->amount + 0 ;
          $fpdf->SetFontSize(15);
          $fpdf->Text(8,28,"$am DA");
          $fpdf->SetFontSize(4);
          $fpdf->QR($bon->token,2, 42, 26);
        }
       $fpdf->Output("NP$id.pdf",'I');
       exit;
    }
    public function printx($start,$end)
    {   
        
        $fpdf = new Fpdf();
        $fpdf->SetFont('helvetica','',10);
        $t1 = V_ticket::where('pak_id',1)->get();
        $t2 = V_ticket::where('pak_id',2)->get();
        $t3 = V_ticket::where('pak_id',3)->get();
        $t4 = V_ticket::where('pak_id',4)->get();
        $t5 = V_ticket::where('pak_id',5)->get();
        $t6 = V_ticket::where('pak_id',6)->get();
        
        for ($i=0; $i < $t1->count() ; $i+=5) { 

            $fpdf->AddPage("L", ['210', '297']);
            $fpdf->Image(storage_path("app/public/ticket/bg.png"), 0, 0, $fpdf->w, $fpdf->h);

            $fpdf->QR($t1[$i]->token,6, 21, 18);
            $fpdf->Text(7,16,$t1[$i]->pak_id);
            $fpdf->Text(7,46,$t1[$i]->id);

            $fpdf->QR($t2[$i]->token,55, 21, 18);
            $fpdf->Text(56,16,$t2[$i]->pak_id);
            $fpdf->Text(56,46,$t2[$i]->id);

            $fpdf->QR($t3[$i]->token,105, 21, 18);
            $fpdf->Text(106,16,$t3[$i]->pak_id);
            $fpdf->Text(106,46,$t3[$i]->id);

            $fpdf->QR($t4[$i]->token,154, 21, 18);
            $fpdf->Text(155,16,$t4[$i]->pak_id);
            $fpdf->Text(155,46,$t4[$i]->id);

            $fpdf->QR($t5[$i]->token,204, 21, 18);
            $fpdf->Text(205,16,$t5[$i]->id);
            $fpdf->Text(205,46,$t5[$i]->pak_id);

            $fpdf->QR($t6[$i]->token,254, 21, 18);
            $fpdf->Text(254,16,$t6[$i]->pak_id);
            $fpdf->Text(254,46,$t6[$i]->id);

            ////////////////////////////////////

                $fpdf->QR($t1[$i+1]->token,6, 61, 18);
                $fpdf->Text(7,56,$t1[$i+1]->pak_id);
                $fpdf->Text(7,86,$t1[$i+1]->id);
    
                $fpdf->QR($t2[$i+1]->token,55, 61, 18);
                $fpdf->Text(56,56,$t2[$i+1]->pak_id);
                $fpdf->Text(56,86,$t2[$i+1]->id);
    
                $fpdf->QR($t3[$i+1]->token,105, 61, 18);
                $fpdf->Text(106,56,$t3[$i+1]->pak_id);
                $fpdf->Text(106,86,$t3[$i+1]->id);
    
                $fpdf->QR($t4[$i+1]->token,154, 61, 18);
                $fpdf->Text(155,56,$t4[$i+1]->pak_id);
                $fpdf->Text(155,86,$t4[$i+1]->id);
    
                $fpdf->QR($t5[$i+1]->token,204, 61, 18);
                $fpdf->Text(205,56,$t5[$i+1]->pak_id);
                $fpdf->Text(205,86,$t5[$i+1]->id);
    
                $fpdf->QR($t6[$i+1]->token,254, 61, 18);
                $fpdf->Text(254,56,$t6[$i+1]->pak_id);
                $fpdf->Text(254,86,$t6[$i+1]->id);
            

                        ////////////////////////////////////
                      
                            $fpdf->QR($t1[$i+2]->token,6, 101, 18);
                            $fpdf->Text(7,96,$t1[$i+2]->pak_id);
                            $fpdf->Text(7,126,$t1[$i+2]->id);
                
                            $fpdf->QR($t2[$i+2]->token,55, 101, 18);
                            $fpdf->Text(56,96,$t2[$i+2]->pak_id);
                            $fpdf->Text(56,126,$t2[$i+2]->id);
                
                            $fpdf->QR($t3[$i+2]->token,105, 101, 18);
                            $fpdf->Text(106,96,$t3[$i+2]->pak_id);
                            $fpdf->Text(106,126,$t3[$i+2]->id);
                
                            $fpdf->QR($t4[$i+2]->token,154, 101, 18);
                            $fpdf->Text(155,96,$t4[$i+2]->pak_id);
                            $fpdf->Text(155,126,$t4[$i+2]->id);
                
                            $fpdf->QR($t5[$i+2]->token,204, 101, 18);
                            $fpdf->Text(205,96,$t5[$i+2]->pak_id);
                            $fpdf->Text(205,126,$t5[$i+2]->id);
                
                            $fpdf->QR($t6[$i+2]->token,254, 101, 18);
                            $fpdf->Text(254,96,$t6[$i+2]->pak_id);
                            $fpdf->Text(254,126,$t6[$i+2]->id);

                                                    ////////////////////////////////////
                      
                                                    $fpdf->QR($t1[$i+3]->token,6, 141, 18);
                                                    $fpdf->Text(7,136,$t1[$i+3]->pak_id);
                                                    $fpdf->Text(7,166,$t1[$i+3]->id);
                                        
                                                    $fpdf->QR($t2[$i+3]->token,55, 141, 18);
                                                    $fpdf->Text(56,136,$t2[$i+3]->pak_id);
                                                    $fpdf->Text(56,166,$t2[$i+3]->id);
                                        
                                                    $fpdf->QR($t3[$i+3]->token,105, 141, 18);
                                                    $fpdf->Text(106,136,$t3[$i+3]->pak_id);
                                                    $fpdf->Text(106,166,$t3[$i+3]->id);
                                        
                                                    $fpdf->QR($t4[$i+3]->token,154, 141, 18);
                                                    $fpdf->Text(155,136,$t4[$i+3]->pak_id);
                                                    $fpdf->Text(155,166,$t4[$i+3]->id);
                                        
                                                    $fpdf->QR($t5[$i+3]->token,204, 141, 18);
                                                    $fpdf->Text(205,136,$t5[$i+3]->pak_id);
                                                    $fpdf->Text(205,166,$t5[$i+3]->id);
                                        
                                                    $fpdf->QR($t6[$i+3]->token,254, 141, 18);
                                                    $fpdf->Text(254,136,$t6[$i+3]->pak_id);
                                                    $fpdf->Text(254,166,$t6[$i+3]->id);

                                                                                                        ////////////////////////////////////
                      
                                                                                                        $fpdf->QR($t1[$i+4]->token,6, 181, 18);
                                                                                                        $fpdf->Text(7,176,$t1[$i+4]->pak_id);
                                                                                                        $fpdf->Text(7,206,$t1[$i+4]->id);
                                                                                            
                                                                                                        $fpdf->QR($t2[$i+4]->token,55, 181, 18);
                                                                                                        $fpdf->Text(56,176,$t2[$i+4]->pak_id);
                                                                                                        $fpdf->Text(56,206,$t2[$i+4]->id);
                                                                                            
                                                                                                        $fpdf->QR($t3[$i+4]->token,105, 181, 18);
                                                                                                        $fpdf->Text(106,176,$t3[$i+4]->pak_id);
                                                                                                        $fpdf->Text(106,206,$t3[$i+4]->id);
                                                                                            
                                                                                                        $fpdf->QR($t4[$i+4]->token,154, 181, 18);
                                                                                                        $fpdf->Text(155,176,$t4[$i+4]->pak_id);
                                                                                                        $fpdf->Text(155,206,$t4[$i+4]->id);
                                                                                            
                                                                                                        $fpdf->QR($t5[$i+4]->token,204, 181, 18);
                                                                                                        $fpdf->Text(205,176,$t5[$i+4]->pak_id);
                                                                                                        $fpdf->Text(205,206,$t5[$i+4]->id);
                                                                                            
                                                                                                        $fpdf->QR($t6[$i+4]->token,254, 181, 18);
                                                                                                        $fpdf->Text(254,176,$t6[$i+4]->pak_id);
                                                                                                        $fpdf->Text(254,206,$t6[$i+4]->id);
                        

        }




        $fpdf->Output("IMP_".$start."_".$end."_.pdf",'I');
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
     * @param  \App\Models\PV_ticket  $pV_ticket
     * @return \Illuminate\Http\Response
     */
    public function show(PV_ticket $pV_ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PV_ticket  $pV_ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(PV_ticket $pV_ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PV_ticket  $pV_ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PV_ticket $pV_ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PV_ticket  $pV_ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(PV_ticket $pV_ticket)
    {
        //
    }
}
