<div>
    @if (session()->has('message'))
    <div class="alert alert-success" style="margin-top:30px;">x
      {{ session('message') }}
    </div>
   @endif
    <div class="row gy-2">
        <div class="col-sm-3 gy-2">
            @can('ad_lines')
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                Ajouter ligne
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
            <td>Nom</td>
            <td>Arrets</td>
            <td>Nombre De Stations</td>
            <td>ACTION</td>
        </tr>

        @foreach($users as $row)
            <tr>
                <td>{{$row->id}}</td>
                <td>{{$row->name}}</td>
                @if ($row->arrets ==NULL)
                <td>{{'Aucun arrêt de bus'}}</td>
                @else
                <td>
                     @php
                      $php1 = $this->getarretname($row->arrets)  
                     @endphp
                    @foreach ( $php1 as $item)
                        @if ($loop->index == 0 )
                        <p class="text-success"><i class="right fas fa-bus">{{$item->name}} </i> </p> 
                        @else
                        @if ($loop->index == $row->Length-1 )
                        <p  class="text-danger"><i class="right fas fa-bus">{{$item->name}}  </i>   </p> 
                        @else
                        <i class="right fas fa-bus">{{$item->name}}  </i> ,
                        @endif
                        @endif
                    @endforeach
                
                </td>
                @endif
                <td>{{$row->Length}}</td>
                <td>
                   
                @can('edit_lines')
                <button data-toggle="modal" data-target="#updateModal" wire:click="edit({{ $row->id }})" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></button>
                @endcan
                @can('delete_lines')  
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
      @can('edit_lines')
     <div wire:ignore.self class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
           <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modifier Ligne</h5>
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
                            <label for="exampleFormControlInput1">Map Color</label>
                            <select wire:model.lazy="color" class="form-control" aria-label="Default select example">
                                <option value="">--Selectioner Coulour--</option>
                                <option value="0xffff0000" style="background-color: #FF0000">color1</option>
                                <option value="0xff7700ff" style="background-color: #7700FF">color2</option>
                                <option value="0xfffbff00" style="background-color: #FBFF00">color3</option>
                                <option value="0xff00ff44" style="background-color: #00FF44">color4</option>
                                <option value="0xff40e0d0" style="background-color: #40E0D0">color5</option>
                                <option value="0xffc5fe04" style="background-color: #C5FE04">color6</option>
                                <option value="0xff4dffdc" style="background-color: #4DFFDC">color7</option>
                                <option value="0xfff513f5" style="background-color: #F513F5">color8</option>
                                <option value="0xff8e44ad" style="background-color: #8E44AD">color9</option>
                                <option value="0xffb74093" style="background-color: #b74093">color10</option>
                                <option value="0xff008080" style="background-color: #008080">color11</option>
                                <option value="0xffffffff" style="background-color: #ffffff">color12</option>
                                <option value="0xff000000" style="background-color: #000000">color13</option>
                                <option value="0xff0000ff" style="background-color: #0000FF">color14</option>
                                <option value="0xff000080" style="background-color: #000080">color15</option>
                                <option value="0xffccccff" style="background-color: #CCCCFF">color16</option>
                                <option value="0xfff39c12" style="background-color: #F39C12">color17</option>
                                <option value="0xffffbf00" style="background-color: #FFBF00">color18</option>
                                <option value="0xffde3163" style="background-color: #DE3163">color19</option>
                                <option value="0xffdfff00" style="background-color: #dfff00">color20</option>
                              </select>
                              @error('color') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">polyline</label>
                            <input type="text" class="form-control" wire:model.lazy="maps" id="exampleFormControlInput1" >
                            @error('maps') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <hr>
                        <input type="text"  class="form-control" placeholder="Search" wire:model="searchTerm2" />
                        @error('arrets') <span class="text-danger">{{ $message }}</span>@enderror
                        {{implode(",",$arrets)}}
                        <table class="table table-striped table-sm" style="margin-top:10px;">
                            <tr>
                                <td></td>
                                <td>Nom</td>
                                <td>N</td>
                            </tr>
                            @foreach($ar as $arret)
                                <tr>
                                    <td><input wire:model.lazy="arrets" type="checkbox"  value="{{$arret->id}}"></td>
                                    <td>{{$arret->name}}</td>
                                    <td>{{$arret->id}}</td>
                                </tr>
                            @endforeach
                        </table>
                        <div class="row">
                            <div class="col-12 d-flex justify-content-center pt-4" >
                                {{$ar->links()}}
                            </div>
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
    @can('ad_lines')
    <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter Ligne</h5>
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
                            <label for="exampleFormControlInput1">Map Color</label>
                            <select wire:model.lazy="color" class="form-control" aria-label="Default select example">
                                <option value="">--Selectioner Coulour--</option>
                                <option value="0xffff0000" style="background-color: #FF0000">color1</option>
                                <option value="0xff7700ff" style="background-color: #7700FF">color2</option>
                                <option value="0xfffbff00" style="background-color: #FBFF00">color3</option>
                                <option value="0xff00ff44" style="background-color: #00FF44">color4</option>
                                <option value="0xff40e0d0" style="background-color: #40E0D0">color5</option>
                                <option value="0xffc5fe04" style="background-color: #C5FE04">color6</option>
                                <option value="0xff4dffdc" style="background-color: #4DFFDC">color7</option>
                                <option value="0xfff513f5" style="background-color: #F513F5">color8</option>
                                <option value="0xff8e44ad" style="background-color: #8E44AD">color9</option>
                                <option value="0xffb74093" style="background-color: #b74093">color10</option>
                                <option value="0xff008080" style="background-color: #008080">color11</option>
                                <option value="0xffffffff" style="background-color: #ffffff">color12</option>
                                <option value="0xff000000" style="background-color: #000000">color13</option>
                                <option value="0xff0000ff" style="background-color: #0000FF">color14</option>
                                <option value="0xff000080" style="background-color: #000080">color15</option>
                                <option value="0xffccccff" style="background-color: #CCCCFF">color16</option>
                                <option value="0xfff39c12" style="background-color: #F39C12">color17</option>
                                <option value="0xffffbf00" style="background-color: #FFBF00">color18</option>
                                <option value="0xffde3163" style="background-color: #DE3163">color19</option>
                                <option value="0xffdfff00" style="background-color: #dfff00">color20</option>
                              </select>
                              @error('color') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">polyline</label>
                            <input type="text" class="form-control" wire:model.lazy="maps" id="exampleFormControlInput1" >
                            @error('maps') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <hr>
                        <input type="text"  class="form-control" placeholder="Search" wire:model="searchTerm2" />
                        @error('arrets') <span class="text-danger">{{ $message }}</span>@enderror
                        {{implode(",",$arrets)}}
                        <table class="table table-striped table-sm" style="margin-top:10px;">
                            <tr>
                                <td></td>
                                <td>Nom</td>
                                <td>N</td>
                            </tr>
                            @foreach($ar as $arret)
                                <tr>
                                    <td><input wire:model.lazy="arrets" type="checkbox"  value="{{$arret->id}}"></td>
                                    <td>{{$arret->name}}</td>
                                    <td>{{$arret->id}}</td>
                                </tr>
                            @endforeach
                        </table>
                        <div class="row">
                            <div class="col-12 d-flex justify-content-center pt-4" >
                                {{$ar->links()}}
                            </div>
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
</div>



