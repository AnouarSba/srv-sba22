<?php

namespace App\Http\Livewire;

use App\Models\Payget;
use Livewire\Component;

use Livewire\WithPagination;

class Getwaytrans extends Component
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
        
        $cart =  Payget :: find($this->cart);
        $searchTerm = '%'.$this->searchTerm.'%';
        $data = $cart->transactions()
          ->where('transactions.id','like', $searchTerm)->orderBy('transactions.created_at', 'DESC')->paginate(10);
        return view('livewire.getwaytrans', [
            'trans' => $data ,
        ]);

    }
}