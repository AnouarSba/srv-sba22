<div>
    @if (session()->has('message'))
    <div class="alert alert-success" style="margin-top:30px;">x
      {{ session('message') }}
    </div>
   @endif
@if (!$this->showmode)
<div class="row gy-2">
    <div class="col-sm-3 gy-2">
        @can('ad_carts')
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
            Ajouter des cartes 
        </button>
        @endcan
    </div>
    <div class="col-sm-3 gy-2">
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
        <td>N</td>
        <td>type</td>
        <td>statut</td>
        <td>Vent/paquet</td>
        <td>Propriétaire</td>
        <td>début - Fin</td>
        <td>jeton</td>
        <td>ACTION</td>
    </tr>

    @foreach($users as $row)
    <tr @if ($row->status == 'Forned')
        class="table-success"
    @endif>
            <td>{{$row->id}}</td>
            <td>{{$row->type}}</td>
            <td>{{$row->status}}</td>
            <td>{{ $this->pcart($row->id)}}</td>
            <td>
            @if($row->forn_type != NULL && $row->Forn_id != NULL)
                {{class_basename($row->forn_type)}}
                ({{ $row->forn_type::find($row->Forn_id)->name ??'deleted user' }}) 
            @endif
           </td>
            <td>{{$row->start}}--{{$row->end}}</td>
            <td>{!! QrCode::size(100)->generate($row->token); !!}</td>
            <td>
                @can('edit_p_carts')
                <button data-toggle="modal" data-target="#updateModal" wire:click="edit({{ $row->id }})" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></button>
                @endcan
            @can('delete_p_carts')
            @if($confirming===$row->id)
            <button wire:click="delete({{ $row->id }})" class="btn btn-warning btn-sm"><i class="fa fa-check" aria-hidden="true"></i></button>
            <button wire:click="cancelDelete({{ $row->id }})" class="btn btn-success btn-sm">X</button>
            @else
            <button wire:click="confirmDelete({{ $row->id }})" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></button>
            @endif 
            @endcan    
            @can('print_p_carts')
            <button  wire:click="print({{ $row->id }})" class="btn btn-primary btn-sm"><i class="fa fa-print" aria-hidden="true"></i></button>
             @endcan    
             @can('show_carts')
            <button  wire:click="show({{ $row->id }})" class="btn btn-warning btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></button>
            @endcan    
            
            </td>
        </tr>
    @endforeach
</table>
<div class="row">
    <div class="col-12 d-flex justify-content-center pt-4" >
        {{$users->links()}}
    </div>
  </div>
    
@can('edit_p_carts')
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


                    <div class="form-group">
                        <label for="exampleFormControlInput1">Statut</label>
                        <select wire:model.lazy="status" class="form-control" aria-label="Default select example">
                            <option value="">--Selectioner le statut--</option>
                            <option value="printed">imprimée</option>
                            <option value="in stock">en stock</option>
                            <option value="Forned">reçu</option>
                          </select>
                          @error('type') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">type d'ouvrier</label>
                        <select wire:model.lazy="forn_type" class="form-control" aria-label="Default select example">
                            <option value="">--Selectioner le status--</option>
                            <option value="App\Models\Kabid">Receveur</option>
                            <option value="App\Models\Control">Controller</option>
                            <option value="App\Models\Vendeur">Vendeur</option>
                          </select>
                          @error('forn_type') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">id d'ouvrier @if ($forn_type != NULL && $forn_id != NULL)
                            ({{ $forn_type::find($forn_id)->email ??'deleted user' }}) 
                        @endif </label>
                        <input type="numeric" class="form-control" id="exampleFormControlInput1"  wire:model.lazy="forn_id">
                        @error('forn_id') <span class="text-danger error">{{ $message }}</span>@enderror
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
@can('ad_carts')
<div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">

                <form>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">type de Cartes</label>
                        <select wire:model.lazy="type" class="form-control" aria-label="Default select example">
                            <option value="type1">type 1</option>
                            <option value="type2">type 2</option>
                            <option value="type2">type 3</option>
                          </select>
                          @error('type') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                 
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Nombre de Cartes dans le paquet</label>
                        <input type="numeric" class="form-control" id="exampleFormControlInput1" placeholder="Enter pak" wire:model.lazy="pak">
                        @error('pak') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">montante</label>
                        <input type="numeric" class="form-control" id="exampleFormControlInput1" placeholder="Enter pak" wire:model.lazy="amount">
                        @error('amount') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        @if ($photo)
                        <img src="{{ $photo->temporaryUrl() }}" width="200" height="auto">
                        @endif
           
                      <input type="file" wire:model.lazy="photo">
             
                     @error('photo') <span class="error">{{ $message }}</span> @enderror
                    </div>



                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary close-btn" data-dismiss="modal">Fermer</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">sauvegarder</button>
            </div>
        </div>
    </div>
