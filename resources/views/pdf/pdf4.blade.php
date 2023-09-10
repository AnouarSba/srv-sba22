<!DOCTYPE html>
<html>
<head>
 <title>PDF</title>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
 <style>
                @page {
                margin: 20px 30px;
            }
            .page-break {
             page-break-after: always;
            }
 </style>
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>
<body style="font-family:Arial" >
 

 <div class="row">
    <h2> Date :{{Carbon\Carbon::now()->toDateTimeString()}}</h2>
    <h2 style="color: rgb(4, 7, 34)" >{{config('etus.server.name')}}</h2>
    <h5 style="color: rgb(4, 7, 34)" > rapport de : {{$from}} a: {{$to}}</h5>
    <h5 style="color: rgb(4, 7, 34)" > rapport  par receveur</h5>
    <table class="table table-bordered table-striped table-sm">
                                
                             <thead>

                            <th>receveur</th>
                            <th colspan="2">0 dz</th>
                            <th colspan="2">15 dz</th>
                            <th colspan="2">20 dz</th>
                            <th colspan="2">25 dz</th>
                            <th colspan="2">30 dz</th>
                            <th colspan="2">total</th>
                            <th>bus</th>
                            <th>ligne</th>
                            
                             </thead>
                  
                   @foreach ($et as $lig)
                          {{
                            $bus=  App\Models\E_ticket::where('skey',$en)->where('ligne_id',$lig['ligne_id'])->whereBetween('created_at', [$from, $to])->groupBy('bus_id')->get('bus_id');
                          }}
                        @foreach ($bus as $bu)

                           @php
                          $forn =  App\Models\E_ticket::whereBetween('created_at', [$from, $to])->where('skey',$en)->where('ligne_id',$lig['ligne_id'])->where('bus_id',$bu['bus_id'])->groupBy('Forn_id')->get('Forn_id');
                           @endphp
                           @foreach ($forn as $fo)
                                @php
                                  $tikk =  App\Models\E_ticket::whereBetween('created_at', [$from, $to])->where('skey',$en)->where('ligne_id',$lig['ligne_id'])->where('bus_id',$bu['bus_id'])->where('Forn_id',$fo['Forn_id'])->get();
                                  $color = substr(App\Models\Ligne::find($lig['ligne_id'])->color, 3, 6);
                                @endphp
                                   <tr>
                                    <td>
                                        {{App\Models\Kabid::find($fo['Forn_id'])->name;}}
                                    </td>
                                    <td>
                                        {{$tikk->where('amount',0)->count()}}
                                        </td>
                                        <td>
                                        {{$tikk->where('amount',0)->sum('amount')}}
                                        </td>
                                    <td>
                                    {{$tikk->where('amount',15)->count()}}
                                    </td>
                                    <td>
                                    {{$tikk->where('amount',15)->sum('amount')}}
                                    </td>
                                    <td>
                                        {{$tikk->where('amount',20)->count()}}
                                        </td>
                                        <td>
                                        {{$tikk->where('amount',20)->sum('amount')}}
                                        </td>
                                        <td>
                                            {{$tikk->where('amount',25)->count()}}
                                        </td>
                                         <td>
                                            {{$tikk->where('amount',25)->sum('amount')}}
                                        </td>
                                    <td>
                                    {{$tikk->where('amount',30)->count()}}
                                    </td>
                                    <td>
                                     {{$tikk->where('amount',30)->sum('amount')}}
                                    </td>

                                        <td>
                                        {{$tikk->count()}}
                                        </td>
                                        <td>
                                         {{$tikk->sum('amount')}}
                                        </td>


                                    <td style="background-color:#{{substr(App\Models\Ligne::find($lig['ligne_id'])->color, 2, 3)}}{{App\Models\Bus::find($bu['bus_id'])->name}}">
                                        {{App\Models\Bus::find($bu['bus_id'])->name;}}
                                    </td>
                                    <td style="background-color:#{{$color}}">
                                        {{App\Models\Ligne::find($lig['ligne_id'])->name;}}
                                    </td>

                               
                              
                            @endforeach 


                        @endforeach  
                        
                     @endforeach

                    </tr>
                    <tfoot>
                 {{ $tikk1 =  App\Models\E_ticket::whereBetween('created_at', [$from, $to])->where('skey',$en)->get();}}
                        <th>Total</th>
                        <th >{{$tikk1->where('amount',0)->count()}}</th>
                        <th >{{$tikk1->where('amount',0)->sum('amount')}}</th>
                        <th >{{$tikk1->where('amount',15)->count()}}</th>
                        <th >{{$tikk1->where('amount',15)->sum('amount')}}</th>
                        <th >{{$tikk1->where('amount',20)->count()}}</th>
                        <th >{{$tikk1->where('amount',20)->sum('amount')}}</th>
                        <th >{{$tikk1->where('amount',25)->count()}}</th>
                        <th >{{$tikk1->where('amount',25)->sum('amount')}}</th>
                        <th>{{$tikk1->where('amount',30)->count()}}</th>
                        <th >{{$tikk1->where('amount',30)->sum('amount')}}</th>
                        <th >{{$tikk1->count()}}</th>
                        <th >{{$tikk1->sum('amount')}}</th>
                        <th></th>
                        <th></th>
                        
                  </tfoot>
    </table>
 
