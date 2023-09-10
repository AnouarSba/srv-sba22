<?php

namespace App\Http\Livewire;
use App\Http\Controllers\EncryptionController;
use App\Http\Controllers\PVTicketController;
use Illuminate\Support\Facades\Http;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\PV_ticket as kab;
use App\Models\V_ticket;
use Livewire\Component;
use Livewire\WithPagination;
class VTickets extends Component
{
    use WithPagination,LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $searchTerm;
    public  $sp,$ep,$x,$y;
    public $type;
    public $numpak =10;
    public $pak = 100;
    public $status,$token,$amount,$forn_type,$forn_id,$kadid_id,$printed,$user_id;
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
        return view('livewire.v-tickets', [
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
        $this->kadid_id ='';
        $this->printed ='';
        $this->confirming ='';
        $this->user_id ='';

    }
    public function store()
    {
        $en=config('etus.server.code');
        $validatedDate = $this->validate([
            'type' => 'required',
            'numpak' => 'nullable|numeric|max:50|min:10',
            'pak' => 'required|numeric|max:100|min:100',
            'amount' => 'required|numeric|max:100|min:15',
        ]);
        if (isset($validatedDate['numpak'])) {

        for ($j=0; $j<$validatedDate['numpak'] ; $j++) { 

         $user = new kab;
         $user->type=$validatedDate['type'];
         if (isset($validatedDate['amount'])) {
            $user->amount=$validatedDate['amount'];
         }else{
            $user->amount=0;
         }
         $user->save();

         for ($i=0; $i < $validatedDate['pak'] ; $i++) { 

             $tik = new V_ticket;
             $tik->type = $validatedDate['type'];
             $tik->pak_id = $user->id;
             $tik->amount=$user->amount;
             $tik->save();
             $data = "$en,vt,$user->id,$tik->id,$tik->amount";
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
         $data = "$en,pvt,$user->id,$user->start,$user->end,$user->amount";
         $user->token = (new EncryptionController)->privEncrypt($data);
         $user->save();
          
        }
            
        } else {

            $user = new kab;
            $user->type=$validatedDate['type'];
            if (isset($validatedDate['amount'])) {
               $user->amount=$validatedDate['amount'];
            }else{
               $user->amount=0;
            }
            $user->save();
   
            for ($i=0; $i < $validatedDate['pak'] ; $i++) { 
   
                $tik = new V_ticket;
                $tik->type = $validatedDate['type'];
                $tik->pak_id = $user->id;
                $tik->amount=$user->amount;
                $tik->save();
                $data = "$en,vt,$user->id,$tik->id,$tik->amount";
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
            $data = "$en,pvt,$user->id,$user->start,$user->end,$user->amount";
            $user->token = (new EncryptionController)->privEncrypt($data);
            $user->save();
        $this->emit('userStore');
        $this->resetInputFields();
        $this->alert('success', 'pak Added Successfully.', 
        [
            'position' => 'center',
            'timer' => 2000,
            'toast' => false,
           ]
        );
        
        
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
            if ($pak->kabid_id <> NULL) {

                $this->alert('warning', 'you cant delete a in explotation pak .', 
                [
                    'position' => 'center',
                    'timer' => 2000,
                    'toast' => false,
                   ]
               ); 
            }else {
                if($pak->printed){
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
        return redirect()->to("/print/$id");
    }

    public function printx()
    {
        $sp=$this->sp;
        $ep=$this->ep;
        $this->emit('userStore1');
        $this->resetInputFields();
        return redirect()->to("/printx/$sp/$ep");
    }
    public function vcart($id)
    {
      $all = V_ticket :: where('pak_id',$id)->count();
      $valid = V_ticket :: where('pak_id',$id)->where('valid',true)->count();
      $pa = "$valid/$all";
      return $pa;
    }
}