</div>
@endcan    


@else




<div class="row gy-2">
    <div class="col-sm-3 gy-2">
        <a href="{{ url('/pcarts') }}" wire:click.prevent="cancelshow()"  class="btn btn-danger">
            <i class="fa fa-undo" aria-hidden="true"></i> 
        </a>
    </div>
    <div class="col-sm-3 gy-2">
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
        <td>NO</td>
        <td>type</td>
        <td>paquet</td>
        <td>jeton</td>
        <td>Solde</td>
        <td>Propriétaire</td>
        <td>ACTION</td>
    </tr>

    @foreach($carts as $row)
        <tr @if ($row->valid)
            class="table-success"
        @else
        class="table-default"
        @endif>
            <td>{{$row->id}}</td>
            <td>{{$row->type}}</td>
            
            <td>{{$row->pc_id}}</td>
            <td>{!! QrCode::size(100)->generate($row->token); !!}</td>
            <td>{{$row->balance}}
             
            </td>
            <td>{{$row->cname}}<br>
                {{$row->cphone}}<br>
            </td>
            <td>
                   
            @can('print_p_carts')
            <button data-toggle="modal" data-target="#updateModal" wire:click="edit1({{ $row->id }})" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i>            </button>
            @endcan    
            @can('ban_carts')
            @if($row->ban)
            <button wire:click="unban({{ $row->id }})" class="btn btn-warning btn-sm"><i class="fa fa-ban" aria-hidden="true"></i></button>
            @else
            <button wire:click="ban({{ $row->id }})" class="btn btn-danger btn-sm"><i class="fa fa-ban" aria-hidden="true"></i></button>
            @endif
            @endcan    
            @can('cart_add_fund')
            <button data-toggle="modal" data-target="#addModal" wire:click="edit1({{ $row->id }})" class="btn btn-success btn-sm">+</button>
            @endcan    
            @can('cart_mines_fund')
            <button data-toggle="modal" data-target="#minesModal" wire:click="edit1({{ $row->id }})" class="btn btn-danger btn-sm">-</button>
            @endcan    
            @can('carttrans')
            <a target="_blank" href="/carttrans/{{ $row->id }}"   class="btn btn-primary btn-sm"><i class="fas fa-exchange-alt" aria-hidden="true"></i></a>
            @endcan 
            @can('gen_carts')   
            @if ($row->valid)
            @if($confirming1===$row->id)
            <button wire:click="generation({{ $row->id }})" class="btn btn-warning btn-sm"><i class="fa fa-check" aria-hidden="true"></i></button>
            <button wire:click="cancelGen({{ $row->id }})" class="btn btn-success btn-sm">X</button>
            @else
            <button wire:click="confirmGen({{ $row->id }})" class="btn btn-sm" style="background-color: rgb(225, 0, 255)"><i class="fa fa-undo" aria-hidden="true"></i> </button>
            @endif   
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
    
@can('edit_carts')  
  <div wire:ignore.self class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modifier</h5>
                <button wire:click.prevent="cancel1()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">type de carte</label>
                        <select wire:model.lazy="cart_type" class="form-control" aria-label="Default select example">
                            <option value="">--Selectioner le type--</option>
                            <option value="type1">type1</option>
                            <option value="type2">type2</option>
                            <option value="type3">type3</option>
                          </select>
                          @error('cart_type') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">

                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                            <input type="checkbox" class="custom-control-input" id="customSwitch3" wire:model.lazy="cart_valid" >
                            <label class="custom-control-label" for="customSwitch3">valide</label>
                          </div>
                          @error('cart_valid') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">NOM</label>
                        <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter pak" wire:model.lazy="cname">
                        @error('cname') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">TELEPHONE</label>
                        <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter pak" wire:model.lazy="cphone">
                        @error('cphone') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel1()" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" wire:click.prevent="update1()" class="btn btn-primary close-modal">sauvegarder</button>
            </div>
       </div>
    </div>
</div>

@endcan    
    
@can('cart_add_fund')

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
                <button type="button" wire:click.prevent="cancel1()" class="btn btn-secondary close-btn" data-dismiss="modal">Fermer</button>
                <button type="button" wire:click.prevent="add()" class="btn btn-primary close-modal">sauvegarder</button>
            </div>
        </div>
    </div>
</div> 
@endcan    
@can('cart_mines_fund')

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
                <button type="button" wire:click.prevent="cancel1()" class="btn btn-secondary close-btn" data-dismiss="modal">Fermer</button>
                <button type="button" wire:click.prevent="mines()" class="btn btn-primary close-modal">sauvegarder</button>
            </div>
        </div>
    </div>
</div> 
@endcan    

@endif

</div>




