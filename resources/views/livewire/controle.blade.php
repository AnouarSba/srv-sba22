<div>
    @if (session()->has('message'))
    <div class="alert alert-success" style="margin-top:30px;">x
      {{ session('message') }}
    </div>
   @endif
    <div class="row gy-2">
        <div class="col-sm-3 gy-2">
            @can('ad_controller')
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                Ajouter Controlleur
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
            <td>Photo</td>
            <td>Nom</td>
            <td>EMAIL</td>
            <td>Telephone</td>
            <td>Solde</td>
            <td>ACTION</td>
        </tr>

        @foreach($users as $row)
            <tr>
                <td>{{$row->id}}</td>
                <td>
                    @if ($row->photo)
                    <img src="{{ asset('storage/photos/'.$row->photo)}}" alt="" title="" width="auto" height="70"></a>

                    @endif
                
                </td>
                <td>{{$row->name}}</td>
                <td>{{$row->email}}</td>
                <td>{{$row->phone}}</td>
                <td @if ($row->balance < 2000)
                    class="table-danger"
                @endif>{{$row->balance}}</td>
                <td>
                    @can('edit_controller')
                    <button data-toggle="modal" data-target="#updateModal" wire:click="edit({{ $row->id }})" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></button> 
                    @endcan
                    
                    @can('delete_controller')
                    @if($confirming===$row->id)
                    <button wire:click="delete({{ $row->id }})" class="btn btn-warning btn-sm"><i class="fa fa-check" aria-hidden="true"></i></button>
                    <button wire:click="cancelDelete({{ $row->id }})" class="btn btn-success btn-sm">X</button>
                    @else
                    <button wire:click="confirmDelete({{ $row->id }})" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></button>
                    @endif 
                    @endcan
                    @can('ban_controller')
                    @if($row->ban)
                    <button wire:click="unban({{ $row->id }})" class="btn btn-warning btn-sm"><i class="fa fa-ban" aria-hidden="true"></i></button>
                    @else
                    <button wire:click="ban({{ $row->id }})" class="btn btn-danger btn-sm"><i class="fa fa-ban" aria-hidden="true"></i></button>
                    @endif
                    @endcan

                @can('add_fund_controller')
                <button data-toggle="modal" data-target="#addModal" wire:click="edit({{ $row->id }})" class="btn btn-success btn-sm">+</button>
                @endcan
                @can('mines_fund_controller')
                <button data-toggle="modal" data-target="#minesModal" wire:click="edit({{ $row->id }})" class="btn btn-danger btn-sm">-</button>
  
                @endcan
                @can('printauthcartco')
                <button  wire:click="printcart({{ $row->id }})" class="btn btn-success btn-sm"><i class="fas fa-id-card" aria-hidden="true"></i></button>
                @endcan
                @can('controltrans')
                <a target="_blank" href="/controltrans/{{ $row->id }}"   class="btn btn-primary btn-sm"><i class="fas fa-exchange-alt" aria-hidden="true"></i></a>     
                @endcan
                @can('gen_controller')
                @if($confirming1===$row->id)
                <button wire:click="generation({{ $row->id }})" class="btn btn-warning btn-sm"><i class="fa fa-check" aria-hidden="true"></i></button>
                <button wire:click="cancelGen({{ $row->id }})" class="btn btn-success btn-sm">X</button>
                @else
                <button wire:click="confirmGen({{ $row->id }})" class="btn btn-sm" style="background-color: rgb(225, 0, 255)"><i class="fa fa-undo" aria-hidden="true"></i> </button>
                @endif 
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
@can('edit_controller')
<div wire:ignore.self class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modifier le Controlleur</h5>
                <button wire:click.prevent="cancel()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <input type="hidden" wire:model.lazy="user_id">
                        <label for="exampleFormControlInput1">Nom</label>
                        <input type="text" class="form-control" wire:model.lazy="name" id="exampleFormControlInput1" placeholder="Enter Name">
                        @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput2">Telephone</label>
                        <input type="tel" class="form-control" wire:model.lazy="phone" id="exampleFormControlInput2" placeholder="Enter phone number">
                        @error('phone') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput2">Email</label>
                        <input type="email" class="form-control" wire:model.lazy="email" id="exampleFormControlInput2" placeholder="Enter Email">
                        @error('email') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput3">Mot de Passe</label>
                        <input placeholder="Password" id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" wire:model.lazy="password">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                     </div>
                      <div class="form-group">
                        <label for="exampleFormControlInput4">Confirmation mot de passe</label>
                        <input placeholder="Password Confirm" id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" wire:model.lazy="password_confirmation">
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
                                <img src="{{ asset('storage/photos/'.$photo1)}}" width="200" height="auto">
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
                <button type="button" wire:click.prevent="update()" class="btn btn-primary close-modal">Sauvegarder</button>
            </div>
       </div>
    </div>
</div>
@endcan



@can('ad_controller')
<div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter Controlleur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Nom</label>
                        <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter Name" wire:model.lazy="name">
                        @error('name') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput2">Telephone</label>
                        <input type="tel" class="form-control" wire:model.lazy="phone" id="exampleFormControlInput2" placeholder="Enter phone number">
                        @error('phone') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput2">Email</label>
                        <input type="email" class="form-control" id="exampleFormControlInput2" wire:model.lazy="email" placeholder="Enter Email">
                        @error('email') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput3"> Mot de passe</label>
                        <input placeholder="Password" id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" wire:model.lazy="password">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                      </div>
                      <div class="form-group">
                        <label for="exampleFormControlInput4">Confirmation mot de passe</label>
                        <input placeholder="Password Confirm" id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" wire:model.lazy="password_confirmation">
                      </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Fermer</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">Sauvegarder</button>
            </div>
        </div>
    </div>
</div>   
@endcan
@can('add_fund_controller')
<div wire:ignore.self class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Dèposer</h5>
                <button wire:click.prevent="cancel()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">le Montant</label>
                        <input type="numeric" class="form-control" id="exampleFormControlInput1" placeholder="Enter pak" wire:model.lazy="amount1">
                        @error('amount1') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary close-btn" data-dismiss="modal">Fermer</button>
                <button type="button" wire:click.prevent="add()" class="btn btn-primary close-modal">Sauvegarder</button>
            </div>
        </div>
    </div>
</div> 
@endcan

    
    @can('mines_fund_controller')
    <div wire:ignore.self class="modal fade" id="minesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Retrait</h5>
                    <button wire:click.prevent="cancel()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
               <div class="modal-body">
    
                    <form>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Le Montant</label>
                            <input type="numeric" class="form-control" id="exampleFormControlInput1" placeholder="Enter pak" wire:model.lazy="amount1">
                            @error('amount1') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary close-btn" data-dismiss="modal">Fermer</button>
                    <button type="button" wire:click.prevent="mines()" class="btn btn-primary close-modal">Sauvegarder</button>
                </div>
            </div>
        </div>
    </div> 
    @endcan


</div>



