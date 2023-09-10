<?php

namespace App\Http\Livewire;
use App\Http\Controllers\EncryptionController;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Hash;
use App\Models\Payget as kab;
use Livewire\Component;
use Livewire\WithPagination;
class Payget extends Component
{
    use WithPagination,LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $searchTerm;
    public $amount1;
    public  $name ,$username,$user_id,$password,$password_confirmation ,$ip ;
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
            $query->orWhere('username','like', $searchTerm);
        })->paginate(10);
        return view('livewire.payget', [
            'users' => $data ,
        ]);
    }

    private function resetInputFields(){
        
        $this->ip ='';
        $this->amount1 ='';
        $this->confirming ='';
        $this->user_id ='';
        $this->name = '';
        $this->username = '';
        $this->password = '';
        $this->password_confirmation = '';
    }
    public function store()
    {
        $en = config('etus.server.code');
        $validatedDate = $this->validate([
            'name' => 'required',
            'username' => 'required|unique:paygets',
            'ip' => 'required',
            'password'=>'required|min:6|confirmed'
        ]);
         $user = new kab;
         $user->name=$validatedDate['name'];
         $user->username=$validatedDate['username'];
         $user->ip=$validatedDate['ip'];
         $user->password= Hash::make($validatedDate['password']);

         $user->save();
        $this->resetInputFields();
        $this->alert('success', 'pay gatway Added Successfully.', 
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
        $this->username = $user->username;
        $this->ip = $user->ip;
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
            'username' => 'required',
            'ip' => 'required',
        ]);
        if ($this->password) {
            $pasval=$this->validate([
                'password'=>'required|min:6|confirmed'
            ]);
        }
        if ($this->user_id) {
            $user = kab::find($this->user_id);
            $user->name= $validatedDate['name'];
            $user->username=$validatedDate['username'];
            $user->ip=$validatedDate['ip'];
            if ($this->password) {
                $user->password= Hash::make($pasval['password']);
            }
            $user->save();
            $this->updateMode = false;
            $this->emit('userUpdate');
            $this->alert('success', 'pay gatway Updated Successfully.', 
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
            $this->alert('warning', 'pay getway Deleted Successfully.', 
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
     public function unban($id)
    {
        if($id){
          $kab =  kab::where('id',$id)->first();
          $kab->ban=false;
          $kab->save();
        }
    }
    public function ban($id)
    {
        if($id){
          $kab =  kab::where('id',$id)->first();
          $kab->ban=true;
          $kab->save();
        }
    }

    public function cancelDelete($id)
    {
        $this->confirming = '';
    }

    public function add(){
        $validatedDate = $this->validate([
            'amount1' => 'required|numeric',
        
        ]);

        if($validatedDate['amount1']){
            $kab =  Kab::find($this->user_id);
            $kab->deposit($validatedDate['amount1'], ['action' => ' شحن الرصيد']);
            $this->updateMode = false;
            $this->emit('add');
            $this->alert('success', 'found added  successfully .', 
            [
                'position' => 'center',
                'timer' => 2000,
                'toast' => false,
               ]
           );
           $this->resetInputFields();
          }
    }

    public function mines(){

        $validatedDate = $this->validate([
            'amount1' => 'required|numeric',
        
        ]);

        if($validatedDate['amount1']){
            $kab =  Kab::find($this->user_id);
            $kab->forceWithdraw($validatedDate['amount1'], ['action' => ' استرجاع الرصيد ']);
            $this->updateMode = false;
            $this->emit('mines');
            $this->alert('success', ' refound  successfully .', 
            [
                'position' => 'center',
                'timer' => 2000,
                'toast' => false,
               ]
           );
           $this->resetInputFields();
          }
    }
}

