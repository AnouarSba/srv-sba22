<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Interfaces\Wallet;

use Laravel\Sanctum\HasApiTokens;
class Payget extends Model implements Wallet
{
   
    use HasApiTokens;
    use HasFactory;
    use HasWallet;
    protected $connection = 'mysql';
}
