<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Client;
use App\Models\E_ticket;
use App\Models\V_ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Asantibanez\LivewireCharts\Models\RadarChartModel;
use Asantibanez\LivewireCharts\Models\TreeMapChartModel;
use Bavix\Wallet\Models\Transaction;

class ShowPosts extends Component
{

    public function render()
    {

        $cartdepost = Transaction::where('type','deposit')->where('payable_type',Cart::class)->where('confirmed',true)->whereNotNull('meta')->sum('amount');
        $cartw = Transaction::where('type','withdraw')->where('payable_type',Cart::class)->where('confirmed',true)->whereNotNull('meta')->sum('amount');
        $appdepost = Transaction::where('type','deposit')->where('payable_type',Client::class)->where('confirmed',true)->sum('amount');
        $appw = Transaction::where('type','withdraw')->where('payable_type',Client::class)->where('confirmed',true)->sum('amount');
        
        $columnChartModel = 
        LivewireCharts::columnChartModel()
        ->setLegendVisibility(true)
        ->setColumnWidth(30)
        ->withGrid()
        ->setTitle(' (DZD)  احصائيات البيع')
        ->addColumn('شحن البطاقات', $cartdepost, '#A0F655',)
        ->addColumn('استعمال البطاقة', $cartw * -1, '#FC81F4')   
        ->addColumn('شحن التطبيق', $appdepost, '#fc8181')
        ->addColumn('استعمال التطبيق', $appw * -1, '#90cdf4')
     ;
     $pieChartModel = 
     LivewireCharts::pieChartModel()
     ->setLegendVisibility(true)
     ->withGrid()
     ->setType('donut')
     ->withOnSliceClickEvent('onSliceClick')
     //->withoutLegend()
     ->legendPositionBottom()
     ->legendHorizontallyAlignedCenter()
     ->setTitle('نسبة استعمال الدفع الالكتروني')
     ->addSlice('دفع الكتروني',E_ticket::count(), '#A0F655')
     ->addSlice('دفع عادي', V_ticket::where('valid',true)->count(), '#fc8181')
  ;
        return view('livewire.show-posts')->with([
            'columnChartModel' => $columnChartModel,
            'pieChartModel' => $pieChartModel,
        ]);;
    }
}
