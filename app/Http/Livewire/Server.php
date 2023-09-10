<?php

namespace App\Http\Livewire;
use App\Http\Controllers\EncryptionController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Models\Server as cli ;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
class Server extends Component
{
    use WithPagination,LivewireAlert,WithFileUploads;
    protected $paginationTheme = 'bootstrap';
    public $searchTerm;
    public $amount1;
    public $srvuser,$srvpass,$srvlink;
    public $name ,$user_id,$type,$key ,$email,$password,$password_confirmation,$url  ;
    public $updateMode = false;
    public $confirming;
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $searchTerm = '%'.$this->searchTerm.'%';
        $data = cli::where('name','like', $searchTerm)->orwhere('key','like', $searchTerm)->paginate(10);
        return view('livewire.server', [
            'clients' => $data ,
        ]);
    }

    private function resetInputFields(){
        $this->amount1 ='';
        $this->srvlink ='';
        $this->srvpass ='';
        $this->srvuser ='';
        $this->confirming ='';
        $this->user_id ='';
        $this->name = '';
        $this->key = '';
        $this->type = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->url = '';
    }
    public function store()
    {
        $validatedDate = $this->validate([
            'email' => 'required|unique:servers,email',
            'key' => 'required|string',
            'name' => 'required|string',
            'password'=>'required|min:6|confirmed',
            'url' => 'required|string',
            'type' => 'required|string'
        ]);
         $user= $user = new cli;
         $user->name=$validatedDate['name'];
         $user->key=$validatedDate['key'];
         $user->email=$validatedDate['email'];
         $user->type=$validatedDate['type'];
         $user->password=  (new EncryptionController)->privEncrypt($validatedDate['password']);
         $user->url= $validatedDate['url'];
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
        $user = cli::where('id',$id)->first();
        $this->user_id = $id;
        $this->name = $user->name;
        $this->key = $user->key;
        $this->url = $user->url;
        $this->email = $user->email;
        $this->type = $user->type;
     }
    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }
    public function update()
    {
        $validatedDate = $this->validate([
            'email' => 'required|unique:servers,email',
            'key' => 'required|string',
            'name' => 'required|string',
            'url' => 'required|string',
            'type' => 'required|string'
        ]);
        if ($this->password) {
            $pasval=$this->validate([
                'password'=>'required|min:6|confirmed'
            ]);
        }

        if ($this->user_id) {
            $user = cli::find($this->user_id);
            $user->name=$validatedDate['name'];
            $user->key=$validatedDate['key'];
            $user->email=$validatedDate['email'];
            $user->type=$validatedDate['type'];
            
            $user->url= $validatedDate['url'];
            if ($this->password) {
                $user->password=  (new EncryptionController)->privEncrypt($validatedDate['password']);
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
            cli::where('id',$id)->delete();
            $this->alert('warning', 'Client Deleted Successfully.', 
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

   public function checkconnect(){
    $cli = cli::whereNotNull('token')->get();
    foreach ($cli as $key) {
        $link = 'https://'.$key->url.'/api/server_checktoken';
        $response = Http::withToken($key->token)->get($link);
        if ($response->status() == 202) {
            $key->connect = true;
            $key->save();
        }else {
            $key->connect = false;
            $key->save();
        }
    }
   }

   public function getbalance($id){
    $cli = cli::Find($id);
    if ($cli->connect) {
        $link = 'https://'.$cli->url.'/api/server_getbalance';
        $response = Http::withToken($cli->token)->get($link);
        
        if ($response->status() == 202) {
            $res = json_decode($response->body());
            return $res->balance;
        }else {
           return 'connection error';
        }

    } else {
        return 'desconected';
    }
    

   }
    public function srvconnect(){

        $validatedDate = $this->validate([
            'srvlink' => 'required',
            'srvuser' => 'required|email',
            'srvpass'=>'required|min:6'
        ]);
        
         $link = 'https://'.$validatedDate['srvlink'].'/api/server_login';
         $data=[
            'srvuser' => $validatedDate['srvuser'],
            'srvpass'=>$validatedDate['srvpass']
          ];
          $response = Http::get($link,$data);
          $res = json_decode($response->body());
           if ($response->status() == 200) {
            $cli = cli::where('id',$this->user_id)->first();
            $cli->url = $validatedDate['srvlink'];
            $cli->email2 = $validatedDate['srvuser'];
            $cli->password2 =(new EncryptionController)->privEncrypt($validatedDate['srvpass']);
            $cli->token = $res->token;
            $cli->connect = true;
            $cli->save();
            $this->resetInputFields();
            $this->alert('success', 'server connect  Successfully.', 
            [
                'position' => 'center',
                'timer' => 2000,
                'toast' => false,
               ]
           );
            $this->emit('add');
           } else {
            $this->resetInputFields();
            $this->alert('warning', $res->message, 
            [
                'position' => 'center',
                'timer' => 2000,
                'toast' => false,
               ]
           );
            $this->emit('add');
           }
           
    }

    public function add(){
        $validatedDate = $this->validate([
            'amount1' => 'required|numeric',
        ]);

        if($validatedDate['amount1']){
            $kab =  cli::find($this->user_id);
            $kab->deposit($validatedDate['amount1'], ['action' => 'Rechargement du solde par le gérant','admin_id' => auth()->user()->id]);
            $this->updateMode = false;
            $this->emit('add2');
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
            $kab =  cli::find($this->user_id);
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
    
}
