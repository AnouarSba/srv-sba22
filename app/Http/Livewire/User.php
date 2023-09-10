<?php

namespace App\Http\Livewire;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use App\Models\User as users;
use Livewire\Component;
use Livewire\WithPagination;
class User extends Component
{
    use WithPagination,LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $searchTerm;
    public  $name ,$email,$user_id,$password,$password_confirmation , $role ;
    public $updateMode = false;
    public $confirming;
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $searchTerm = '%'.$this->searchTerm.'%';
        $roles=  \Spatie\Permission\Models\Role::all();
        $data = users::where('name','like', $searchTerm)->orwhere('email','like', $searchTerm)->paginate(10);
        return view('livewire.user', [
            'users' => $data ,
            'roles'=>$roles
        ]);
    }

    private function resetInputFields(){
        $this->confirming ='';
        $this->user_id ='';
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = '';
    }
    public function store()
    {
        $validatedDate = $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password'=>'required|min:6|confirmed'
        ]);
         $user= $user = new users;
         $user->name=$validatedDate['name'];
         $user->email=$validatedDate['email'];
         $user->password= Hash::make($validatedDate['password']);
        if ($this->role) {
            $user->assignRole($this->role);
        }
        $user->save();
        $this->resetInputFields();
        $this->alert('success', 'Users Added Successfully.', 
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
        $user = users::where('id',$id)->first();
        $this->user_id = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->getRoleNames()[0] ?? '';
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
            'email' => 'required|email',
        ]);
        if ($this->password) {
            $pasval=$this->validate([
                'password'=>'required|min:6|confirmed'
            ]);
        }
        if ($this->user_id) {
            $user = users::find($this->user_id);
            $user->name= $validatedDate['name'];
            $user->email=$validatedDate['email'];
            if ($this->password) {
                $user->password= Hash::make($pasval['password']);
            }
            if ($this->role == NULL){
                $user->removeRole($user->getRoleNames()[0]);
            }
            
                if ($this->role){
                    $user->syncRoles($this->role); 
                }
            $user->save();
            $this->updateMode = false;
            $this->emit('userUpdate');
            $this->alert('success', 'Users Updated Successfully.', 
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
            $this->alert('warning', 'لا يمكن حذف   هذا المستعمل', 
            [
                'position' => 'center',
                'timer' => 2000,
                'toast' => false,
               ]
           );
           $this->confirming = '';
        }else {
            users::where('id',$id)->delete();
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
    public function cancelDelete($id)
    {
        $this->confirming = '';
    }

    public function confirmDelete($id)
    {
        $this->confirming = $id;
    }

}
