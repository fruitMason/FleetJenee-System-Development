<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PaymentRequest extends Model
{
    //
    protected $fillable = [
        'user_id',
        'car_id',
        'request_date',
        'payment_type',
        'description',
        'amount',
        'status',
        'date_paid',
        'amount_paid',
        'for_user_id',
        'car_assigned',
        'invoice_no'
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function car(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    // protected $casts = [
    //     'date_paid' => 'datetime',
    //     'created_at' => 'datetime',
    // ];
    public function for_user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'for_user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->status = 'pending';
            $model->user_id = Auth::user()->id;
        });
    }
}
