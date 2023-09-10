<!DOCTYPE html>
<html>
<head>
 <title>PDF</title>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>
<body style="font-family:Arial" >
 
<div class="row">
    date: <b>{{ now()->format('Y-m-d  H:m') }}</b><br>
    <hr>
    Debut:<b>{{$from}}</b> <br>
    Fin:<b>{{$to}}</b> 
</div>

 <br>
 <div class="row">
  <h5 style="color: blue" >billets classiques </h5>
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
        <td>{{$vtick_15->count()}}</td>
        <td>{{$vtick_15->sum('amount')}}</td>
        
      </tr>
      <tr>
        <td>20</td>
        <td>{{$vtick_20->count()}}</td>
        <td>{{$vtick_20->sum('amount')}}</td>
        
      </tr>
      <tr>
        <td>25</td>
        <td>{{$vtick_25->count()}}</td>
        <td>{{$vtick_25->sum('amount')}}</td>
        
      </tr>
      <tr>
        <td>30</td>
        <td>{{$vtick_30->count()}}</td>
        <td>{{$vtick_30->sum('amount')}}</td>
        
      </tr>
      <tr>
        <td>total</td>
        <td>{{$vtick}}</td>
        <td>{{$vsum}}</td>
        
      </tr>
    </tbody>

  </table>
  <h5 style="color: blue" >billets numériques </h5>
  <table class="table table-striped table-sm">
    <thead>
      <th>
       
      </th>
      <th colspan="2" style="background-color: rgb(94, 167, 251)">
        carte d'abonnement
      </th>
      <th colspan="2" style="background-color: rgb(0, 255, 179)">
        Utilisateurs d'applications
      </th>
      <th colspan="2" style="background-color: aqua">
        total
      </th>
    </thead>
    <tbody>
      <tr>
        <th style="background-color: aqua">
          Tarif (dzd)
        </th>
        <td style="background-color: rgb(94, 167, 251)">Nombre de billets</td>
        <td style="background-color: rgb(94, 167, 251)"> Valeur du billet (dzd)</td>
        <td style="background-color: rgb(0, 255, 179)">Nombre de billets</td>
        <td style="background-color: rgb(0, 255, 179)"> Valeur du billet (dzd)</td>
        <td style="background-color: aqua">Nombre</td>
        <td style="background-color: aqua">Valeur</td>
      </tr>
      <tr>
        <td style="background-color: aqua">0</td>
        <td style="background-color: rgb(94, 167, 251)">{{$etick_0_cart->count()}}</td>
        <td style="background-color: rgb(94, 167, 251)">{{$etick_0_cart->sum('amount')}}</td>
        <td style="background-color: rgb(0, 255, 179)">{{$etick_0_app->count()}}</td>
        <td style="background-color: rgb(0, 255, 179)">{{$etick_0_app->sum('amount')}}</td>
        <td style="background-color: aqua">{{$etick_0_app->count() + $etick_0_cart->count()}}</td>
        <td style="background-color: aqua">{{$etick_0_app->sum('amount') + $etick_0_cart->sum('amount')}}</td>
      </tr>
      <tr>
        <td style="background-color: aqua">15</td>
        <td style="background-color: rgb(94, 167, 251)">{{$etick_15_cart->count()}}</td>
        <td style="background-color: rgb(94, 167, 251)">{{$etick_15_cart->sum('amount')}}</td>
        <td style="background-color: rgb(0, 255, 179)">{{$etick_15_app->count()}}</td>
        <td style="background-color: rgb(0, 255, 179)">{{$etick_15_app->sum('amount')}}</td>
        <td style="background-color: aqua">{{$etick_15_app->count() + $etick_15_cart->count()}}</td>
        <td style="background-color: aqua">{{$etick_15_app->sum('amount') + $etick_15_cart->sum('amount')}}</td>
      </tr>
      <tr>
        <td style="background-color: aqua">20</td>
        <td style="background-color: rgb(94, 167, 251)">{{$etick_20_cart->count()}}</td>
        <td style="background-color: rgb(94, 167, 251)">{{$etick_20_cart->sum('amount')}}</td>
        <td style="background-color: rgb(0, 255, 179)">{{$etick_20_app->count()}}</td>
        <td style="background-color: rgb(0, 255, 179)">{{$etick_20_app->sum('amount')}}</td>
        <td style="background-color: aqua">{{$etick_20_app->count() + $etick_20_cart->count()}}</td>
        <td style="background-color: aqua">{{$etick_20_app->sum('amount') + $etick_20_cart->sum('amount')}}</td>
      </tr>
      <tr>
        <td style="background-color: aqua">25</td>
        <td style="background-color: rgb(94, 167, 251)">{{$etick_25_cart->count()}}</td>
        <td style="background-color: rgb(94, 167, 251)">{{$etick_25_cart->sum('amount')}}</td>
        <td style="background-color: rgb(0, 255, 179)">{{$etick_25_app->count()}}</td>
        <td style="background-color: rgb(0, 255, 179)">{{$etick_25_app->sum('amount')}}</td>
        <td style="background-color: aqua">{{$etick_25_app->count() + $etick_25_cart->count()}}</td>
        <td style="background-color: aqua" >{{$etick_25_app->sum('amount') + $etick_25_cart->sum('amount')}}</td>
      </tr>
      <tr>
        <td style="background-color: aqua">30</td>
        <td style="background-color: rgb(94, 167, 251)">{{$etick_30_cart->count()}}</td>
        <td style="background-color: rgb(94, 167, 251)" >{{$etick_30_cart->sum('amount')}}</td>
        <td style="background-color: rgb(0, 255, 179)">{{$etick_30_app->count()}}</td>
        <td style="background-color: rgb(0, 255, 179)">{{$etick_30_app->sum('amount')}}</td>
        <td style="background-color: aqua" >{{$etick_30_app->count() + $etick_30_cart->count()}}</td>
        <td style="background-color: aqua" >{{$etick_30_app->sum('amount') + $etick_30_cart->sum('amount')}}</td>
      </tr>
      <tr style="background-color: aqua">
        <td>total</td>
        <td>{{$etick_0_cart->count() +$etick_15_cart->count() + $etick_20_cart->count() + $etick_25_cart->count() +$etick_30_cart->count()}}</td>
        <td>{{$etick_0_cart->sum('amount') +$etick_15_cart->sum('amount') + $etick_20_cart->sum('amount') + $etick_25_cart->sum('amount') + $etick_30_cart->sum('amount')}}</td>
        <td>{{$etick_0_app->count() +$etick_15_app->count() + $etick_20_app->count() + $etick_25_app->count() + $etick_30_app->count()}}</td>
        <td>{{$etick_0_app->sum('amount') + $etick_15_app->sum('amount') + $etick_20_app->sum('amount') + $etick_25_app->sum('amount') + $etick_30_app->sum('amount')}}</td>
        <td>{{$etick_0_cart->count() +$etick_15_cart->count() + $etick_20_cart->count() + $etick_25_cart->count() +$etick_30_cart->count()+ $etick_15_app->count() + $etick_20_app->count() + $etick_25_app->count() + $etick_30_app->count()}}</td>
        <td>{{$etick_0_cart->sum('amount') +$etick_15_cart->sum('amount') + $etick_20_cart->sum('amount') + $etick_25_cart->sum('amount') + $etick_30_cart->sum('amount') + $etick_15_app->sum('amount') + $etick_20_app->sum('amount') + $etick_25_app->sum('amount') + $etick_30_app->sum('amount')}}</td>
      </tr>
    </tbody>

  </table>

  <h5 style="color: blue" >Statistiques supplémentaires </h5>
    <table class="table table-striped table-sm">
        <tbody>
            <thead>
            <th>attribut</th>
            <th>le nombre</th>
            <th>la valeur (dzd)</th>
            </thead>
          <tr>
            
            <td>flixy</td>
            <td> {{$flixy_count}} </td>
            <td> {{$flixy_sum}} </td>
            
          </tr>
          <tr>
            <td>Cartes de solde</td>
            <td> {{$solde_count}} </td>
            <td> {{$solde}} </td>
          </tr>
          <tr>
            <td>Satim</td>
            <td> {{$satim_count}} </td>
            <td> {{$satim_sum*-1}} </td>
          </tr>
          <tr>
            <td>Nombre de cartes vendues</td>
            <td> {{$cartvend}} </td>
            <td>  </td>
          </tr>
        </tbody>
      </table>
 </div>

</body>

</html>