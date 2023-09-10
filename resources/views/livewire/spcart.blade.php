
<div>
    @if (session()->has('message'))
    <div class="alert alert-success" style="margin-top:30px;">x
      {{ session('message') }}
    </div>
   @endif
<div class="row gy-2">
    <div class="col-sm-3 gy-2">
        @can('add_spcarts')
        <button wire:click="$set('type','HAND')" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal_hand">
            Ajouter Carte handicape
        </button>
        @endcan
    </div>
   
    <div class="col-sm-3 gy-2">
    @can('add_spcarts')
        <button wire:click="$set('type','ELEV')" type="button" class="btn btn-default" data-toggle="modal" data-target="#exampleModal_elev">
            Ajouter Carte Elève
        </button>
        @endcan
    </div>
    <div class="col-sm-2 gy-2">
    </div>
    <label class="col-sm-1 gy-2 "></label>
    <div class="col-sm-3 gy-2">
      <input type="text"  class="form-control" placeholder="Search" wire:model="searchTerm" />
    </div>
  </div>
<table class="table table-striped table-sm" style="margin-top:10px;">
    <tr>
        <td>Photo</td>
        <td>Nom</td>
        <td>Type</td>
        <td>UUID</td>
        <td>QRCODE</td>
        <td>Solde</td>
        <td>Abon</td>
        <td>ACTION</td>
    </tr>

    @foreach($carts as $row)
        <tr @if ($row->valid)
            class="table-success"
        @else
        class="table-default"
        @endif>
           <td>
            @if ($row->photo)
            <img src="{{ asset('storage/scphotos/'.$row->photo)}}" alt="" title="" width="auto" height="100"></a>

            @endif
        
           </td>
           <td>
            {{$row->name}}<br>
            {{$row->phone}}<br>
           </td>
            <td>{{$row->type}}</td>
            
            <td>{{$row->uuid}}</td>
            <td>{!! QrCode::size(100)->generate($row->token); !!}</td>
            <td>{{$row->balance}}
             
            </td>

            <td>
              @php
              $abons =$row->abons()->latest()->first();
              if ($abons) {
                if ($abons->end > now()->format('Y-m-d')) {
                   echo $abons->end;
                }else {
                    echo 'exp';
                }
              }
              @endphp  
            </td>

            <td>
                   
            @can('edit_spcarts')
            <button data-toggle="modal" data-target="#updateModal" wire:click="edit({{ $row->id }})" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i>            </button>
            @endcan    
            @can('ban_spcarts')
            @if($row->ban)
            <button wire:click="unban({{ $row->id }})" class="btn btn-warning btn-sm"><i class="fa fa-ban" aria-hidden="true"></i></button>
            @else
            <button wire:click="ban({{ $row->id }})" class="btn btn-danger btn-sm"><i class="fa fa-ban" aria-hidden="true"></i></button>
            @endif
            @endcan    
            @can('spcart_add_fund')
            <button data-toggle="modal" data-target="#addModal" wire:click="edit({{ $row->id }})" class="btn btn-success btn-sm">+</button>
            @endcan    
            @can('spcart_mines_fund')
            <button data-toggle="modal" data-target="#minesModal" wire:click="edit({{ $row->id }})" class="btn btn-danger btn-sm">-</button>
            @endcan    
            @can('spcarttrans')
            <a target="_blank" href="/spcarttrans/{{ $row->id }}"   class="btn btn-primary btn-sm"><i class="fas fa-exchange-alt" aria-hidden="true"></i></a>
            @endcan 
            @can('printspcart')
            <a target="_blank" href="/printspcart/{{ $row->id }}"   class="btn btn-primary btn-sm"><i class="fa fa-id-card" aria-hidden="true"></i></a>
            @endcan 
            @can('gen_spcarts')   
            @if ($row->valid)
            @if($confirming1===$row->id)
            <button wire:click="generation({{ $row->id }})" class="btn btn-warning btn-sm"><i class="fa fa-check" aria-hidden="true"></i></button>
            <button wire:click="cancelGen({{ $row->id }})" class="btn btn-success btn-sm">X</button>
            @else
            <button wire:click="confirmGen({{ $row->id }})" class="btn btn-sm" style="background-color: rgb(225, 0, 255)"><i class="fa fa-undo" aria-hidden="true"></i> </button>
            @endif 
            
            @endif
            @endcan 
            @can('active_ab_spcarts')
            @if($confirming2===$row->id)
            <button wire:click="activeab({{ $row->id }})" class="btn btn-warning btn-sm"><i class="fa fa-check" aria-hidden="true"></i></button>
            <button wire:click="cancelActive({{ $row->id }})" class="btn btn-success btn-sm">X</button>
            @else
            <button wire:click="confirmActive({{ $row->id }})" class="btn btn-sm" style="background-color: rgb(60, 255, 0)"><i class="fa fa-toggle-on" aria-hidden="true"></i> </button>
            @endif 
            @endcan
        </td>
        </tr>
    @endforeach
