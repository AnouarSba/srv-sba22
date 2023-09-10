<?php



namespace App\Http\Livewire;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Spatie\Permission\Models\Role as kab;
use Spatie\Permission\Models\Permission;
use Livewire\Component;
use Livewire\WithPagination;
class Role extends Component
{
    use WithPagination,LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $searchTerm;
    public $searchTerm2;
    public  $name  ;
    public $per =[];
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
        $perm = Permission ::where(function($query) use ($searchTerm2){
            $query->where('name','like', $searchTerm2);
        })->paginate(10);
        $data = kab::where(function($query) use ($searchTerm){
            $query->where('name','like', $searchTerm);
        })->paginate(10);
        return view('livewire.role', [
            'users' => $data ,'perm' => $perm ,
        ]);
    }

    private function resetInputFields(){
        
        $this->name ='';
        $this->per =[];
        
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

    public function perm()
    {
        if ($this->user_id) {

            $user = kab::find($this->user_id);

            $user->syncPermissions($this->per);
            $this->updateMode = false;
            $this->emit('perm');
            $this->alert('success', 'تمت العملية  بنجاح', 
                [
                    'position' => 'center',
                    'timer' => 2000,
                    'toast' => false,
                   ]
               );
            $this->resetInputFields();
           
        }

    }
    public function edit($id)
    {
        $this->updateMode = true;
        $user = kab::where('id',$id)->first();
        $this->user_id = $id;
        $this->name = $user->name;
        $this->per = $user->getPermissionNames();
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
            if ($id == 1) {
                $this->alert('warning', 'لا يمكن حذف   هذا الدور', 
                [
                    'position' => 'center',
                    'timer' => 2000,
                    'toast' => false,
                   ]
               );
               $this->confirming = '';
            }else {
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

