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
</div>