</div>
<div class="page-break"></div>
<div class="row">
    <h5 style="color: rgb(4, 7, 34)" > rapport par bus </h5>
    <table class="table table-bordered table-striped table-sm">
        <thead>
            <th>Bus</th>
            <th colspan="2">0 dz</th>
            <th colspan="2">15 dz</th>
            <th colspan="2">20 dz</th>
            <th colspan="2">25 dz</th>
            <th colspan="2">30 dz</th>
            <th colspan="2">total</th>
        </thead>
        {{$bus=  App\Models\E_ticket::where('skey',$en)->whereBetween('created_at', [$from, $to])->groupBy('bus_id')->get()}}
        <tbody>
           @foreach ($bus as $buss)
           @php
           $buss1=  App\Models\E_ticket::where('skey',$en)->whereBetween('created_at', [$from, $to])->where('bus_id',$buss['bus_id'])->get();
           @endphp
             <tr>
             <td>{{App\Models\Bus::find($buss['bus_id'])->name;}}</td>
             <td >{{$buss1->where('amount',0)->count()}}</td>
             <td >{{$buss1->where('amount',0)->sum('amount')}}</td>
             <td >{{$buss1->where('amount',15)->count()}}</td>
             <td >{{$buss1->where('amount',15)->sum('amount')}}</td>
             <td >{{$buss1->where('amount',20)->count()}}</td>
             <td >{{$buss1->where('amount',20)->sum('amount')}}</td>
             <td >{{$buss1->where('amount',25)->count()}}</td>
             <td >{{$buss1->where('amount',25)->sum('amount')}}</td>
             <td>{{$buss1->where('amount',30)->count()}}</td>
             <td >{{$buss1->where('amount',30)->sum('amount')}}</td>
             <td >{{$buss1->count()}}</td>
             <td >{{$buss1->sum('amount')}}</td>
            </tr>
           
           @endforeach
        </tbody>
        <tfoot>
                   <th>Total</th>
                   <th >{{$tikk1->where('amount',0)->count()}}</th>
                   <th >{{$tikk1->where('amount',0)->sum('amount')}}</th>
                   <th >{{$tikk1->where('amount',15)->count()}}</th>
                   <th >{{$tikk1->where('amount',15)->sum('amount')}}</th>
                   <th >{{$tikk1->where('amount',20)->count()}}</th>
                   <th >{{$tikk1->where('amount',20)->sum('amount')}}</th>
                   <th >{{$tikk1->where('amount',25)->count()}}</th>
                   <th >{{$tikk1->where('amount',25)->sum('amount')}}</th>
                   <th>{{$tikk1->where('amount',30)->count()}}</th>
                   <th >{{$tikk1->where('amount',30)->sum('amount')}}</th>
                   <th >{{$tikk1->count()}}</th>
                   <th >{{$tikk1->sum('amount')}}</th>                   
             </tfoot>
    </table> 
</div>

<div class="page-break"></div>
<div class="row">
    <h5 style="color: rgb(4, 7, 34)" > rapport par Ligne </h5>
    <table class="table table-bordered table-striped table-sm">
        <thead>
            <th>Ligne</th>
            <th colspan="2">0 dz</th>
            <th colspan="2">15 dz</th>
            <th colspan="2">20 dz</th>
            <th colspan="2">25 dz</th>
            <th colspan="2">30 dz</th>
            <th colspan="2">total</th>
        </thead>
        {{$bus=  App\Models\E_ticket::where('skey',$en)->whereBetween('created_at', [$from, $to])->groupBy('ligne_id')->get()}}
        <tbody>
           @foreach ($bus as $buss)
           @php
           $buss1=  App\Models\E_ticket::where('skey',$en)->whereBetween('created_at', [$from, $to])->where('ligne_id',$buss['ligne_id'])->get();
           @endphp
             <tr>
             <td>{{App\Models\Ligne::find($buss['ligne_id'])->name;}}</td>
             <td >{{$buss1->where('amount',0)->count()}}</td>
             <td >{{$buss1->where('amount',0)->sum('amount')}}</td>
             <td >{{$buss1->where('amount',15)->count()}}</td>
             <td >{{$buss1->where('amount',15)->sum('amount')}}</td>
             <td >{{$buss1->where('amount',20)->count()}}</td>
             <td >{{$buss1->where('amount',20)->sum('amount')}}</td>
             <td >{{$buss1->where('amount',25)->count()}}</td>
             <td >{{$buss1->where('amount',25)->sum('amount')}}</td>
             <td>{{$buss1->where('amount',30)->count()}}</td>
             <td >{{$buss1->where('amount',30)->sum('amount')}}</td>
             <td >{{$buss1->count()}}</td>
             <td >{{$buss1->sum('amount')}}</td>
            </tr>
           
           @endforeach
        </tbody>
        <tfoot>
                   <th>Total</th>
                   <th >{{$tikk1->where('amount',0)->count()}}</th>
                   <th >{{$tikk1->where('amount',0)->sum('amount')}}</th>
                   <th >{{$tikk1->where('amount',15)->count()}}</th>
                   <th >{{$tikk1->where('amount',15)->sum('amount')}}</th>
                   <th >{{$tikk1->where('amount',20)->count()}}</th>
                   <th >{{$tikk1->where('amount',20)->sum('amount')}}</th>
                   <th >{{$tikk1->where('amount',25)->count()}}</th>
                   <th >{{$tikk1->where('amount',25)->sum('amount')}}</th>
                   <th>{{$tikk1->where('amount',30)->count()}}</th>
                   <th >{{$tikk1->where('amount',30)->sum('amount')}}</th>
                   <th >{{$tikk1->count()}}</th>
                   <th >{{$tikk1->sum('amount')}}</th>                   
             </tfoot>
    </table> 
</div>
</body>

</html>