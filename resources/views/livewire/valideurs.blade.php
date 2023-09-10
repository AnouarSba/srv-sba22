<div>
    @if (session()->has('message'))
    <div class="alert alert-success" style="margin-top:30px;">x
      {{ session('message') }}
    </div>
   @endif
    <div class="row gy-2">
        <div class="col-sm-3 gy-2">
            @can('ad_valideurs')
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                Ajouter valideur
            </button>  
            @endcan

        </div>
        <div class="col-sm-3 gy-2">
        </div>
        <div class="col-sm-2 gy-2">
        </div>
        <label class="col-sm-1 gy-2 "></label>
        <div class="col-sm-3 gy-2">
          <input type="text"  class="form-control" placeholder="Chercher" wire:model="searchTerm" />
        </div>
      </div>
    <table class="table table-striped table-sm" style="margin-top:10px;">
        <tr>
            <td>NO</td>
            <td>Nom</td>
            <td>Type</td>
            <td>Statut</td>
            <td>Bus</td>
            <td>ACTION</td>
        </tr>

        @foreach($users as $row)
            <tr>
                <td>{{$row->id}}</td>
                <td>{{$row->device_id}}</td>
                <td>{{$row->type}}</td>
                <td>{{$row->status}}</td>
                <td>{{$this->getlinename($row->bus_id)}}</td>
                <td>
                   
                @can('edit_valideurs')
                <button data-toggle="modal" data-target="#updateModal" wire:click="edit({{ $row->id }})" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></button>
                @endcan
                @can('delete_valideurs')
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
      @can('edit_valideurs')
     <div wire:ignore.self class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
           <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modifier Valideur</h5>
                    <button wire:click.prevent="cancel()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <input type="hidden" wire:model.lazy="user_id">
                            <label for="exampleFormControlInput1">uuid</label>
                            <input type="text" class="form-control" wire:model.lazy="device_id" id="exampleFormControlInput1" placeholder="Enter Name">
                            @error('device_id') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">type du valideur</label>
                            <select wire:model.lazy="type" class="form-control" aria-label="Default select example">
                                <option value="mobile" selected>mobile</option>
                                <option value="fix" >fix</option>
                              </select>
                              @error('type') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">état du bus</label>
                            <select wire:model.lazy="status" class="form-control" aria-label="Default select example">
                                <option value="ca marche" selected>ça marche</option>
                                <option value="en panne">en panne</option>
                                <option value="banned">banned</option>
                              </select>
                              @error('status') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">imei</label>
                            <input type="text" class="form-control" wire:model.lazy="imei" id="exampleFormControlInput1" placeholder="Enter imei">
                            @error('imei') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">bus de valideur</label>
                            <input type="text"  class="form-control" placeholder="Search" wire:model.lazy="searchTerm2" />
                            <select wire:model.lazy="bus_id" class="form-control" aria-label="Default select example">
                                <option value="">--NO BUS--</option>
                                @foreach ($buses as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                              </select>
                              @error('bus_id') <span class="text-danger">{{ $message }}</span>@enderror
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
    @can('ad_valideurs')
    <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter Valideur</h5>
                    <button wire:click.prevent="cancel()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
               <div class="modal-body">
                <form>
                        <div class="form-group">
                            <input type="hidden" wire:model.lazy="user_id">
                            <label for="exampleFormControlInput1">uuid</label>
                            <input type="text" class="form-control" wire:model.lazy="device_id" id="exampleFormControlInput1" placeholder="Enter Name">
                            @error('device_id') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">type du valideur</label>
                            <select wire:model.lazy="type" class="form-control" aria-label="Default select example">
                                <option value="mobile" selected>mobile</option>
                                <option value="fix" >fix</option>
                              </select>
                              @error('type') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">état du bus</label>
                            <select wire:model.lazy="status" class="form-control" aria-label="Default select example">
                                <option value="ca marche" selected>ça marche</option>
                                <option value="en panne">en panne</option>
                                <option value="banned">banned</option>
                              </select>
                              @error('status') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">imei</label>
                            <input type="text" class="form-control" wire:model.lazy="imei" id="exampleFormControlInput1" placeholder="Enter imei">
                            @error('imei') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">bus de valideur</label>
                            <input type="text"  class="form-control" placeholder="Search" wire:model.lazy="searchTerm2" />
                            <select wire:model.lazy="bus_id" class="form-control" aria-label="Default select example">
                                <option value="">--Selectioner le bus de valideur--</option>
                                @foreach ($buses as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                              </select>
                              @error('bus_id') <span class="text-danger">{{ $message }}</span>@enderror
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
</div>

