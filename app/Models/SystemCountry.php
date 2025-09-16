<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemCountry extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'shortname',
      'phonecode'
    ];
    public function states()
    {
        return $this->hasMany(SystemState::class, 'country_id');
    }
}
