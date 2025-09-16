<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * User
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * appends
     *
     * @var array
     */
    protected $appends = ['text', 'avatar_url'];

    // Const
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    public const FILE_STORE_PATH = 'users';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'avatar',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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
        return getStorageImage(self::FILE_STORE_PATH, $this->avatar, true);
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

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'user_warehouses');
    }
    public function userWarehouses()
    {
        return $this->hasMany(UserWarehouse::class);
    }

    public function depot()
    {
        return $this->hasOne(Depot::class, 'user_id'); // or 'manager_id'
    }

    public function depots()
    {
        return $this->hasMany(Depot::class, 'user_id'); // if one user can manage multiple depots
    }
}
