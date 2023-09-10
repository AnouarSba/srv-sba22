<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Valideur extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = ['bus_id'];

    public function bus()
    {
        return $this->belongsTo('App\Models\Bus');
    }
}
