<div>
    @if (session()->has('message'))
    <div class="alert alert-success" style="margin-top:30px;">x
      {{ session('message') }}
    </div>
   @endif
    <div class="row gy-2">
        <div class="col-sm-3 gy-2">
            @can('ad_buses')
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                Ajouter un bus
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
            <td>NO</td>
            <td>NOM</td>
            <td>Statut</td>
            <td>Ligne</td>
            <td>ACTION</td>
        </tr>

        @foreach($users as $row)
            <tr>
                <td>{{$row->id}}</td>
                <td>{{$row->name}}</td>
                <td>{{$row->status}}</td>
                <td>{{$this->getlinename($row->ligne_id)}}</td>
                <td>
                @can('edit_buses')
                <button data-toggle="modal" data-target="#updateModal" wire:click="edit({{ $row->id }})" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></button>
                @endcan
                @can('delete_buses')
                @if($confirming===$row->id)
                <button wire:click="delete({{ $row->id }})" class="btn btn-warning btn-sm"><i class="fa fa-check" aria-hidden="true"></i></button>
                <button wire:click="cancelDelete({{ $row->id }})" class="btn btn-success btn-sm">X</button>
                @else
                <button wire:click="confirmDelete({{ $row->id }})" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></button>
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

      @can('edit_buses')
      <div wire:ignore.self class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
           <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modifier Bus</h5>
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
                            <label for="exampleFormControlInput1">type du bus</label>
                            <select wire:model.lazy="type" class="form-control" aria-label="Default select example">
                                <option value="bus" selected>Bus</option>
                                <option value="mini bus" > Mini Bus</option>
                              </select>
                              @error('type') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">état du bus</label>
                            <select wire:model.lazy="status" class="form-control" aria-label="Default select example">
                                <option value="ca marche" selected>ça marche</option>
                                <option value="en panne">en panne</option>
                              </select>
                              @error('status') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">GPS imei</label>
                            <input type="text" class="form-control" wire:model.lazy="imei" id="exampleFormControlInput1" placeholder="Enter long">
                            @error('imei') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">ligne de bus</label>
                            <input type="text"  class="form-control" placeholder="Search" wire:model="searchTerm2" />
                            <select wire:model.lazy="ligne_id" class="form-control" aria-label="Default select example">
                                
                                @foreach ($lignes as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                              </select>
                              @error('ligne_id') <span class="text-danger">{{ $message }}</span>@enderror
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
      @can('ad_buses')
      <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter Bus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true close-btn">×</span>
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
                        <label for="exampleFormControlInput1">type du bus</label>
                        <select wire:model.lazy="type" class="form-control" aria-label="Default select example">
                            <option value="bus" selected>Bus</option>
                            <option value="mini bus" > Mini Bus</option>
                          </select>
                          @error('type') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">état du bus</label>
                        <select wire:model.lazy="status" class="form-control" aria-label="Default select example">
                            <option value="ca marche" selected>ça marche</option>
                            <option value="en panne">en panne</option>
                          </select>
                          @error('status') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">GPS imei</label>
                        <input type="text" class="form-control" wire:model.lazy="imei" id="exampleFormControlInput1" placeholder="Enter long">
                        @error('imei') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">ligne de bus</label>
                        <input type="text"  class="form-control" placeholder="Search" wire:model="searchTerm2" />
                        <select wire:model.lazy="ligne_id" class="form-control" aria-label="Default select example">
                            <option value="">SELECT LIGNE</option>
                            @foreach ($lignes as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                          </select>
                          @error('ligne_id') <span class="text-danger">{{ $message }}</span>@enderror
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



</div>

