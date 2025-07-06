<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * @method static isPending()
 * @method static isApproved()
 * @method static isRejected
 */
class CarRequest extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function scopeIsPending($query)
    {
        $query->where('status', 'pending');
    }

    public function scopeIsApproved($query)
    {
        $query->where('status', 'approved');
    }

    public function scopeIsRejected($query)
    {
        $query->where('status', 'rejected');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function car(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Car::class, 'user_id', 'user_id'); // Adjust the keys as per your schema
    }
    protected $casts = [
        'date_needed' => 'datetime',
        'return_date' => 'datetime',
    ];
}
