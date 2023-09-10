<?php

namespace App\Http\Livewire;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
class ShowUsers extends Component
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
        $data = User::where('name','like', $searchTerm)->orwhere('email','like', $searchTerm)->paginate(10);
        return view('livewire.show-users', [
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
         $user= $user = new User;
         $user->name=$validatedDate['name'];
         $user->email=$validatedDate['email'];
         $user->password= Hash::make($validatedDate['password']);
        if ($this->role) {
            $user->assignRole($this->role);
        }else{
            $user->assignRole('default'); 
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
        $user = User::where('id',$id)->first();
        $this->user_id = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->getRoleNames()[0];
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
            $user = User::find($this->user_id);
            $user->name= $validatedDate['name'];
            $user->email=$validatedDate['email'];
            if ($this->password) {
                $user->password= Hash::make($pasval['password']);
            }
            $rr =$user->getRoleNames()[0];
            if ($this->role){
                $user->removeRole($rr);
                $user->assignRole($this->role);
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
            User::where('id',$id)->delete();
            $this->alert('warning', 'Users Deleted Successfully.', 
            [
                'position' => 'center',
                'timer' => 2000,
                'toast' => false,
               ]
           );
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
