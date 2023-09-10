<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class E_ticket extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = ['bus_id','ligne_id'];


    public function bus()
    {
        return $this->belongsTo('App\Models\Bus');
    }

    public function ligne()
    {
        return $this->belongsTo('App\Models\Ligne');
    }

    public function arret()
    {
        return Arret::find($this->arret_from_id);
    }

    
    public function forn()
    {
       $type =  $this->forn_type;
       $name = $type::find($this->Forn_id)->name;
        return $name;
    }
}
