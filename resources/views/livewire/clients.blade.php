<div>
    @if (session()->has('message'))
    <div class="alert alert-success" style="margin-top:30px;">x
      {{ session('message') }}
    </div>
   @endif
    <div class="row gy-2">
        <div class="col-sm-3 gy-2">
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
            <td>NAME</td>
            <td>EMAIL</td>
            <td>Phone</td>
            <td>Solde</td>
            <td>ACTION</td>
        </tr>

        @foreach($users as $row)
            <tr @if ($row->ban)
                @class('table-danger')
            @else
              @class('table-success')
            @endif>
                <td>{{$row->id}}</td>
                <td>{{$row->name}}</td>
                <td>{{$row->email}} @if(!$row->active) ( non vérifié)@endif</td>
                <td>{{$row->phone}}</td>
                <td>{{$row->balance}}</td>
                <td>
                @can('edit_clients')
                <button data-toggle="modal" data-target="#updateModal" wire:click="edit({{ $row->id }})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
                @endcan
                @can('delete_clients') 
                @if($confirming===$row->id)
                <button wire:click="delete({{ $row->id }})" class="btn btn-warning btn-sm"><i class="fa fa-check" aria-hidden="true"></i>
                </button>
                <button wire:click="cancelDelete({{ $row->id }})" class="btn btn-success btn-sm">x</button>
                @else
                <button wire:click="confirmDelete({{ $row->id }})" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i>
                </button>
                @endif
                @endcan
                @can('ban_clients') 
                @if($row->ban)
                <button wire:click="unban({{ $row->id }})" class="btn btn-default btn-sm"><i class="fa fa-ban"  style="color: rgb(0, 255, 81)"></i></button>
                @else
                <button wire:click="ban({{ $row->id }})" class="btn btn-default btn-sm"><i class="fa fa-ban"  style="color: red"></i></button>
                @endif
                @endcan
                @can('add_fund_clients')
                <button data-toggle="modal" data-target="#addModal" wire:click="edit({{ $row->id }})" class="btn btn-success btn-sm">+</button>
                @endcan
                @can('mines_fund_clients')
                <button data-toggle="modal" data-target="#minesModal" wire:click="edit({{ $row->id }})" class="btn btn-danger btn-sm">-</button>
                @endcan
                @can('clientstrans')
                <a target="_blank" href="/clientstrans/{{ $row->id }}"   class="btn btn-primary btn-sm">transactions</a>
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

    @can('edit_clients')
    <div wire:ignore.self class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
           <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button wire:click.prevent="cancel()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <input type="hidden" wire:model.lazy="user_id">
                            <label for="exampleFormControlInput1">Name</label>
                            <input type="text" class="form-control" wire:model.lazy="name" id="exampleFormControlInput1" placeholder="Enter Name">
                            @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput2">Phone</label>
                            <input type="tel" class="form-control" wire:model.lazy="phone" id="exampleFormControlInput2" placeholder="Enter phone number">
                            @error('phone') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput2">Email address</label>
                            <input type="email" class="form-control" wire:model.lazy="email" id="exampleFormControlInput2" placeholder="Enter Email">
                            @error('email') <span class="text-danger">{{ $message }}</span>@enderror
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
    @endcan

    @can('add_fund_clients')

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
                            <label for="exampleFormControlInput1">amount</label>
                            <input type="numeric" class="form-control" id="exampleFormControlInput1" placeholder="Enter pak" wire:model.lazy="amount1">
                            @error('amount1') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                    <button type="button" wire:click.prevent="add()" class="btn btn-primary close-modal">Save changes</button>
                </div>
            </div>
        </div>
    </div> 
    @endcan
    @can('mines_fund_clients')
    <div wire:ignore.self class="modal fade" id="minesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
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
                            <label for="exampleFormControlInput1">amount</label>
                            <input type="numeric" class="form-control" id="exampleFormControlInput1" placeholder="Enter pak" wire:model.lazy="amount1">
                            @error('amount1') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                    <button type="button" wire:click.prevent="mines()" class="btn btn-primary close-modal">Save changes</button>
                </div>
            </div>
        </div>
    </div> 
    @endcan
   
</div>



