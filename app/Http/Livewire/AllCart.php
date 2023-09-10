<?php

namespace App\Http\Livewire;
use App\Http\Controllers\EncryptionController;
use App\Models\Aevent;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Cart;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
class AllCart extends Component
{
    use WithFileUploads;
    use WithPagination,LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $searchTerm;
    public $amount1;
    public $cart_ban = false;
    public $cart_type ,$pc_id,$cart_token,$cart_pin,$cart_valid,$cart_id ,$cname,$cphone;
    public $type,$status,$token,$pak,$user_id;
    public $updateMode = false;
    public $confirming1;
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
            $searchTerm = '%'.$this->searchTerm.'%';
            $data = Cart::where('id','like', $searchTerm)->orwhere('cname','like', $searchTerm)->orwhere('cphone','like', $searchTerm)->paginate(10);
            return view('livewire.all-cart', [
                'carts' => $data ,
            ]);
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

    
}

