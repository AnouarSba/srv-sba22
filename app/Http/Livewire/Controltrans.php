<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Control;
use App\Models\E_ticket;
use Livewire\WithPagination;

class Controltrans extends Component
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
        
        $cart =  Control :: find($this->cart);
        $searchTerm = '%'.$this->searchTerm.'%';
        $data = $cart->transactions()
          ->where('transactions.id','like', $searchTerm)->orderBy('transactions.created_at', 'DESC')->paginate(10);
        return view('livewire.controltrans', [
            'trans' => $data ,
        ]);

    }
}