<?php

namespace App\Models;

use App\Traits\Scopes\ScopeActive;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * ExpensesCategory
 */
class ExpensesCategory extends Model
{
    use HasFactory, ScopeActive;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'desc',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $appends = ['text'];

    // CONST
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';


    // MUTATORS & ACCESSORS    
    /**
     * getTextAttribute
     *
     * @return void
     */
    public function getTextAttribute()
    {
        return $this->name;
    }
}
