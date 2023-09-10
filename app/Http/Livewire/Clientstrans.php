<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Client;
use App\Models\E_ticket;
use Livewire\WithPagination;

class Clientstrans extends Component
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
        
        $cart =  Client :: find($this->cart);
        $searchTerm = '%'.$this->searchTerm.'%';
        $data = $cart->transactions()
          ->where('transactions.id','like', $searchTerm)->orderBy('transactions.created_at', 'DESC')->paginate(10);
        return view('livewire.clientstrans', [
            'trans' => $data ,
        ]);

    }
}