<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method static isDriver()
 * @method static isMechanic()
 */
class User extends Authenticatable
{
    use HasApiTokens, HasRoles, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'mobile',
        'role',
        'department_id',
        'status',
        'auth_code',
        'pic',
        'gender',
        'type',
        'license_class',
        'license_number',
        'license_expiry',
        'vendor_id',
        'driver_type',
        'last_login_at',
        'last_login_ip',
        'photo'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'avatar' => 'object',
        'last_login_at' => 'datetime',
        'license_expiry' => 'datetime'
    ];

    public function login($email, $password)
    {
        return auth()->attempt(['email' => $email, 'password' => $password]);
    }

    public function full_name(): string
    {
        $name = $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name;
        return trim(str_replace('  ', ' ', ucwords($name)));
    }

    public function department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function vendor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function getSector()
    {
        return $this->department->region->sector;
    }

    public function car(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Car::class, 'user_id');
    }

    public function scopeIsDriver($query)
    {
        $query->where('type', 'DRIVER');
    }

    public function scopeIsManager($query)
    {
        $query->where('type', 'MANAGER');
    }

    public function scopeIsMechanic($query)
    {
        $query->where('type', 'MECHANIC');
    }

    public function getRole(): string
    {
        if (sizeof($this->getRoleNames()) > 0)
            return strtoupper(str_replace('_', ' ', $this->getRoleNames()->first()));
        return 'N/A';
    }

    public function getRoleData()
    {
        return $this->roles->first();
    }

    public function hasCar()
    {
        return $this->car()->exists();
    }

    public function getAvatar()
    {
        if (!is_null($this->avatar) || !empty($this->avatar))
            return $this->avatar->avatar;

        return '';
    }

    public function getNormal()
    {
        if (!is_null($this->avatar) || !empty($this->avatar))
            return $this->avatar->normal;

        return '';
    }

    public function avatarUrl()
    {
        if (!empty($this->getAvatar()))
            return route('avatar_url', array('dir' => 'avatar', 'filename' => $this->getAvatar()));

        return asset('assets/img/faces/9.jpg');
    }

    public function normalUrl()
    {
        if (!empty($this->getNormal()))
            return route('avatar_url', array('dir' => 'normal', 'filename' => $this->getNormal()));

        return asset('assets/img/faces/9.jpg');
    }
}
