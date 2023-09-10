<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Traits\HasWallets;
use Bavix\Wallet\Interfaces\Wallet;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendeur extends Model implements Wallet
{
    use SoftDeletes;
    use HasWallet, HasWallets;
    use HasFactory;
}
