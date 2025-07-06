<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * @method static isPending()
 * @method static isOngoing()
 * @method static isRejected()
 * @method static isCompleted()
 */
class Waybill extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    protected $table = 'waybills';

    public function scopeIsActive($query)
    {
        $query->where('status', 'active');
    }

    public function scopeIsPending($query)
    {
        $query->where('status', 'pending');
    }

    public function scopeIsOngoing($query)
    {
        $query->where('status', 'ongoing');
    }

    public function scopeIsCompleted($query)
    {
        $query->where('status', 'completed');
    }

    public function scopeIsRejected($query)
    {
        $query->where('status', 'rejected');
    }

    public function driver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function media(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WaybillMedia::class, 'waybill_id');
    }
}
