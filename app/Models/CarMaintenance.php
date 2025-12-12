<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * @method static isPending()
 * @method static isOngoing()
 * @method static isDone
 * @method static isCompleted()
 */
class CarMaintenance extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    protected $table = 'car_maintenances';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (is_null($model->status)) {
                $model->status = 'pending';
                $model->fin_status = 'pending';
            }
        });
    }

    protected $fillable = [
        'car_id',
        'type',
        'mechanic_id',
        'comment',
        'start_date',
        'end_date',
        'fin_status',
        'fin_date',
        'fin_user',
        'fin_comment',
        'user_id',
        'normal_overdue',
        'odometer'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'fin_date' => 'datetime'
    ];

    public function scopeIsPending($query)
    {
        $query->where('status', 'Pending')->orWhere('status', 'pending');
    }

    public function scopeIsOngoing($query)
    {
        $query->where('status', 'Ongoing')->orWhere('status', 'ongoing');
    }

    public function scopeIsCompleted($query)
    {
        $query->where('status', 'Completed')->orWhere('status', 'completed');
    }

    public function car(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function mechanic(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function media(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CarMaintenanceMedia::class, 'car_maintenance_id');
    }

    public function invoice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
