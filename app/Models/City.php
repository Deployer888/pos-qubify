<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';

    protected $fillable = [
        'name',
        'state_id',
        'created_by',
        'updated_by'
    ];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }
}
