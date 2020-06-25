<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    protected $fillable = [

    ];

    public function vehicles(){
        return $this->hasMany(Inspection::class);
    }

    public function scopeWithoutMot($query)
    {
        $query->where('created_at', '!=', null);
    }
}
