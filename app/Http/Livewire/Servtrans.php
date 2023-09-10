<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Server;
use Livewire\WithPagination;

class Servtrans extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $searchTerm;
    public $cart;
    public function mount($cart)
    {
        $this->cart = $cart;
        
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function render()
    {
        
        $cart =  Server :: find($this->cart);
        $searchTerm = '%'.$this->searchTerm.'%';
        if ($cart ) {
            $data = $cart->transactions()
              ->where('transactions.id','like', $searchTerm)->orderBy('transactions.created_at', 'DESC')->paginate(10);
            return view('livewire.servtrans', [
                'trans' => $data ,
            ]);
        }else{
            return view('livewire.servtrans'); 
        }


    }
}