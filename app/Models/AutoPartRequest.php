<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutoPartRequest extends Model
{

    protected $fillable = [
        'auto_part_id',
        'user_id',
        'car_id',
        'request_type',
        'qnt_requested',
        'reason_for_request',
        'qnt_approved',
        'status',
        'auth_by',
        'reason_for_decline'
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function auto_part(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AutoPart::class, 'auto_part_id');
    }
    public function car(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
