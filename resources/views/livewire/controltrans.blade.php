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
            <td>amount</td>
            <td>date</td>
            <td>meta</td>
            <td>uuid</td>
            <td>client type</td>
            <td>Client id</td>
            <td>Admin</td>
        </tr>

        @foreach($trans as $row)
            <tr @if ($row->amount < 0)
                class="table-danger"
                @else
                class="table-success"
               @endif>
                <td>{{$row->id}}</td>
                <td>{{$row->amount}}</td>
                <td>{{$row->created_at}}</td>
                <td>
                @if ($row->meta)
                    {{$row->meta['action']}}
                @endif
                </td>
                <td>
                    {{$row->uuid}}
                </td>
                <td>
                    @if(isset($row->meta['class']))
                    {{$row->meta['class']}}
                     @endif
                    
                </td>
                <td>
                    @if (isset($row->meta['cl_name']))
                    {{$row->meta['cl_name']}}
                @endif
                    
                </td>
                <td>
                    @if (isset($row->meta['admin_id']))
                        {{ app\Models\User::find($row->meta['admin_id'])->name}}
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
    <div class="row">
        <div class="col-12 d-flex justify-content-center pt-4" >
            {{$trans->links()}}
        </div>
      </div>


</div>



