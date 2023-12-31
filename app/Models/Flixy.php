<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Flixy extends Model
{
    use SoftDeletes;
    use HasFactory;
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
