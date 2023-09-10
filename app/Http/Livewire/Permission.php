<?php



namespace App\Http\Livewire;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Spatie\Permission\Models\Role ;
use Spatie\Permission\Models\Permission as kab;
use Livewire\Component;
use Livewire\WithPagination;
class Permission extends Component
{
    use WithPagination,LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $searchTerm;
    public  $name ;
    public $updateMode = false;
    public $confirming;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $searchTerm = '%'.$this->searchTerm.'%';
        $data = kab::where(function($query) use ($searchTerm){
            $query->where('name','like', $searchTerm);
        })->paginate(10);
        return view('livewire.permission', [
            'users' => $data ,
        ]);
    }

    private function resetInputFields(){
        
        $this->name ='';
    }
    public function store()
    {
        $validatedDate = $this->validate([
            'name' => 'required|string|unique:roles,name',
        ]);
       
         $user = new kab;
 
         $user->name=$validatedDate['name'];

         $user->save();
        $this->resetInputFields();
        $this->alert('success', 'تمت الاضافة بنجاح', 
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

     }
    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }
    public function update()
    {
        $validatedDate = $this->validate([
            'name' => 'required|string',

        ]);
        if ($this->user_id) {

            $user = kab::find($this->user_id);

            if ($this->name != $user->name) {
                $sn = $this->validate([
                    'name' => 'required|string|unique:roles,name',
                ]);
                $user->name=$sn['name'];
                $user->save();
            }
           
            $this->updateMode = false;
            $this->emit('userUpdate');
            $this->alert('success', 'تم التعديل بنجاح', 
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
            $this->alert('warning', 'تم الحذف بنجاح', 
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

