<?php

namespace App\Http\Livewire;

use App\Models\Bus;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Hash;
use App\Models\Valideur as kab;
use Livewire\Component;
use Livewire\WithPagination;

class Valideurs extends Component
{
    use WithPagination,LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $searchTerm;
    public $searchTerm2;
    public $device_id;
    public $type ='mobile';
    public $status ='ca marche';
    public  $imei,$bus_id,$user_id;
    public $updateMode = false;
    public $confirming;
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $searchTerm = '%'.$this->searchTerm.'%';
        $searchTerm2 = '%'.$this->searchTerm2.'%';
        $data = kab::where('device_id','like', $searchTerm)->paginate(10);
        $data2 = Bus::where('name','like', $searchTerm2)->get();
        return view('livewire.valideurs', [
            'users' => $data ,
            'buses' => $data2 ,
        ]);
    }

    private function resetInputFields(){

        $this->confirming ='';
        $this->type ='mobile';
        $this->bus_id =NULL;
        $this->status ='ca marche';
        $this->imei ='';
        $this->user_id ='';
        $this->device_id = '';
        $this->searchTerm2 = '';
    }
    public function store()
    {
        $validatedDate = $this->validate([
            'device_id' => 'required',
            'bus_id' => 'nullable|integer',
            'type' => 'required',
            'status' => 'required',
            'imei' => 'nullable|string',
        ]);
         $user = new kab;
         $user->device_id=$validatedDate['device_id'];
         $user->bus_id=$validatedDate['bus_id'];
         $user->type=$validatedDate['type'];
         $user->status=$validatedDate['status'];
         $user->imei=$validatedDate['imei'];
         $user->save();
        $this->resetInputFields();
        $this->alert('success', 'device Added Successfully.', 
        [
            'position' => 'center',
            'timer' => 2000,
            'toast' => false,
           ]
        );
        
        $this->emit('userStore'); // Close model to using to jquery
    }
    public function edit($id)
    {
        $this->updateMode = true;
        $user = kab::where('id',$id)->first();
        $this->user_id = $id;
        $this->device_id = $user->device_id;
        $this->type = $user->type;
        $this->status = $user->status;
        $this->imei = $user->imei;
        $this->bus_id = $user->bus_id;
     }
    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }
    public function update()
    {
        $validatedDate = $this->validate([
            'device_id' => 'required',
            'bus_id' => 'nullable|integer',
            'type' => 'required',
            'status' => 'required',
            'imei' => 'nullable|string',
        ]);
        if ($this->user_id) {
            $user = kab::find($this->user_id);
            $user->device_id=$validatedDate['device_id'];
            if ($this->bus_id == '') {
                $user->bus_id=NULL;
             }else{
                $user->bus_id=$validatedDate['bus_id'];
             }
            $user->type=$validatedDate['type'];
            $user->status=$validatedDate['status'];
            $user->imei=$validatedDate['imei'];
            $user->save();
            $this->updateMode = false;
            $this->emit('userUpdate');
            $this->alert('success', 'device Updated Successfully.', 
                [
                    'position' => 'center',
                    'timer' => 2000,
                    'toast' => false,
                   ]
               );
            $this->resetInputFields();
           
        }
    }
    public function delete($id)
    {
        if($id){
            kab::where('id',$id)->delete();
            $this->alert('warning', 'device Deleted Successfully.', 
            [
                'position' => 'center',
                'timer' => 2000,
                'toast' => false,
               ]
           );
        }
    }
    public function confirmDelete($id)
    {
        $this->confirming = $id;
    }

    public function cancelDelete($id)
    {
        $this->confirming = '';
    }
    public function getlinename($id)
    {
        if ($id == NULL) {
            return 'No Bus Selected'; 
        }
        $linename = Bus::where('id', $id)->get('name');
       if (!$linename->isEmpty()) {
        return $linename = $linename[0]['name'];
       }else {
        return 'No Bus Found';
       }
       
    }
}

