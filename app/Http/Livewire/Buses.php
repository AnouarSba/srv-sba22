<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Hash;
use App\Models\Bus as kab;
use App\Models\Ligne;
use Livewire\Component;
use Livewire\WithPagination;

class Buses extends Component
{
    use WithPagination,LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $searchTerm;
    public $searchTerm2;
    public $arrets =[];
    public $type ='bus';
    public $status ='ca marche';
    public  $name,$imei,$ligne_id,$user_id;
    public $updateMode = false;
    public $confirming;
    public $mahata = false;
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $searchTerm = '%'.$this->searchTerm.'%';
        $searchTerm2 = '%'.$this->searchTerm2.'%';
        $data = kab::with('ligne')->where('name','like', $searchTerm)->paginate(10);
        $data2 = Ligne::where('name','like', $searchTerm2)->get();
        return view('livewire.buses', [
            'users' => $data ,
            'lignes' => $data2 ,
        ]);
    }

    private function resetInputFields(){

        $this->confirming ='';
        $this->type ='bus';
        $this->ligne_id =NULL;
        $this->status ='ca marche';
        $this->imei ='';
        $this->user_id ='';
        $this->name = '';

    }
    public function store()
    {
        $validatedDate = $this->validate([
            'name' => 'required',
            'ligne_id' => 'nullable|integer',
            'type' => 'required',
            'status' => 'required',
            'imei' => 'nullable|string',
        ]);
         $user = new kab;
         $user->name=$validatedDate['name'];
         $user->ligne_id=$validatedDate['ligne_id'];
         $user->type=$validatedDate['type'];
         $user->status=$validatedDate['status'];
         $user->imei=$validatedDate['imei'];
         $user->save();
        $this->resetInputFields();
        $this->alert('success', 'bus Added Successfully.', 
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
        $this->type = $user->type;
        $this->status = $user->status;
        $this->imei = $user->imei;
        $this->ligne_id = $user->ligne_id;
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
            'ligne_id' => 'nullable|integer',
            'type' => 'required',
            'status' => 'required',
            'imei' => 'nullable|string',
        ]);
        if ($this->user_id) {
            $user = kab::find($this->user_id);
            $user->name=$validatedDate['name'];
            $user->ligne_id=$validatedDate['ligne_id'];
            $user->type=$validatedDate['type'];
            $user->status=$validatedDate['status'];
            $user->imei=$validatedDate['imei'];
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
    public function getlinename($id)
    {
        $linename = Ligne::where('id', $id)->get('name');
       if (!$linename->isEmpty()) {
        return $linename = $linename[0]['name'];
       }else {
        return 'no line selected';
       }
       
    }
}

