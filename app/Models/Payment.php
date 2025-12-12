<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'payment_request_id',
        'payment_date',
        'amount_paid',
        'payment_mode',
        'payment_reference',
        'narration',
        'payment_status',
      
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function request_info(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PaymentRequest::class, 'payement_request_id');
    }

   
  
}
