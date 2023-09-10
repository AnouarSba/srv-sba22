<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Traits\HasWallets;
use Bavix\Wallet\Interfaces\Wallet;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model implements Wallet
{

    use SoftDeletes;
    use HasWallet, HasWallets;
    use HasFactory;
    protected $fillable = ['pc_id'];

    public function paquet()
    {
        return P_Cart::find($this->pc_id);
    }

}
