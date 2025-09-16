<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'states';

    protected $fillable = [
        'name',
        'country_id',
        'created_by',
        'updated_by'
    ];

    public function cities()
    {
        return $this->hasMany(City::class, 'state_id');
    }
}
