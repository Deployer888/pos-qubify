<?php

namespace App\Models;

use App\Traits\ModelBoot;
use App\Traits\Scopes\ScopeActive;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Supplier
 */
class Supplier extends Model
{
    use HasFactory, ScopeActive, ModelBoot;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        "first_name",
        "last_name",
        "email",
        "phone",
        "company",
        "designation",
        "address_line_1",
        "address_line_2",
        "country",
        "state",
        "city",
        "zipcode",
        "short_address",
        "avatar",
        "status",
        "created_by",
        "updated_by"
    ];

    /**
     * appends
     *
     * @var array
     */
    protected $appends = ['text', 'avatar_url', 'full_name'];

    // CONST
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';


    public const FILE_STORE_PATH = 'suppliers';

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
    /**
     * getAvatarUrlAttribute
     *
     * @return void
     */
    public function getAvatarUrlAttribute()
    {
        return getStorageImage(self::FILE_STORE_PATH, $this->avatar);
    }

    /**
     * getFullNameAttribute
     *
     * @return void
     */
    public function getFullNameAttribute()
    {
        return $this->last_name ? $this->first_name . ' ' . $this->last_name : $this->first_name;
    }

    /**
     * systemCountry
     *
     * @return void
     */
    public function systemCountry()
    {
        return $this->belongsTo(SystemCountry::class, 'country');
    }

    /**
     * systemState
     *
     * @return void
     */
    public function systemState()
    {
        return $this->belongsTo(SystemState::class, 'state');
    }

    /**
     * systemCity
     *
     * @return void
     */
    public function systemCity()
    {
        return $this->belongsTo(SystemCity::class, 'city');
    }

}
