<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ExpensesItem
 */
class ExpensesItem extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'expenses_id',
        'item_name',
        'item_qty',
        'amount',
        'note'
    ];
}
