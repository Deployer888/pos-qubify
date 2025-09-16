<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Expenses
 */
class Expenses extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'title',
        'date',
        'total',
        'notes',
        'expense_by',
        'created_by',
        'updated_by'
    ];


    // CONST
    public const FILE_STORE_PATH = 'expenses';


    // RELATIONS

    /**
     * category
     *
     * @return void
     */
    public function category()
    {
        return $this->belongsTo(ExpensesCategory::class, 'category_id');
    }

    /**
     * items
     *
     * @return void
     */
    public function items()
    {
        return $this->hasMany(ExpensesItem::class, 'expenses_id');
    }

    /**
     * files
     *
     * @return void
     */
    public function files()
    {
        return $this->hasMany(ExpensesFile::class, 'expenses_id');
    }

    /**
     * expense by
     *
     * @return void
     */
    public function expenseBy()
    {
        return $this->belongsTo(User::class,'expense_by', 'id');
    }
}
