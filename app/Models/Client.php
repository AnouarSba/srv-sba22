<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Interfaces\Wallet;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
class Client extends Model implements Wallet
{
    use SoftDeletes;
    use HasApiTokens,HasWallet;
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];
    protected $hidden = [
        'password',
        'pin',
    ];
    public function flixy()
   { 
 
       return $this->hasMany(Flixy::class);
   }
}
