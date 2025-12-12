<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutoPartStatement extends Model
{
    //

    protected $fillable = [
        'auto_part_id',
        'user_id',
        'stock_in',
        'stock_out',
        'trans_type',       
        'narration',
        'trans_status',
    ];


    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function auto_part(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AutoPart::class, 'auto_part_id');
    }
}
