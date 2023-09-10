<!DOCTYPE html>
<html>
<head>
 <title>PDF</title>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>
<body style="font-family:Arial" >
  date: <b>{{ now()->format('Y-m-d  H:i') }}</b>
  <div style="clear:both; position:relative;">
    <div style="position:absolute; left:0pt; width:192pt;" >
      <h4>{{$user->name}}</h4>  
      <h6>type:<b>Receveur</b></h6>
      <h6>Solde: <b>{{$user->balance}} </b>DZD</h6>
      <h6>Debut:<b>{{$from}}</b></h6>
      <h6>Fin:<b>{{$to}}</b></h6>
    </div>
    <div style="margin-left:200pt;">
       
      <table class="table table-striped table-sm">
        <thead>
          <tr>
            <th scope="col">date</th>
            <th scope="col">Bus</th>
          </tr>
        </thead>
        <tbody>
          @foreach($info as $inf)
          <tr
         @if ($inf->type == 'login')
          class="table-success"
          @else
          class="table-danger"
          @endif>
            <td>{{ $inf->created_at->format('Y-m-d  H:i:s') }}</td>
            <td>
                @if ($inf->bus_id)
                {{ App\Models\Bus::where('id',$inf->bus_id)->first()->name}}
                @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>  
    </div>
</div>

 <br>
 <div class="row" 
 @if($info->count() < 3)
  style="margin-top:50pt;"
 @endif >
<hr>
 
  <h5 style="color: blue">billets numériques</h5>
  <table class="table table-striped table-sm">
    <thead>
      <th>
        Tarif (dzd)
      </th>
      <th>
        Nombre de billets
      </th>
      <th>
        Valeur du billet (dzd)
      </th>
    </thead>
    <tbody>
      <tr>
        <td>0</td>
        <td>{{$alltickets_0}}</td>
        <td>{{$all_sum_0}}</td>
        
      </tr>
      <tr>
        <td>15</td>
        <td>{{$alltickets_15}}</td>
        <td>{{$all_sum_15}}</td>
        
      </tr>
      <tr>
        <td>20</td>
        <td>{{$alltickets_20}}</td>
        <td>{{$all_sum_20}}</td>
       
      </tr>
      <tr>
        <td>25</td>
        <td>{{$alltickets_25}}</td>
        <td>{{$all_sum_25}}</td>
        
      </tr>
      <tr>
        <td>30</td>
        <td>{{$alltickets_30}}</td>
        <td>{{$all_sum_30}}</td>
       
      </tr>
      <tr>
        <td>totale</td>
        <td>{{$alltickets}}</td>
        <td>{{$all_sum}}</td>
       
      </tr>
    </tbody>

  </table>

  <h5 style="color: blue" >billets classiques ( {{$all_vt_vent}}::{{$all_vt_has}} ) ( {{$all_vt_has - $all_vt_vent}} )</h5>
  <table class="table table-striped table-sm">
    <thead>
      <th>
        Tarif (dzd)
      </th>
      <th>
        Nombre de billets
      </th>
      <th>
        Valeur du billet (dzd)
      </th>
    </thead>
    <tbody>
      <tr>
        <td>15</td>
        <td>{{$allvtickets_15}}</td>
        <td>{{$all_vsum_15}}</td>
        
      </tr>
      <tr>
        <td>20</td>
        <td>{{$allvtickets_20}}</td>
        <td>{{$all_vsum_20}}</td>
        
      </tr>
      <tr>
        <td>25</td>
        <td>{{$allvtickets_25}}</td>
        <td>{{$all_vsum_25}}</td>
        
      </tr>
      <tr>
        <td>30</td>
        <td>{{$allvtickets_30}}</td>
        <td>{{$all_vsum_30}}</td>
        
      </tr>
      <tr>
        <td>totale</td>
        <td>{{$allvtickets}}</td>
        <td>{{$all_vsum}}</td>
        
      </tr>
    </tbody>

  </table>
  <h5 style="color: blue">Recharger les soldes des clients</h5>
  <table class="table table-striped table-sm">
        <tbody>
          <tr>
            <td>Cartes de solde</td>
            <td>{{$solde}} (dzd) /({{$solde2}})</td>
          </tr>
          <tr>
            <td>Flixy</td>
            <td>{{$flixy}} (dzd) </td>
          </tr>
        </tbody>
      </table>
 </div>

  <div class="row">
    <h5 style="color: blue"> les transactions </h5>
  </div>
  <div class="row">
    <table class="table table-striped table-sm" >
        <tr>
            <td>NO</td>
            <td>Montant</td>
            <td>date</td>
            <td>meta</td>
            <td>client type</td>
            <td>Client id</td>
        </tr>

        @foreach($trans as $row)
            <tr @if ($row->amount < 0)
                class="table-danger"
                @else
                class="table-success"
               @endif>
                <td>{{$row->id}}</td>
                <td>{{$row->amount}}</td>
                <td>{{$row->created_at}}</td>
                <td>
                @if ($row->meta)
                    {{$row->meta['action']}}
                @endif
                </td>
                <td>
                    @if(isset($row->meta['class']))
                    {{$row->meta['class']}}
                     @endif
                    
                </td>
                <td>
                    @if (isset($row->meta['cl_name']))
                    {{$row->meta['cl_name']}}
                @endif
                    
                </td>
            </tr>
        @endforeach
    </table>
  </div>
</body>

</html>