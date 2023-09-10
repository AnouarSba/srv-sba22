<?php

namespace App\Http\Livewire;
use App\Http\Controllers\EncryptionController;
use App\Http\Controllers\PVTicketController;
use Illuminate\Support\Facades\Http;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Ps_cart as kab;
use App\Models\Sold_cart;
use Livewire\Component;
use Livewire\WithPagination;
class Soldecarts extends Component
{
    use WithPagination,LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $searchTerm;
    public  $sp,$ep,$x,$y;
    public $type;
    public $numpak =10;
    public $pak = 50;
    public $status,$token,$amount,$forn_type,$forn_id,$printed,$user_id;
    public $updateMode = false;
    public $confirming;
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $searchTerm = '%'.$this->searchTerm.'%';
        $data = kab::where('id','like', $searchTerm)->paginate(10);
        return view('livewire.soldecarts', [
            'users' => $data ,
        ]);
    }

    private function resetInputFields(){

        $this->sp ='';
        $this->ep ='';
        $this->type ='';
        $this->status ='';
        $this->numpak ='';
        $this->amount ='';
        $this->pak ='';
        $this->forn_type ='';
        $this->forn_id ='';
        $this->printed ='';
        $this->confirming ='';
        $this->user_id ='';

    }
    public function store()
    {
        $en=config('etus.server.code');
        $validatedDate = $this->validate([
            'type' => 'required',
            'numpak' => 'required|numeric|max:20',
            'pak' => 'required|numeric|max:100',
            'amount' => 'required|numeric',
        ]);
       
        for ($j=0; $j<$validatedDate['numpak'] ; $j++) { 

         $user = new kab;
         $user->type=$validatedDate['type'];
         $sold=$validatedDate['amount'];
         $user->save();

         for ($i=0; $i < $validatedDate['pak'] ; $i++) { 

             $tik = new Sold_cart;
             $tik->type = $validatedDate['type'];
             $tik->ps_id = $user->id;
             $tik->sold=$sold;
             $tik->save();
             $data = "$en,sc,$user->id,$tik->id,$tik->sold";
             $tik->token = (new EncryptionController)->privEncrypt($data);
             $tik->save();
             if ($i==0) {
                $star= $tik->id;
             }
             if ($i==$validatedDate['pak'] - 1) {
                $end= $tik->id;
             }
         }
         $user->start = $star;
         $user->end = $end;
         $data = "$en,psc,$user->id,$user->start,$user->end,$sold";
         $user->token = (new EncryptionController)->privEncrypt($data);
         $user->save();
          
        }
            
        $this->emit('userStore'); 
        $this->resetInputFields();
        $this->alert('success', 'paks Added Successfully.', 
        [
            'position' => 'center',
            'timer' => 2000,
            'toast' => false,
           ]
       );
        
        
        // Close model to using to jquery
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
    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
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

    public function print($id)
    {
        return redirect()->to("/prints/$id");
    }

    public function scart($id)
    {
      $all = Sold_cart :: where('ps_id',$id)->count();
      $valid = Sold_cart :: where('ps_id',$id)->where('valid',true)->count();
      $pa = "$valid/$all";
      return $pa;
    }

    public function printx()
    {
        $sp=$this->sp;
        $ep=$this->ep;
        $this->emit('userStore1');
        $this->resetInputFields();
        return redirect()->to("/printsolde/$sp/$ep");
    }
    
}

