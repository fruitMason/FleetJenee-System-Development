<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * @method static isActive()
 * @method static isInactive()
 * @method static dueMaintenance()
 * @method static inMaintenance()
 * @method static inRepairs()
 */
class Car extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    protected $table = 'cars';

    public function scopeIsActive($query)
    {
        $query->where('status', 'active');
    }

    public function scopeIsInactive($query)
    {
        $query->where('status', 'inactive');
    }

    public function scopeDueMaintenance($query)
    {
        $query->where('status', 'due_maintenance');
    }

    public function scopeInMaintenance($query)
    {
        $query->where('status', 'in_maintenance');
    }

    public function scopeInRepairs($query)
    {
        $query->where('status', 'in_repairs');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function sector(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Sector::class, 'zone_id');
    }

    public function maintenance(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CarMaintenance::class, 'car_id');
    }

    public function isInMaintenance()
    {
        return $this->maintenance()->whereIn('status', ['pending', 'ongoing'])->exists();
    }

    public function odometerHistory(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OdometerHistory::class, 'car_id');
    }

    public function latestOdometerHistory(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(OdometerHistory::class, 'car_id')->selectRaw('id, user_id, car_id, max(created_at) as created_at, new_value')->groupBy(['user_id', 'car_id'])->orderByRaw('max(created_at)');
    }

    public function getLastOdometerEntry()
    {
        return $this->odometerHistory()->selectRaw('id, user_id, car_id, max(created_at) as created_at, new_value')->groupBy(['user_id', 'car_id'])->orderByRaw('max(created_at)')->first();
    }
    public function latestOdometer()
    {
        return $this->hasOne(OdometerHistory::class, 'car_id')
            ->latestOfMany();
    }
    public function odometerHistories()
    {
        return $this->hasMany(OdometerHistory::class);
    }

    public function carRequests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CarRequest::class, 'user_id', 'user_id'); // Adjust the keys if necessary
    }


    protected $fillable = [
        'model',
        'year',
        'body_style',
        'trim_level',
        'color',
        'car_number',
        'chassis',
        'odometer',
        'engine_capacity',
        'fuel_type',
        'tank_size',
        'car_cost',
        'purchase_date',
        'condition',
        'dvla_code',
        'dvla_expiry',
        'road_worthy_start_date',
        'road_worthy_expiry_date',
        'status',
        'comment',
        'insurance_start_date',
        'insurance_expiry',
        'user_id',
        'car_group',
        'is_archived',
        'odometer_level',
        'odometer_status', //Active,Overdue,Maintenance,
        'created_by'
    ];

    protected $casts = [
        'road_worthy_expiry_date' => 'datetime',
        'road_worthy_start_date' => 'datetime',
        'insurance_start_date' => 'datetime',
        'insurance_expiry' => 'datetime',
        'insurance_expiry' => 'datetime',
        'date_due_maintenance' => 'datetime',
    ];

    public function car_features(): string
    {
        return  $this->model . ', ' . $this->year .    ' , ' . $this->car_number;
        //trim(str_replace('  ', ' ', ucwords($name)));
    }
}
