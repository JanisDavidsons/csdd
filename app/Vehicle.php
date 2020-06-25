<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'number_plate',
        'column_id',
        'user_id'
    ];

    public function inspection(){
        return $this->belongsTo(Inspection::class);
    }
}
