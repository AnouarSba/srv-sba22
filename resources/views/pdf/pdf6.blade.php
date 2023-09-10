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
                            <th >HAND</th>
                            <th >ELEV</th>
                            <th >total</th>
                            <th>bus</th>
                            <th>ligne</th>
                            
                             </thead>
                  
                  
                           @php
                           
                          $forn =  App\Models\Cont::where('spcart_id' , '!=', null)->whereBetween('updated_at', [$from, $to])->where('bus_id', '!=', NULL)->groupBy('c_id')->get();
                           @endphp
                           @foreach ($forn as $fo)
                                @php
                                  $tikk =  App\Models\Cont::where('spcart_id' , '!=', null)->with(['spcart'])->whereBetween('updated_at', [$from, $to])->where('bus_id', '!=', NULL)->where('c_id',$fo['c_id']);
                                  $tikk2 =  App\Models\Cont::where('spcart_id' , '!=', null)->with(['spcart'])->whereBetween('updated_at', [$from, $to])->where('bus_id', '!=', NULL)->where('c_id',$fo['c_id']);
                                @endphp
                                   <tr>
                                    <td>
                                        {{App\Models\Kabid::find($fo['c_id'])->name;}}
                                    </td>
                                    <td>
                                        {{$x = $tikk->whereRelation('spcart', 'type', '=', 'HAND')->count()}}
                                    </td>
                                    <td>
                                        {{$y = $tikk2->whereRelation('spcart', 'type', '=', 'ELEV')->count()}}
                                    </td>
                                   <td>
                                     {{$x+$y}}
                                    </td>


                                    <td style="background-color:grey">
                                        {{($fo['bus_id'])? App\Models\Bus::find($fo['bus_id'])->name : 'xxx' }}
                                    </td>
                                    <td style="background-color:grey">
                                        {{($fo['ligne_id'])? App\Models\Ligne::find($fo['ligne_id'])->name: 'xxx'}}
                                    </td>

                               
                              
                            @endforeach 


                    </tr>
                    <tfoot>
                        <th>Total</th>
                        <th >{{$ht->count()}}</th>
                        <th >{{$et->count()}}</th>
                     <th >{{$eh->count()}}</th>
                        
                  </tfoot>
    </table>
 
</div>
<div class="page-break"></div>
<div class="row">
    <h5 style="color: rgb(4, 7, 34)" > rapport par bus </h5>
    <table class="table table-bordered table-striped table-sm">
        <thead>
            <th>Bus</th>
            <th >HAND</th>
            <th >ELEV</th>
            <th >total</th>
        </thead>
        {{$bus=  App\Models\Cont::where('spcart_id' , '!=', null)->whereBetween('updated_at', [$from, $to])->where('bus_id', '!=', NULL)->groupBy('bus_id')->get()}}
        <tbody>
           @foreach ($bus as $buss)
           @php
           $buss1=  App\Models\Cont::where('spcart_id' , '!=', null)->whereBetween('updated_at', [$from, $to])->where('bus_id',$buss['bus_id']);
           $buss2=  App\Models\Cont::where('spcart_id' , '!=', null)->whereBetween('updated_at', [$from, $to])->where('bus_id',$buss['bus_id']);
           @endphp
             <tr>
             <td>{{App\Models\Bus::find($buss['bus_id'])->name;}}</td>
             <td >{{$x = $buss1->whereRelation('spcart', 'type', '=', 'HAND')->count()}}</td>
             <td >{{$y = $buss2->whereRelation('spcart', 'type', '=', 'ELEV')->count()}}</td>
             <td >{{$x + $y}}</td>
            </tr>
           
           @endforeach
        </tbody>
        <tfoot>
                   <th>Total</th>
                   <th >{{$ht->count()}}</th>
                   <th >{{$et->count()}}</th>
                   <th >{{$eh->count()}}</th>
             </tfoot>
    </table> 
</div>

<div class="page-break"></div>
<div class="row">
    <h5 style="color: rgb(4, 7, 34)" > rapport par ligne </h5>
    <table class="table table-bordered table-striped table-sm">
        <thead>
            <th>Ligne</th>
            <th >HAND</th>
            <th >ELEV</th>
            <th >total</th>
        </thead>
        {{$bus=  App\Models\Cont::where('spcart_id' , '!=', null)->whereBetween('updated_at', [$from, $to])->where('bus_id', '!=', NULL)->groupBy('ligne_id')->get()}}
        <tbody>
           @foreach ($bus as $buss)
           @php
           $buss1=  App\Models\Cont::where('spcart_id' , '!=', null)->whereBetween('updated_at', [$from, $to])->where('ligne_id',$buss['ligne_id']);
           $buss2=  App\Models\Cont::where('spcart_id' , '!=', null)->whereBetween('updated_at', [$from, $to])->where('ligne_id',$buss['ligne_id']);
           @endphp
             <tr>
             <td>{{App\Models\Ligne::find($buss['ligne_id'])->name;}}</td>
             <td >{{$x = $buss1->whereRelation('spcart', 'type', '=', 'HAND')->count()}}</td>
             <td >{{$y = $buss2->whereRelation('spcart', 'type', '=', 'ELEV')->count()}}</td>
             <td >{{$x + $y}}</td>
            </tr>
           
           @endforeach
        </tbody>
        <tfoot>
                   <th>Total</th>
                   <th >{{$ht->count()}}</th>
                   <th >{{$et->count()}}</th>
                   <th >{{$eh->count()}}</th>
             </tfoot>
    </table> 
</div>
</body>

</html>