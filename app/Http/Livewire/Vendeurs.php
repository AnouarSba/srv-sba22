<?php

namespace App\Http\Livewire;
use App\Http\Controllers\EncryptionController;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Hash;
use App\Models\Vendeur as kab;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
class Vendeurs extends Component
{
    use WithFileUploads;
    use WithPagination,LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $searchTerm;
    public $amount1;
    public $photo;
    public $photo1;
    public $type ="Interne";
    public  $name ,$email,$user_id,$password,$password_confirmation ,$phone ;
    public $updateMode = false;
    public $confirming;
    public $confirming1;
    public $type1="";
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        if ($this->type1 == "") {
            $type = '%'.$this->type1.'%';
        } else {
            $type = $this->type1;
        }
        $searchTerm = '%'.$this->searchTerm.'%';
        $data = kab::where('type','like', $type)->where(function($query) use ($searchTerm){
            $query->where('name','like', $searchTerm);
            $query->orWhere('email','like', $searchTerm);
            $query->orWhere('phone','like', $searchTerm);
        })->paginate(10);
        return view('livewire.vendeurs', [
            'users' => $data ,
        ]);
    }

    private function resetInputFields(){
        $this->photo ='';
        $this->photo1 ='';
        $this->type='';
        $this->phone ='';
        $this->amount1 ='';
        $this->confirming ='';
        $this->user_id ='';
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
    }
    public function store()
    {
        $en = config('etus.server.code');
        $validatedDate = $this->validate([
            'name' => 'required',
            'type' => 'required',
            'email' => 'required|email|unique:vendeurs',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
            'password'=>'required|min:6|confirmed'
        ]);
         $user = new kab;
         $user->name=$validatedDate['name'];
         $user->email=$validatedDate['email'];
         $user->phone=$validatedDate['phone'];
         $user->type=$validatedDate['type'];
         $user->password= Hash::make($validatedDate['password']);

            
            $data = "$en,ven,$user->email";
            $user->authtoken = (new EncryptionController)->privEncrypt($data);

         $user->save();
        $this->resetInputFields();
        $this->alert('success', 'vendeur Added Successfully.', 
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
        $this->type = $user->type;
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
            'type' => 'required|string|min:3',
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
            $user->type=$validatedDate['type'];
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
            $this->alert('success', 'vendeur Updated Successfully.', 
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
            $kab->forceWithdraw($validatedDate['amount1'], ['action' => 'Récupération de solde par le gérant','admin_id' => auth()->user()->id]);
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
                $en=env('SER_KEY');
                $rand=rand(0,99);
                $data = "$en,ven,$pak->email,$rand";
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

