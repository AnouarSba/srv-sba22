<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Hash;
use App\Models\Arret as kab;
use Livewire\Component;
use Livewire\WithPagination;
class Arrets extends Component
{
    use WithPagination,LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $searchTerm;
    public $arrets =[];
    public  $name,$lat,$long,$user_id;
    public $updateMode = false;
    public $confirming;
  
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $searchTerm = '%'.$this->searchTerm.'%';
        $data = kab::where('name','like', $searchTerm)->paginate(10);
        return view('livewire.arrets', [
            'users' => $data ,
        ]);
    }

    private function resetInputFields(){

        $this->confirming ='';
        $this->lat ='';
        $this->long ='';
        
        $this->user_id ='';
        $this->name = '';

    }
    public function store()
    {
        $validatedDate = $this->validate([
            'name' => 'required',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            
        ]);
         $user = new kab;
         $user->name=$validatedDate['name'];
         $user->lat=$validatedDate['lat'];
         $user->long=$validatedDate['long'];
      
         $user->save();
        $this->resetInputFields();
        $this->alert('success', 'arrets Added Successfully.', 
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
        $this->name = $user->name;
        $this->lat = $user->lat;
        $this->long = $user->long;
   
     }
    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }
    public function update()
    {
        $validatedDate = $this->validate([
            'name' => 'required',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            
        ]);
        if ($this->user_id) {
            $user = kab::find($this->user_id);
            $user->name= $validatedDate['name'];
            $user->lat=$validatedDate['lat'];
            $user->long=$validatedDate['long'];
          
            $user->save();
            $this->updateMode = false;
            $this->emit('userUpdate');
            $this->alert('success', 'Ligne Updated Successfully.', 
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
            $this->alert('warning', 'vendeur Deleted Successfully.', 
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
}