</table>
<div class="row">
    <div class="col-12 d-flex justify-content-center pt-4" >
        {{$carts->links()}}
    </div>
  </div>
  @can('add_spcarts')  
  <div wire:ignore.self class="modal fade" id="exampleModal_hand" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modifier</h5>
                <button wire:click.prevent="cancel()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div hidden class="form-group">
                        <label for="exampleFormControlInput1">type de carte</label>
                    <!-- <select wire:model.lazy="type"  id="sel" class="form-control"aria-label="Default select example">
                            @php
                            $types = App\Models\Abtype::All();
                            @endphp
                            @foreach ($types as $type)
                            
                            <option value="{{$type->code}}">{{$type->name}}/{{$type->dur}}/{{$type->amount}}</option>  
                            
                            @endforeach
                          
                          </select>  -->
                          @error('type') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    
                    <div class="form-group">
                        <input type="hidden" wire:model.lazy="user_id">
                        <label for="exampleFormControlInput1">Nom</label>
                        <input type="text" class="form-control" wire:model.lazy="name" id="exampleFormControlInput1" placeholder="Enter Name">
                        @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <input type="hidden" wire:model.lazy="date_nes">
                        <label for="exampleFormControlInput1">Date de naissance</label>
                        <input type="date" class="form-control" wire:model.lazy="date_nes" id="game-date-time-text"  value="{{ now()->setTimezone('T')->format('Y-m-d') }}" >
                        @error('date_nes') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                   <!-- <div class="form-group" id="exp2" >
                        <label for="exampleFormControlInput2">telephone</label>
                        <input type="tel" class="form-control" wire:model.lazy="phone" id="exampleFormControlInput2" placeholder="Enter phone number">
                        @error('phone') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    
                    <div class="form-group" id="exp22">
                        <label for="exampleFormControlInput2">Prénom du pére</label>
                        <input type="text" class="form-control" wire:model.lazy="father" id="exampleFormControlInput22" placeholder="Enter prénom du pére">
                        @error('father') <span class="text-danger">{{ $message }}</span>@enderror
                    </div> -->
                    <div class="form-group" hidden id="exp">
                        <label for="exampleFormControlInput1">Le max de billets par jour</label>
                        <input type="numeric" class="form-control" id="exampleFormControlInput1" placeholder="Enter max" wire:model.lazy="max">
                        @error('max') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div> 
                    <div class="form-group" id="exp222">
                        <label for="exampleFormControlInput2">pourcentage hendicape %</label>
                        <input type="tel" class="form-control" wire:model.lazy="hand_prcnt" id="exampleFormControlInput222" placeholder="Enter آضفعقث">
                        @error('hand_prcnt') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group" id="exp222">
                        <label for="exampleFormControlInput2">Nature hendicape </label>
                        <input type="text" class="form-control" wire:model.lazy="hand_nature" id="exampleFormControlInput222" placeholder="Enter %">
                        @error('hand_nature') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group" id="exp2222">
                        <label for="exampleFormControlInput2">N° carte</label>
                        <input type="text" class="form-control" wire:model.lazy="card_number" id="exampleFormControlInput2222" placeholder="Enter numero de la carte">
                        @error('card_number') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">

                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                            <input type="checkbox" class="custom-control-input" id="customSwitch3" wire:model.lazy="valid" >
                            <label class="custom-control-label" for="customSwitch3">valide</label>
                          </div>
                          @error('valid') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                @if ($photo) 
                                <img src="{{ $photo->temporaryUrl() }}" width="auto" height="200">
                                @endif
                            </div>
                        </div>          
                      <input type="file" wire:model.lazy="photo">
             
                     @error('photo') <span class="error">{{ $message }}</span> @enderror
                    </div>
 
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">sauvegarder</button>
            </div>
       </div>
    </div>
