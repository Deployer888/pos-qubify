<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Permission
 */
class Permission extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = ['name', 'guard_name', 'parent_id'];

    /**
     * childs
     *
     * @return void
     */
    public function childs()
    {
        return $this->hasMany(Permission::class, 'parent_id');
    }
    /**
     * parent
     *
     * @return void
     */
    public function parent()
    {
        return $this->belongsTo(Permission::class, 'parent_id');
    }
}
