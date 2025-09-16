<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'settings_key',
        'settings_value'
    ];

    protected $casts = ['settings_value' => 'array'];
}
