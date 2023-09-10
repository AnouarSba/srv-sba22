<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
  body { font-family: Arial, Helvetica, sans-serif; }
</style>
 <title>PDF</title>
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>
<body>
  date: <b>{{ now()->format('Y-m-d  h:i') }}</b>
  <div style="clear:both; position:relative;">
    <div style="position:absolute; left:0pt; width:192pt;" >
      <h4>{{$user->name}}</h4>  
      <h6>type:<b>controller</b></h6>
      <h6>Solde: <b>{{$user->balance}} </b>DZD</h6>
      <h6>Debut:<b>{{$from}}</b></h6>
      <h6>Fin:<b>{{$to}}</b></h6>
    </div>
    <div style="margin-left:200pt;">
       
      <table class="table table-striped table-sm">
        <thead>
          <tr>
            <th scope="col">date</th>
            <th scope="col">valideur</th>
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
            <td>{{ $inf->created_at->format('Y-m-d  H:i') }}</td>
            <td>{{ App\Models\Valideur::where('device_id',$inf->deviceid)->first()->id}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>  
    </div>
</div>

 <br>
 <div class="row" 
 @if($info->count() < 3)
  style="margin-top:30pt;"
 @endif>
    <table class="table table-striped">
        <thead class="thead-dark">
          <tr>
          
            <th scope="col"></th>
            <th scope="col">Value</th>
            
          </tr>
        </thead>
        <tbody>
          <tr>
            
            <td>Nombre de cartes vendues</td>
            <td>{{$cartvent}}</td>
       
          </tr>
          <tr>
            
            <td>Nombre de cartes surveillées</td>
            <td>{{$cont_cart}}</td>
            
          </tr>
          <tr>
            
            <td>Nombre des clients surveillées</td>
            <td>{{$cont_app}}</td>
            
          </tr>
          <tr>
            
            <td>Nombre des tickets surveillées</td>
            <td>{{$cont_vt}}</td>
            
          </tr>
          <tr>
            
            <td>Cartes de solde</td>
            <td>{{$solde}} dzd /({{$solde2}})</td>
          </tr>
          <tr>
            
            <td>Flixy</td>
            <td>{{$flixy}} dzd </td>
          </tr>
        </tbody>
      </table>
 </div>


 <div class="row">
  <h4> les Cartes Vendues </h4>
</div>
<div class="row">
  <table class="table table-striped table-sm" >
      <tr>
          
          <td>Carte</td>
          <td>PR</td>
          <td>date</td>

      </tr>

      @foreach($vent as $row)
          <tr>
              
              <td>{{$row->cart_id}}</td>
              <td>{{ App\Models\Cart::find($row->cart_id)->cname }}<br>
                  {{ App\Models\Cart::find($row->cart_id)->cphone }}</td>
              <td>
                {{ $inf->created_at->format('Y-m-d  h:i:s') }}
              </td>


          </tr>
      @endforeach
  </table>
</div>

  <div class="row">
    <h4> les transactions </h4>
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