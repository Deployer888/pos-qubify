<?php


namespace App\Traits;


use App\Models\User;

trait CreatedByRelationship
{
    /**
     * createdBy
     *
     * @return void
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
