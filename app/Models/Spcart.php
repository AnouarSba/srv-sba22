<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Traits\HasWallets;
use Bavix\Wallet\Interfaces\Wallet;
class Spcart extends Model implements Wallet
{
    use HasFactory;
    use SoftDeletes;
    use HasWallet, HasWallets;

    public function abons()
    {
        return $this->morphMany('App\Models\Abonment', 'abon');
    }
    public function cont()
   { 
 
       return $this->hasMany(Cont::class);
   }
}
