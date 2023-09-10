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
    <h5 style="color: rgb(4, 7, 34)" > liste des éléves : {{$from}} a: {{$to}}</h5>
    
    <table class="table table-bordered table-striped table-sm">
                                
                             <thead>

                            <th>ID</th>
                            <th >Nom & Prenom eleve</th>
                            <th >Date naissance</th>
                            <th >Nom Pere</th>
                            <th>Numero Tel</th>
                            <th>Montant</th>
                            
                            
                             </thead>
                  
                  
                          @php
                          $num =0;
                          @endphp
                           @foreach ($et as $eleve)
                                {{$num++;}} 
                                   <tr>
                                    <td>
                                        {{$num;}}
                                    </td>
                                    <td>
                                        {{$eleve->name;}}
                                    </td>
                                    <td>
                                        {{$eleve->date_nes;}}
                                    </td>
                                   <td>
                                     {{$eleve->father;}}
                                    </td>
                                    <td>
                                     {{$eleve->phone;}}
                                    </td>
                                    <td>
                                     {{$amount=  App\Models\Abtype::where('code' , '=', "ELEV")->first()->amount;}}
                                    </td>

                                    
                    </tr>

                               
                              
                            @endforeach 


                    <tfoot>
                        <th colspan="5">Total</th>
                       
                     <th >{{$amount*$num}}</th>
                        
                  </tfoot>
    </table>
 
</div>



</body>

</html>