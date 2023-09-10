<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Bus extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $fillable = ['ligne_id'];

    public function ligne()
    {
        return $this->belongsTo('App\Models\Ligne');
    }
}
