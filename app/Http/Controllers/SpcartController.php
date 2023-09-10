<?php

namespace App\Http\Controllers;


use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use App\Models\Spcart;

class SpcartController extends Controller
{
   
    public function index()
    {
        return view('ui.spcarts');
    }
    public function generatePdf()
{
 // Create a new instance of PhpWord
        
        $phpWord = new PhpWord();
        
        // Add a new section to the document
        $section = $phpWord->addSection();
        
        // Add a table to the section
        $table = $section->addTable();
        
        // Add a row for the table headers
        $table->addRow();
        $table->addCell(Converter::cmToTwip(3))->addText('رقم بطاقة الاشتراك');
        $table->addCell(Converter::cmToTwip(3))->addText('طبيعة ونسبة الاعاقة');
        $table->addCell(Converter::cmToTwip(3))->addText('رقم بطاقة المعوق');
        $table->addCell(Converter::cmToTwip(5))->addText('الاسم و اللقب');
        $table->addCell(Converter::cmToTwip(2))->addText('الرقم');
        
        // Fetch the data from your Laravel model
        $data = Spcart::where('type','HAND')->where('id', '>', 2)->get();
        
        // Loop through the data and add a row for each item
        foreach ($data as $key => $item) {
            $table->addRow();
            $table->addCell(Converter::cmToTwip(3))->addText($key +1);
            $table->addCell(Converter::cmToTwip(3))->addText($item->hand_prcnt.' '.$item->hand_nature);
            $table->addCell(Converter::cmToTwip(3))->addText($item->card_number);
            $table->addCell(Converter::cmToTwip(5))->addText($item->name);
            $table->addCell(Converter::cmToTwip(2))->addText($key +1);
        }
        
       
        // Generate the Word document
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save(storage_path('app/public/document.docx'));
        
        // Return a download response to the user
        return response()->download(storage_path('app/public/document.docx'))->deleteFileAfterSend();
    }





}
