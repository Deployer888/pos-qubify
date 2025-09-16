<?php


namespace App\Traits;


use App\Models\User;

trait UpdatedByRelationship
{
    /**
     * updatedBy
     *
     * @return void
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
