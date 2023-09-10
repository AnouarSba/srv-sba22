<?php

namespace App\Http\Livewire;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Ligne as kab;
use App\Models\Arret;
use Livewire\Component;
use Livewire\WithPagination;
class Lines extends Component
{
    use WithPagination,LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $searchTerm;
    public $searchTerm2;
    public $arrets =[];
    public  $name,$maps,$color ,$user_id;
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
        $data = kab::where('name','like', $searchTerm)->paginate(10);
        $data2 = Arret::where('name','like', $searchTerm2)->paginate(5);
        return view('livewire.lines', [
            'users' => $data ,
            'ar' => $data2 ,
        ]);
    }

    private function resetInputFields(){
        $this->arrets =[];
        $this->confirming ='';
        $this->user_id ='';
        $this->name = '';
        $this->maps = '';
        $this->color = '';

    }
    public function store()
    {
        $validatedDate = $this->validate([
            'name' => 'required',
            'arrets' => 'required|array|min:2',
            'maps' => 'nullable|string',
            'color' => 'nullable|string',
        ]);
         $user = new kab;
         $user->name=$validatedDate['name'];
         $user->arrets=$validatedDate['arrets'];
         $user->maps= $validatedDate['maps'];
         $user->color= $validatedDate['color'];
         $user->Length=sizeof($validatedDate['arrets']);
         $user->save();
        $this->resetInputFields();
        $this->alert('success', 'ligne Added Successfully.', 
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
        $this->arrets = $user->arrets;
        $this->color = $user->color;
        $this->maps = $user->maps;
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
            'arrets'    => 'required|array|min:2',
            'maps' => 'nullable|string',
            'color' => 'nullable|string',
        ]);
        if ($this->user_id) {
            $user = kab::find($this->user_id);
            $user->name= $validatedDate['name'];
            $user->arrets=$validatedDate['arrets'];
            $user->maps= $validatedDate['maps'];
            $user->color=$validatedDate['color'];
            $user->Length=sizeof($validatedDate['arrets']);
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
    public function getarretname($id)
    {
        $ids_ordered = implode(',', $id);
        return  Arret::whereIn('id', $id)->orderByRaw("FIELD(id, $ids_ordered)")->get('name');
    }
}

