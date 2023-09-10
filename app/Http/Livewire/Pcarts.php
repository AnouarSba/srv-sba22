<?php

namespace App\Http\Livewire;
use App\Http\Controllers\EncryptionController;
use App\Http\Controllers\PVTicketController;
use App\Models\Aevent;
use Illuminate\Support\Facades\Http;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\P_Cart as kab;
use App\Models\Cart;
use App\Models\E_ticket;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
class Pcarts extends Component
{
    use WithFileUploads;
    use WithPagination,LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $searchTerm;
    public $photo;
    public $amount1;
    public $cart_ban = false;
    public $cart_type ,$pc_id,$cart_token,$cart_pin,$cart_valid,$cart_id ,$forn_type,$forn_id,$cname,$cphone;
    public $type,$status,$token,$amount,$pak,$user_id;
    public $updateMode = false;
    public $showmode = false;
    public $confirming;
    public $confirming1;
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        if (!$this->showmode) {
            $searchTerm = '%'.$this->searchTerm.'%';
            $data = kab::where('id','like', $searchTerm)->paginate(10);

            return view('livewire.pcarts', [
                'users' => $data ,
            ]);
        }else{
            $searchTerm = '%'.$this->searchTerm.'%';
            $data = Cart::where('id','like', $searchTerm)->where('pc_id',$this->user_id)->paginate(10);

            return view('livewire.pcarts', [
                'carts' => $data ,
            ]);
        }
    }

    private function resetInputFields(){
        $this->photo ='';
        $this->type ='';
        $this->status ='';
        $this->amount ='';
        $this->pak ='';
        $this->confirming ='';
        $this->user_id ='';
        $this->forn_type ='';
        $this->forn_id ='';

    }
    private function resetInputFields1(){

        $this->cart_ban =false;
        $this->cart_pin ='';
        $this->cart_token ='';
        $this->cart_type ='';
        $this->cart_valid =false;
        $this->cart_id ='';
        $this->amount1 ='';
        $this->cname ='';
        $this->cphone ='';

    }
    public function store()
    {
        $en=config('etus.server.code');
        $validatedDate = $this->validate([
            'type' => 'required',
            'photo' => 'required|image|max:1024',
            'pak' => 'required|integer|min:10',
            'amount' => 'nullable|numeric',
        ]);
        
            $user = new kab;
            $user->type=$validatedDate['type'];
            $user->token = 'wait';
            $user->save();
            for ($i=0; $i < $validatedDate['pak'] ; $i++) { 
   
                $tik = new Cart;
                $tik->type = $validatedDate['type'];
                $tik->pc_id = $user->id;
                $tik->pin =rand(1000,9999);
                $tik->token = 'wait';
                $tik->save();
                if (isset($validatedDate['amount'])) {
                    $tik->deposit($validatedDate['amount']);
                }
                
                $data = "$en,ca,$user->id,$tik->id";
                $tik->token = (new EncryptionController)->privEncrypt($data);
                $tik->save();
                if ($i==0) {
                   $star= $tik->id;
                }
                if ($i==$validatedDate['pak'] - 1) {
                   $end= $tik->id;
                }
            }
            $filename = ''.$user->id.'-'. time() .'.'. $this->photo->getClientOriginalExtension();
            $this->photo->storeAs('public/carts',$filename);
            $user->image = $filename;
            $user->start = $star;
            $user->end = $end;
            $data = "$en,pc,$user->id,$user->start,$user->end";
            $user->token = (new EncryptionController)->privEncrypt($data);
            $user->save();

        $this->resetInputFields();
        $this->alert('success', 'pak Added Successfully.', 
        [
            'position' => 'center',
            'timer' => 2000,
            'toast' => false,
           ]
        );
        
        $this->emit('userStore'); 
            // Close model to using to jquery
    }


    public function edit1($id)
    {
        $this->updateMode = true;
        $user = Cart::where('id',$id)->first();
        $this->cart_id = $id;
        $this->cart_type= $user->type;
        $this->cart_token = $user->token;
        $this->cart_ban = $user->ban;
        $this->cart_pin = $user->pin;
        $this->cart_valid = $user->valid;
        $this->cname = $user->cname;
        $this->cphone = $user->cphone;
     }
    public function edit($id)
    {
        $this->updateMode = true;
        $user = kab::where('id',$id)->first();
        $this->user_id = $id;
        $this->type = $user->type;
        $this->status = $user->status;
        $this->forn_type = $user->forn_type;
        $this->forn_id = $user->Forn_id;
     }

     public function cancelshow()
     {
         $this->showmode = false;
         $this->resetInputFields();
     }
    public function cancel()
    {
        $this->showmode = false;
        $this->updateMode = false;
        $this->resetInputFields();
    }
    public function cancel1()
    {
        $this->updateMode = false;
        $this->resetInputFields1();
    }


    public function update1()
    {
        $validatedDate = $this->validate([
            'cart_type' => 'nullable|string',
            'cart_valid' => 'boolean',
            'cname' => 'nullable|string',
            'cphone' => 'nullable|string',
        ]);
        if ($this->cart_id) {
            $user = Cart::find($this->cart_id);
            $user->type = $validatedDate['cart_type'];
            $user->valid = $validatedDate['cart_valid'];

            $user->cname = $validatedDate['cname'];
            $user->cphone = $validatedDate['cphone'];

            $user->save();
            $this->updateMode = false;
            $this->emit('userUpdate');
            $this->alert('success', 'cart Updated Successfully.', 
                [
                    'position' => 'center',
                    'timer' => 2000,
                    'toast' => false,
                   ]
               );
               $this->resetInputFields1();
           
        }
    }


    public function update()
    {
        $validatedDate = $this->validate([
            'type' => 'nullable|string',
            'status' => 'nullable|string',
            'forn_type' => 'nullable|string',
            'forn_id' => 'nullable|integer',
        ]);
        if ($this->user_id) {
            $user = kab::find($this->user_id);
            $user->type= $validatedDate['type'];
            $user->status=$validatedDate['status'];
            $user->forn_type= $validatedDate['forn_type'];
            $user->Forn_id=$validatedDate['forn_id'];
            $user->save();
            $this->updateMode = false;
            $this->emit('userUpdate');
            $this->alert('success', 'pak Updated Successfully.', 
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
            
            $pak=kab::where('id',$id)->first();
            if ($pak->forn_id <> NULL) {

                $this->alert('warning', 'you cant delete a in explotation pak .', 
                [
                    'position' => 'center',
                    'timer' => 2000,
                    'toast' => false,
                   ]
               ); 
            }else {
                if($pak->status <> NULL){
                    $this->alert('warning', 'you cant delete a printed pak .', 
                    [
                        'position' => 'center',
                        'timer' => 2000,
                        'toast' => false,
                       ]
                   ); 
                }else {
                    $pak->delete();
                    $this->alert('success', 'pak deleted successfully .', 
                    [
                        'position' => 'center',
                        'timer' => 2000,
                        'toast' => false,
                       ]
                   );
                }
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
            
            $pak=Cart::where('id',$id)->first();
            if ($pak->valid == false) {

                $this->alert('warning', 'you cant Regeneration this Cart .', 
                [
                    'position' => 'center',
                    'timer' => 2000,
                    'toast' => false,
                   ]
               ); 
               $this->confirming1 = '';
            }else {
                $en=config('etus.server.code');
                $pin =rand(1000,9999);
                $data = "$en,ca,$pak->pc_id,$pak->id,$pin";
                $pak->token = (new EncryptionController)->privEncrypt($data);
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


    public function print($id)
    {
        return redirect()->to("/printcart/$id");
    }

    public function show($id)
    {
        $this->user_id= $id;
        $this->showmode = true;
    }

    public function unban($id)
    {
        if($id){
          $kab = Cart::where('id',$id)->first();
          $kab->ban=false;
          $kab->save();
        }
    }
    public function ban($id)
    {
        if($id){
          $kab =  Cart::where('id',$id)->first();
          $kab->ban=true;
          $kab->save();
        }
    }
    

    public function add(){
        $validatedDate = $this->validate([
            'amount1' => 'required|numeric',
        
        ]);

        if($validatedDate['amount1']){
            $kab =  Cart::where('id',$this->cart_id)->first();
            $kab->deposit($validatedDate['amount1'], ['action' => 'Rechargement du solde par le gérant','admin_id' => auth()->user()->id]);

            $event = new Aevent();
            $event->cat ='solde';
            $event->type ='add_fund';
            $event->user_type = User::class;
            $event->user_id = auth()->user()->id;
            $event->class_type = Cart::class;
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
           $this->resetInputFields1();
          }
    }

    public function mines(){

        $validatedDate = $this->validate([
            'amount1' => 'required|numeric',
        
        ]);

        if($validatedDate['amount1']){
            $kab =  Cart::where('id',$this->cart_id)->first();
            $kab->forceWithdraw($validatedDate['amount1'], ['action' => 'Récupération de solde par le gérant','admin_id' => auth()->user()->id]);
            $event = new Aevent;
            $event->cat ='solde';
            $event->type ='mines_fund';
            $event->user_type = User::class;
            $event->user_id = auth()->user()->id;
            $event->class_type = Cart::class;
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
           $this->resetInputFields1();
          }
    }

    public function pcart($id)
    {
      $all = Cart :: where('pc_id',$id)->count();
      $valid = Cart :: where('pc_id',$id)->where('valid',true)->count();
      $pa = "$valid/$all";
      return $pa;
    }
    
}

