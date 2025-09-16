<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemState extends Model
{
    use HasFactory;
    protected $fillable = [
        'country_id',
        'name'
    ];
    public function country()
    {
        return $this->belongsTo(SystemCountry::class, 'country_id');
    }

    public function cities()
    {
        return $this->hasMany(SystemCity::class, 'state_id');
    }
}
