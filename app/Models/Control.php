<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Interfaces\Wallet;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
class Control extends Model implements Wallet
{
    use SoftDeletes;
    use HasWallet,HasApiTokens;
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];
    protected $hidden = [
        'password',
        'authtoken',
    ];
}
