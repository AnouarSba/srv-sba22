<?php

namespace App\Http\Livewire;
use App\Http\Controllers\EncryptionController;
use App\Models\Aevent;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Hash;
use App\Models\Client as kab;
use App\Models\Ftoken;
use App\Models\User;
use App\Services\FCMService;
use Livewire\Component;
use Livewire\WithPagination;
class Clients extends Component
{
    use WithPagination,LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $searchTerm;
    public $amount1;
    public  $name ,$email,$user_id,$password,$password_confirmation ,$phone;
    public $updateMode = false;
    public $confirming;
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $searchTerm = '%'.$this->searchTerm.'%';
        $data = kab::where('name','like', $searchTerm)->orwhere('email','like', $searchTerm)->orwhere('phone','like', $searchTerm)->paginate(10);
        return view('livewire.clients', [
            'users' => $data ,
        ]);
    }

    private function resetInputFields(){
        $this->phone ='';
        $this->amount1 ='';
        $this->confirming ='';
        $this->user_id ='';
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function edit($id)
    {
        $this->updateMode = true;
        $user = kab::where('id',$id)->first();
        $this->user_id = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
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
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        ]);
        if ($this->password) {
            $pasval=$this->validate([
                'password'=>'required|min:6|confirmed'
            ]);
        }
        if ($this->user_id) {
            $user = kab::find($this->user_id);
            $user->name= $validatedDate['name'];
            $user->email=$validatedDate['email'];
            $user->phone=$validatedDate['phone'];
            if ($this->password) {
                $user->password= Hash::make($pasval['password']);
            }
            $user->save();
            $this->updateMode = false;
            $this->emit('userUpdate');
            $this->alert('success', 'kabid Updated Successfully.', 
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
            $this->alert('warning', 'Users Deleted Successfully.', 
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
    public function ban(Request $request,$id)
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
            $kab->deposit($validatedDate['amount1'], ['action' => 'Rechargement du solde par le gérant','admin_id' => auth()->user()->id]);
           
            $event = new Aevent();
            $event->cat ='solde';
            $event->type ='add_fund';
            $event->user_type = User::class;
            $event->user_id = auth()->user()->id;
            $event->class_type = Kab::class;
            $event->class_id = $kab->id;
            $event->amount = $validatedDate['amount1'];
            $event->info = 'Rechargement du solde par le gérant';
            $event->save();

            if ($kab->device_id != NUll) {
                $checkIfEmailExist = Ftoken::where('device_id',$kab->device_id)->where('type','cl')->first();
                if ($checkIfEmailExist) {
                    FCMService::send(
                        $checkIfEmailExist->ftoken,
                        [
                            'title' => '+'.$validatedDate['amount1'],
                            'body' => 'تم شحن رصيدك بنجاح',
                        ],
                        [
                             'message' => ''
                        ],
                                        );
                     }
                }
            $this->updateMode = false;
            $this->emit('add');
            $this->alert('success', 'found added  successfully aissa .', 
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
            $kab->forceWithdraw($validatedDate['amount1'], ['action' => 'Récupération de solde par le gérant','admin_id' => auth()->user()->id]);

            $event = new Aevent;
            $event->cat ='solde';
            $event->type ='mines_fund';
            $event->user_type = User::class;
            $event->user_id = auth()->user()->id;
            $event->class_type = Kab::class;
            $event->class_id = $kab->id;
            $event->amount = $validatedDate['amount1'];
            $event->info = 'Récupération de solde par le gérant';
            $event->save();

            if ($kab->device_id != NUll) {
                $checkIfEmailExist = Ftoken::where('device_id',$kab->device_id)->where('type','cl')->first();
                if ($checkIfEmailExist) {
                    FCMService::send(
                        $checkIfEmailExist->ftoken,
                        [
                            'title' => '+'.$validatedDate['amount1'],
                            'body' => 'تم خصم رصيدك',
                        ],
                        [
                             'message' => ''
                        ],
                                        );
                     }
                }
            
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
