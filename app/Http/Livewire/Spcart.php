<?php

namespace App\Http\Livewire;
use App\Http\Controllers\EncryptionController;
use App\Models\Abonment;
use App\Models\Abtype;
use App\Models\Aevent;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Spcart as kab;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
class Spcart extends Component
{
    use WithFileUploads;
    use WithPagination,LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $searchTerm;
    public $photo;
    public $photo1;
    public $amount1;
    public $ban = false;
    public $valid = false;
    public $uuid,$date_nes,$token,$cont,$max,$user_id ,$type,$name,$phone,$father,$card_number,$hand_prcnt,$hand_nature;
    public $updateMode = false;
    public $confirming;
    public $confirming1;
    public $confirming2;
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
            $searchTerm = '%'.$this->searchTerm.'%';
            $data = kab::where('name','like', $searchTerm)->paginate(10);
            return view('livewire.spcart', [
                'carts' => $data ,
            ]);
    }

    private function resetInputFields(){
        $this->phone ='';
        $this->photo ='';
        $this->photo1 ='';
        $this->type ='';
        $this->uuid ='';
        $this->max ='';
        $this->cont ='';
        $this->valid ='';
        $this->ban ='';
        $this->token ='';
        $this->confirming ='';
        $this->confirming1 ='';
        $this->user_id ='';
        $this->date_nes ='';
        $this->name ='';
        $this->hand_prcnt ='';
        $this->hand_nature ='';
        $this->card_number ='';
        $this->father ='';

    }
   
    public function store()
    {
        
        $en=config('etus.server.code');
        $validatedDate = $this->validate([
            'type' => 'required|string',
        ]);
        
        
        if ($validatedDate['type'] == 'HAND') {
            
        $validatedDate = $this->validate([
            'name' => 'required|string',
            'date_nes' => 'required|date',
            'type' => 'required|string',
            'photo' => 'required|image|max:1024',
            'max' => 'nullable|integer',
            'valid' => 'boolean',
            'hand_prcnt' => 'required|integer',
            'hand_nature' => 'required|string',
            'card_number' => 'required|string',
        ]);
        $hand_prcnt = $validatedDate['hand_prcnt'];
        $hand_nature = $validatedDate['hand_nature'];
        $card_number = $validatedDate['card_number'];
        $phone = NULL;
        $father = NULL;
        } else{
            $validatedDate = $this->validate([
                'name' => 'required|string',
                'date_nes' => 'required|date',
                'type' => 'required|string',
                'photo' => 'required|image|max:1024',
                'max' => 'nullable|integer',
                'valid' => 'boolean',
            'father' => 'required|string',
            'phone' => 'required|string',
        ]);
        $hand_prcnt = NULL;
        $card_number = NULL;
        $phone = $validatedDate['phone'];
        $father = $validatedDate['father'];
        }
            $user = new kab;
            $user->type=$validatedDate['type'];
            $user->name=$validatedDate['name'];
            $user->phone=$phone;
            if ($validatedDate['max'] == NULL) {
               $type = Abtype::where('code',$validatedDate['type'])->first();
               $user->max= 100;
            } else {
                $user->max=$validatedDate['max'];
            }
            
            $user->valid=$validatedDate['valid'];
            $user->hand_prcnt=$hand_prcnt;
            $user->card_number=$card_number;
            $user->father=$father;
            $user->phone=$phone;
            $user->date_nes=$validatedDate['date_nes'];
            $user->uuid=rand(0001,9999);
            $user->save();
            
            $data = "$en,spc,$user->type,$user->id,$user->uuid";
            $user->uuid=$en.'t'.$user->type.'c'.$user->id;
            $user->token = (new EncryptionController)->privEncrypt($data);
            
            $filename = $user->uuid.'_'. time() .'.'. $this->photo->getClientOriginalExtension();
            $this->photo->storeAs('public/scphotos',$filename);
            $user->photo = $filename;
            $user->save();
            $en = config('etus.server.code');
      if ($en == 'e22' && $validatedDate['type'] == 'ELEV') {
            $user->deposit(300, ['action' => 'Rechargement sp_eleve','admin_id' => auth()->user()->id]);
      }
        $this->resetInputFields();
        $this->alert('success', 'Card Added Successfully.', 
        [
            'position' => 'center',
            'timer' => 2000,
            'toast' => false,
           ]
        );
        
        $this->emit('userStore'); 
            // Close model to using to jquery
    }


    public function edit($id)
    {
        $this->updateMode = true;
        $user = kab::where('id',$id)->first();
        $this->user_id = $id;
        $this->type = $user->type;
        $this->max = $user->max;
        if ($user->type == 'HAND') {
            $this->phone = NULL;
        $this->card_number = $user->card_number;
        $this->hand_prcnt = $user->hand_prcnt;
        $this->hand_nature = $user->hand_nature;
        $this->father = Null;
        } else {
            $this->phone = $user->phone;
            $this->card_number = Null;
            $this->hand_prcnt = NULL;
            $this->hand_nature = NULL;
            $this->father = $user->father;
        }
        
        $this->valid = $user->valid;
        $this->date_nes = $user->date_nes;
        $this->name = $user->name;
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
            'type' => 'required|string',
        ]);
        
        if ($validatedDate['type'] == 'HAND') {
            
        $validatedDate = $this->validate([
            'name' => 'required|string',
            'date_nes' => 'required|date',
            'type' => 'required|string',
            'photo' => 'required|image|max:1024',
            'max' => 'nullable|integer',
            'valid' => 'boolean',
            'hand_prcnt' => 'required|integer',
            'hand_nature' => 'required|string',
            'card_number' => 'required|string',
        ]);
        $type = $validatedDate['type'];
        $hand_prcnt = $validatedDate['hand_prcnt'];
        $hand_nature = $validatedDate['hand_nature'];
        $card_number = $validatedDate['card_number'];
        $phone = NULL;
        $father = NULL;
        } else{
            $validatedDate = $this->validate([
                'name' => 'required|string',
                'date_nes' => 'required|date',
                'type' => 'required|string',
                'photo' => 'required|image|max:1024',
                'max' => 'nullable|integer',
                'valid' => 'boolean',
            'father' => 'required|string',
            'phone' => 'required|string',
        ]);
        $hand_prcnt = NULL;
        $hand_nature = NULL;
        $card_number = NULL;
        $phone = $validatedDate['phone'];
        $father = $validatedDate['father'];
        }
       
        if ($this->photo) {
            $photoval=$this->validate([
                'photo' => 'required|image|max:1024',
            ]);
        }
        if ($this->user_id) {
            $user = kab::find($this->user_id);
            $user->type= $validatedDate['type'];
            $user->phone=$phone;
            $user->father=$father;
            $user->card_number=$card_number;
            $user->hand_prcnt=$hand_prcnt;
            $user->hand_nature=$hand_nature;
            $user->valid = $validatedDate['valid'];
            $user->name= $validatedDate['name'];
            $user->date_nes=$validatedDate['date_nes'];
            if ($validatedDate['max'] == NULL) {
                $type = Abtype::where('code',$validatedDate['type'])->first();
                $user->max= 100;
             } else {
                 $user->max=$validatedDate['max'];
             }
            if ($this->photo) {
                if($user->photo != NULL){
                    $image_path = public_path().'/storage/scphotos/'.$user->photo;
                    unlink($image_path);
                }
                $filename = ''.$user->uuid.'-'. time() .'.'. $this->photo->getClientOriginalExtension();
                $this->photo->storeAs('public/scphotos',$filename);
                $user->photo = $filename;
            }

            $user->save();

            $this->updateMode = false;
            $this->emit('userUpdate');
            $this->alert('success', 'card Updated Successfully.', 
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
            if ($pak->valid == true) {

                $this->alert('warning', 'you cant delete a valid card .', 
                [
                    'position' => 'center',
                    'timer' => 2000,
                    'toast' => false,
                   ]
               ); 
            }else {
                    $pak->delete();
                    $this->alert('success', 'card deleted successfully .', 
                    [
                        'position' => 'center',
                        'timer' => 2000,
                        'toast' => false,
                       ]
                   ); 
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

    public function cancelActive($id)
    {
        $this->confirming2 = '';
    }

    public function confirmActive($id)
    {
        $this->confirming2 = $id;
    }

    public function generation($id)
    {
        if($id){
            
            $pak=kab::where('id',$id)->first();
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
                $data = "$en,spc,$pak->type,$pak->id,$pin";
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

    public function activeab($id)
    {
        if($id){
            
            $pak=kab::where('id',$id)->first();
            if ($pak->valid == false) {

                $this->alert('warning', 'لا يمكنك تشغيل هذا العرض البطاقة غير مفعلة', 
                [
                    'position' => 'center',
                    'timer' => 2000,
                    'toast' => false,
                   ]
               ); 
               $this->confirming2 = '';
            }else {

                $ab= Abonment::where('abon_type',get_class($pak))->where('abon_id',$pak->id)->whereDate('end', '>', Carbon::today()->toDateString())->latest()->first();
                 if ($ab) {
                    $this->alert('warning', 'هذه البطاقة تملك عرض مشغل بالفعل', 
                    [
                        'position' => 'center',
                        'timer' => 2000,
                        'toast' => false,
                       ]
                   ); 
                   $this->confirming2 = '';
                 } else {
                   
                    $aptype = Abtype::where('code',$pak->type)->first();
                    if ($aptype->amount == 0) {
                        $nab = new Abonment();
                        $nab->abon_type = get_class($pak);
                        $nab->abon_id = $pak->id;
                        $nab->type = $pak->type;
                        $nab->amount = $aptype->amount;
                        $nab->start = Carbon::now();
                        if ($pak->type == 'HAND') {
                            $nab->end =  new carbon('01 january '.$nab->start->format('Y')+1);
            
                        } else {
                        $nab->end = Carbon::now()->addDays($aptype->dur);
            
                        }
                        $nab->save();
                        $event = new Aevent;
                        $event->cat ='spc';
                        $event->type ='active_abn';
                        $event->user_type = get_class(auth()->user());
                        $event->user_id = auth()->user()->id;
                        $event->class_type = Kab::class;
                        $event->class_id = $pak->id;
                        $event->amount = $aptype->amount;
                        $event->info = 'activeation abonment par le gérant';
                        $event->save();

                        $this->alert('success', 'تم تفعيل الاشتراك بنجاح',
                        [
                            'position' => 'center',
                            'timer' => 2000,
                            'toast' => false,
                        ]);
                    } else {
                       if ($pak->balance >= $aptype->amount) {
                         $pak->forceWithdraw($aptype->amount, ['action' => 'Activation de labonnement','admin_id' => auth()->user()->id]);
                         $nab = new Abonment();
                         $nab->abon_type = get_class($pak);
                         $nab->abon_id = $pak->id;
                         $nab->type = $pak->type;
                         $nab->amount = $aptype->amount;
                         $nab->start = Carbon::now();
                         if ($pak->type == 'HAND') {
                            $nab->end =  new carbon('01 january '.$nab->start->format('Y')+1);
            
                        } else {
                        $nab->end = Carbon::now()->addDays($aptype->dur);
            
                        }
                         $nab->save();
                         $event = new Aevent;
                         $event->cat ='spc';
                         $event->type ='active_abn';
                         $event->user_type = get_class(auth()->user());
                         $event->user_id = auth()->user()->id;
                         $event->class_type = Kab::class;
                         $event->class_id = $pak->id;
                         $event->amount = $aptype->amount;
                         $event->info = 'activeation abonment par le gérant';
                         $event->save();
                         $this->alert('success', 'تم تفعيل الاشتراك بنجاح', 
                         [
                             'position' => 'center',
                             'timer' => 2000,
                             'toast' => false,
                         ]);
                       }else {
                        $this->alert('warning', ' رصيد البطاقة غير كافي ', 
                        [
                            'position' => 'center',
                            'timer' => 2000,
                            'toast' => false,
                           ]
                       ); 
                       $this->confirming2 = '';
                       }
                    }
                    
                    $this->confirming2 = '';
                 }
                 
                
            }


        }
    }


    public function unban($id)
    {
        if($id){
          $kab = kab::where('id',$id)->first();
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
    

    public function add(){
        $validatedDate = $this->validate([
            'amount1' => 'required|numeric',
        ]);

        if($validatedDate['amount1']){
            $kab =  kab::where('id',$this->user_id)->first();
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
            $kab =  kab::where('id',$this->user_id)->first();
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

