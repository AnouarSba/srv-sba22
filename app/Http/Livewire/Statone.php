<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Client;
use App\Models\Kabid;
use App\Models\Control;
use App\Models\Sold_cart;
use App\Models\Vendeur;
use Bavix\Wallet\Models\Transaction;
use Livewire\Component;

class Statone extends Component
{
    public $contr;
    public $rece;
    public $vend;
    public $client;
    public $cart;
    public $scart;
    public $epay;
    public function render()
    {
        $this->contr = Control::count();
        $this->rece = Kabid::count();
        $this->vend = Vendeur::count();
        $this->client = Client::count();
        $this->cart = Cart::where('valid',true)->count();
        $this->scart = Sold_cart::where('valid',false)->count();
        $this->epay = Transaction::where('payable_type','App\Models\Payget')->where('type','withdraw')->count();
        return view('livewire.statone');
    }
}
