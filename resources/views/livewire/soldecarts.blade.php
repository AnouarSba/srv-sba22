<div>
    @if (session()->has('message'))
    <div class="alert alert-success" style="margin-top:30px;">x
      {{ session('message') }}
    </div>
   @endif
    <div class="row gy-2">
        <div class="col-sm-3 gy-2">
         
            @can('ad_ps_carts')
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                ajouter un paquet
            </button>
            @endcan    
            
        </div>
        <div class="col-sm-3 gy-2">
              
            @can('printx_s_carts')
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal1">
                Imprimer un champ de paquets
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
            <td>NO</td>
            <td>type</td>
            <td>statut</td>
            <td>Vent/paquet</td>
            <td>Propriétaire</td>
            <td>Debut - Fin</td>
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
                <td>{{ $this->Scart($row->id)}}</td>
                <td>
                    @if($row->forn_type != NULL && $row->Forn_id != NULL)
                        {{class_basename($row->forn_type)}}
                        ({{ $row->forn_type::find($row->Forn_id)->name ??'deleted user' }}) 
                    @endif
                   </td>
                <td>{{$row->start}}--{{$row->end}}</td>
                <td>{!! QrCode::size(100)->generate($row->token); !!}</td>
                
                <td>
                        
                @can('edit_ps_carts')
                <button data-toggle="modal" data-target="#updateModal" wire:click="edit({{ $row->id }})" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></button>
                @endcan    
                 @can('delete_ps_carts')
                @if($confirming===$row->id)
                <button wire:click="delete({{ $row->id }})" class="btn btn-warning btn-sm"><i class="fa fa-check" aria-hidden="true"></i></button>
                <button wire:click="cancelDelete({{ $row->id }})" class="btn btn-success btn-sm">X</button>
                @else
                <button wire:click="confirmDelete({{ $row->id }})" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></button>
                @endif
                @endcan    
                @can('print_ps_carts')
                <button  wire:click="print({{ $row->id }})" class="btn btn-default btn-sm"><i class="fa fa-print" aria-hidden="true"></i></button>
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

    @can('edit_ps_carts')
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
                              @error('status') <span class="text-danger">{{ $message }}</span>@enderror
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
    @can('ad_ps_carts')

    <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter des paquets</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
               <div class="modal-body">

                    <form>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">nombre de paquets</label>
                            <input type="numeric" class="form-control" id="exampleFormControlInput1" placeholder="Enter pak number" wire:model.lazy="numpak">
                            @error('numpak') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">type de tickets</label>
                            <select wire:model.lazy="type" class="form-control" aria-label="Default select example">
                                <option value="">--Selectioner le type--</option>
                                <option value="type1">Type 1</option>
                                <option value="type2">Type 2</option>
                                <option value="type2">Type 3</option>
                              </select>
                              @error('type') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Nombre de cartes dans le paquet</label>
                            <input type="numeric" class="form-control" id="exampleFormControlInput1" placeholder="Enter pak" wire:model.lazy="pak">
                            @error('pak') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">montante</label>
                            <input type="numeric" class="form-control" id="exampleFormControlInput1" placeholder="Enter pak" wire:model.lazy="amount">
                            @error('amount') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Fermer</button>
                    <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">sauvegarder</button>
                </div>
            </div>
        </div>
    </div>
    @endcan    
    @can('printx_s_carts')
    <div wire:ignore.self class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Imprimer un champ de paquets</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
               <div class="modal-body">

                    <form>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">début</label>
                            <input type="numeric" class="form-control" id="exampleFormControlInput1" placeholder="Enter start" wire:model.lazy="sp">
                            @error('sp') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Fin</label>
                            <input type="numeric" class="form-control" id="exampleFormControlInput1" placeholder="Enter end" wire:model.lazy="ep">
                            @error('ep') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Fermer</button>
                    <button type="button" wire:click.prevent="printx()" class="btn btn-primary close-modal">sauvegarder</button>
                </div>
            </div>
        </div>
    </div>
    @endcan    
    
</div>





