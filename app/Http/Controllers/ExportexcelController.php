<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Client;
use App\Models\Flixy;
use App\Models\Vent;
class ExportexcelController extends Controller
{
    public function ExportExcel($customer_data, $customer_data2, $customer_data3){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');
        try {
            $spreadSheet = new Spreadsheet();
            $spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
            $spreadSheet->getActiveSheet()->fromArray($customer_data);
        $spreadSheet->getActiveSheet()->setTitle('Client');
            $spreadSheet->createSheet();
            
        /* Add some data */
        $spreadSheet->setActiveSheetIndex(1);
        $spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
        $spreadSheet->getActiveSheet()->fromArray($customer_data2);
        
        $spreadSheet->getActiveSheet()->setTitle('Receveur');
        $spreadSheet->createSheet();
            
        /* Add some data */
        $spreadSheet->setActiveSheetIndex(2);
        $spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
        $spreadSheet->getActiveSheet()->fromArray($customer_data3);
        
        $spreadSheet->getActiveSheet()->setTitle('Controlleur');
            $Excel_writer = new Xls($spreadSheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Customer_ExportedData.xls"');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            $Excel_writer->save('php://output');
            exit();
        } catch (Exception $e) {
            return;
        }
    }
    /**
     *This function loads the customer data from the database then converts it
     * into an Array that will be exported to Excel
     */
    function exportData(Request $request){
        $req = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
           ]);
           $from= $req['start_date'];	
           $to= $req['end_date'];
        $data = Flixy::query()
        ->with(["client:id,name,phone,email"])->where('client_type','!=','App\Models\Spcart')->whereBetween('created_at', [$from, $to])->select("client_id", DB::raw("sum(amount) as flexy"))
        ->groupBy('client_id')->orderBy('flexy', 'DESC')
        ->get()->take(10);
        $data_array [] = array("Num","Name","Flexy","Phone","E-mail");
        $i = 0;
        foreach($data as $data_item)
        { $i++;
            $data_array[] = array(
                'Num' =>$i,
                'Name' => $data_item->client['name'],
                'Flexy' => $data_item->flexy,
                'Phone' => $data_item->client['phone'],
                'E-mail' => $data_item->client['email']
            );
        }
        $data = Flixy::query()
        ->join('kabids', 'kabids.id', '=', 'flixies.flixy_id')->where('flixy_type','App\Models\Kabid')->whereBetween('flixies.created_at', [$from, $to])->select("flixy_id", "name", DB::raw("sum(amount) as flexy"))
        ->groupBy('flixy_id')->orderBy('flexy', 'DESC')
        ->get();
    
        $data_array2 [] = array("Num","Name","Flexy");
        $i = 0;
        foreach($data as $data_item)
        { $i++;
            $data_array2[] = array(
                'Num' =>$i,
                'Name' => $data_item->name,
                'Flexy' => $data_item->flexy
            );
        }
        $data = Vent::query()
        ->join('controls', 'controls.id', '=', 'vents.c_id')->where('c_type','App\Models\Control')
        ->whereBetween('vents.created_at', [$from, $to])
        ->select("c_id", "name", DB::raw("count(vents.id) as cmpt"))
        ->groupBy('c_id')->orderBy('cmpt', 'DESC')
        ->get();
        $data_array3 [] = array("Num","Name","Count");
        $i = 0;
        foreach($data as $data_item)
        { $i++;
            $data_array3[] = array(
                'Num' =>$i,
                'Name' => $data_item->name,
                'Count' => $data_item->cmpt
            );
        }
        $this->ExportExcel($data_array,$data_array2,$data_array3);
    }
}
