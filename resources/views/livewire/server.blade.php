<div>
    @if (session()->has('message'))
    <div class="alert alert-success" style="margin-top:30px;">x
      {{ session('message') }}
    </div>
   @endif
    <div class="row gy-2">
        <div class="col-sm-3 gy-2">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                Add Server
            </button>
        </div>
        <div class="col-sm-3 gy-2">

            <button wire:click="checkconnect()" class="btn btn-primary btn-sm">Check connections</button>
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
            <td>key</td>
            <td>Name</td>
            <td>url</td>
            <td>email</td>
            <td>balance IN</td>
            <td>balance OUT</td>
            <td>ACTION</td>
        </tr>

        @foreach($clients as $row)
            <tr @if ($row->connect)
             class="table-success"
            @else
            class="table-danger" 
            @endif>
                <td>{{$row->id}}</td>
                <td>{{$row->type}}</td>
                <td>{{$row->key}}</td>
                <td>{{$row->name}}</td>
                <td>{{$row->url}}</td>
                <td>{{$row->email}}</td>
                <td>{{$row->balance}}</td>
                <td> @if ($row->connect)
                    {{ $this->getbalance($row->id)}}
                    @endif</td>
                <td>
                    <button data-toggle="modal" data-target="#updateModal" wire:click="edit({{ $row->id }})" class="btn btn-primary btn-sm">Edit</button>
                    @if (!$row->connected)
                    <button data-toggle="modal" data-target="#addModal" wire:click="edit({{ $row->id }})" class="btn btn-success btn-sm">Connect Server</button>
                    @endif

                @if($confirming===$row->id)
                <button wire:click="delete({{ $row->id }})" class="btn btn-warning btn-sm ">sure ? </button>
                <button wire:click="cancelDelete({{ $row->id }})" class="btn btn-success btn-sm"> no </button>
                @else
                <button wire:click="confirmDelete({{ $row->id }})" class="btn btn-danger btn-sm">Delete</button>
                @endif
              
                <button data-toggle="modal" data-target="#addModal2" wire:click="edit({{ $row->id }})" class="btn btn-success btn-sm">+</button>
                
            
                <button data-toggle="modal" data-target="#minesModal" wire:click="edit({{ $row->id }})" class="btn btn-danger btn-sm">-</button>
                <a target="_blank" href="/servertrans/{{ $row->id }}"   class="btn btn-primary btn-sm"><i class="fas fa-exchange-alt" aria-hidden="true"></i></a>
                </td>
            </tr>
        @endforeach
    </table>
    <div class="row">
        <div class="col-12 d-flex justify-content-center pt-4" >
            {{$clients->links()}}
        </div>
      </div>
      <div wire:ignore.self class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
           <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">modifier Serveur</h5>
                    <button wire:click.prevent="cancel()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">type de serveur</label>
                            <select wire:model.lazy="type" class="form-control" aria-label="Default select example">
                                <option value="etus">etus</option>
                                <option value="tramway">tramway</option>
                                <option value="metro">métro</option>
                              </select>
                        </div>
                     
                        <div class="form-group">
                            <label for="exampleFormControlInput1">name</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1"  wire:model.lazy="name">
                            @error('name') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput2">key </label>
                            <input type="text" class="form-control" wire:model.lazy="key" id="exampleFormControlInput2" >
                            @error('key') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="exampleFormControlInput2">url </label>
                            <input type="text" class="form-control" wire:model.lazy="url" id="exampleFormControlInput2" >
                            @error('url') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">email</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1" " wire:model.lazy="email">
                            @error('email') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput3">Password</label>
                            <input placeholder="Password" id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" wire:model.lazy="password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                         </div>
                          <div class="form-group">
                            <label for="exampleFormControlInput4">password confirmation</label>
                            <input placeholder="Password Confirm" id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" wire:model.lazy="password_confirmation">
                          </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" wire:click.prevent="update()" class="btn btn-primary close-modal">Save changes</button>
                </div>
           </div>
        </div>
    </div>


    <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
               <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">type de serveur</label>
                            <select wire:model.lazy="type" class="form-control" aria-label="Default select example">
                                <option value="etus">etus</option>
                                <option value="tramway">tramway</option>
                                <option value="metro">métro</option>
                              </select>
                        </div>
                     
                        <div class="form-group">
                            <label for="exampleFormControlInput1">name</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1"  wire:model.lazy="name">
                            @error('name') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput2">key </label>
                            <input type="text" class="form-control" wire:model.lazy="key" id="exampleFormControlInput2" >
                            @error('key') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="exampleFormControlInput2">url </label>
                            <input type="text" class="form-control" wire:model.lazy="url" id="exampleFormControlInput2" >
                            @error('url') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">email</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1" " wire:model.lazy="email">
                            @error('email') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput3">Password</label>
                            <input placeholder="Password" id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" wire:model.lazy="password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                         </div>
                          <div class="form-group">
                            <label for="exampleFormControlInput4">password confirmation</label>
                            <input placeholder="Password Confirm" id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" wire:model.lazy="password_confirmation">
                          </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                    <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button wire:click.prevent="cancel()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
               <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="exampleFormControlInput2">Link</label>
                            <input type="srvlink" class="form-control" wire:model.lazy="srvlink" id="exampleFormControlInput2" placeholder="Enter srvlink">
                            @error('srvlink') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput2">user email </label>
                            <input type="email" class="form-control" wire:model.lazy="srvuser" id="exampleFormControlInput2" placeholder="Enter username">
                            @error('srvuser') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput3">Password</label>
                            <input placeholder="Password" id="srvpass" type="password" class="form-control @error('srvpass') is-invalid @enderror" name="srvpass" required autocomplete="new-password" wire:model.lazy="srvpass">
                            @error('srvpass')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                    <button type="button" wire:click.prevent="srvconnect()" class="btn btn-primary close-modal">Connect</button>
                </div>
            </div>
        </div>
    </div> 

    <div wire:ignore.self class="modal fade" id="addModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">déposer </h5>
                    <button wire:click.prevent="cancel()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
               <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">le montant</label>
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
                            <label for="exampleFormControlInput1">le montant</label>
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


</div>