</div>
<div wire:ignore.self class="modal fade" id="exampleModal_elev" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modifier</h5>
                <button wire:click.prevent="cancel()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div hidden class="form-group">
                        <label for="exampleFormControlInput1">type de carte</label>
                       <!-- <select wire:model.lazy="type" id="sel" class="form-control"  aria-label="Default select example">
                            @php
                            $types = App\Models\Abtype::All();
                            @endphp
                            @foreach ($types as $type)
                            if($type->code== 'ELEV')

                            <option value="{{$type->code}}" {{($type->code== 'ELEV')? 'selected' : '' }}>{{$type->name}}/{{$type->dur}}/{{$type->amount}}</option>  
                            @endforeach
                          
                          </select> -->
                          @error('type') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    
                    <div class="form-group">
                        <input type="hidden" wire:model.lazy="user_id">
                        <label for="exampleFormControlInput1">Nom</label>
                        <input type="text" class="form-control" wire:model.lazy="name" id="exampleFormControlInput1" placeholder="Enter Name">
                        @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <input type="hidden" wire:model.lazy="date_nes">
                        <label for="exampleFormControlInput1">Date de naissance</label>
                        <input type="date" class="form-control" wire:model.lazy="date_nes" id="game-date-time-text"  value="{{ now()->setTimezone('T')->format('Y-m-d') }}" >
                        @error('date_nes') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group" id="exp2" >
                        <label for="exampleFormControlInput2">telephone</label>
                        <input type="tel" class="form-control" wire:model.lazy="phone" id="exampleFormControlInput2" placeholder="Enter phone number">
                        @error('phone') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                   
                    <div class="form-group" id="exp22">
                        <label for="exampleFormControlInput2">Prénom du pére</label>
                        <input type="text" class="form-control" wire:model.lazy="father" id="exampleFormControlInput22" placeholder="Enter prénom du pére">
                        @error('father') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                     <div class="form-group" id="exp">
                        <label for="exampleFormControlInput1">Le max de billets par jour</label>
                        <input type="numeric" class="form-control" id="exampleFormControlInput1" placeholder="Enter max" wire:model.lazy="max">
                        @error('max') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div> 
                   <!-- <div class="form-group" id="exp222">
                        <label for="exampleFormControlInput2">%</label>
                        <input type="tel" class="form-control" wire:model.lazy="hand_prcnt" id="exampleFormControlInput222" placeholder="Enter %">
                        @error('hand_prcnt') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                   
                    <div class="form-group" id="exp2222">
                        <label for="exampleFormControlInput2">N° carte</label>
                        <input type="text" class="form-control" wire:model.lazy="card_number" id="exampleFormControlInput2222" placeholder="Enter numero de la carte">
                        @error('card_number') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>-->
                    <div class="form-group">

                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                            <input type="checkbox" class="custom-control-input" id="customSwitch3" wire:model.lazy="valid" >
                            <label class="custom-control-label" for="customSwitch3">valide</label>
                          </div>
                          @error('valid') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                @if ($photo) 
                                <img src="{{ $photo->temporaryUrl() }}" width="auto" height="200">
                                @endif
                            </div>
                        </div>          
                      <input type="file" wire:model.lazy="photo">
             
                     @error('photo') <span class="error">{{ $message }}</span> @enderror
                    </div>
 
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">sauvegarder</button>
            </div>
       </div>
    </div>
