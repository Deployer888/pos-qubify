<?php

namespace App\Models;

use App\Traits\ModelBoot;
use App\Traits\Scopes\ScopeActive;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;

/**
 * Customer
 */
class Customer extends Authenticatable implements JWTSubject
{
    protected $guard = 'customer';

    use HasFactory, ScopeActive, ModelBoot, Notifiable;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        "first_name",
        "last_name",
        "email",
        'password',
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
        "billing_same",
        "b_first_name",
        "b_last_name",
        "b_email",
        "b_phone",
        "b_address_line_1",
        "b_address_line_2",
        "b_country",
        "b_state",
        "b_city",
        "b_zipcode",
        "b_short_address",
        "avatar",
        "status",
        "is_verified",
        "created_by",
        "updated_by",
        "type",
        "customer_id",
        "total_wallet_amount"
    ];
    protected $hidden = [
        'password',
    ];

    protected $appends = ['text', 'avatar_url', 'full_name'];

    // CONST
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_VERIFIED = 'verified';
    public const STATUS_UNVERIFIED = 'unverified';
    public const TYPE_CUSTOMER = 'customer';
    public const TYPER_EMPLOYEE = 'employee';

    public const FILE_STORE_PATH = 'customers';

    // MUTATORS & ACCESSORS
    /**
     * getFullNameAttribute
     *
     * @return void
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    /**
     * getTextAttribute
     *
     * @return void
     */
    public function getTextAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
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

    // Relations

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


    /**
     * b_country_data
     *
     * @return void
     */
    public function b_country_data()
    {
        return $this->belongsTo(SystemCountry::class, 'b_country');
    }
    /**
     * b_state_data
     *
     * @return void
     */
    public function b_state_data()
    {
        return $this->belongsTo(SystemState::class, 'b_state');
    }
    /**
     * b_city_data
     *
     * @return void
     */
    public function b_city_data()
    {
        return $this->belongsTo(SystemCity::class, 'b_city');
    }
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
