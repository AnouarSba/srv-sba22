<?php

namespace App\Http\Livewire;

use App\Http\Controllers\EncryptionController;
use App\Models\Aevent;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Hash;
use App\Models\Control as kab;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
class Controle extends Component
{
    use WithFileUploads;
    use WithPagination,LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $searchTerm;
    public $amount1;
    public $photo;
    public $photo1;
    public  $name ,$email,$user_id,$password,$password_confirmation ,$phone;
    public $updateMode = false;
    public $confirming;
    public $confirming1;
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $searchTerm = '%'.$this->searchTerm.'%';
        $data = kab::where('name','like', $searchTerm)->orwhere('email','like', $searchTerm)->orwhere('phone','like', $searchTerm)->paginate(10);
        return view('livewire.controle', [
            'users' => $data ,
        ]);
    }

    private function resetInputFields(){
        $this->photo ='';
        $this->photo1 ='';
        $this->phone ='';
        $this->confirming ='';
        $this->user_id ='';
        $this->amount1 ='';
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
    }
    public function store()
    {
        $validatedDate = $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:controls',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'password'=>'required|min:6|confirmed'
        ]);
         $user= $user = new kab;
         $user->name=$validatedDate['name'];
         $user->email=$validatedDate['email'];
         $user->phone=$validatedDate['phone'];
         $user->password= Hash::make($validatedDate['password']);
         $en=config('etus.server.code');
         $rand=rand(0,99);
         $data = "$en,co,$user->id,$rand";
         $user->authtoken = (new EncryptionController)->privEncrypt($data);
         $user->save();
        $this->resetInputFields();
        $this->alert('success', 'kabid Added Successfully.', 
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
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->photo1 = $user->photo;
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
        if ($this->photo) {
            $photoval=$this->validate([
                'photo' => 'required|image|max:1024',
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
            if ($this->photo) {

                if($user->photo != NULL){
                    $image_path = public_path().'/storage/photos/'.$user->photo;
                    unlink($image_path);
                }

                $filename = ''.$user->id.'-'. time() .'.'. $this->photo->getClientOriginalExtension();
                $this->photo->storeAs('public/photos',$filename);
                $user->photo = $filename;
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
            $kab->deposit($validatedDate['amount1'], ['action' => 'Rechargement du solde par le gérant','admin_id' => auth()->user()->id]);

            $event = new Aevent;
            $event->cat ='solde';
            $event->type ='add_fund';
            $event->user_type = User::class;
            $event->user_id = auth()->user()->id;
            $event->class_type = Kab::class;
            $event->class_id = $kab->id;
            $event->amount = $validatedDate['amount1'];
            $event->info = 'Rechargement du solde par le gérant';
            $event->save();

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
            $kab->forceWithdraw($validatedDate['amount1'], ['action' => ' Récupération de solde par le gérant','admin_id' => auth()->user()->id]);
            $event = new Aevent();
            $event->cat ='solde';
            $event->type ='mines_fund';
            $event->user_type = User::class;
            $event->user_id = auth()->user()->id;
            $event->class_type = Kab::class;
            $event->class_id = $kab->id;
            $event->amount = $validatedDate['amount1'];
            $event->info = 'Récupération de solde par le gérant';
            $event->save();
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

    public function printcart($id){
        return redirect()->to("/printauthcartco/$id");
    }
    public function changecart($id){return redirect()->to("/changeauthcartco/$id");}

    public function cancelGen($id)
    {
        $this->confirming1 = '';
    }

    public function confirmGen($id)
    {
        $this->confirming1 = $id;
    }

    public function generation($id)
    {
        if($id){
            
            $pak=Kab::where('id',$id)->first();
            if ($pak->ban) {
                $this->alert('warning', 'you cant Regeneration this Receveur .', 
                [
                    'position' => 'center',
                    'timer' => 2000,
                    'toast' => false,
                   ]
               ); 
               $this->confirming1 = '';
            }else {
                $en=config('etus.server.code');
                $rand=rand(0,99);
                $data = "$en,co,$pak->id,$rand";
                $pak->authtoken = (new EncryptionController)->privEncrypt($data);
                $pak->save();

                $this->alert('success', ' successful Regeneration .', 
                [
                    'position' => 'center',
                    'timer' => 2000,
                    'toast' => false,
                ]);
                $this->confirming1 = '';
            }
        }
    }
}