</div>
@endcan    
@can('edit_spcarts')  
  <div wire:ignore.self class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modifier</h5>
                <button wire:click.prevent="cancel()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                   <!-- <div class="form-group">
                        <label for="exampleFormControlInput1">type de carte</label>
                        <select wire:model.lazy="type" class="form-control" aria-label="Default select example">
                            <option value="">--Selectioner le type--</option>
                            @php
                            $types = App\Models\Abtype::All();
                            @endphp
                            @foreach ($types as $type)
                            <option value="{{$type->code}}">{{$type->name}}/{{$type->dur}}/{{$type->amount}}</option>  
                            @endforeach
                          </select>
                          @error('type') <span class="text-danger">{{ $message }}</span>@enderror
                    </div> -->
                    <div class="form-group">
                        <input type="hidden" wire:model.lazy="user_id">
                        <label for="exampleFormControlInput1">Nom</label>
                        <input type="text" class="form-control" wire:model.lazy="name" id="exampleFormControlInput1" placeholder="Enter Name">
                        @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <input type="hidden" wire:model.lazy="date_nes">
                        <label for="exampleFormControlInput1">Date de naissance</label>
                        <input type="date" class="form-control" wire:model.lazy="date_nes" id="game-date-time-text"  value="{{ now()->setTimezone('T')->format('Y-m-d') }}" >
                        @error('date_nes') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                    @if($phone != null)
                        <label for="exampleFormControlInput2" wire:model.lazy="phone">telephone</label>
                        <input type="tel" class="form-control" wire:model.lazy="phone" id="exampleFormControlInput2" placeholder="Enter phone number">
                        @error('phone') <span class="text-danger">{{ $message }}</span>@enderror
                        @endif
                    </div>
                    <div class="form-group" id="exp22">
                    @if($father != null)
                        <label for="exampleFormControlInput2" wire:model.lazy="father" >Prénom du pére</label>
                        <input type="text" class="form-control" wire:model.lazy="father" id="exampleFormControlInput22" placeholder="Enter prénom du pére">
                        @error('father') <span class="text-danger">{{ $message }}</span>@enderror
                        @endif
                    </div>
                    <div class="form-group" id="exp222">
                    @if($hand_prcnt != null)
                        <label for="exampleFormControlInput2" wire:model.lazy="hand_prcnt">pourcentage hendicape %</label>
                        <input type="tel" class="form-control" wire:model.lazy="hand_prcnt" id="exampleFormControlInput222" placeholder="Enter %">
                        @error('hand_prcnt') <span class="text-danger">{{ $message }}</span>@enderror
                        @endif
                    </div>
                    <div class="form-group" id="exp222">
                    @if($hand_prcnt)
                        <label for="exampleFormControlInput2" wire:model.lazy="hand_nature">Nature hendicape </label>
                        <input type="text" class="form-control" wire:model.lazy="hand_nature" id="exampleFormControlInput222" placeholder="Enter Nature">
                        @error('hand_nature') <span class="text-danger">{{ $message }}</span>@enderror
                        @endif
                    </div>
                    <div class="form-group" id="exp2222">
                    @if($card_number != null)
                        <label for="exampleFormControlInput2" wire:model.lazy="card_number">N° carte</label>
                        <input type="text" class="form-control" wire:model.lazy="card_number" id="exampleFormControlInput2222" placeholder="Enter numero de la carte">
                        @error('card_number') <span class="text-danger">{{ $message }}</span>@enderror
                        @endif
                    </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Le max de billets par jour</label>
                            <input type="numeric" class="form-control" id="exampleFormControlInput1" placeholder="Enter max" wire:model.lazy="max">
                            @error('max') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    
                    <div class="form-group">

                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                            <input type="checkbox" class="custom-control-input" id="customSwitch3" wire:model.lazy="valid" >
                            <label class="custom-control-label" for="customSwitch3">valide</label>
                          </div>
                          @error('valid') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <h5>Nouvelle photo</h5>
                                @if ($photo) 
                                <img src="{{ $photo->temporaryUrl() }}" width="200" height="auto">
                                @endif
                            </div>
                            <div class="col">
                                <h5>ancienne image</h5>
                                @if ($photo1) 
                                <img src="{{ asset('storage/scphotos/'.$photo1)}}" width="200" height="auto">
                                @endif
                            </div>
                        </div>

                        
                      <input type="file" wire:model.lazy="photo">
             
                     @error('photo') <span class="error">{{ $message }}</span> @enderror
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" wire:click.prevent="update()" class="btn btn-primary close-modal">sauvegarder</button>
            </div>
       </div>
    </div>
</div>

@endcan    
    
@can('spcart_add_fund')

<div wire:ignore.self class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">déposer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">montante</label>
                        <input type="numeric" class="form-control" id="exampleFormControlInput1" placeholder="Enter pak" wire:model.lazy="amount1">
                        @error('amount1') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary close-btn" data-dismiss="modal">Fermer</button>
                <button type="button" wire:click.prevent="add()" class="btn btn-primary close-modal">sauvegarder</button>
            </div>
        </div>
    </div>
</div> 
@endcan    
@can('spcart_mines_fund')

<div wire:ignore.self class="modal fade" id="minesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Retrait</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">

                <form>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">montante</label>
                        <input type="numeric" class="form-control" id="exampleFormControlInput1" placeholder="Enter pak" wire:model.lazy="amount1">
                        @error('amount1') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary close-btn" data-dismiss="modal">Fermer</button>
                <button type="button" wire:click.prevent="mines()" class="btn btn-primary close-modal">sauvegarder</button>
            </div>
        </div>
    </div>
</div> 
@endcan    



</div>
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>

<script>
   
    function ch() {
        var x = $('#sel').val();
        if(x == 'HAND'){
            $("#exp2").css("display","none");
            $("#exp22").css("display","none");
            $("#exp222").css("display","block");
            $("#exp2222").css("display","block");
        }else {
            $("#exp2").css("display","block");
            $("#exp22").css("display","block");
            $("#exp222").css("display","none");
            $("#exp2222").css("display","none");
        }
    }
</script>





