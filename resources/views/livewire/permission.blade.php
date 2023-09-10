<div>
    @if (session()->has('message'))
    <div class="alert alert-success" style="margin-top:30px;">x
      {{ session('message') }}
    </div>
   @endif
   <hr>
    <div class="row gy-2">
        <div class="col-sm-2 gy-2">
            @can('add_permission')
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                <i class="fa fa-plus" aria-hidden="true"></i>
            </button>
            @endcan

        </div>
        <label class="col-sm-1 gy-2 "></label>
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
            <td>Role</td>
            <td>Action</td>
        </tr>

        @foreach($users as $row)
            <tr>
                <td>{{$row->id}}</td>
                <td>{{$row->name}}</td>

                <td>
                    @can('edit_permission')
                    <button data-toggle="modal" data-target="#updateModal" wire:click="edit({{ $row->id }})" class="btn btn-primary btn-sm">Edit</button>
                    @endcan
                    
                @can('delete_permission')  
                @if($confirming===$row->id)
                <button wire:click="delete({{ $row->id }})" class="btn btn-warning btn-sm">sure ?</button>
                <button wire:click="cancelDelete({{ $row->id }})" class="btn btn-success btn-sm"> no </button>
                @else
                <button wire:click="confirmDelete({{ $row->id }})" class="btn btn-danger btn-sm">Delete</button>
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
      <div wire:ignore.self class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
           <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Roles</h5>
                    <button wire:click.prevent="cancel()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>


                        <div class="form-group">
                            <label for="exampleFormControlInput1">Name</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="role name" wire:model.lazy="name">
                            @error('name') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-dismiss="modal">Annuller</button>
                    <button type="button" wire:click.prevent="update()" class="btn btn-primary close-modal">enregistrer</button>
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
                            <label for="exampleFormControlInput1">Name</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="role name" wire:model.lazy="name">
                            @error('name') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">annuler</button>
                    <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">enregistrer</button>
                </div>
            </div>
        </div>
    </div>

</div>







